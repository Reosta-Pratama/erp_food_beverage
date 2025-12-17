<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentReconciliationController extends Controller
{
    //
    use LogsActivity;

    /**
     * Display reconciliation dashboard
     */
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $paymentMethod = $request->get('payment_method', 'Bank Transfer');

        // Get payments for the period
        $payments = DB::table('payments')
            ->where('status', 'Completed')
            ->where('payment_method', $paymentMethod)
            ->whereRaw('DATE_FORMAT(payment_date, "%Y-%m") = ?', [$month])
            ->select(
                'payment_id',
                'payment_code',
                'payment_date',
                'payment_type',
                'payment_amount',
                'reference_number',
                'bank_account'
            )
            ->orderBy('payment_date')
            ->get();

        // Calculate totals
        $totalPayable = $payments->where('payment_type', 'Payable')->sum('payment_amount');
        $totalReceivable = $payments->where('payment_type', 'Receivable')->sum('payment_amount');
        $netCashFlow = $totalReceivable - $totalPayable;

        // Get available payment methods
        $paymentMethods = DB::table('payments')
            ->select('payment_method')
            ->distinct()
            ->orderBy('payment_method')
            ->pluck('payment_method');

        return view('admin.finance.reconciliation.index', compact(
            'payments',
            'month',
            'paymentMethod',
            'totalPayable',
            'totalReceivable',
            'netCashFlow',
            'paymentMethods'
        ));
    }

    /**
     * Bank reconciliation
     */
    public function bankReconciliation(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $bankAccount = $request->get('bank_account');

        // Get payments for specific bank account
        $payments = DB::table('payments as p')
            ->leftJoin('suppliers as s', 'p.supplier_id', '=', 's.supplier_id')
            ->leftJoin('customers as c', 'p.customer_id', '=', 'c.customer_id')
            ->where('p.status', 'Completed')
            ->where('p.payment_method', 'Bank Transfer')
            ->whereRaw('DATE_FORMAT(p.payment_date, "%Y-%m") = ?', [$month])
            ->select(
                'p.payment_id',
                'p.payment_code',
                'p.payment_date',
                'p.payment_type',
                'p.payment_amount',
                'p.reference_number',
                'p.bank_account',
                's.supplier_name',
                'c.customer_name'
            )
            ->orderBy('p.payment_date')
            ->get();

        if ($bankAccount) {
            $payments = $payments->where('bank_account', $bankAccount);
        }

        // Get distinct bank accounts
        $bankAccounts = DB::table('payments')
            ->where('payment_method', 'Bank Transfer')
            ->whereNotNull('bank_account')
            ->distinct()
            ->pluck('bank_account');

        // Calculate opening and closing balance
        $openingBalance = 0; // TODO: Get from previous period
        $closingBalance = $openingBalance + $payments->where('payment_type', 'Receivable')->sum('payment_amount') 
                         - $payments->where('payment_type', 'Payable')->sum('payment_amount');

        return view('admin.finance.reconciliation.bank', compact(
            'payments',
            'month',
            'bankAccount',
            'bankAccounts',
            'openingBalance',
            'closingBalance'
        ));
    }

    /**
     * Mark payment as reconciled
     */
    public function markReconciled(Request $request)
    {
        $validated = $request->validate([
            'payment_ids' => ['required', 'array', 'min:1'],
            'payment_ids.*' => ['exists:payments,payment_id'],
            'reconciliation_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::beginTransaction();
        try {
            // TODO: Create reconciliation record
            // For now, just log the reconciliation
            
            $paymentCodes = DB::table('payments')
                ->whereIn('payment_id', $validated['payment_ids'])
                ->pluck('payment_code')
                ->join(', ');

            $this->logActivity(
                'Payments Reconciled',
                "Reconciled " . count($validated['payment_ids']) . " payment(s): {$paymentCodes}",
                'Finance - Payment Reconciliation'
            );

            DB::commit();

            return back()->with('success', count($validated['payment_ids']) . ' payment(s) marked as reconciled.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reconcile: ' . $e->getMessage());
        }
    }

    /**
     * Cash flow report
     */
    public function cashFlowReport(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        // Daily cash flow
        $dailyCashFlow = DB::table('payments')
            ->where('status', 'Completed')
            ->whereBetween('payment_date', [$dateFrom, $dateTo])
            ->select(
                DB::raw('DATE(payment_date) as date'),
                DB::raw('SUM(CASE WHEN payment_type = "Receivable" THEN payment_amount ELSE 0 END) as cash_in'),
                DB::raw('SUM(CASE WHEN payment_type = "Payable" THEN payment_amount ELSE 0 END) as cash_out'),
                DB::raw('SUM(CASE WHEN payment_type = "Receivable" THEN payment_amount ELSE -payment_amount END) as net_flow')
            )
            ->groupBy(DB::raw('DATE(payment_date)'))
            ->orderBy('date')
            ->get();

        // By payment method
        $byMethod = DB::table('payments')
            ->where('status', 'Completed')
            ->whereBetween('payment_date', [$dateFrom, $dateTo])
            ->select(
                'payment_method',
                DB::raw('SUM(CASE WHEN payment_type = "Receivable" THEN payment_amount ELSE 0 END) as cash_in'),
                DB::raw('SUM(CASE WHEN payment_type = "Payable" THEN payment_amount ELSE 0 END) as cash_out')
            )
            ->groupBy('payment_method')
            ->get();

        // Top payers (customers)
        $topPayers = DB::table('payments as p')
            ->join('customers as c', 'p.customer_id', '=', 'c.customer_id')
            ->where('p.payment_type', 'Receivable')
            ->where('p.status', 'Completed')
            ->whereBetween('p.payment_date', [$dateFrom, $dateTo])
            ->select(
                'c.customer_name',
                DB::raw('SUM(p.payment_amount) as total_amount'),
                DB::raw('COUNT(*) as payment_count')
            )
            ->groupBy('c.customer_id', 'c.customer_name')
            ->orderBy('total_amount', 'desc')
            ->limit(10)
            ->get();

        // Top payees (suppliers)
        $topPayees = DB::table('payments as p')
            ->join('suppliers as s', 'p.supplier_id', '=', 's.supplier_id')
            ->where('p.payment_type', 'Payable')
            ->where('p.status', 'Completed')
            ->whereBetween('p.payment_date', [$dateFrom, $dateTo])
            ->select(
                's.supplier_name',
                DB::raw('SUM(p.payment_amount) as total_amount'),
                DB::raw('COUNT(*) as payment_count')
            )
            ->groupBy('s.supplier_id', 's.supplier_name')
            ->orderBy('total_amount', 'desc')
            ->limit(10)
            ->get();

        // Summary
        $summary = [
            'total_cash_in' => $dailyCashFlow->sum('cash_in'),
            'total_cash_out' => $dailyCashFlow->sum('cash_out'),
            'net_cash_flow' => $dailyCashFlow->sum('net_flow'),
            'payment_count' => DB::table('payments')
                ->where('status', 'Completed')
                ->whereBetween('payment_date', [$dateFrom, $dateTo])
                ->count(),
        ];

        return view('admin.finance.reconciliation.cash-flow', compact(
            'dailyCashFlow',
            'byMethod',
            'topPayers',
            'topPayees',
            'summary',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Payment method analysis
     */
    public function methodAnalysis(Request $request)
    {
        $period = $request->get('period', 'this_month');

        // Determine date range
        [$dateFrom, $dateTo] = $this->getPeriodDates($period);

        // By method
        $byMethod = DB::table('payments')
            ->where('status', 'Completed')
            ->whereBetween('payment_date', [$dateFrom, $dateTo])
            ->select(
                'payment_method',
                'payment_type',
                DB::raw('SUM(payment_amount) as total_amount'),
                DB::raw('COUNT(*) as count'),
                DB::raw('AVG(payment_amount) as avg_amount')
            )
            ->groupBy('payment_method', 'payment_type')
            ->orderBy('total_amount', 'desc')
            ->get();

        // Method trends (last 12 months)
        $trends = DB::table('payments')
            ->where('status', 'Completed')
            ->where('payment_date', '>=', now()->subMonths(12)->startOfMonth())
            ->select(
                DB::raw('DATE_FORMAT(payment_date, "%Y-%m") as month'),
                'payment_method',
                DB::raw('SUM(payment_amount) as total_amount')
            )
            ->groupBy(DB::raw('DATE_FORMAT(payment_date, "%Y-%m")'), 'payment_method')
            ->orderBy('month')
            ->get();

        return view('admin.finance.reconciliation.method-analysis', compact(
            'byMethod',
            'trends',
            'period',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Unreconciled payments
     */
    public function unreconciled()
    {
        // TODO: Implement proper reconciliation tracking
        // For now, show payments without reference numbers
        
        $unreconciledPayments = DB::table('payments as p')
            ->leftJoin('suppliers as s', 'p.supplier_id', '=', 's.supplier_id')
            ->leftJoin('customers as c', 'p.customer_id', '=', 'c.customer_id')
            ->where('p.status', 'Completed')
            ->where(function($q) {
                $q->whereNull('p.reference_number')
                  ->orWhere('p.reference_number', '');
            })
            ->select(
                'p.*',
                's.supplier_name',
                'c.customer_name'
            )
            ->orderBy('p.payment_date', 'desc')
            ->paginate(20);

        return view('admin.finance.reconciliation.unreconciled', compact('unreconciledPayments'));
    }

    /**
     * Export reconciliation report
     */
    public function exportReconciliation(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $paymentMethod = $request->get('payment_method');

        $query = DB::table('payments as p')
            ->leftJoin('suppliers as s', 'p.supplier_id', '=', 's.supplier_id')
            ->leftJoin('customers as c', 'p.customer_id', '=', 'c.customer_id')
            ->where('p.status', 'Completed')
            ->whereRaw('DATE_FORMAT(p.payment_date, "%Y-%m") = ?', [$month])
            ->select(
                'p.payment_code',
                'p.payment_date',
                'p.payment_type',
                's.supplier_name',
                'c.customer_name',
                'p.payment_amount',
                'p.payment_method',
                'p.bank_account',
                'p.reference_number'
            )
            ->orderBy('p.payment_date');

        if ($paymentMethod) {
            $query->where('p.payment_method', $paymentMethod);
        }

        $data = $query->get();
        $filename = 'payment_reconciliation_' . $month . '_' . now()->format('His') . '.csv';

        $this->logExport('Finance - Payment Reconciliation', "Exported reconciliation report for {$month}");

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function() use ($data) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'Payment Code',
                'Date',
                'Type',
                'Party',
                'Amount',
                'Method',
                'Bank Account',
                'Reference'
            ]);
            
            foreach ($data as $row) {
                $party = $row->payment_type === 'Payable' ? $row->supplier_name : $row->customer_name;
                
                fputcsv($file, [
                    $row->payment_code,
                    $row->payment_date,
                    $row->payment_type,
                    $party,
                    $row->payment_amount,
                    $row->payment_method,
                    $row->bank_account ?? '-',
                    $row->reference_number ?? '-',
                ]);
            }
            
            fclose($file);
        }, 200, $headers);
    }

    /**
     * Get date range for period
     */
    private function getPeriodDates(string $period): array
    {
        return match($period) {
            'today' => [now()->toDateString(), now()->toDateString()],
            'yesterday' => [now()->subDay()->toDateString(), now()->subDay()->toDateString()],
            'this_week' => [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()],
            'last_week' => [now()->subWeek()->startOfWeek()->toDateString(), now()->subWeek()->endOfWeek()->toDateString()],
            'this_month' => [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()],
            'last_month' => [now()->subMonth()->startOfMonth()->toDateString(), now()->subMonth()->endOfMonth()->toDateString()],
            'this_quarter' => [now()->startOfQuarter()->toDateString(), now()->endOfQuarter()->toDateString()],
            'this_year' => [now()->startOfYear()->toDateString(), now()->endOfYear()->toDateString()],
            default => [now()->startOfMonth()->toDateString(), now()->toDateString()],
        };
    }
}
