<?php

namespace App\Http\Controllers\Business;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesReturnController extends Controller
{
    //
    use LogsActivity;

    /**
     * Display a listing of sales returns
     */
    public function index(Request $request)
    {
        $query = DB::table('sales_returns as sr')
            ->join('customers', 'sr.customer_id', '=', 'customers.customer_id')
            ->leftJoin('deliveries', 'sr.delivery_id', '=', 'deliveries.delivery_id')
            ->leftJoin('users', 'sr.created_by', '=', 'users.user_id')
            ->select(
                'sr.sr_id',
                'sr.sr_code',
                'sr.customer_id',
                'sr.delivery_id',
                'sr.return_date',
                'sr.return_reason',
                'sr.status',
                'sr.total_amount',
                'sr.refund_method',
                'sr.notes',
                'sr.created_at',
                'customers.customer_code',
                'customers.customer_name',
                'deliveries.delivery_code',
                'users.full_name as created_by_name'
            );

        // Filter by customer
        if ($request->filled('customer_id')) {
            $query->where('sr.customer_id', $request->customer_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('sr.status', $request->status);
        }

        // Filter by return reason
        if ($request->filled('return_reason')) {
            $query->where('sr.return_reason', $request->return_reason);
        }

        // Filter by refund method
        if ($request->filled('refund_method')) {
            $query->where('sr.refund_method', $request->refund_method);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('sr.return_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('sr.return_date', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('sr.sr_code', 'like', "%{$search}%")
                  ->orWhere('customers.customer_name', 'like', "%{$search}%")
                  ->orWhere('deliveries.delivery_code', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'return_date');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSort = ['sr_code', 'return_date', 'customer_name', 'status', 'total_amount', 'created_at'];
        if (in_array($sortBy, $allowedSort)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $salesReturns = $query->paginate(15)->withQueryString();

        // Get filter options
        $customers = DB::table('customers')
            ->where('is_active', 1)
            ->orderBy('customer_name')
            ->get();

        $statuses = DB::table('sales_returns')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $returnReasons = DB::table('sales_returns')
            ->select('return_reason', DB::raw('COUNT(*) as count'))
            ->groupBy('return_reason')
            ->pluck('count', 'return_reason');

        $refundMethods = DB::table('sales_returns')
            ->whereNotNull('refund_method')
            ->select('refund_method', DB::raw('COUNT(*) as count'))
            ->groupBy('refund_method')
            ->pluck('count', 'refund_method');

        return view('admin.sales.returns.index', compact(
            'salesReturns',
            'customers',
            'statuses',
            'returnReasons',
            'refundMethods'
        ));
    }

    /**
     * Show the form for creating a new sales return
     */
    public function create()
    {
        // Get active customers
        $customers = DB::table('customers')
            ->where('is_active', 1)
            ->orderBy('customer_name')
            ->get();

        // Get recent deliveries (last 90 days)
        $deliveries = DB::table('deliveries as d')
            ->join('customers', 'd.customer_id', '=', 'customers.customer_id')
            ->where('d.delivery_date', '>=', now()->subDays(90))
            ->where('d.status', 'Delivered')
            ->select(
                'd.delivery_id',
                'd.delivery_code',
                'd.delivery_date',
                'customers.customer_name'
            )
            ->orderBy('d.delivery_date', 'desc')
            ->get();

        return view('admin.sales.returns.create', compact('customers', 'deliveries'));
    }

    /**
     * Store a newly created sales return
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,customer_id'],
            'delivery_id' => ['nullable', 'exists:deliveries,delivery_id'],
            'return_date' => ['required', 'date', 'before_or_equal:today'],
            'return_reason' => ['required', 'string', 'max:30'],
            'refund_method' => ['nullable', 'string', 'max:30'],
            'notes' => ['nullable', 'string'],
            
            // Items
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,product_id'],
            'items.*.lot_id' => ['nullable', 'exists:lots,lot_id'],
            'items.*.quantity_returned' => ['required', 'numeric', 'min:0.0001'],
            'items.*.uom_id' => ['required', 'exists:units_of_measure,uom_id'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.defect_type' => ['nullable', 'string', 'max:100'],
            'items.*.notes' => ['nullable', 'string'],
        ], [
            'customer_id.required' => 'Customer is required.',
            'return_date.required' => 'Return date is required.',
            'return_reason.required' => 'Return reason is required.',
            'items.required' => 'At least one item is required.',
            'items.min' => 'At least one item is required.',
        ]);

        DB::beginTransaction();
        try {
            // Generate SR code
            $srCode = CodeGeneratorHelper::generateSRCode();

            // Calculate total amount
            $totalAmount = collect($validated['items'])->sum(function($item) {
                return $item['quantity_returned'] * $item['unit_price'];
            });

            // Insert sales return
            $srId = DB::table('sales_returns')->insertGetId([
                'sr_code' => $srCode,
                'customer_id' => $validated['customer_id'],
                'delivery_id' => $validated['delivery_id'] ?? null,
                'return_date' => $validated['return_date'],
                'return_reason' => $validated['return_reason'],
                'status' => 'Pending',
                'total_amount' => $totalAmount,
                'refund_method' => $validated['refund_method'] ?? null,
                'created_by' => Auth::id(),
                'notes' => $validated['notes'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert sales return items
            foreach ($validated['items'] as $item) {
                $lineTotal = $item['quantity_returned'] * $item['unit_price'];
                
                DB::table('sales_return_items')->insert([
                    'sr_id' => $srId,
                    'product_id' => $item['product_id'],
                    'lot_id' => $item['lot_id'] ?? null,
                    'quantity_returned' => $item['quantity_returned'],
                    'uom_id' => $item['uom_id'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $lineTotal,
                    'defect_type' => $item['defect_type'] ?? null,
                    'notes' => $item['notes'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Log CREATE
            $this->logCreate(
                'Business Management - Sales Returns',
                'sales_returns',
                $srId,
                array_merge($validated, ['sr_code' => $srCode, 'total_amount' => $totalAmount])
            );

            DB::commit();

            return redirect()
                ->route('sales.returns.show', $srCode)
                ->with('success', 'Sales return created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create sales return: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified sales return
     */
    public function show($srCode)
    {
        $salesReturn = DB::table('sales_returns as sr')
            ->join('customers', 'sr.customer_id', '=', 'customers.customer_id')
            ->leftJoin('deliveries', 'sr.delivery_id', '=', 'deliveries.delivery_id')
            ->leftJoin('users as creator', 'sr.created_by', '=', 'creator.user_id')
            ->where('sr.sr_code', $srCode)
            ->select(
                'sr.*',
                'customers.customer_code',
                'customers.customer_name',
                'customers.contact_person',
                'customers.email',
                'customers.phone',
                'customers.shipping_address',
                'deliveries.delivery_code',
                'deliveries.delivery_date',
                'creator.full_name as created_by_name'
            )
            ->first();

        if (!$salesReturn) {
            abort(404, 'Sales return not found');
        }

        // Get return items
        $items = DB::table('sales_return_items as sri')
            ->join('products', 'sri.product_id', '=', 'products.product_id')
            ->join('units_of_measure as uom', 'sri.uom_id', '=', 'uom.uom_id')
            ->leftJoin('lots', 'sri.lot_id', '=', 'lots.lot_id')
            ->where('sri.sr_id', $salesReturn->sr_id)
            ->select(
                'sri.*',
                'products.product_code',
                'products.product_name',
                'uom.uom_name',
                'lots.lot_code'
            )
            ->get();

        // Log VIEW
        $this->logView(
            'Business Management - Sales Returns',
            "Viewed sales return: {$srCode}"
        );

        return view('admin.sales.returns.show', compact('salesReturn', 'items'));
    }

    /**
     * Show the form for editing the specified sales return
     */
    public function edit($srCode)
    {
        $salesReturn = DB::table('sales_returns')
            ->where('sr_code', $srCode)
            ->first();

        if (!$salesReturn) {
            abort(404, 'Sales return not found');
        }

        // Only allow edit if status is Pending
        if ($salesReturn->status !== 'Pending') {
            return back()->with('error', 'Cannot edit sales return with status: ' . $salesReturn->status);
        }

        // Get customers
        $customers = DB::table('customers')
            ->where('is_active', 1)
            ->orderBy('customer_name')
            ->get();

        // Get deliveries
        $deliveries = DB::table('deliveries as d')
            ->join('customers', 'd.customer_id', '=', 'customers.customer_id')
            ->where('d.delivery_date', '>=', now()->subDays(90))
            ->where('d.status', 'Delivered')
            ->select(
                'd.delivery_id',
                'd.delivery_code',
                'd.delivery_date',
                'customers.customer_name'
            )
            ->orderBy('d.delivery_date', 'desc')
            ->get();

        // Get existing items
        $items = DB::table('sales_return_items as sri')
            ->join('products', 'sri.product_id', '=', 'products.product_id')
            ->join('units_of_measure as uom', 'sri.uom_id', '=', 'uom.uom_id')
            ->leftJoin('lots', 'sri.lot_id', '=', 'lots.lot_id')
            ->where('sri.sr_id', $salesReturn->sr_id)
            ->select('sri.*', 'products.product_name', 'uom.uom_name', 'lots.lot_code')
            ->get();

        return view('admin.sales.returns.edit', compact('salesReturn', 'customers', 'deliveries', 'items'));
    }

    /**
     * Update the specified sales return
     */
    public function update(Request $request, $srCode)
    {
        $salesReturn = DB::table('sales_returns')
            ->where('sr_code', $srCode)
            ->first();

        if (!$salesReturn) {
            abort(404, 'Sales return not found');
        }

        // Only allow update if status is Pending
        if ($salesReturn->status !== 'Pending') {
            return back()->with('error', 'Cannot update sales return with status: ' . $salesReturn->status);
        }

        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,customer_id'],
            'delivery_id' => ['nullable', 'exists:deliveries,delivery_id'],
            'return_date' => ['required', 'date', 'before_or_equal:today'],
            'return_reason' => ['required', 'string', 'max:30'],
            'refund_method' => ['nullable', 'string', 'max:30'],
            'notes' => ['nullable', 'string'],
            
            // Items
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,product_id'],
            'items.*.lot_id' => ['nullable', 'exists:lots,lot_id'],
            'items.*.quantity_returned' => ['required', 'numeric', 'min:0.0001'],
            'items.*.uom_id' => ['required', 'exists:units_of_measure,uom_id'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.defect_type' => ['nullable', 'string', 'max:100'],
            'items.*.notes' => ['nullable', 'string'],
        ]);

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'customer_id' => $salesReturn->customer_id,
                'delivery_id' => $salesReturn->delivery_id,
                'return_date' => $salesReturn->return_date,
                'return_reason' => $salesReturn->return_reason,
                'refund_method' => $salesReturn->refund_method,
                'total_amount' => $salesReturn->total_amount,
                'notes' => $salesReturn->notes,
            ];

            // Calculate new total amount
            $totalAmount = collect($validated['items'])->sum(function($item) {
                return $item['quantity_returned'] * $item['unit_price'];
            });

            // Update sales return
            DB::table('sales_returns')
                ->where('sr_id', $salesReturn->sr_id)
                ->update([
                    'customer_id' => $validated['customer_id'],
                    'delivery_id' => $validated['delivery_id'] ?? null,
                    'return_date' => $validated['return_date'],
                    'return_reason' => $validated['return_reason'],
                    'refund_method' => $validated['refund_method'] ?? null,
                    'total_amount' => $totalAmount,
                    'notes' => $validated['notes'] ?? null,
                    'updated_at' => now(),
                ]);

            // Delete old items and insert new ones
            DB::table('sales_return_items')
                ->where('sr_id', $salesReturn->sr_id)
                ->delete();

            foreach ($validated['items'] as $item) {
                $lineTotal = $item['quantity_returned'] * $item['unit_price'];
                
                DB::table('sales_return_items')->insert([
                    'sr_id' => $salesReturn->sr_id,
                    'product_id' => $item['product_id'],
                    'lot_id' => $item['lot_id'] ?? null,
                    'quantity_returned' => $item['quantity_returned'],
                    'uom_id' => $item['uom_id'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $lineTotal,
                    'defect_type' => $item['defect_type'] ?? null,
                    'notes' => $item['notes'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Log UPDATE
            $this->logUpdate(
                'Business Management - Sales Returns',
                'sales_returns',
                $salesReturn->sr_id,
                $oldData,
                array_merge($validated, ['total_amount' => $totalAmount])
            );

            DB::commit();

            return redirect()
                ->route('sales.returns.show', $srCode)
                ->with('success', 'Sales return updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update sales return: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified sales return
     */
    public function destroy($srCode)
    {
        $salesReturn = DB::table('sales_returns')
            ->where('sr_code', $srCode)
            ->first();

        if (!$salesReturn) {
            abort(404, 'Sales return not found');
        }

        // Only allow delete if status is Pending
        if ($salesReturn->status !== 'Pending') {
            return back()->with('error', 'Cannot delete sales return with status: ' . $salesReturn->status);
        }

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'sr_code' => $salesReturn->sr_code,
                'customer_id' => $salesReturn->customer_id,
                'return_date' => $salesReturn->return_date,
                'return_reason' => $salesReturn->return_reason,
                'status' => $salesReturn->status,
                'total_amount' => $salesReturn->total_amount,
            ];

            // Delete return items first
            DB::table('sales_return_items')
                ->where('sr_id', $salesReturn->sr_id)
                ->delete();

            // Delete sales return
            DB::table('sales_returns')
                ->where('sr_id', $salesReturn->sr_id)
                ->delete();

            // Log DELETE
            $this->logDelete(
                'Business Management - Sales Returns',
                'sales_returns',
                $salesReturn->sr_id,
                $oldData
            );

            DB::commit();

            return redirect()
                ->route('sales.returns.index')
                ->with('success', 'Sales return deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete sales return: ' . $e->getMessage());
        }
    }

    /**
     * Approve sales return
     */
    public function approve($srCode)
    {
        $salesReturn = DB::table('sales_returns')
            ->where('sr_code', $srCode)
            ->first();

        if (!$salesReturn) {
            abort(404, 'Sales return not found');
        }

        if ($salesReturn->status !== 'Pending') {
            return back()->with('error', 'Sales return is not in Pending status.');
        }

        DB::beginTransaction();
        try {
            // Update status to Approved
            DB::table('sales_returns')
                ->where('sr_id', $salesReturn->sr_id)
                ->update([
                    'status' => 'Approved',
                    'updated_at' => now(),
                ]);

            // TODO: Update inventory (add back stock)
            // TODO: Create credit note or update accounts receivable
            // TODO: Process refund based on refund_method

            // Log approval
            $this->logApproval(
                'Business Management - Sales Returns',
                'sales_returns',
                $salesReturn->sr_id,
                'approved',
                'Sales return approved'
            );

            DB::commit();

            return back()->with('success', 'Sales return approved successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve sales return: ' . $e->getMessage());
        }
    }

    /**
     * Reject sales return
     */
    public function reject(Request $request, $srCode)
    {
        $validated = $request->validate([
            'rejection_notes' => ['required', 'string', 'max:500'],
        ]);

        $salesReturn = DB::table('sales_returns')
            ->where('sr_code', $srCode)
            ->first();

        if (!$salesReturn) {
            abort(404, 'Sales return not found');
        }

        if ($salesReturn->status !== 'Pending') {
            return back()->with('error', 'Sales return is not in Pending status.');
        }

        DB::beginTransaction();
        try {
            // Update status to Rejected
            DB::table('sales_returns')
                ->where('sr_id', $salesReturn->sr_id)
                ->update([
                    'status' => 'Rejected',
                    'notes' => ($salesReturn->notes ? $salesReturn->notes . "\n\n" : '') 
                              . "REJECTED: " . $validated['rejection_notes'],
                    'updated_at' => now(),
                ]);

            // Log rejection
            $this->logApproval(
                'Business Management - Sales Returns',
                'sales_returns',
                $salesReturn->sr_id,
                'rejected',
                $validated['rejection_notes']
            );

            DB::commit();

            return back()->with('success', 'Sales return rejected.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reject sales return: ' . $e->getMessage());
        }
    }

    /**
     * Process refund for approved sales return
     */
    public function processRefund(Request $request, $srCode)
    {
        $validated = $request->validate([
            'refund_method' => ['required', 'string', 'max:30'],
            'refund_reference' => ['nullable', 'string', 'max:100'],
            'refund_notes' => ['nullable', 'string'],
        ]);

        $salesReturn = DB::table('sales_returns')
            ->where('sr_code', $srCode)
            ->first();

        if (!$salesReturn) {
            abort(404, 'Sales return not found');
        }

        if ($salesReturn->status !== 'Approved') {
            return back()->with('error', 'Only approved sales returns can be refunded.');
        }

        DB::beginTransaction();
        try {
            // Update sales return with refund information
            DB::table('sales_returns')
                ->where('sr_id', $salesReturn->sr_id)
                ->update([
                    'status' => 'Refunded',
                    'refund_method' => $validated['refund_method'],
                    'notes' => ($salesReturn->notes ? $salesReturn->notes . "\n\n" : '') 
                              . "REFUND PROCESSED: Method - {$validated['refund_method']}"
                              . ($validated['refund_reference'] ? ", Reference: {$validated['refund_reference']}" : '')
                              . ($validated['refund_notes'] ? "\n{$validated['refund_notes']}" : ''),
                    'updated_at' => now(),
                ]);

            // TODO: Create payment/refund transaction
            // TODO: Update accounts receivable

            // Log refund
            $this->logActivity(
                'Refund Processed',
                "Processed refund for sales return: {$srCode} via {$validated['refund_method']}",
                'Business Management - Sales Returns'
            );

            DB::commit();

            return back()->with('success', 'Refund processed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process refund: ' . $e->getMessage());
        }
    }

    /**
     * Export sales returns to CSV
     */
    public function export(Request $request)
    {
        $query = DB::table('sales_returns as sr')
            ->join('customers', 'sr.customer_id', '=', 'customers.customer_id')
            ->leftJoin('deliveries', 'sr.delivery_id', '=', 'deliveries.delivery_id')
            ->select(
                'sr.sr_code',
                'sr.return_date',
                'customers.customer_name',
                'deliveries.delivery_code',
                'sr.return_reason',
                'sr.status',
                'sr.total_amount',
                'sr.refund_method',
                'sr.notes'
            );

        // Apply same filters as index
        if ($request->filled('customer_id')) {
            $query->where('sr.customer_id', $request->customer_id);
        }
        if ($request->filled('status')) {
            $query->where('sr.status', $request->status);
        }
        if ($request->filled('return_reason')) {
            $query->where('sr.return_reason', $request->return_reason);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('sr.return_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('sr.return_date', '<=', $request->date_to);
        }

        $returns = $query->orderBy('sr.return_date', 'desc')
            ->limit(5000)
            ->get();

        $filename = 'sales_returns_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($returns) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'Return Code',
                'Return Date',
                'Customer',
                'Delivery Code',
                'Return Reason',
                'Status',
                'Total Amount',
                'Refund Method',
                'Notes'
            ]);
            
            // Data
            foreach ($returns as $return) {
                fputcsv($file, [
                    $return->sr_code,
                    $return->return_date,
                    $return->customer_name,
                    $return->delivery_code ?? '-',
                    $return->return_reason,
                    $return->status,
                    $return->total_amount,
                    $return->refund_method ?? '-',
                    $return->notes ?? '-',
                ]);
            }
            
            fclose($file);
        };

        // Log export
        $this->logExport(
            'Business Management - Sales Returns',
            'Exported sales returns to CSV'
        );

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Print sales return
     */
    public function print($srCode)
    {
        $salesReturn = DB::table('sales_returns as sr')
            ->join('customers', 'sr.customer_id', '=', 'customers.customer_id')
            ->leftJoin('deliveries', 'sr.delivery_id', '=', 'deliveries.delivery_id')
            ->where('sr.sr_code', $srCode)
            ->select('sr.*', 'customers.*', 'deliveries.delivery_code')
            ->first();

        if (!$salesReturn) {
            abort(404, 'Sales return not found');
        }

        // Get items
        $items = DB::table('sales_return_items as sri')
            ->join('products', 'sri.product_id', '=', 'products.product_id')
            ->join('units_of_measure as uom', 'sri.uom_id', '=', 'uom.uom_id')
            ->leftJoin('lots', 'sri.lot_id', '=', 'lots.lot_id')
            ->where('sri.sr_id', $salesReturn->sr_id)
            ->select('sri.*', 'products.product_name', 'uom.uom_name', 'lots.lot_code')
            ->get();

        // Log print
        $this->logPrint(
            'Business Management - Sales Returns',
            "Printed sales return: {$srCode}"
        );

        return view('admin.sales.returns.print', compact('salesReturn', 'items'));
    }
}
