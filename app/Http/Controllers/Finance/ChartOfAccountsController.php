<?php

namespace App\Http\Controllers\Finance;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartOfAccountsController extends Controller
{
    //
    use LogsActivity;

    /**
     * Display a listing of chart of accounts
     */
    public function index(Request $request)
    {
        $query = DB::table('chart_of_accounts as coa')
            ->leftJoin('chart_of_accounts as parent', 'coa.parent_account_id', '=', 'parent.account_id')
            ->select(
                'coa.account_id',
                'coa.account_code',
                'coa.account_name',
                'coa.account_type',
                'coa.parent_account_id',
                'coa.description',
                'coa.is_active',
                'coa.created_at',
                'parent.account_code as parent_code',
                'parent.account_name as parent_name'
            );

        // Filter by account type
        if ($request->filled('account_type')) {
            $query->where('coa.account_type', $request->account_type);
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('coa.is_active', $request->is_active);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('coa.account_code', 'like', "%{$search}%")
                  ->orWhere('coa.account_name', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'account_code');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $allowedSort = ['account_code', 'account_name', 'account_type', 'is_active', 'created_at'];
        if (in_array($sortBy, $allowedSort)) {
            $query->orderBy("coa.{$sortBy}", $sortOrder);
        }

        $accounts = $query->paginate(20)->withQueryString();

        // Get account types for filter
        $accountTypes = DB::table('chart_of_accounts')
            ->select('account_type', DB::raw('COUNT(*) as count'))
            ->groupBy('account_type')
            ->pluck('count', 'account_type');

        return view('admin.finance.chart-of-accounts.index', compact('accounts', 'accountTypes'));
    }

    /**
     * Show the form for creating a new account
     */
    public function create()
    {
        // Get parent accounts (only root level and active)
        $parentAccounts = DB::table('chart_of_accounts')
            ->where('is_active', 1)
            ->orderBy('account_code')
            ->get();

        return view('admin.finance.chart-of-accounts.create', compact('parentAccounts'));
    }

    /**
     * Store a newly created account
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_name' => ['required', 'string', 'max:200'],
            'account_type' => ['required', 'string', 'in:Asset,Liability,Equity,Revenue,Expense'],
            'parent_account_id' => ['nullable', 'exists:chart_of_accounts,account_id'],
            'description' => ['nullable', 'string'],
        ], [
            'account_name.required' => 'Account name is required.',
            'account_type.required' => 'Account type is required.',
            'account_type.in' => 'Invalid account type.',
        ]);

        DB::beginTransaction();
        try {
            // Generate account code
            $accountCode = CodeGeneratorHelper::generateAccountCode();

            $accountId = DB::table('chart_of_accounts')->insertGetId([
                'account_code' => $accountCode,
                'account_name' => $validated['account_name'],
                'account_type' => $validated['account_type'],
                'parent_account_id' => $validated['parent_account_id'] ?? null,
                'description' => $validated['description'] ?? null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Log CREATE
            $this->logCreate(
                'Finance - Chart of Accounts',
                'chart_of_accounts',
                $accountId,
                array_merge($validated, ['account_code' => $accountCode])
            );

            DB::commit();

            return redirect()
                ->route('finance.chart-of-accounts.index')
                ->with('success', 'Account created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create account: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified account
     */
    public function show($accountCode)
    {
        $account = DB::table('chart_of_accounts as coa')
            ->leftJoin('chart_of_accounts as parent', 'coa.parent_account_id', '=', 'parent.account_id')
            ->where('coa.account_code', $accountCode)
            ->select(
                'coa.*',
                'parent.account_code as parent_code',
                'parent.account_name as parent_name'
            )
            ->first();

        if (!$account) {
            abort(404, 'Account not found');
        }

        // Get child accounts
        $childAccounts = DB::table('chart_of_accounts')
            ->where('parent_account_id', $account->account_id)
            ->orderBy('account_code')
            ->get();

        // Get recent journal entries for this account
        $recentEntries = DB::table('journal_entry_lines as jel')
            ->join('journal_entries as je', 'jel.journal_id', '=', 'je.journal_id')
            ->where('jel.account_id', $account->account_id)
            ->select(
                'je.journal_code',
                'je.journal_date',
                'je.description',
                'jel.debit_amount',
                'jel.credit_amount',
                'je.status'
            )
            ->orderBy('je.journal_date', 'desc')
            ->limit(10)
            ->get();

        // Calculate account balance
        $balance = $this->calculateAccountBalance($account->account_id);

        // Log VIEW
        $this->logView(
            'Finance - Chart of Accounts',
            "Viewed account: {$account->account_name} ({$accountCode})"
        );

        return view('admin.finance.chart-of-accounts.show', compact(
            'account',
            'childAccounts',
            'recentEntries',
            'balance'
        ));
    }

    /**
     * Show the form for editing the specified account
     */
    public function edit($accountCode)
    {
        $account = DB::table('chart_of_accounts')
            ->where('account_code', $accountCode)
            ->first();

        if (!$account) {
            abort(404, 'Account not found');
        }

        // Get parent accounts (excluding self and its children)
        $parentAccounts = DB::table('chart_of_accounts')
            ->where('is_active', 1)
            ->where('account_id', '!=', $account->account_id)
            ->orderBy('account_code')
            ->get();

        return view('admin.finance.chart-of-accounts.edit', compact('account', 'parentAccounts'));
    }

    /**
     * Update the specified account
     */
    public function update(Request $request, $accountCode)
    {
        $account = DB::table('chart_of_accounts')
            ->where('account_code', $accountCode)
            ->first();

        if (!$account) {
            abort(404, 'Account not found');
        }

        $validated = $request->validate([
            'account_name' => ['required', 'string', 'max:200'],
            'account_type' => ['required', 'string', 'in:Asset,Liability,Equity,Revenue,Expense'],
            'parent_account_id' => ['nullable', 'exists:chart_of_accounts,account_id'],
            'description' => ['nullable', 'string'],
        ]);

        // Prevent circular reference
        if ($validated['parent_account_id'] == $account->account_id) {
            return back()
                ->withInput()
                ->with('error', 'Account cannot be its own parent.');
        }

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'account_name' => $account->account_name,
                'account_type' => $account->account_type,
                'parent_account_id' => $account->parent_account_id,
                'description' => $account->description,
            ];

            DB::table('chart_of_accounts')
                ->where('account_id', $account->account_id)
                ->update([
                    'account_name' => $validated['account_name'],
                    'account_type' => $validated['account_type'],
                    'parent_account_id' => $validated['parent_account_id'] ?? null,
                    'description' => $validated['description'] ?? null,
                    'updated_at' => now(),
                ]);

            // Log UPDATE
            $this->logUpdate(
                'Finance - Chart of Accounts',
                'chart_of_accounts',
                $account->account_id,
                $oldData,
                $validated
            );

            DB::commit();

            return redirect()
                ->route('finance.chart-of-accounts.index')
                ->with('success', 'Account updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update account: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified account
     */
    public function destroy($accountCode)
    {
        $account = DB::table('chart_of_accounts')
            ->where('account_code', $accountCode)
            ->first();

        if (!$account) {
            abort(404, 'Account not found');
        }

        // Check if account has child accounts
        $hasChildren = DB::table('chart_of_accounts')
            ->where('parent_account_id', $account->account_id)
            ->exists();

        if ($hasChildren) {
            return back()->with('error', 'Cannot delete account that has child accounts.');
        }

        // Check if account has journal entries
        $hasEntries = DB::table('journal_entry_lines')
            ->where('account_id', $account->account_id)
            ->exists();

        if ($hasEntries) {
            return back()->with('error', 'Cannot delete account that has journal entries. Consider deactivating instead.');
        }

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'account_code' => $account->account_code,
                'account_name' => $account->account_name,
                'account_type' => $account->account_type,
            ];

            DB::table('chart_of_accounts')
                ->where('account_id', $account->account_id)
                ->delete();

            // Log DELETE
            $this->logDelete(
                'Finance - Chart of Accounts',
                'chart_of_accounts',
                $account->account_id,
                $oldData
            );

            DB::commit();

            return redirect()
                ->route('finance.chart-of-accounts.index')
                ->with('success', 'Account deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete account: ' . $e->getMessage());
        }
    }

    /**
     * Toggle account status
     */
    public function toggleStatus($accountCode)
    {
        $account = DB::table('chart_of_accounts')
            ->where('account_code', $accountCode)
            ->first();

        if (!$account) {
            abort(404, 'Account not found');
        }

        DB::beginTransaction();
        try {
            $newStatus = !$account->is_active;

            DB::table('chart_of_accounts')
                ->where('account_id', $account->account_id)
                ->update([
                    'is_active' => $newStatus,
                    'updated_at' => now(),
                ]);

            // Log activity
            $statusText = $newStatus ? 'activated' : 'deactivated';
            $this->logActivity(
                'Status Changed',
                "Account {$account->account_name} {$statusText}",
                'Finance - Chart of Accounts'
            );

            DB::commit();

            return back()->with('success', "Account {$statusText} successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }

    /**
     * Calculate account balance
     */
    private function calculateAccountBalance($accountId): array
    {
        $totals = DB::table('journal_entry_lines as jel')
            ->join('journal_entries as je', 'jel.journal_id', '=', 'je.journal_id')
            ->where('jel.account_id', $accountId)
            ->where('je.status', 'Posted')
            ->select(
                DB::raw('SUM(jel.debit_amount) as total_debit'),
                DB::raw('SUM(jel.credit_amount) as total_credit')
            )
            ->first();

        $debit = $totals->total_debit ?? 0;
        $credit = $totals->total_credit ?? 0;
        $balance = $debit - $credit;

        return [
            'total_debit' => $debit,
            'total_credit' => $credit,
            'balance' => $balance,
            'balance_type' => $balance >= 0 ? 'Debit' : 'Credit',
        ];
    }

    /**
     * Export to CSV
     */
    public function export(Request $request)
    {
        $query = DB::table('chart_of_accounts as coa')
            ->leftJoin('chart_of_accounts as parent', 'coa.parent_account_id', '=', 'parent.account_id')
            ->select(
                'coa.account_code',
                'coa.account_name',
                'coa.account_type',
                'parent.account_name as parent_account',
                'coa.is_active'
            );

        // Apply same filters as index
        if ($request->filled('account_type')) {
            $query->where('coa.account_type', $request->account_type);
        }
        if ($request->filled('is_active')) {
            $query->where('coa.is_active', $request->is_active);
        }

        $accounts = $query->orderBy('coa.account_code')
            ->limit(5000)
            ->get();

        $filename = 'chart_of_accounts_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($accounts) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'Account Code',
                'Account Name',
                'Account Type',
                'Parent Account',
                'Status'
            ]);
            
            // Data
            foreach ($accounts as $account) {
                fputcsv($file, [
                    $account->account_code,
                    $account->account_name,
                    $account->account_type,
                    $account->parent_account ?? '-',
                    $account->is_active ? 'Active' : 'Inactive',
                ]);
            }
            
            fclose($file);
        };

        // Log export
        $this->logExport(
            'Finance - Chart of Accounts',
            'Exported chart of accounts to CSV'
        );

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get account hierarchy (for tree view)
     */
    public function hierarchy()
    {
        $accounts = DB::table('chart_of_accounts')
            ->where('is_active', 1)
            ->orderBy('account_code')
            ->get();

        // Build tree structure
        $tree = $this->buildAccountTree($accounts);

        return view('admin.finance.chart-of-accounts.hierarchy', compact('tree'));
    }

    /**
     * Build account tree structure
     */
    private function buildAccountTree($accounts, $parentId = null): array
    {
        $branch = [];

        foreach ($accounts as $account) {
            if ($account->parent_account_id == $parentId) {
                $children = $this->buildAccountTree($accounts, $account->account_id);
                
                $branch[] = [
                    'account' => $account,
                    'children' => $children,
                ];
            }
        }

        return $branch;
    }
}
