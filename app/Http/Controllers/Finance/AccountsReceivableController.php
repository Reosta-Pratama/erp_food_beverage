<?php

namespace App\Http\Controllers\Finance;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountsReceivableController extends Controller
{
    //
     use LogsActivity;

    public function index(Request $request)
    {
        $query = DB::table('accounts_receivable as ar')
            ->join('customers as c', 'ar.customer_id', '=', 'c.customer_id')
            ->leftJoin('sales_orders as so', 'ar.so_id', '=', 'so.so_id')
            ->select(
                'ar.*',
                'c.customer_code',
                'c.customer_name',
                'so.so_code',
                DB::raw('DATEDIFF(ar.due_date, CURDATE()) as days_until_due')
            );

        if ($request->filled('customer_id')) $query->where('ar.customer_id', $request->customer_id);
        if ($request->filled('status')) $query->where('ar.status', $request->status);
        if ($request->filled('overdue')) {
            $query->where('ar.status', '!=', 'Paid')
                  ->whereDate('ar.due_date', '<', now());
        }
        if ($request->filled('date_from')) $query->whereDate('ar.due_date', '>=', $request->date_from);
        if ($request->filled('date_to')) $query->whereDate('ar.due_date', '<=', $request->date_to);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ar.ar_code', 'like', "%{$search}%")
                  ->orWhere('c.customer_name', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->get('sort_by', 'due_date');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $receivables = $query->paginate(20)->withQueryString();

        $customers = DB::table('customers')->where('is_active', 1)->orderBy('customer_name')->get();
        $statuses = DB::table('accounts_receivable')->select('status', DB::raw('COUNT(*) as count'))->groupBy('status')->pluck('count', 'status');

        return view('admin.finance.accounts-receivable.index', compact('receivables', 'customers', 'statuses'));
    }

    public function create()
    {
        $customers = DB::table('customers')->where('is_active', 1)->orderBy('customer_name')->get();
        $salesOrders = DB::table('sales_orders as so')
            ->join('customers as c', 'so.customer_id', '=', 'c.customer_id')
            ->where('so.status', '!=', 'Cancelled')
            ->select('so.so_id', 'so.so_code', 'c.customer_name', 'so.total_amount')
            ->orderBy('so.order_date', 'desc')
            ->get();

        return view('admin.finance.accounts-receivable.create', compact('customers', 'salesOrders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,customer_id'],
            'so_id' => ['nullable', 'exists:sales_orders,so_id'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:invoice_date'],
            'invoice_amount' => ['required', 'numeric', 'min:0.01'],
            'payment_terms' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::beginTransaction();
        try {
            $arCode = CodeGeneratorHelper::generateARCode();

            $arId = DB::table('accounts_receivable')->insertGetId([
                'ar_code' => $arCode,
                'customer_id' => $validated['customer_id'],
                'so_id' => $validated['so_id'] ?? null,
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'],
                'invoice_amount' => $validated['invoice_amount'],
                'paid_amount' => 0,
                'balance_amount' => $validated['invoice_amount'],
                'status' => 'Pending',
                'payment_terms' => $validated['payment_terms'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->logCreate('Finance - Accounts Receivable', 'accounts_receivable', $arId, array_merge($validated, ['ar_code' => $arCode]));

            DB::commit();
            return redirect()->route('finance.accounts-receivable.show', $arCode)->with('success', 'AR invoice created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create: ' . $e->getMessage());
        }
    }

    public function show($arCode)
    {
        $receivable = DB::table('accounts_receivable as ar')
            ->join('customers as c', 'ar.customer_id', '=', 'c.customer_id')
            ->leftJoin('sales_orders as so', 'ar.so_id', '=', 'so.so_id')
            ->where('ar.ar_code', $arCode)
            ->select('ar.*', 'c.*', 'so.so_code')
            ->first();

        if (!$receivable) abort(404, 'AR invoice not found');

        $payments = DB::table('payments')
            ->where('ar_id', $receivable->ar_id)
            ->orderBy('payment_date', 'desc')
            ->get();

        $this->logView('Finance - Accounts Receivable', "Viewed AR invoice: {$arCode}");

        return view('admin.finance.accounts-receivable.show', compact('receivable', 'payments'));
    }

    public function recordPayment(Request $request, $arCode)
    {
        $validated = $request->validate([
            'payment_amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', 'string', 'max:30'],
            'bank_account' => ['nullable', 'string', 'max:100'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $receivable = DB::table('accounts_receivable')->where('ar_code', $arCode)->first();
        if (!$receivable) abort(404);

        if ($validated['payment_amount'] > $receivable->balance_amount) {
            return back()->with('error', 'Payment amount exceeds balance.');
        }

        DB::beginTransaction();
        try {
            $paymentCode = CodeGeneratorHelper::generatePaymentCode();
            $newPaidAmount = $receivable->paid_amount + $validated['payment_amount'];
            $newBalance = $receivable->balance_amount - $validated['payment_amount'];
            $newStatus = $newBalance == 0 ? 'Paid' : 'Partial';

            // Create payment record
            DB::table('payments')->insert([
                'payment_code' => $paymentCode,
                'payment_type' => 'Receivable',
                'customer_id' => $receivable->customer_id,
                'ar_id' => $receivable->ar_id,
                'payment_date' => $validated['payment_date'],
                'payment_amount' => $validated['payment_amount'],
                'payment_method' => $validated['payment_method'],
                'bank_account' => $validated['bank_account'] ?? null,
                'reference_number' => $validated['reference_number'] ?? null,
                'status' => 'Completed',
                'processed_by' => Auth::id(),
                'notes' => $validated['notes'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update AR
            DB::table('accounts_receivable')
                ->where('ar_id', $receivable->ar_id)
                ->update([
                    'paid_amount' => $newPaidAmount,
                    'balance_amount' => $newBalance,
                    'status' => $newStatus,
                    'updated_at' => now(),
                ]);

            $this->logActivity('Payment Received', "Received payment of {$validated['payment_amount']} for AR {$arCode}", 'Finance - Accounts Receivable');

            DB::commit();
            return back()->with('success', 'Payment received successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to record payment: ' . $e->getMessage());
        }
    }

    public function sendReminder($arCode)
    {
        $receivable = DB::table('accounts_receivable as ar')
            ->join('customers as c', 'ar.customer_id', '=', 'c.customer_id')
            ->where('ar.ar_code', $arCode)
            ->select('ar.*', 'c.customer_name', 'c.email')
            ->first();

        if (!$receivable) abort(404);

        // TODO: Implement email sending
        // Mail::to($receivable->email)->send(new PaymentReminder($receivable));

        $this->logActivity('Reminder Sent', "Sent payment reminder for AR {$arCode} to {$receivable->customer_name}", 'Finance - Accounts Receivable');

        return back()->with('success', 'Payment reminder sent successfully.');
    }

    public function agingReport()
    {
        $aging = DB::table('accounts_receivable as ar')
            ->join('customers as c', 'ar.customer_id', '=', 'c.customer_id')
            ->where('ar.status', '!=', 'Paid')
            ->select(
                'c.customer_name',
                DB::raw('SUM(CASE WHEN DATEDIFF(CURDATE(), ar.due_date) <= 0 THEN ar.balance_amount ELSE 0 END) as current'),
                DB::raw('SUM(CASE WHEN DATEDIFF(CURDATE(), ar.due_date) BETWEEN 1 AND 30 THEN ar.balance_amount ELSE 0 END) as days_1_30'),
                DB::raw('SUM(CASE WHEN DATEDIFF(CURDATE(), ar.due_date) BETWEEN 31 AND 60 THEN ar.balance_amount ELSE 0 END) as days_31_60'),
                DB::raw('SUM(CASE WHEN DATEDIFF(CURDATE(), ar.due_date) BETWEEN 61 AND 90 THEN ar.balance_amount ELSE 0 END) as days_61_90'),
                DB::raw('SUM(CASE WHEN DATEDIFF(CURDATE(), ar.due_date) > 90 THEN ar.balance_amount ELSE 0 END) as over_90'),
                DB::raw('SUM(ar.balance_amount) as total')
            )
            ->groupBy('c.customer_id', 'c.customer_name')
            ->orderBy('total', 'desc')
            ->get();

        $this->logView('Finance - Accounts Receivable', 'Viewed AR aging report');

        return view('admin.finance.accounts-receivable.aging-report', compact('aging'));
    }

    public function export(Request $request)
    {
        $query = DB::table('accounts_receivable as ar')
            ->join('customers as c', 'ar.customer_id', '=', 'c.customer_id')
            ->select('ar.ar_code', 'ar.invoice_date', 'ar.due_date', 'c.customer_name', 'ar.invoice_amount', 'ar.balance_amount', 'ar.status');

        if ($request->filled('status')) $query->where('ar.status', $request->status);
        
        $data = $query->orderBy('ar.due_date')->limit(5000)->get();
        $filename = 'accounts_receivable_' . now()->format('Y-m-d_His') . '.csv';

        $this->logExport('Finance - Accounts Receivable', 'Exported AR invoices to CSV');

        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"{$filename}\""];
        
        return response()->stream(function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['AR Code', 'Invoice Date', 'Due Date', 'Customer', 'Invoice Amount', 'Balance', 'Status']);
            foreach ($data as $row) {
                fputcsv($file, [$row->ar_code, $row->invoice_date, $row->due_date, $row->customer_name, $row->invoice_amount, $row->balance_amount, $row->status]);
            }
            fclose($file);
        }, 200, $headers);
    }
}
