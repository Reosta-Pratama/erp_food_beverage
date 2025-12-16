<?php

namespace App\Http\Controllers\Finance;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountsPayableController extends Controller
{
    //
    use LogsActivity;

    public function index(Request $request)
    {
        $query = DB::table('accounts_payable as ap')
            ->join('suppliers as s', 'ap.supplier_id', '=', 's.supplier_id')
            ->leftJoin('purchase_orders as po', 'ap.po_id', '=', 'po.po_id')
            ->select(
                'ap.*',
                's.supplier_code',
                's.supplier_name',
                'po.po_code',
                DB::raw('DATEDIFF(ap.due_date, CURDATE()) as days_until_due')
            );

        if ($request->filled('supplier_id')) $query->where('ap.supplier_id', $request->supplier_id);
        if ($request->filled('status')) $query->where('ap.status', $request->status);
        if ($request->filled('overdue')) {
            $query->where('ap.status', '!=', 'Paid')
                  ->whereDate('ap.due_date', '<', now());
        }
        if ($request->filled('date_from')) $query->whereDate('ap.due_date', '>=', $request->date_from);
        if ($request->filled('date_to')) $query->whereDate('ap.due_date', '<=', $request->date_to);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ap.ap_code', 'like', "%{$search}%")
                  ->orWhere('s.supplier_name', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->get('sort_by', 'due_date');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $payables = $query->paginate(20)->withQueryString();

        $suppliers = DB::table('suppliers')->where('is_active', 1)->orderBy('supplier_name')->get();
        $statuses = DB::table('accounts_payable')->select('status', DB::raw('COUNT(*) as count'))->groupBy('status')->pluck('count', 'status');

        return view('admin.finance.accounts-payable.index', compact('payables', 'suppliers', 'statuses'));
    }

    public function create()
    {
        $suppliers = DB::table('suppliers')->where('is_active', 1)->orderBy('supplier_name')->get();
        $purchaseOrders = DB::table('purchase_orders as po')
            ->join('suppliers as s', 'po.supplier_id', '=', 's.supplier_id')
            ->where('po.status', '!=', 'Cancelled')
            ->select('po.po_id', 'po.po_code', 's.supplier_name', 'po.total_amount')
            ->orderBy('po.order_date', 'desc')
            ->get();

        return view('admin.finance.accounts-payable.create', compact('suppliers', 'purchaseOrders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,supplier_id'],
            'po_id' => ['nullable', 'exists:purchase_orders,po_id'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:invoice_date'],
            'invoice_amount' => ['required', 'numeric', 'min:0.01'],
            'payment_terms' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::beginTransaction();
        try {
            $apCode = CodeGeneratorHelper::generateAPCode();

            $apId = DB::table('accounts_payable')->insertGetId([
                'ap_code' => $apCode,
                'supplier_id' => $validated['supplier_id'],
                'po_id' => $validated['po_id'] ?? null,
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

            $this->logCreate('Finance - Accounts Payable', 'accounts_payable', $apId, array_merge($validated, ['ap_code' => $apCode]));

            DB::commit();
            return redirect()->route('finance.accounts-payable.show', $apCode)->with('success', 'AP invoice created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create: ' . $e->getMessage());
        }
    }

    public function show($apCode)
    {
        $payable = DB::table('accounts_payable as ap')
            ->join('suppliers as s', 'ap.supplier_id', '=', 's.supplier_id')
            ->leftJoin('purchase_orders as po', 'ap.po_id', '=', 'po.po_id')
            ->where('ap.ap_code', $apCode)
            ->select('ap.*', 's.*', 'po.po_code')
            ->first();

        if (!$payable) abort(404, 'AP invoice not found');

        $payments = DB::table('payments')
            ->where('ap_id', $payable->ap_id)
            ->orderBy('payment_date', 'desc')
            ->get();

        $this->logView('Finance - Accounts Payable', "Viewed AP invoice: {$apCode}");

        return view('admin.finance.accounts-payable.show', compact('payable', 'payments'));
    }

    public function recordPayment(Request $request, $apCode)
    {
        $validated = $request->validate([
            'payment_amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', 'string', 'max:30'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $payable = DB::table('accounts_payable')->where('ap_code', $apCode)->first();
        if (!$payable) abort(404);

        if ($validated['payment_amount'] > $payable->balance_amount) {
            return back()->with('error', 'Payment amount exceeds balance.');
        }

        DB::beginTransaction();
        try {
            $paymentCode = CodeGeneratorHelper::generatePaymentCode();
            $newPaidAmount = $payable->paid_amount + $validated['payment_amount'];
            $newBalance = $payable->balance_amount - $validated['payment_amount'];
            $newStatus = $newBalance == 0 ? 'Paid' : 'Partial';

            // Create payment record
            DB::table('payments')->insert([
                'payment_code' => $paymentCode,
                'payment_type' => 'Payable',
                'supplier_id' => $payable->supplier_id,
                'ap_id' => $payable->ap_id,
                'payment_date' => $validated['payment_date'],
                'payment_amount' => $validated['payment_amount'],
                'payment_method' => $validated['payment_method'],
                'reference_number' => $validated['reference_number'] ?? null,
                'status' => 'Completed',
                'processed_by' => Auth::id(),
                'notes' => $validated['notes'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update AP
            DB::table('accounts_payable')
                ->where('ap_id', $payable->ap_id)
                ->update([
                    'paid_amount' => $newPaidAmount,
                    'balance_amount' => $newBalance,
                    'status' => $newStatus,
                    'updated_at' => now(),
                ]);

            $this->logActivity('Payment Recorded', "Recorded payment of {$validated['payment_amount']} for AP {$apCode}", 'Finance - Accounts Payable');

            DB::commit();
            return back()->with('success', 'Payment recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to record payment: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $query = DB::table('accounts_payable as ap')
            ->join('suppliers as s', 'ap.supplier_id', '=', 's.supplier_id')
            ->select('ap.ap_code', 'ap.invoice_date', 'ap.due_date', 's.supplier_name', 'ap.invoice_amount', 'ap.balance_amount', 'ap.status');

        if ($request->filled('status')) $query->where('ap.status', $request->status);
        
        $data = $query->orderBy('ap.due_date')->limit(5000)->get();
        $filename = 'accounts_payable_' . now()->format('Y-m-d_His') . '.csv';

        $this->logExport('Finance - Accounts Payable', 'Exported AP invoices to CSV');

        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"{$filename}\""];
        
        return response()->stream(function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['AP Code', 'Invoice Date', 'Due Date', 'Supplier', 'Invoice Amount', 'Balance', 'Status']);
            foreach ($data as $row) {
                fputcsv($file, [$row->ap_code, $row->invoice_date, $row->due_date, $row->supplier_name, $row->invoice_amount, $row->balance_amount, $row->status]);
            }
            fclose($file);
        }, 200, $headers);
    }
}
