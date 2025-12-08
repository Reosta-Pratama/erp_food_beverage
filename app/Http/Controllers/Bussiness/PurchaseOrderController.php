<?php

namespace App\Http\Controllers\Bussiness;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    //
    use LogsActivity;
    
    /**
     * Display purchase orders with filtering
     */
    public function index(Request $request)
    {
        $query = DB::table('purchase_orders as po')
            ->join('suppliers as s', 'po.supplier_id', '=', 's.supplier_id')
            ->join('users as u', 'po.created_by', '=', 'u.user_id')
            ->leftJoin('employees as e', 'po.approved_by', '=', 'e.employee_id')
            ->select(
                'po.po_id',
                'po.po_code',
                'po.order_date',
                'po.expected_delivery',
                'po.actual_delivery',
                'po.status',
                'po.total_amount',
                'po.payment_terms',
                'po.created_at',
                's.supplier_code',
                's.supplier_name',
                'u.full_name as created_by_name',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as approved_by_name")
            );

        // Filter by status
        if ($request->filled('status')) {
            $query->where('po.status', $request->status);
        }

        // Filter by supplier
        if ($request->filled('supplier_id')) {
            $query->where('po.supplier_id', $request->supplier_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('po.order_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('po.order_date', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('po.po_code', 'like', "%{$search}%")
                  ->orWhere('s.supplier_name', 'like', "%{$search}%");
            });
        }

        $purchaseOrders = $query->orderByDesc('po.order_date')
            ->orderByDesc('po.created_at')
            ->paginate(20);

        // Get filter options
        $statuses = DB::table('purchase_orders')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $suppliers = DB::table('suppliers')
            ->where('is_active', 1)
            ->orderBy('supplier_name')
            ->get(['supplier_id', 'supplier_code', 'supplier_name']);

        $stats = [
            'total' => DB::table('purchase_orders')->count(),
            'pending' => DB::table('purchase_orders')->where('status', 'Pending')->count(),
            'approved' => DB::table('purchase_orders')->where('status', 'Approved')->count(),
            'completed' => DB::table('purchase_orders')->where('status', 'Completed')->count(),
            'total_amount' => DB::table('purchase_orders')
                ->whereIn('status', ['Approved', 'Completed'])
                ->sum('total_amount'),
        ];

        return view('purchase.orders.index', compact(
            'purchaseOrders',
            'statuses',
            'suppliers',
            'stats'
        ));
    }

    /**
     * Show create form
     */
    public function create()
    {
        // Get active suppliers
        $suppliers = DB::table('suppliers')
            ->where('is_active', 1)
            ->orderBy('supplier_name')
            ->get(['supplier_id', 'supplier_code', 'supplier_name', 'payment_terms']);

        // Get active products (raw materials)
        $products = DB::table('products as p')
            ->join('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->where('p.is_active', 1)
            ->whereIn('p.product_type', ['Raw Material', 'Packaging Material'])
            ->orderBy('p.product_name')
            ->get([
                'p.product_id',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'p.standard_cost',
                'uom.uom_id',
                'uom.uom_name',
                'uom.uom_code'
            ]);

        return view('purchase.orders.create', compact('suppliers', 'products'));
    }

    /**
     * Store new purchase order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,supplier_id'],
            'order_date' => ['required', 'date'],
            'expected_delivery' => ['required', 'date', 'after_or_equal:order_date'],
            'payment_terms' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
            
            // Order items
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,product_id'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.0001'],
            'items.*.uom_id' => ['required', 'exists:units_of_measure,uom_id'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.discount_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'items.*.tax_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'items.*.expected_date' => ['nullable', 'date'],
            'items.*.notes' => ['nullable', 'string'],
        ], [
            'supplier_id.required' => 'Please select a supplier.',
            'supplier_id.exists' => 'Selected supplier is invalid.',
            
            'order_date.required' => 'Order date is required.',
            'order_date.date' => 'Invalid order date format.',
            
            'expected_delivery.required' => 'Expected delivery date is required.',
            'expected_delivery.date' => 'Invalid delivery date format.',
            'expected_delivery.after_or_equal' => 'Delivery date must be on or after order date.',
            
            'items.required' => 'Please add at least one item to the order.',
            'items.array' => 'Invalid items format.',
            'items.min' => 'Please add at least one item.',
            
            'items.*.product_id.required' => 'Product is required for each item.',
            'items.*.product_id.exists' => 'One or more products are invalid.',
            
            'items.*.quantity.required' => 'Quantity is required for each item.',
            'items.*.quantity.numeric' => 'Quantity must be a number.',
            'items.*.quantity.min' => 'Quantity must be greater than zero.',
            
            'items.*.uom_id.required' => 'Unit of measure is required.',
            'items.*.uom_id.exists' => 'Invalid unit of measure.',
            
            'items.*.unit_price.required' => 'Unit price is required.',
            'items.*.unit_price.numeric' => 'Unit price must be a number.',
            'items.*.unit_price.min' => 'Unit price cannot be negative.',
        ]);

        DB::beginTransaction();
        try {
            // Generate PO Code
            $poCode = CodeGeneratorHelper::generatePOCode();

            // Calculate totals
            $subtotal = 0;
            $taxAmount = 0;
            $discountAmount = 0;

            foreach ($validated['items'] as $item) {
                $lineSubtotal = $item['quantity'] * $item['unit_price'];
                $lineDiscount = $lineSubtotal * (($item['discount_percentage'] ?? 0) / 100);
                $lineTax = ($lineSubtotal - $lineDiscount) * (($item['tax_percentage'] ?? 0) / 100);
                
                $subtotal += $lineSubtotal;
                $discountAmount += $lineDiscount;
                $taxAmount += $lineTax;
            }

            $totalAmount = $subtotal - $discountAmount + $taxAmount;

            // Insert purchase order
            $poId = DB::table('purchase_orders')->insertGetId([
                'po_code' => $poCode,
                'supplier_id' => $validated['supplier_id'],
                'order_date' => $validated['order_date'],
                'expected_delivery' => $validated['expected_delivery'],
                'actual_delivery' => null,
                'status' => 'Pending',
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'payment_terms' => $validated['payment_terms'] ?? null,
                'created_by' => Auth::id(),
                'approved_by' => null,
                'notes' => $validated['notes'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert purchase order items
            foreach ($validated['items'] as $item) {
                $lineSubtotal = $item['quantity'] * $item['unit_price'];
                $lineDiscount = $lineSubtotal * (($item['discount_percentage'] ?? 0) / 100);
                $lineTax = ($lineSubtotal - $lineDiscount) * (($item['tax_percentage'] ?? 0) / 100);
                $lineTotal = $lineSubtotal - $lineDiscount + $lineTax;

                DB::table('purchase_order_items')->insert([
                    'po_id' => $poId,
                    'product_id' => $item['product_id'],
                    'quantity_ordered' => $item['quantity'],
                    'quantity_received' => 0,
                    'uom_id' => $item['uom_id'],
                    'unit_price' => $item['unit_price'],
                    'discount_percentage' => $item['discount_percentage'] ?? 0,
                    'tax_percentage' => $item['tax_percentage'] ?? 0,
                    'line_total' => $lineTotal,
                    'expected_date' => $item['expected_date'] ?? null,
                    'notes' => $item['notes'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Log CREATE
            $this->logCreate(
                'Purchase Management - Purchase Orders',
                'purchase_orders',
                $poId,
                [
                    'po_code' => $poCode,
                    'supplier_id' => $validated['supplier_id'],
                    'order_date' => $validated['order_date'],
                    'expected_delivery' => $validated['expected_delivery'],
                    'total_amount' => $totalAmount,
                    'items_count' => count($validated['items']),
                ]
            );

            DB::commit();

            return redirect()
                ->route('purchase.orders.show', $poCode)
                ->with('success', 'Purchase order created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create purchase order: ' . $e->getMessage());
        }
    }

    /**
     * Show purchase order details
     */
    public function show($poCode)
    {
        $po = DB::table('purchase_orders as po')
            ->join('suppliers as s', 'po.supplier_id', '=', 's.supplier_id')
            ->join('users as u', 'po.created_by', '=', 'u.user_id')
            ->leftJoin('employees as e', 'po.approved_by', '=', 'e.employee_id')
            ->where('po.po_code', $poCode)
            ->select(
                'po.*',
                's.supplier_code',
                's.supplier_name',
                's.contact_person',
                's.email as supplier_email',
                's.phone as supplier_phone',
                's.address as supplier_address',
                's.city as supplier_city',
                's.country as supplier_country',
                'u.full_name as created_by_name',
                'u.email as created_by_email',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as approved_by_name")
            )
            ->first();

        if (!$po) {
            abort(404, 'Purchase order not found');
        }

        // Log VIEW
        $this->logView(
            'Purchase Management - Purchase Orders',
            "Viewed purchase order: {$po->po_code}"
        );

        // Get purchase order items
        $items = DB::table('purchase_order_items as poi')
            ->join('products as p', 'poi.product_id', '=', 'p.product_id')
            ->join('units_of_measure as uom', 'poi.uom_id', '=', 'uom.uom_id')
            ->where('poi.po_id', $po->po_id)
            ->select(
                'poi.*',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'uom.uom_name',
                'uom.uom_code'
            )
            ->get();

        // Get related receipts
        $receipts = DB::table('purchase_receipts')
            ->where('po_id', $po->po_id)
            ->select('receipt_code', 'receipt_date', 'status')
            ->orderByDesc('receipt_date')
            ->get();

        return view('purchase.orders.show', compact('po', 'items', 'receipts'));
    }

    /**
     * Show edit form
     */
    public function edit($poCode)
    {
        $po = DB::table('purchase_orders')
            ->where('po_code', $poCode)
            ->first();

        if (!$po) {
            abort(404, 'Purchase order not found');
        }

        // Only allow editing of Pending orders
        if ($po->status !== 'Pending') {
            return back()->with('error', 'Only pending purchase orders can be edited.');
        }

        // Get suppliers
        $suppliers = DB::table('suppliers')
            ->where('is_active', 1)
            ->orderBy('supplier_name')
            ->get(['supplier_id', 'supplier_code', 'supplier_name', 'payment_terms']);

        // Get products
        $products = DB::table('products as p')
            ->join('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->where('p.is_active', 1)
            ->whereIn('p.product_type', ['Raw Material', 'Packaging Material'])
            ->orderBy('p.product_name')
            ->get([
                'p.product_id',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'p.standard_cost',
                'uom.uom_id',
                'uom.uom_name',
                'uom.uom_code'
            ]);

        // Get existing items
        $items = DB::table('purchase_order_items as poi')
            ->join('products as p', 'poi.product_id', '=', 'p.product_id')
            ->where('poi.po_id', $po->po_id)
            ->select('poi.*', 'p.product_code', 'p.product_name')
            ->get();

        return view('purchase.orders.edit', compact('po', 'suppliers', 'products', 'items'));
    }

    /**
     * Update purchase order
     */
    public function update(Request $request, $poCode)
    {
        $po = DB::table('purchase_orders')
            ->where('po_code', $poCode)
            ->first();

        if (!$po) {
            abort(404, 'Purchase order not found');
        }

        // Only allow editing of Pending orders
        if ($po->status !== 'Pending') {
            return back()->with('error', 'Only pending purchase orders can be edited.');
        }

        $validated = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,supplier_id'],
            'order_date' => ['required', 'date'],
            'expected_delivery' => ['required', 'date', 'after_or_equal:order_date'],
            'payment_terms' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
            
            // Order items
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,product_id'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.0001'],
            'items.*.uom_id' => ['required', 'exists:units_of_measure,uom_id'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.discount_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'items.*.tax_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'items.*.expected_date' => ['nullable', 'date'],
            'items.*.notes' => ['nullable', 'string'],
        ], [
            'supplier_id.required' => 'Please select a supplier.',
            'supplier_id.exists' => 'Selected supplier is invalid.',
            
            'order_date.required' => 'Order date is required.',
            'order_date.date' => 'Invalid order date format.',
            
            'expected_delivery.required' => 'Expected delivery date is required.',
            'expected_delivery.date' => 'Invalid delivery date format.',
            'expected_delivery.after_or_equal' => 'Delivery date must be on or after order date.',
            
            'items.required' => 'Please add at least one item to the order.',
            'items.min' => 'Please add at least one item.',
        ]);

        DB::beginTransaction();
        try {
            // Capture old data
            $oldItemsCount = DB::table('purchase_order_items')
                ->where('po_id', $po->po_id)
                ->count();

            $oldData = [
                'supplier_id' => $po->supplier_id,
                'order_date' => $po->order_date,
                'expected_delivery' => $po->expected_delivery,
                'total_amount' => $po->total_amount,
                'items_count' => $oldItemsCount,
            ];

            // Calculate totals
            $subtotal = 0;
            $taxAmount = 0;
            $discountAmount = 0;

            foreach ($validated['items'] as $item) {
                $lineSubtotal = $item['quantity'] * $item['unit_price'];
                $lineDiscount = $lineSubtotal * (($item['discount_percentage'] ?? 0) / 100);
                $lineTax = ($lineSubtotal - $lineDiscount) * (($item['tax_percentage'] ?? 0) / 100);
                
                $subtotal += $lineSubtotal;
                $discountAmount += $lineDiscount;
                $taxAmount += $lineTax;
            }

            $totalAmount = $subtotal - $discountAmount + $taxAmount;

            // Update purchase order
            DB::table('purchase_orders')
                ->where('po_id', $po->po_id)
                ->update([
                    'supplier_id' => $validated['supplier_id'],
                    'order_date' => $validated['order_date'],
                    'expected_delivery' => $validated['expected_delivery'],
                    'subtotal' => $subtotal,
                    'tax_amount' => $taxAmount,
                    'discount_amount' => $discountAmount,
                    'total_amount' => $totalAmount,
                    'payment_terms' => $validated['payment_terms'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                    'updated_at' => now(),
                ]);

            // Delete old items
            DB::table('purchase_order_items')->where('po_id', $po->po_id)->delete();

            // Insert new items
            foreach ($validated['items'] as $item) {
                $lineSubtotal = $item['quantity'] * $item['unit_price'];
                $lineDiscount = $lineSubtotal * (($item['discount_percentage'] ?? 0) / 100);
                $lineTax = ($lineSubtotal - $lineDiscount) * (($item['tax_percentage'] ?? 0) / 100);
                $lineTotal = $lineSubtotal - $lineDiscount + $lineTax;

                DB::table('purchase_order_items')->insert([
                    'po_id' => $po->po_id,
                    'product_id' => $item['product_id'],
                    'quantity_ordered' => $item['quantity'],
                    'quantity_received' => 0,
                    'uom_id' => $item['uom_id'],
                    'unit_price' => $item['unit_price'],
                    'discount_percentage' => $item['discount_percentage'] ?? 0,
                    'tax_percentage' => $item['tax_percentage'] ?? 0,
                    'line_total' => $lineTotal,
                    'expected_date' => $item['expected_date'] ?? null,
                    'notes' => $item['notes'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Log UPDATE
            $this->logUpdate(
                'Purchase Management - Purchase Orders',
                'purchase_orders',
                $po->po_id,
                $oldData,
                [
                    'supplier_id' => $validated['supplier_id'],
                    'order_date' => $validated['order_date'],
                    'expected_delivery' => $validated['expected_delivery'],
                    'total_amount' => $totalAmount,
                    'items_count' => count($validated['items']),
                ]
            );

            DB::commit();

            return redirect()
                ->route('purchase.orders.show', $poCode)
                ->with('success', 'Purchase order updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update purchase order: ' . $e->getMessage());
        }
    }

    /**
     * Delete purchase order
     */
    public function destroy($poCode)
    {
        $po = DB::table('purchase_orders')
            ->where('po_code', $poCode)
            ->first();

        if (!$po) {
            abort(404, 'Purchase order not found');
        }

        // Only allow deleting Pending or Cancelled orders
        if (!in_array($po->status, ['Pending', 'Cancelled'])) {
            return back()->with('error', 'Only pending or cancelled purchase orders can be deleted.');
        }

        // Check if there are receipts
        $hasReceipts = DB::table('purchase_receipts')
            ->where('po_id', $po->po_id)
            ->exists();

        if ($hasReceipts) {
            return back()->with('error', 'Cannot delete purchase order with existing receipts.');
        }

        DB::beginTransaction();
        try {
            // Capture old data
            $itemsCount = DB::table('purchase_order_items')
                ->where('po_id', $po->po_id)
                ->count();

            $oldData = [
                'po_code' => $po->po_code,
                'supplier_id' => $po->supplier_id,
                'order_date' => $po->order_date,
                'total_amount' => $po->total_amount,
                'status' => $po->status,
                'items_count' => $itemsCount,
            ];

            // Delete items
            DB::table('purchase_order_items')->where('po_id', $po->po_id)->delete();

            // Delete purchase order
            DB::table('purchase_orders')->where('po_id', $po->po_id)->delete();

            // Log DELETE
            $this->logDelete(
                'Purchase Management - Purchase Orders',
                'purchase_orders',
                $po->po_id,
                $oldData
            );

            DB::commit();

            return redirect()
                ->route('purchase.orders.index')
                ->with('success', 'Purchase order deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete purchase order: ' . $e->getMessage());
        }
    }

    /**
     * Approve purchase order
     */
    public function approve($poCode)
    {
        $po = DB::table('purchase_orders')
            ->where('po_code', $poCode)
            ->first();

        if (!$po) {
            abort(404, 'Purchase order not found');
        }

        if ($po->status !== 'Pending') {
            return back()->with('error', 'Only pending purchase orders can be approved.');
        }

        // Check if user is employee
        $employee = DB::table('employees')
            ->where('employee_id', Auth::user()->employee_id)
            ->first();

        if (!$employee) {
            return back()->with('error', 'Only employees can approve purchase orders.');
        }

        DB::beginTransaction();
        try {
            DB::table('purchase_orders')
                ->where('po_id', $po->po_id)
                ->update([
                    'status' => 'Approved',
                    'approved_by' => $employee->employee_id,
                    'updated_at' => now(),
                ]);

            // Log approval
            $this->logApproval(
                'Purchase Management - Purchase Orders',
                'purchase_orders',
                $po->po_id,
                'approved',
                "Purchase order {$po->po_code} approved"
            );

            DB::commit();

            return back()->with('success', 'Purchase order approved successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve purchase order: ' . $e->getMessage());
        }
    }

    /**
     * Cancel purchase order
     */
    public function cancel($poCode)
    {
        $po = DB::table('purchase_orders')
            ->where('po_code', $poCode)
            ->first();

        if (!$po) {
            abort(404, 'Purchase order not found');
        }

        if (!in_array($po->status, ['Pending', 'Approved'])) {
            return back()->with('error', 'Only pending or approved purchase orders can be cancelled.');
        }

        // Check if there are receipts
        $hasReceipts = DB::table('purchase_receipts')
            ->where('po_id', $po->po_id)
            ->exists();

        if ($hasReceipts) {
            return back()->with('error', 'Cannot cancel purchase order with existing receipts.');
        }

        DB::beginTransaction();
        try {
            DB::table('purchase_orders')
                ->where('po_id', $po->po_id)
                ->update([
                    'status' => 'Cancelled',
                    'updated_at' => now(),
                ]);

            // Log cancellation
            $this->logActivity(
                'Cancelled',
                "Cancelled purchase order: {$po->po_code}",
                'Purchase Management - Purchase Orders'
            );

            DB::commit();

            return back()->with('success', 'Purchase order cancelled successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel purchase order: ' . $e->getMessage());
        }
    }

    /**
     * Export purchase orders to CSV
     */
    public function export(Request $request)
    {
        $this->logExport('Purchase Management - Purchase Orders', 'Exported purchase orders to CSV');

        $query = DB::table('purchase_orders as po')
            ->join('suppliers as s', 'po.supplier_id', '=', 's.supplier_id')
            ->select(
                'po.po_code',
                'po.order_date',
                'po.expected_delivery',
                'po.actual_delivery',
                's.supplier_name',
                'po.subtotal',
                'po.tax_amount',
                'po.discount_amount',
                'po.total_amount',
                'po.status',
                'po.payment_terms',
                'po.created_at'
            );

        // Apply filters
        if ($request->filled('status')) {
            $query->where('po.status', $request->status);
        }
        if ($request->filled('supplier_id')) {
            $query->where('po.supplier_id', $request->supplier_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('po.order_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('po.order_date', '<=', $request->date_to);
        }

        $orders = $query->orderByDesc('po.order_date')
            ->limit(5000)
            ->get();

        $filename = 'purchase_orders_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, [
                'PO Code',
                'Order Date',
                'Expected Delivery',
                'Actual Delivery',
                'Supplier',
                'Subtotal',
                'Tax Amount',
                'Discount Amount',
                'Total Amount',
                'Status',
                'Payment Terms',
                'Created At'
            ]);

            // Data
            foreach ($orders as $po) {
                fputcsv($file, [
                    $po->po_code,
                    $po->order_date,
                    $po->expected_delivery,
                    $po->actual_delivery ?? '-',
                    $po->supplier_name,
                    $po->subtotal,
                    $po->tax_amount,
                    $po->discount_amount,
                    $po->total_amount,
                    $po->status,
                    $po->payment_terms ?? '-',
                    $po->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
