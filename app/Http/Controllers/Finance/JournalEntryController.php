<?php

namespace App\Http\Controllers\Finance;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JournalEntryController extends Controller
{
    //
    use LogsActivity;

    /**
     * Display a listing of journal entries
     */
    public function index(Request $request)
    {
        $query = DB::table('journal_entries as je')
            ->leftJoin('users as creator', 'je.created_by', '=', 'creator.user_id')
            ->leftJoin('employees as approver', 'je.approved_by', '=', 'approver.employee_id')
            ->select(
                'je.journal_id',
                'je.journal_code',
                'je.journal_date',
                'je.journal_type',
                'je.reference_type',
                'je.reference_id',
                'je.description',
                'je.total_debit',
                'je.total_credit',
                'je.status',
                'je.created_at',
                'creator.full_name as created_by_name',
                DB::raw('CONCAT(approver.first_name, " ", approver.last_name) as approved_by_name')
            );

        // Filter by status
        if ($request->filled('status')) {
            $query->where('je.status', $request->status);
        }

        // Filter by journal type
        if ($request->filled('journal_type')) {
            $query->where('je.journal_type', $request->journal_type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('je.journal_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('je.journal_date', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('je.journal_code', 'like', "%{$search}%")
                  ->orWhere('je.description', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'journal_date');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSort = ['journal_code', 'journal_date', 'journal_type', 'status', 'total_debit', 'created_at'];
        if (in_array($sortBy, $allowedSort)) {
            $query->orderBy("je.{$sortBy}", $sortOrder);
        }

        $journalEntries = $query->paginate(20)->withQueryString();

        // Get filter options
        $statuses = DB::table('journal_entries')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $journalTypes = DB::table('journal_entries')
            ->select('journal_type', DB::raw('COUNT(*) as count'))
            ->groupBy('journal_type')
            ->pluck('count', 'journal_type');

        return view('admin.finance.journal-entries.index', compact(
            'journalEntries',
            'statuses',
            'journalTypes'
        ));
    }

    /**
     * Show the form for creating a new journal entry
     */
    public function create()
    {
        // Get active accounts
        $accounts = DB::table('chart_of_accounts')
            ->where('is_active', 1)
            ->orderBy('account_code')
            ->get();

        return view('admin.finance.journal-entries.create', compact('accounts'));
    }

    /**
     * Store a newly created journal entry
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'journal_date' => ['required', 'date'],
            'journal_type' => ['required', 'string', 'in:General,Adjustment,Closing,Opening'],
            'reference_type' => ['nullable', 'string', 'max:100'],
            'reference_id' => ['nullable', 'integer'],
            'description' => ['nullable', 'string'],
            
            // Lines
            'lines' => ['required', 'array', 'min:2'],
            'lines.*.account_id' => ['required', 'exists:chart_of_accounts,account_id'],
            'lines.*.debit_amount' => ['required', 'numeric', 'min:0'],
            'lines.*.credit_amount' => ['required', 'numeric', 'min:0'],
            'lines.*.description' => ['nullable', 'string'],
        ], [
            'journal_date.required' => 'Journal date is required.',
            'lines.required' => 'At least two journal lines are required.',
            'lines.min' => 'At least two journal lines are required.',
        ]);

        // Validate double-entry accounting (debits must equal credits)
        $totalDebit = collect($validated['lines'])->sum('debit_amount');
        $totalCredit = collect($validated['lines'])->sum('credit_amount');

        if (abs($totalDebit - $totalCredit) > 0.01) { // Allow 0.01 rounding difference
            return back()
                ->withInput()
                ->with('error', 'Total debits must equal total credits. Debit: ' . number_format($totalDebit, 2) . ', Credit: ' . number_format($totalCredit, 2));
        }

        // Validate each line has either debit or credit (not both)
        foreach ($validated['lines'] as $index => $line) {
            if ($line['debit_amount'] > 0 && $line['credit_amount'] > 0) {
                return back()
                    ->withInput()
                    ->with('error', "Line " . ($index + 1) . " cannot have both debit and credit amounts.");
            }
            
            if ($line['debit_amount'] == 0 && $line['credit_amount'] == 0) {
                return back()
                    ->withInput()
                    ->with('error', "Line " . ($index + 1) . " must have either debit or credit amount.");
            }
        }

        DB::beginTransaction();
        try {
            // Generate journal code
            $journalCode = CodeGeneratorHelper::generateJournalCode();

            // Insert journal entry
            $journalId = DB::table('journal_entries')->insertGetId([
                'journal_code' => $journalCode,
                'journal_date' => $validated['journal_date'],
                'journal_type' => $validated['journal_type'],
                'reference_type' => $validated['reference_type'] ?? null,
                'reference_id' => $validated['reference_id'] ?? null,
                'description' => $validated['description'] ?? null,
                'total_debit' => $totalDebit,
                'total_credit' => $totalCredit,
                'status' => 'Draft',
                'created_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert journal entry lines
            foreach ($validated['lines'] as $line) {
                DB::table('journal_entry_lines')->insert([
                    'journal_id' => $journalId,
                    'account_id' => $line['account_id'],
                    'debit_amount' => $line['debit_amount'],
                    'credit_amount' => $line['credit_amount'],
                    'description' => $line['description'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Log CREATE
            $this->logCreate(
                'Finance - Journal Entries',
                'journal_entries',
                $journalId,
                array_merge($validated, [
                    'journal_code' => $journalCode,
                    'total_debit' => $totalDebit,
                    'total_credit' => $totalCredit
                ])
            );

            DB::commit();

            return redirect()
                ->route('finance.journal-entries.show', $journalCode)
                ->with('success', 'Journal entry created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create journal entry: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified journal entry
     */
    public function show($journalCode)
    {
        $journalEntry = DB::table('journal_entries as je')
            ->leftJoin('users as creator', 'je.created_by', '=', 'creator.user_id')
            ->leftJoin('employees as approver', 'je.approved_by', '=', 'approver.employee_id')
            ->where('je.journal_code', $journalCode)
            ->select(
                'je.*',
                'creator.full_name as created_by_name',
                DB::raw('CONCAT(approver.first_name, " ", approver.last_name) as approved_by_name')
            )
            ->first();

        if (!$journalEntry) {
            abort(404, 'Journal entry not found');
        }

        // Get journal lines
        $lines = DB::table('journal_entry_lines as jel')
            ->join('chart_of_accounts as coa', 'jel.account_id', '=', 'coa.account_id')
            ->where('jel.journal_id', $journalEntry->journal_id)
            ->select(
                'jel.*',
                'coa.account_code',
                'coa.account_name',
                'coa.account_type'
            )
            ->get();

        // Log VIEW
        $this->logView(
            'Finance - Journal Entries',
            "Viewed journal entry: {$journalCode}"
        );

        return view('admin.finance.journal-entries.show', compact('journalEntry', 'lines'));
    }

    /**
     * Show the form for editing the specified journal entry
     */
    public function edit($journalCode)
    {
        $journalEntry = DB::table('journal_entries')
            ->where('journal_code', $journalCode)
            ->first();

        if (!$journalEntry) {
            abort(404, 'Journal entry not found');
        }

        // Only allow edit if status is Draft
        if ($journalEntry->status !== 'Draft') {
            return back()->with('error', 'Cannot edit journal entry with status: ' . $journalEntry->status);
        }

        // Get accounts
        $accounts = DB::table('chart_of_accounts')
            ->where('is_active', 1)
            ->orderBy('account_code')
            ->get();

        // Get existing lines
        $lines = DB::table('journal_entry_lines as jel')
            ->join('chart_of_accounts as coa', 'jel.account_id', '=', 'coa.account_id')
            ->where('jel.journal_id', $journalEntry->journal_id)
            ->select('jel.*', 'coa.account_code', 'coa.account_name')
            ->get();

        return view('admin.finance.journal-entries.edit', compact('journalEntry', 'accounts', 'lines'));
    }

    /**
     * Update the specified journal entry
     */
    public function update(Request $request, $journalCode)
    {
        $journalEntry = DB::table('journal_entries')
            ->where('journal_code', $journalCode)
            ->first();

        if (!$journalEntry) {
            abort(404, 'Journal entry not found');
        }

        // Only allow update if status is Draft
        if ($journalEntry->status !== 'Draft') {
            return back()->with('error', 'Cannot update journal entry with status: ' . $journalEntry->status);
        }

        $validated = $request->validate([
            'journal_date' => ['required', 'date'],
            'journal_type' => ['required', 'string', 'in:General,Adjustment,Closing,Opening'],
            'reference_type' => ['nullable', 'string', 'max:100'],
            'reference_id' => ['nullable', 'integer'],
            'description' => ['nullable', 'string'],
            
            // Lines
            'lines' => ['required', 'array', 'min:2'],
            'lines.*.account_id' => ['required', 'exists:chart_of_accounts,account_id'],
            'lines.*.debit_amount' => ['required', 'numeric', 'min:0'],
            'lines.*.credit_amount' => ['required', 'numeric', 'min:0'],
            'lines.*.description' => ['nullable', 'string'],
        ]);

        // Validate double-entry
        $totalDebit = collect($validated['lines'])->sum('debit_amount');
        $totalCredit = collect($validated['lines'])->sum('credit_amount');

        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()
                ->withInput()
                ->with('error', 'Total debits must equal total credits.');
        }

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'journal_date' => $journalEntry->journal_date,
                'journal_type' => $journalEntry->journal_type,
                'description' => $journalEntry->description,
                'total_debit' => $journalEntry->total_debit,
                'total_credit' => $journalEntry->total_credit,
            ];

            // Update journal entry
            DB::table('journal_entries')
                ->where('journal_id', $journalEntry->journal_id)
                ->update([
                    'journal_date' => $validated['journal_date'],
                    'journal_type' => $validated['journal_type'],
                    'reference_type' => $validated['reference_type'] ?? null,
                    'reference_id' => $validated['reference_id'] ?? null,
                    'description' => $validated['description'] ?? null,
                    'total_debit' => $totalDebit,
                    'total_credit' => $totalCredit,
                    'updated_at' => now(),
                ]);

            // Delete old lines and insert new ones
            DB::table('journal_entry_lines')
                ->where('journal_id', $journalEntry->journal_id)
                ->delete();

            foreach ($validated['lines'] as $line) {
                DB::table('journal_entry_lines')->insert([
                    'journal_id' => $journalEntry->journal_id,
                    'account_id' => $line['account_id'],
                    'debit_amount' => $line['debit_amount'],
                    'credit_amount' => $line['credit_amount'],
                    'description' => $line['description'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Log UPDATE
            $this->logUpdate(
                'Finance - Journal Entries',
                'journal_entries',
                $journalEntry->journal_id,
                $oldData,
                array_merge($validated, [
                    'total_debit' => $totalDebit,
                    'total_credit' => $totalCredit
                ])
            );

            DB::commit();

            return redirect()
                ->route('finance.journal-entries.show', $journalCode)
                ->with('success', 'Journal entry updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update journal entry: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified journal entry
     */
    public function destroy($journalCode)
    {
        $journalEntry = DB::table('journal_entries')
            ->where('journal_code', $journalCode)
            ->first();

        if (!$journalEntry) {
            abort(404, 'Journal entry not found');
        }

        // Only allow delete if status is Draft
        if ($journalEntry->status !== 'Draft') {
            return back()->with('error', 'Cannot delete journal entry with status: ' . $journalEntry->status);
        }

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'journal_code' => $journalEntry->journal_code,
                'journal_date' => $journalEntry->journal_date,
                'journal_type' => $journalEntry->journal_type,
                'total_debit' => $journalEntry->total_debit,
                'total_credit' => $journalEntry->total_credit,
            ];

            // Delete lines first
            DB::table('journal_entry_lines')
                ->where('journal_id', $journalEntry->journal_id)
                ->delete();

            // Delete journal entry
            DB::table('journal_entries')
                ->where('journal_id', $journalEntry->journal_id)
                ->delete();

            // Log DELETE
            $this->logDelete(
                'Finance - Journal Entries',
                'journal_entries',
                $journalEntry->journal_id,
                $oldData
            );

            DB::commit();

            return redirect()
                ->route('finance.journal-entries.index')
                ->with('success', 'Journal entry deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete journal entry: ' . $e->getMessage());
        }
    }

    /**
     * Post journal entry (make it effective)
     */
    public function post($journalCode)
    {
        $journalEntry = DB::table('journal_entries')
            ->where('journal_code', $journalCode)
            ->first();

        if (!$journalEntry) {
            abort(404, 'Journal entry not found');
        }

        if ($journalEntry->status !== 'Draft') {
            return back()->with('error', 'Journal entry is not in Draft status.');
        }

        DB::beginTransaction();
        try {
            // Update status to Posted
            DB::table('journal_entries')
                ->where('journal_id', $journalEntry->journal_id)
                ->update([
                    'status' => 'Posted',
                    'approved_by' => Auth::user()->employee_id ?? null,
                    'updated_at' => now(),
                ]);

            // Log approval
            $this->logApproval(
                'Finance - Journal Entries',
                'journal_entries',
                $journalEntry->journal_id,
                'posted',
                'Journal entry posted to ledger'
            );

            DB::commit();

            return back()->with('success', 'Journal entry posted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to post journal entry: ' . $e->getMessage());
        }
    }

    /**
     * Reverse journal entry
     */
    public function reverse(Request $request, $journalCode)
    {
        $validated = $request->validate([
            'reversal_date' => ['required', 'date'],
            'reversal_reason' => ['required', 'string', 'max:500'],
        ]);

        $journalEntry = DB::table('journal_entries')
            ->where('journal_code', $journalCode)
            ->first();

        if (!$journalEntry) {
            abort(404, 'Journal entry not found');
        }

        if ($journalEntry->status !== 'Posted') {
            return back()->with('error', 'Can only reverse posted journal entries.');
        }

        DB::beginTransaction();
        try {
            // Generate reversal code
            $reversalCode = CodeGeneratorHelper::generateJournalCode();

            // Get original lines
            $originalLines = DB::table('journal_entry_lines')
                ->where('journal_id', $journalEntry->journal_id)
                ->get();

            // Create reversal entry
            $reversalId = DB::table('journal_entries')->insertGetId([
                'journal_code' => $reversalCode,
                'journal_date' => $validated['reversal_date'],
                'journal_type' => 'Adjustment',
                'reference_type' => 'Journal Reversal',
                'reference_id' => $journalEntry->journal_id,
                'description' => "Reversal of {$journalCode}: {$validated['reversal_reason']}",
                'total_debit' => $journalEntry->total_credit, // Reversed
                'total_credit' => $journalEntry->total_debit, // Reversed
                'status' => 'Posted',
                'created_by' => Auth::id(),
                'approved_by' => Auth::user()->employee_id ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create reversed lines (swap debit and credit)
            foreach ($originalLines as $line) {
                DB::table('journal_entry_lines')->insert([
                    'journal_id' => $reversalId,
                    'account_id' => $line->account_id,
                    'debit_amount' => $line->credit_amount, // Reversed
                    'credit_amount' => $line->debit_amount, // Reversed
                    'description' => "Reversal: " . ($line->description ?? ''),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Mark original as reversed
            DB::table('journal_entries')
                ->where('journal_id', $journalEntry->journal_id)
                ->update([
                    'status' => 'Reversed',
                    'updated_at' => now(),
                ]);

            // Log activity
            $this->logActivity(
                'Journal Reversed',
                "Reversed journal entry {$journalCode}, created reversal {$reversalCode}",
                'Finance - Journal Entries'
            );

            DB::commit();

            return redirect()
                ->route('finance.journal-entries.show', $reversalCode)
                ->with('success', 'Journal entry reversed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reverse journal entry: ' . $e->getMessage());
        }
    }

    /**
     * Export to CSV
     */
    public function export(Request $request)
    {
        $query = DB::table('journal_entries as je')
            ->leftJoin('users', 'je.created_by', '=', 'users.user_id')
            ->select(
                'je.journal_code',
                'je.journal_date',
                'je.journal_type',
                'je.description',
                'je.total_debit',
                'je.total_credit',
                'je.status',
                'users.full_name as created_by'
            );

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('je.status', $request->status);
        }
        if ($request->filled('journal_type')) {
            $query->where('je.journal_type', $request->journal_type);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('je.journal_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('je.journal_date', '<=', $request->date_to);
        }

        $entries = $query->orderBy('je.journal_date', 'desc')
            ->limit(5000)
            ->get();

        $filename = 'journal_entries_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($entries) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'Journal Code',
                'Date',
                'Type',
                'Description',
                'Total Debit',
                'Total Credit',
                'Status',
                'Created By'
            ]);
            
            // Data
            foreach ($entries as $entry) {
                fputcsv($file, [
                    $entry->journal_code,
                    $entry->journal_date,
                    $entry->journal_type,
                    $entry->description ?? '-',
                    $entry->total_debit,
                    $entry->total_credit,
                    $entry->status,
                    $entry->created_by,
                ]);
            }
            
            fclose($file);
        };

        // Log export
        $this->logExport(
            'Finance - Journal Entries',
            'Exported journal entries to CSV'
        );

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Print journal entry
     */
    public function print($journalCode)
    {
        $journalEntry = DB::table('journal_entries as je')
            ->leftJoin('users as creator', 'je.created_by', '=', 'creator.user_id')
            ->where('je.journal_code', $journalCode)
            ->select('je.*', 'creator.full_name as created_by_name')
            ->first();

        if (!$journalEntry) {
            abort(404, 'Journal entry not found');
        }

        // Get lines
        $lines = DB::table('journal_entry_lines as jel')
            ->join('chart_of_accounts as coa', 'jel.account_id', '=', 'coa.account_id')
            ->where('jel.journal_id', $journalEntry->journal_id)
            ->select('jel.*', 'coa.account_code', 'coa.account_name')
            ->get();

        // Log print
        $this->logPrint(
            'Finance - Journal Entries',
            "Printed journal entry: {$journalCode}"
        );

        return view('admin.finance.journal-entries.print', compact('journalEntry', 'lines'));
    }
}
