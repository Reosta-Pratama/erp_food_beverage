<?php

namespace App\Http\Controllers\Business;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesOrderController extends Controller
{
    //
    use LogsActivity;

    /**
     * Display sales orders with filtering
     */
    public function index(Request $request)
    {
        $this->logView('Sales Management - Sales Orders', 'Viewed sales orders list');

        $query = DB::table('sales_orders as so')
            ->join('customers as c', 'so.customer_id', '=', 'c.customer_id')
            ->join('users as u', 'so.created_by', '=', 'u.user_id')
            ->leftJoin('employees as e', 'so.approved_by', '=', 'e.employee_id')
            ->leftJoin('employees as sp', 'so.sales_person_id', '=', 'sp.employee_id')
            ->select(
                'so.so_id',
                'so.so_code',
                'so.order_date',
                'so.requested_delivery',
                'so.confirmed_delivery',
                'so.status',
                'so.payment_status',
                'so.order_type',
                'so.total_amount',
                'so.created_at',
                'c.customer_code',
                'c.customer_name',
                'c.customer_type',
                'u.full_name as created_by_name',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as approved_by_name"),
                DB::raw("CONCAT(sp.first_name, ' ', sp.last_name) as sales_person_name")
            );

        // Filter by status
        if ($request->filled('status')) {
            $query->where('so.status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('so.payment_status', $request->payment_status);
        }

        // Filter by order type
        if ($request->filled('order_type')) {
            $query->where('so.order_type', $request->order_type);
        }

        // Filter by customer
        if ($request->filled('customer_id')) {
            $query->where('so.customer_id', $request->customer_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('so.order_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('so.order_date', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('so.so_code', 'like', "%{$search}%")
                  ->orWhere('c.customer_name', 'like', "%{$search}%");
            });
        }

        $salesOrders = $query->orderByDesc('so.order_date')
            ->orderByDesc('so.created_at')
            ->paginate(20);

        // Get filter options
        $statuses = DB::table('sales_orders')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $paymentStatuses = DB::table('sales_orders')
            ->select('payment_status', DB::raw('COUNT(*) as count'))
            ->groupBy('payment_status')
            ->pluck('count', 'payment_status');

        $orderTypes = DB::table('sales_orders')
            ->select('order_type', DB::raw('COUNT(*) as count'))
            ->groupBy('order_type')
            ->pluck('count', 'order_type');

        $customers = DB::table('customers')
            ->where('is_active', 1)
            ->orderBy('customer_name')
            ->get(['customer_id', 'customer_code', 'customer_name']);

        $stats = [
            'total' => DB::table('sales_orders')->count(),
            'pending' => DB::table('sales_orders')->where('status', 'Pending')->count(),
            'approved' => DB::table('sales_orders')->where('status', 'Approved')->count(),
            'completed' => DB::table('sales_orders')->where('status', 'Completed')->count(),
            'total_revenue' => DB::table('sales_orders')
                ->whereIn('status', ['Approved', 'Completed'])
                ->sum('total_amount'),
            'unpaid_amount' => DB::table('sales_orders')
                ->where('payment_status', 'Unpaid')
                ->sum('total_amount'),
        ];

        return view('sales.orders.index', compact(
            'salesOrders',
            'statuses',
            'paymentStatuses',
            'orderTypes',
            'customers',
            'stats'
        ));
    }

    /**
     * Show create form
     */
    public function create()
    {
        // Get active customers
        $customers = DB::table('customers')
            ->where('is_active', 1)
            ->orderBy('customer_name')
            ->get(['customer_id', 'customer_code', 'customer_name', 'payment_terms', 'credit_limit']);

        // Get active products (finished goods)
        $products = DB::table('products as p')
            ->join('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->where('p.is_active', 1)
            ->where('p.product_type', 'Finished Goods')
            ->orderBy('p.product_name')
            ->get([
                'p.product_id',
                'p.product_code',
                'p.product_name',
                'p.selling_price',
                'uom.uom_id',
                'uom.uom_name',
                'uom.uom_code'
            ]);

        // Get sales persons (employees)
        $salesPersons = DB::table('employees')
            ->where('employment_status', 'Active')
            ->orderBy('first_name')
            ->get(['employee_id', 'employee_code', 'first_name', 'last_name']);

        return view('sales.orders.create', compact('customers', 'products', 'salesPersons'));
    }

    /**
     * Store new sales order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,customer_id'],
            'order_date' => ['required', 'date'],
            'requested_delivery' => ['required', 'date', 'after_or_equal:order_date'],
            'order_type' => ['required', 'string', 'in:Regular,Urgent,Pre-Order,Custom'],
            'sales_person_id' => ['nullable', 'exists:employees,employee_id'],
            'shipping_cost' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            
            // Order items
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,product_id'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.0001'],
            'items.*.uom_id' => ['required', 'exists:units_of_measure,uom_id'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.discount_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'items.*.tax_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'items.*.notes' => ['nullable', 'string'],
        ], [
            'customer_id.required' => 'Please select a customer.',
            'customer_id.exists' => 'Selected customer is invalid.',
            
            'order_date.required' => 'Order date is required.',
            'order_date.date' => 'Invalid order date format.',
            
            'requested_delivery.required' => 'Requested delivery date is required.',
            'requested_delivery.date' => 'Invalid delivery date format.',
            'requested_delivery.after_or_equal' => 'Delivery date must be on or after order date.',
            
            'order_type.required' => 'Please select an order type.',
            'order_type.in' => 'Invalid order type selected.',
            
            'items.required' => 'Please add at least one item to the order.',
            'items.min' => 'Please add at least one item.',
            
            'items.*.product_id.required' => 'Product is required for each item.',
            'items.*.quantity.required' => 'Quantity is required for each item.',
            'items.*.quantity.min' => 'Quantity must be greater than zero.',
            'items.*.unit_price.required' => 'Unit price is required.',
        ]);

        // Check customer credit limit
        $customer = DB::table('customers')->where('customer_id', $validated['customer_id'])->first();
        
        if ($customer->credit_limit) {
            $currentOutstanding = DB::table('sales_orders')
                ->where('customer_id', $validated['customer_id'])
                ->where('payment_status', 'Unpaid')
                ->sum('total_amount');

            // Calculate new order total
            $newOrderTotal = 0;
            foreach ($validated['items'] as $item) {
                $lineSubtotal = $item['quantity'] * $item['unit_price'];
                $lineDiscount = $lineSubtotal * (($item['discount_percentage'] ?? 0) / 100);
                $lineTax = ($lineSubtotal - $lineDiscount) * (($item['tax_percentage'] ?? 0) / 100);
                $newOrderTotal += $lineSubtotal - $lineDiscount + $lineTax;
            }
            $newOrderTotal += ($validated['shipping_cost'] ?? 0);

            if (($currentOutstanding + $newOrderTotal) > $customer->credit_limit) {
                return back()
                    ->withInput()
                    ->with('error', 'Order exceeds customer credit limit. Current outstanding: ' . number_format($currentOutstanding, 2) . ', Credit limit: ' . number_format($customer->credit_limit, 2));
            }
        }

        DB::beginTransaction();
        try {
            // Generate SO Code
            $soCode = CodeGeneratorHelper::generateSOCode();

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

            $shippingCost = $validated['shipping_cost'] ?? 0;
            $totalAmount = $subtotal - $discountAmount + $taxAmount + $shippingCost;

            // Insert sales order
            $soId = DB::table('sales_orders')->insertGetId([
                'so_code' => $soCode,
                'customer_id' => $validated['customer_id'],
                'order_date' => $validated['order_date'],
                'requested_delivery' => $validated['requested_delivery'],
                'confirmed_delivery' => null,
                'status' => 'Pending',
                'order_type' => $validated['order_type'],
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
                'payment_status' => 'Unpaid',
                'sales_person_id' => $validated['sales_person_id'] ?? null,
                'created_by' => Auth::id(),
                'approved_by' => null,
                'notes' => $validated['notes'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert sales order items
            foreach ($validated['items'] as $item) {
                $lineSubtotal = $item['quantity'] * $item['unit_price'];
                $lineDiscount = $lineSubtotal * (($item['discount_percentage'] ?? 0) / 100);
                $lineTax = ($lineSubtotal - $lineDiscount) * (($item['tax_percentage'] ?? 0) / 100);
                $lineTotal = $lineSubtotal - $lineDiscount + $lineTax;

                DB::table('sales_order_items')->insert([
                    'so_id' => $soId,
                    'product_id' => $item['product_id'],
                    'quantity_ordered' => $item['quantity'],
                    'quantity_delivered' => 0,
                    'uom_id' => $item['uom_id'],
                    'unit_price' => $item['unit_price'],
                    'discount_percentage' => $item['discount_percentage'] ?? 0,
                    'tax_percentage' => $item['tax_percentage'] ?? 0,
                    'line_total' => $lineTotal,
                    'notes' => $item['notes'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Log CREATE
            $this->logCreate(
                'Sales Management - Sales Orders',
                'sales_orders',
                $soId,
                [
                    'so_code' => $soCode,
                    'customer_id' => $validated['customer_id'],
                    'order_date' => $validated['order_date'],
                    'requested_delivery' => $validated['requested_delivery'],
                    'order_type' => $validated['order_type'],
                    'total_amount' => $totalAmount,
                    'items_count' => count($validated['items']),
                ]
            );

            DB::commit();

            return redirect()
                ->route('sales.orders.show', $soCode)
                ->with('success', 'Sales order created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create sales order: ' . $e->getMessage());
        }
    }

    /**
     * Show sales order details
     */
    public function show($soCode)
    {
        $so = DB::table('sales_orders as so')
            ->join('customers as c', 'so.customer_id', '=', 'c.customer_id')
            ->join('users as u', 'so.created_by', '=', 'u.user_id')
            ->leftJoin('employees as e', 'so.approved_by', '=', 'e.employee_id')
            ->leftJoin('employees as sp', 'so.sales_person_id', '=', 'sp.employee_id')
            ->where('so.so_code', $soCode)
            ->select(
                'so.*',
                'c.customer_code',
                'c.customer_name',
                'c.customer_type',
                'c.contact_person',
                'c.email as customer_email',
                'c.phone as customer_phone',
                'c.shipping_address',
                'c.billing_address',
                'u.full_name as created_by_name',
                'u.email as created_by_email',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as approved_by_name"),
                DB::raw("CONCAT(sp.first_name, ' ', sp.last_name) as sales_person_name")
            )
            ->first();

        if (!$so) {
            abort(404, 'Sales order not found');
        }

        // Log VIEW
        $this->logView(
            'Sales Management - Sales Orders',
            "Viewed sales order: {$so->so_code}"
        );

        // Get sales order items
        $items = DB::table('sales_order_items as soi')
            ->join('products as p', 'soi.product_id', '=', 'p.product_id')
            ->join('units_of_measure as uom', 'soi.uom_id', '=', 'uom.uom_id')
            ->where('soi.so_id', $so->so_id)
            ->select(
                'soi.*',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'uom.uom_name',
                'uom.uom_code'
            )
            ->get();

        // Get related deliveries
        $deliveries = DB::table('deliveries')
            ->where('so_id', $so->so_id)
            ->select('delivery_code', 'delivery_date', 'status')
            ->orderByDesc('delivery_date')
            ->get();

        return view('sales.orders.show', compact('so', 'items', 'deliveries'));
    }

    /**
     * Show edit form
     */
    public function edit($soCode)
    {
        $so = DB::table('sales_orders')
            ->where('so_code', $soCode)
            ->first();

        if (!$so) {
            abort(404, 'Sales order not found');
        }

        // Only allow editing of Pending orders
        if ($so->status !== 'Pending') {
            return back()->with('error', 'Only pending sales orders can be edited.');
        }

        // Get customers
        $customers = DB::table('customers')
            ->where('is_active', 1)
            ->orderBy('customer_name')
            ->get(['customer_id', 'customer_code', 'customer_name', 'payment_terms', 'credit_limit']);

        // Get products
        $products = DB::table('products as p')
            ->join('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->where('p.is_active', 1)
            ->where('p.product_type', 'Finished Goods')
            ->orderBy('p.product_name')
            ->get([
                'p.product_id',
                'p.product_code',
                'p.product_name',
                'p.selling_price',
                'uom.uom_id',
                'uom.uom_name',
                'uom.uom_code'
            ]);

        // Get sales persons
        $salesPersons = DB::table('employees')
            ->where('employment_status', 'Active')
            ->orderBy('first_name')
            ->get(['employee_id', 'employee_code', 'first_name', 'last_name']);

        // Get existing items
        $items = DB::table('sales_order_items as soi')
            ->join('products as p', 'soi.product_id', '=', 'p.product_id')
            ->where('soi.so_id', $so->so_id)
            ->select('soi.*', 'p.product_code', 'p.product_name')
            ->get();

        return view('sales.orders.edit', compact('so', 'customers', 'products', 'salesPersons', 'items'));
    }

    /**
     * Update sales order
     */
    public function update(Request $request, $soCode)
    {
        $so = DB::table('sales_orders')
            ->where('so_code', $soCode)
            ->first();

        if (!$so) {
            abort(404, 'Sales order not found');
        }

        // Only allow editing of Pending orders
        if ($so->status !== 'Pending') {
            return back()->with('error', 'Only pending sales orders can be edited.');
        }

        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,customer_id'],
            'order_date' => ['required', 'date'],
            'requested_delivery' => ['required', 'date', 'after_or_equal:order_date'],
            'order_type' => ['required', 'string', 'in:Regular,Urgent,Pre-Order,Custom'],
            'sales_person_id' => ['nullable', 'exists:employees,employee_id'],
            'shipping_cost' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            
            // Order items
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,product_id'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.0001'],
            'items.*.uom_id' => ['required', 'exists:units_of_measure,uom_id'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.discount_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'items.*.tax_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'items.*.notes' => ['nullable', 'string'],
        ]);

        DB::beginTransaction();
        try {
            // Capture old data
            $oldItemsCount = DB::table('sales_order_items')
                ->where('so_id', $so->so_id)
                ->count();

            $oldData = [
                'customer_id' => $so->customer_id,
                'order_date' => $so->order_date,
                'requested_delivery' => $so->requested_delivery,
                'order_type' => $so->order_type,
                'total_amount' => $so->total_amount,
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

            $shippingCost = $validated['shipping_cost'] ?? 0;
            $totalAmount = $subtotal - $discountAmount + $taxAmount + $shippingCost;

            // Update sales order
            DB::table('sales_orders')
                ->where('so_id', $so->so_id)
                ->update([
                    'customer_id' => $validated['customer_id'],
                    'order_date' => $validated['order_date'],
                    'requested_delivery' => $validated['requested_delivery'],
                    'order_type' => $validated['order_type'],
                    'subtotal' => $subtotal,
                    'tax_amount' => $taxAmount,
                    'discount_amount' => $discountAmount,
                    'shipping_cost' => $shippingCost,
                    'total_amount' => $totalAmount,
                    'sales_person_id' => $validated['sales_person_id'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                    'updated_at' => now(),
                ]);

            // Delete old items
            DB::table('sales_order_items')->where('so_id', $so->so_id)->delete();

            // Insert new items
            foreach ($validated['items'] as $item) {
                $lineSubtotal = $item['quantity'] * $item['unit_price'];
                $lineDiscount = $lineSubtotal * (($item['discount_percentage'] ?? 0) / 100);
                $lineTax = ($lineSubtotal - $lineDiscount) * (($item['tax_percentage'] ?? 0) / 100);
                $lineTotal = $lineSubtotal - $lineDiscount + $lineTax;

                DB::table('sales_order_items')->insert([
                    'so_id' => $so->so_id,
                    'product_id' => $item['product_id'],
                    'quantity_ordered' => $item['quantity'],
                    'quantity_delivered' => 0,
                    'uom_id' => $item['uom_id'],
                    'unit_price' => $item['unit_price'],
                    'discount_percentage' => $item['discount_percentage'] ?? 0,
                    'tax_percentage' => $item['tax_percentage'] ?? 0,
                    'line_total' => $lineTotal,
                    'notes' => $item['notes'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Log UPDATE
            $this->logUpdate(
                'Sales Management - Sales Orders',
                'sales_orders',
                $so->so_id,
                $oldData,
                [
                    'customer_id' => $validated['customer_id'],
                    'order_date' => $validated['order_date'],
                    'requested_delivery' => $validated['requested_delivery'],
                    'order_type' => $validated['order_type'],
                    'total_amount' => $totalAmount,
                    'items_count' => count($validated['items']),
                ]
            );

            DB::commit();

            return redirect()
                ->route('sales.orders.show', $soCode)
                ->with('success', 'Sales order updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update sales order: ' . $e->getMessage());
        }
    }

    /**
     * Delete sales order
     */
    public function destroy($soCode)
    {
        $so = DB::table('sales_orders')
            ->where('so_code', $soCode)
            ->first();

        if (!$so) {
            abort(404, 'Sales order not found');
        }

        // Only allow deleting Pending or Cancelled orders
        if (!in_array($so->status, ['Pending', 'Cancelled'])) {
            return back()->with('error', 'Only pending or cancelled sales orders can be deleted.');
        }

        // Check if there are deliveries
        $hasDeliveries = DB::table('deliveries')
            ->where('so_id', $so->so_id)
            ->exists();

        if ($hasDeliveries) {
            return back()->with('error', 'Cannot delete sales order with existing deliveries.');
        }

        DB::beginTransaction();
        try {
            // Capture old data
            $itemsCount = DB::table('sales_order_items')
                ->where('so_id', $so->so_id)
                ->count();

            $oldData = [
                'so_code' => $so->so_code,
                'customer_id' => $so->customer_id,
                'order_date' => $so->order_date,
                'total_amount' => $so->total_amount,
                'status' => $so->status,
                'items_count' => $itemsCount,
            ];

            // Delete items
            DB::table('sales_order_items')->where('so_id', $so->so_id)->delete();

            // Delete sales order
            DB::table('sales_orders')->where('so_id', $so->so_id)->delete();

            // Log DELETE
            $this->logDelete(
                'Sales Management - Sales Orders',
                'sales_orders',
                $so->so_id,
                $oldData
            );

            DB::commit();

            return redirect()
                ->route('sales.orders.index')
                ->with('success', 'Sales order deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete sales order: ' . $e->getMessage());
        }
    }

    /**
     * Approve sales order
     */
    public function approve($soCode)
    {
        $so = DB::table('sales_orders')
            ->where('so_code', $soCode)
            ->first();

        if (!$so) {
            abort(404, 'Sales order not found');
        }

        if ($so->status !== 'Pending') {
            return back()->with('error', 'Only pending sales orders can be approved.');
        }

        // Check if user is employee
        $employee = DB::table('employees')
            ->where('employee_id', Auth::user()->employee_id)
            ->first();

        if (!$employee) {
            return back()->with('error', 'Only employees can approve sales orders.');
        }

        DB::beginTransaction();
        try {
            DB::table('sales_orders')
                ->where('so_id', $so->so_id)
                ->update([
                    'status' => 'Approved',
                    'approved_by' => $employee->employee_id,
                    'confirmed_delivery' => $so->requested_delivery,
                    'updated_at' => now(),
                ]);

            // Log approval
            $this->logApproval(
                'Sales Management - Sales Orders',
                'sales_orders',
                $so->so_id,
                'approved',
                "Sales order {$so->so_code} approved"
            );

            DB::commit();

            return back()->with('success', 'Sales order approved successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve sales order: ' . $e->getMessage());
        }
    }

    /**
     * Process sales order (mark as In Process)
     */
    public function process($soCode)
    {
        $so = DB::table('sales_orders')
            ->where('so_code', $soCode)
            ->first();

        if (!$so) {
            abort(404, 'Sales order not found');
        }

        if ($so->status !== 'Approved') {
            return back()->with('error', 'Only approved sales orders can be processed.');
        }

        DB::beginTransaction();
        try {
            DB::table('sales_orders')
                ->where('so_id', $so->so_id)
                ->update([
                    'status' => 'In Process',
                    'updated_at' => now(),
                ]);

            // Log activity
            $this->logActivity(
                'In Process',
                "Sales order {$so->so_code} marked as in process",
                'Sales Management - Sales Orders'
            );

            DB::commit();

            return back()->with('success', 'Sales order is now in process.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process sales order: ' . $e->getMessage());
        }
    }

    /**
     * Cancel sales order
     */
    public function cancel($soCode)
    {
        $so = DB::table('sales_orders')
            ->where('so_code', $soCode)
            ->first();

        if (!$so) {
            abort(404, 'Sales order not found');
        }

        if (!in_array($so->status, ['Pending', 'Approved'])) {
            return back()->with('error', 'Only pending or approved sales orders can be cancelled.');
        }

        // Check if there are deliveries
        $hasDeliveries = DB::table('deliveries')
            ->where('so_id', $so->so_id)
            ->exists();

        if ($hasDeliveries) {
            return back()->with('error', 'Cannot cancel sales order with existing deliveries.');
        }

        DB::beginTransaction();
        try {
            DB::table('sales_orders')
                ->where('so_id', $so->so_id)
                ->update([
                    'status' => 'Cancelled',
                    'updated_at' => now(),
                ]);

            // Log cancellation
            $this->logActivity(
                'Cancelled',
                "Cancelled sales order: {$so->so_code}",
                'Sales Management - Sales Orders'
            );

            DB::commit();

            return back()->with('success', 'Sales order cancelled successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel sales order: ' . $e->getMessage());
        }
    }

    /**
     * Export sales orders to CSV
     */
    public function export(Request $request)
    {
        $this->logExport('Sales Management - Sales Orders', 'Exported sales orders to CSV');

        $query = DB::table('sales_orders as so')
            ->join('customers as c', 'so.customer_id', '=', 'c.customer_id')
            ->select(
                'so.so_code',
                'so.order_date',
                'so.requested_delivery',
                'so.confirmed_delivery',
                'c.customer_name',
                'so.order_type',
                'so.subtotal',
                'so.tax_amount',
                'so.discount_amount',
                'so.shipping_cost',
                'so.total_amount',
                'so.status',
                'so.payment_status',
                'so.created_at'
            );

        // Apply filters
        if ($request->filled('status')) {
            $query->where('so.status', $request->status);
        }
        if ($request->filled('payment_status')) {
            $query->where('so.payment_status', $request->payment_status);
        }
        if ($request->filled('customer_id')) {
            $query->where('so.customer_id', $request->customer_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('so.order_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('so.order_date', '<=', $request->date_to);
        }

        $orders = $query->orderByDesc('so.order_date')
            ->limit(5000)
            ->get();

        $filename = 'sales_orders_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, [
                'SO Code',
                'Order Date',
                'Requested Delivery',
                'Confirmed Delivery',
                'Customer',
                'Order Type',
                'Subtotal',
                'Tax Amount',
                'Discount Amount',
                'Shipping Cost',
                'Total Amount',
                'Status',
                'Payment Status',
                'Created At'
            ]);

            // Data
            foreach ($orders as $so) {
                fputcsv($file, [
                    $so->so_code,
                    $so->order_date,
                    $so->requested_delivery,
                    $so->confirmed_delivery ?? '-',
                    $so->customer_name,
                    $so->order_type,
                    $so->subtotal,
                    $so->tax_amount,
                    $so->discount_amount,
                    $so->shipping_cost,
                    $so->total_amount,
                    $so->status,
                    $so->payment_status,
                    $so->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Print sales order
     */
    public function print($soCode)
    {
        $so = DB::table('sales_orders as so')
            ->join('customers as c', 'so.customer_id', '=', 'c.customer_id')
            ->where('so.so_code', $soCode)
            ->select('so.*', 'c.*')
            ->first();

        if (!$so) {
            abort(404, 'Sales order not found');
        }

        // Get items
        $items = DB::table('sales_order_items as soi')
            ->join('products as p', 'soi.product_id', '=', 'p.product_id')
            ->join('units_of_measure as uom', 'soi.uom_id', '=', 'uom.uom_id')
            ->where('soi.so_id', $so->so_id)
            ->select('soi.*', 'p.product_code', 'p.product_name', 'uom.uom_name')
            ->get();

        // Get company profile
        $company = DB::table('company_profile')->first();

        // Log PRINT
        $this->logPrint('Sales Management - Sales Orders', "Printed sales order: {$so->so_code}");

        return view('sales.orders.print', compact('so', 'items', 'company'));
    }
}
