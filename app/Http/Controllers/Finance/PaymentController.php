<?php

namespace App\Http\Controllers\Finance;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    //
    use LogsActivity;

    /**
     * Display a listing of payments
     */
    public function index(Request $request)
    {
        $query = DB::table('payments as p')
            ->leftJoin('suppliers as s', 'p.supplier_id', '=', 's.supplier_id')
            ->leftJoin('customers as c', 'p.customer_id', '=', 'c.customer_id')
            ->leftJoin('accounts_payable as ap', 'p.ap_id', '=', 'ap.ap_id')
            ->leftJoin('accounts_receivable as ar', 'p.ar_id', '=', 'ar.ar_id')
            ->leftJoin('employees as processor', 'p.processed_by', '=', 'processor.employee_id')
            ->select(
                'p.payment_id',
                'p.payment_code',
                'p.payment_type',
                'p.payment_date',
                'p.payment_amount',
                'p.payment_method',
                'p.status',
                'p.reference_number',
                'p.created_at',
                's.supplier_code',
                's.supplier_name',
                'c.customer_code',
                'c.customer_name',
                'ap.ap_code',
                'ar.ar_code',
                DB::raw('CONCAT(processor.first_name, " ", processor.last_name) as processor_name')
            );

        // Filter by payment type
        if ($request->filled('payment_type')) {
            $query->where('p.payment_type', $request->payment_type);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('p.payment_method', $request->payment_method);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('p.status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('p.payment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('p.payment_date', '<=', $request->date_to);
        }

        // Filter by supplier or customer
        if ($request->filled('supplier_id')) {
            $query->where('p.supplier_id', $request->supplier_id);
        }
        if ($request->filled('customer_id')) {
            $query->where('p.customer_id', $request->customer_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('p.payment_code', 'like', "%{$search}%")
                  ->orWhere('p.reference_number', 'like', "%{$search}%")
                  ->orWhere('s.supplier_name', 'like', "%{$search}%")
                  ->orWhere('c.customer_name', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'payment_date');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSort = ['payment_code', 'payment_date', 'payment_type', 'payment_amount', 'status', 'created_at'];
        if (in_array($sortBy, $allowedSort)) {
            $query->orderBy("p.{$sortBy}", $sortOrder);
        }

        $payments = $query->paginate(20)->withQueryString();

        // Get filter options
        $paymentTypes = ['Payable', 'Receivable'];
        
        $paymentMethods = DB::table('payments')
            ->select('payment_method', DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->pluck('count', 'payment_method');

        $statuses = DB::table('payments')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $suppliers = DB::table('suppliers')->where('is_active', 1)->orderBy('supplier_name')->get();
        $customers = DB::table('customers')->where('is_active', 1)->orderBy('customer_name')->get();

        return view('admin.finance.payments.index', compact(
            'payments',
            'paymentTypes',
            'paymentMethods',
            'statuses',
            'suppliers',
            'customers'
        ));
    }

    /**
     * Show the form for creating a new payment
     */
    public function create(Request $request)
    {
        $paymentType = $request->get('type', 'Payable');

        if ($paymentType === 'Payable') {
            // Get pending/partial AP invoices
            $invoices = DB::table('accounts_payable as ap')
                ->join('suppliers as s', 'ap.supplier_id', '=', 's.supplier_id')
                ->whereIn('ap.status', ['Pending', 'Partial'])
                ->select(
                    'ap.ap_id',
                    'ap.ap_code',
                    's.supplier_name',
                    'ap.invoice_date',
                    'ap.due_date',
                    'ap.balance_amount'
                )
                ->orderBy('ap.due_date', 'asc')
                ->get();
            
            $suppliers = DB::table('suppliers')->where('is_active', 1)->orderBy('supplier_name')->get();
            
            return view('admin.finance.payments.create-payable', compact('invoices', 'suppliers'));
        } else {
            // Get pending/partial AR invoices
            $invoices = DB::table('accounts_receivable as ar')
                ->join('customers as c', 'ar.customer_id', '=', 'c.customer_id')
                ->whereIn('ar.status', ['Pending', 'Partial'])
                ->select(
                    'ar.ar_id',
                    'ar.ar_code',
                    'c.customer_name',
                    'ar.invoice_date',
                    'ar.due_date',
                    'ar.balance_amount'
                )
                ->orderBy('ar.due_date', 'asc')
                ->get();
            
            $customers = DB::table('customers')->where('is_active', 1)->orderBy('customer_name')->get();
            
            return view('admin.finance.payments.create-receivable', compact('invoices', 'customers'));
        }
    }

    /**
     * Store a newly created payment (Payable)
     */
    public function storePayable(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,supplier_id'],
            'ap_id' => ['nullable', 'exists:accounts_payable,ap_id'],
            'payment_date' => ['required', 'date', 'before_or_equal:today'],
            'payment_amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'string', 'in:Cash,Bank Transfer,Check,Credit Card,Debit Card,E-Wallet'],
            'bank_account' => ['nullable', 'string', 'max:100'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        // Validate payment amount if linked to AP
        if ($validated['ap_id']) {
            $ap = DB::table('accounts_payable')->where('ap_id', $validated['ap_id'])->first();
            
            if ($validated['payment_amount'] > $ap->balance_amount) {
                return back()
                    ->withInput()
                    ->with('error', 'Payment amount exceeds AP balance.');
            }
        }

        DB::beginTransaction();
        try {
            $paymentCode = CodeGeneratorHelper::generatePaymentCode();

            // Insert payment
            $paymentId = DB::table('payments')->insertGetId([
                'payment_code' => $paymentCode,
                'payment_type' => 'Payable',
                'supplier_id' => $validated['supplier_id'],
                'ap_id' => $validated['ap_id'] ?? null,
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

            // Update AP if linked
            if ($validated['ap_id']) {
                $newPaidAmount = $ap->paid_amount + $validated['payment_amount'];
                $newBalance = $ap->balance_amount - $validated['payment_amount'];
                $newStatus = $newBalance <= 0.01 ? 'Paid' : 'Partial';

                DB::table('accounts_payable')
                    ->where('ap_id', $ap->ap_id)
                    ->update([
                        'paid_amount' => $newPaidAmount,
                        'balance_amount' => $newBalance,
                        'status' => $newStatus,
                        'updated_at' => now(),
                    ]);
            }

            // Log CREATE
            $this->logCreate(
                'Finance - Payments',
                'payments',
                $paymentId,
                array_merge($validated, ['payment_code' => $paymentCode])
            );

            DB::commit();

            return redirect()
                ->route('finance.payments.show', $paymentCode)
                ->with('success', 'Payment created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create payment: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created payment (Receivable)
     */
    public function storeReceivable(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,customer_id'],
            'ar_id' => ['nullable', 'exists:accounts_receivable,ar_id'],
            'payment_date' => ['required', 'date', 'before_or_equal:today'],
            'payment_amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'string', 'in:Cash,Bank Transfer,Check,Credit Card,Debit Card,E-Wallet'],
            'bank_account' => ['nullable', 'string', 'max:100'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        // Validate payment amount if linked to AR
        if ($validated['ar_id']) {
            $ar = DB::table('accounts_receivable')->where('ar_id', $validated['ar_id'])->first();
            
            if ($validated['payment_amount'] > $ar->balance_amount) {
                return back()
                    ->withInput()
                    ->with('error', 'Payment amount exceeds AR balance.');
            }
        }

        DB::beginTransaction();
        try {
            $paymentCode = CodeGeneratorHelper::generatePaymentCode();

            // Insert payment
            $paymentId = DB::table('payments')->insertGetId([
                'payment_code' => $paymentCode,
                'payment_type' => 'Receivable',
                'customer_id' => $validated['customer_id'],
                'ar_id' => $validated['ar_id'] ?? null,
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

            // Update AR if linked
            if ($validated['ar_id']) {
                $newPaidAmount = $ar->paid_amount + $validated['payment_amount'];
                $newBalance = $ar->balance_amount - $validated['payment_amount'];
                $newStatus = $newBalance <= 0.01 ? 'Paid' : 'Partial';

                DB::table('accounts_receivable')
                    ->where('ar_id', $ar->ar_id)
                    ->update([
                        'paid_amount' => $newPaidAmount,
                        'balance_amount' => $newBalance,
                        'status' => $newStatus,
                        'updated_at' => now(),
                    ]);
            }

            // Log CREATE
            $this->logCreate(
                'Finance - Payments',
                'payments',
                $paymentId,
                array_merge($validated, ['payment_code' => $paymentCode])
            );

            DB::commit();

            return redirect()
                ->route('finance.payments.show', $paymentCode)
                ->with('success', 'Payment received successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to receive payment: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified payment
     */
    public function show($paymentCode)
    {
        $payment = DB::table('payments as p')
            ->leftJoin('suppliers as s', 'p.supplier_id', '=', 's.supplier_id')
            ->leftJoin('customers as c', 'p.customer_id', '=', 'c.customer_id')
            ->leftJoin('accounts_payable as ap', 'p.ap_id', '=', 'ap.ap_id')
            ->leftJoin('accounts_receivable as ar', 'p.ar_id', '=', 'ar.ar_id')
            ->leftJoin('employees as processor', 'p.processed_by', '=', 'processor.employee_id')
            ->where('p.payment_code', $paymentCode)
            ->select(
                'p.*',
                's.supplier_code',
                's.supplier_name',
                's.contact_person as supplier_contact',
                's.email as supplier_email',
                'c.customer_code',
                'c.customer_name',
                'c.contact_person as customer_contact',
                'c.email as customer_email',
                'ap.ap_code',
                'ap.invoice_amount as ap_invoice_amount',
                'ap.balance_amount as ap_balance',
                'ar.ar_code',
                'ar.invoice_amount as ar_invoice_amount',
                'ar.balance_amount as ar_balance',
                DB::raw('CONCAT(processor.first_name, " ", processor.last_name) as processor_name')
            )
            ->first();

        if (!$payment) {
            abort(404, 'Payment not found');
        }

        // Log VIEW
        $this->logView(
            'Finance - Payments',
            "Viewed payment: {$paymentCode}"
        );

        return view('admin.finance.payments.show', compact('payment'));
    }

    /**
     * Void/Cancel payment
     */
    public function void(Request $request, $paymentCode)
    {
        $validated = $request->validate([
            'void_reason' => ['required', 'string', 'max:500'],
        ]);

        $payment = DB::table('payments')->where('payment_code', $paymentCode)->first();

        if (!$payment) {
            abort(404, 'Payment not found');
        }

        if ($payment->status !== 'Completed') {
            return back()->with('error', 'Can only void completed payments.');
        }

        DB::beginTransaction();
        try {
            // Update payment status to Voided
            DB::table('payments')
                ->where('payment_id', $payment->payment_id)
                ->update([
                    'status' => 'Voided',
                    'notes' => ($payment->notes ? $payment->notes . "\n\n" : '') 
                              . "VOIDED: {$validated['void_reason']}",
                    'updated_at' => now(),
                ]);

            // Revert AP/AR if linked
            if ($payment->ap_id) {
                $ap = DB::table('accounts_payable')->where('ap_id', $payment->ap_id)->first();
                $newPaidAmount = $ap->paid_amount - $payment->payment_amount;
                $newBalance = $ap->balance_amount + $payment->payment_amount;
                $newStatus = $newPaidAmount <= 0.01 ? 'Pending' : 'Partial';

                DB::table('accounts_payable')
                    ->where('ap_id', $ap->ap_id)
                    ->update([
                        'paid_amount' => $newPaidAmount,
                        'balance_amount' => $newBalance,
                        'status' => $newStatus,
                        'updated_at' => now(),
                    ]);
            }

            if ($payment->ar_id) {
                $ar = DB::table('accounts_receivable')->where('ar_id', $payment->ar_id)->first();
                $newPaidAmount = $ar->paid_amount - $payment->payment_amount;
                $newBalance = $ar->balance_amount + $payment->payment_amount;
                $newStatus = $newPaidAmount <= 0.01 ? 'Pending' : 'Partial';

                DB::table('accounts_receivable')
                    ->where('ar_id', $ar->ar_id)
                    ->update([
                        'paid_amount' => $newPaidAmount,
                        'balance_amount' => $newBalance,
                        'status' => $newStatus,
                        'updated_at' => now(),
                    ]);
            }

            // Log activity
            $this->logActivity(
                'Payment Voided',
                "Voided payment {$paymentCode}: {$validated['void_reason']}",
                'Finance - Payments'
            );

            DB::commit();

            return back()->with('success', 'Payment voided successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to void payment: ' . $e->getMessage());
        }
    }

    /**
     * Export payments to CSV
     */
    public function export(Request $request)
    {
        $query = DB::table('payments as p')
            ->leftJoin('suppliers as s', 'p.supplier_id', '=', 's.supplier_id')
            ->leftJoin('customers as c', 'p.customer_id', '=', 'c.customer_id')
            ->select(
                'p.payment_code',
                'p.payment_date',
                'p.payment_type',
                's.supplier_name',
                'c.customer_name',
                'p.payment_amount',
                'p.payment_method',
                'p.reference_number',
                'p.status'
            );

        // Apply same filters as index
        if ($request->filled('payment_type')) {
            $query->where('p.payment_type', $request->payment_type);
        }
        if ($request->filled('payment_method')) {
            $query->where('p.payment_method', $request->payment_method);
        }
        if ($request->filled('status')) {
            $query->where('p.status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('p.payment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('p.payment_date', '<=', $request->date_to);
        }

        $payments = $query->orderBy('p.payment_date', 'desc')
            ->limit(5000)
            ->get();

        $filename = 'payments_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'Payment Code',
                'Date',
                'Type',
                'Supplier/Customer',
                'Amount',
                'Method',
                'Reference',
                'Status'
            ]);
            
            // Data
            foreach ($payments as $payment) {
                $party = $payment->payment_type === 'Payable' 
                    ? $payment->supplier_name 
                    : $payment->customer_name;
                
                fputcsv($file, [
                    $payment->payment_code,
                    $payment->payment_date,
                    $payment->payment_type,
                    $party,
                    $payment->payment_amount,
                    $payment->payment_method,
                    $payment->reference_number ?? '-',
                    $payment->status,
                ]);
            }
            
            fclose($file);
        };

        // Log export
        $this->logExport(
            'Finance - Payments',
            'Exported payments to CSV'
        );

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Print payment receipt
     */
    public function print($paymentCode)
    {
        $payment = DB::table('payments as p')
            ->leftJoin('suppliers as s', 'p.supplier_id', '=', 's.supplier_id')
            ->leftJoin('customers as c', 'p.customer_id', '=', 'c.customer_id')
            ->leftJoin('accounts_payable as ap', 'p.ap_id', '=', 'ap.ap_id')
            ->leftJoin('accounts_receivable as ar', 'p.ar_id', '=', 'ar.ar_id')
            ->where('p.payment_code', $paymentCode)
            ->select('p.*', 's.*', 'c.*', 'ap.ap_code', 'ar.ar_code')
            ->first();

        if (!$payment) {
            abort(404, 'Payment not found');
        }

        // Log print
        $this->logPrint(
            'Finance - Payments',
            "Printed payment receipt: {$paymentCode}"
        );

        return view('admin.finance.payments.print', compact('payment'));
    }

    /**
     * Payment summary/dashboard
     */
    public function summary(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        // Total payments by type
        $summary = [
            'payable' => [
                'total' => DB::table('payments')
                    ->where('payment_type', 'Payable')
                    ->where('status', 'Completed')
                    ->whereBetween('payment_date', [$dateFrom, $dateTo])
                    ->sum('payment_amount') ?? 0,
                
                'count' => DB::table('payments')
                    ->where('payment_type', 'Payable')
                    ->where('status', 'Completed')
                    ->whereBetween('payment_date', [$dateFrom, $dateTo])
                    ->count(),
            ],
            
            'receivable' => [
                'total' => DB::table('payments')
                    ->where('payment_type', 'Receivable')
                    ->where('status', 'Completed')
                    ->whereBetween('payment_date', [$dateFrom, $dateTo])
                    ->sum('payment_amount') ?? 0,
                
                'count' => DB::table('payments')
                    ->where('payment_type', 'Receivable')
                    ->where('status', 'Completed')
                    ->whereBetween('payment_date', [$dateFrom, $dateTo])
                    ->count(),
            ],
        ];

        // By payment method
        $byMethod = DB::table('payments')
            ->where('status', 'Completed')
            ->whereBetween('payment_date', [$dateFrom, $dateTo])
            ->select(
                'payment_method',
                DB::raw('SUM(payment_amount) as total_amount'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('payment_method')
            ->get();

        // Daily trend
        $dailyTrend = DB::table('payments')
            ->where('status', 'Completed')
            ->whereBetween('payment_date', [$dateFrom, $dateTo])
            ->select(
                DB::raw('DATE(payment_date) as date'),
                'payment_type',
                DB::raw('SUM(payment_amount) as total_amount')
            )
            ->groupBy(DB::raw('DATE(payment_date)'), 'payment_type')
            ->orderBy('date')
            ->get();

        return view('admin.finance.payments.summary', compact(
            'summary',
            'byMethod',
            'dailyTrend',
            'dateFrom',
            'dateTo'
        ));
    }
}
