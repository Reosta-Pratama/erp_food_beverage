<?php

namespace App\Http\Controllers\Logistics;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeliveryController extends Controller
{
    //
    use LogsActivity;

    /**
     * Display deliveries with filtering
     */
    public function index(Request $request)
    {
        $this->logView('Logistics - Delivery Management', 'Viewed deliveries list');

        $query = DB::table('deliveries as d')
            ->join('sales_orders as so', 'd.so_id', '=', 'so.so_id')
            ->join('customers as c', 'd.customer_id', '=', 'c.customer_id')
            ->join('warehouses as w', 'd.warehouse_id', '=', 'w.warehouse_id')
            ->leftJoin('drivers as dr', 'd.driver_id', '=', 'dr.driver_id')
            ->leftJoin('employees as e', 'dr.employee_id', '=', 'e.employee_id')
            ->leftJoin('vehicles as v', 'd.vehicle_id', '=', 'v.vehicle_id')
            ->select(
                'd.delivery_id',
                'd.delivery_code',
                'd.delivery_date',
                'd.status',
                'd.departure_time',
                'd.arrival_time',
                'd.created_at',
                'so.so_code',
                'c.customer_code',
                'c.customer_name',
                'c.city as customer_city',
                'w.warehouse_code',
                'w.warehouse_name',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as driver_name"),
                'v.vehicle_code',
                'v.license_plate'
            );

        // Filter by status
        if ($request->filled('status')) {
            $query->where('d.status', $request->status);
        }

        // Filter by customer
        if ($request->filled('customer_id')) {
            $query->where('d.customer_id', $request->customer_id);
        }

        // Filter by warehouse
        if ($request->filled('warehouse_id')) {
            $query->where('d.warehouse_id', $request->warehouse_id);
        }

        // Filter by driver
        if ($request->filled('driver_id')) {
            $query->where('d.driver_id', $request->driver_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('d.delivery_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('d.delivery_date', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('d.delivery_code', 'like', "%{$search}%")
                  ->orWhere('so.so_code', 'like', "%{$search}%")
                  ->orWhere('c.customer_name', 'like', "%{$search}%");
            });
        }

        $deliveries = $query->orderByDesc('d.delivery_date')
            ->orderByDesc('d.created_at')
            ->paginate(20);

        // Get filter options
        $statuses = DB::table('deliveries')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $customers = DB::table('customers')
            ->where('is_active', 1)
            ->orderBy('customer_name')
            ->get(['customer_id', 'customer_code', 'customer_name']);

        $warehouses = DB::table('warehouses')
            ->where('is_active', 1)
            ->orderBy('warehouse_name')
            ->get(['warehouse_id', 'warehouse_code', 'warehouse_name']);

        $drivers = DB::table('drivers as d')
            ->join('employees as e', 'd.employee_id', '=', 'e.employee_id')
            ->orderBy('e.first_name')
            ->get([
                'd.driver_id',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as driver_name")
            ]);

        $stats = [
            'total' => DB::table('deliveries')->count(),
            'pending' => DB::table('deliveries')->where('status', 'Pending')->count(),
            'in_transit' => DB::table('deliveries')->where('status', 'In Transit')->count(),
            'completed' => DB::table('deliveries')->where('status', 'Completed')->count(),
        ];

        return view('logistics.deliveries.index', compact(
            'deliveries',
            'statuses',
            'customers',
            'warehouses',
            'drivers',
            'stats'
        ));
    }

    /**
     * Show create form
     */
    public function create()
    {
        // Get approved sales orders that need delivery
        $salesOrders = DB::table('sales_orders as so')
            ->join('customers as c', 'so.customer_id', '=', 'c.customer_id')
            ->whereIn('so.status', ['Approved', 'In Process'])
            ->orderByDesc('so.order_date')
            ->get([
                'so.so_id',
                'so.so_code',
                'so.order_date',
                'so.total_amount',
                'so.customer_id',
                'c.customer_name',
                'c.shipping_address'
            ]);

        // Get warehouses
        $warehouses = DB::table('warehouses')
            ->where('is_active', 1)
            ->orderBy('warehouse_name')
            ->get(['warehouse_id', 'warehouse_code', 'warehouse_name']);

        // Get available drivers
        $drivers = DB::table('drivers as d')
            ->join('employees as e', 'd.employee_id', '=', 'e.employee_id')
            ->where('d.is_available', 1)
            ->orderBy('e.first_name')
            ->get([
                'd.driver_id',
                'e.employee_code',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as driver_name"),
                'd.license_number'
            ]);

        // Get available vehicles
        $vehicles = DB::table('vehicles')
            ->whereIn('status', ['Available', 'Active'])
            ->orderBy('vehicle_code')
            ->get(['vehicle_id', 'vehicle_code', 'license_plate', 'vehicle_type']);

        return view('logistics.deliveries.create', compact(
            'salesOrders',
            'warehouses',
            'drivers',
            'vehicles'
        ));
    }

    /**
     * Store new delivery
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'so_id' => ['required', 'exists:sales_orders,so_id'],
            'delivery_date' => ['required', 'date'],
            'warehouse_id' => ['required', 'exists:warehouses,warehouse_id'],
            'driver_id' => ['nullable', 'exists:drivers,driver_id'],
            'vehicle_id' => ['nullable', 'exists:vehicles,vehicle_id'],
            'shipping_address' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
            
            // Delivery items
            'items' => ['required', 'array', 'min:1'],
            'items.*.so_item_id' => ['required', 'exists:sales_order_items,so_item_id'],
            'items.*.product_id' => ['required', 'exists:products,product_id'],
            'items.*.lot_id' => ['nullable', 'exists:lots,lot_id'],
            'items.*.quantity_delivered' => ['required', 'numeric', 'min:0.0001'],
            'items.*.uom_id' => ['required', 'exists:units_of_measure,uom_id'],
            'items.*.condition' => ['required', 'string', 'in:Good,Damaged'],
            'items.*.notes' => ['nullable', 'string'],
        ], [
            'so_id.required' => 'Please select a sales order.',
            'so_id.exists' => 'Selected sales order is invalid.',
            
            'delivery_date.required' => 'Delivery date is required.',
            'delivery_date.date' => 'Invalid delivery date format.',
            
            'warehouse_id.required' => 'Please select a warehouse.',
            'warehouse_id.exists' => 'Selected warehouse is invalid.',
            
            'driver_id.exists' => 'Selected driver is invalid.',
            'vehicle_id.exists' => 'Selected vehicle is invalid.',
            
            'shipping_address.required' => 'Shipping address is required.',
            
            'items.required' => 'Please add at least one item.',
            'items.min' => 'Please add at least one item.',
        ]);

        // Validate quantities against SO items
        foreach ($validated['items'] as $item) {
            $soItem = DB::table('sales_order_items')
                ->where('so_item_id', $item['so_item_id'])
                ->first();

            $remainingQty = $soItem->quantity_ordered - $soItem->quantity_delivered;

            if ($item['quantity_delivered'] > $remainingQty) {
                return back()
                    ->withInput()
                    ->with('error', 'Delivery quantity exceeds remaining quantity for one or more items.');
            }
        }

        DB::beginTransaction();
        try {
            // Get SO and customer details
            $so = DB::table('sales_orders')->where('so_id', $validated['so_id'])->first();

            // Generate delivery code
            $deliveryCode = CodeGeneratorHelper::generateDeliveryCode();

            // Insert delivery
            $deliveryId = DB::table('deliveries')->insertGetId([
                'delivery_code' => $deliveryCode,
                'so_id' => $validated['so_id'],
                'customer_id' => $so->customer_id,
                'delivery_date' => $validated['delivery_date'],
                'warehouse_id' => $validated['warehouse_id'],
                'driver_id' => $validated['driver_id'] ?? null,
                'vehicle_id' => $validated['vehicle_id'] ?? null,
                'shipping_address' => $validated['shipping_address'],
                'status' => 'Pending',
                'departure_time' => null,
                'arrival_time' => null,
                'delivered_by' => null,
                'signature_path' => null,
                'notes' => $validated['notes'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert delivery items
            foreach ($validated['items'] as $item) {
                DB::table('delivery_items')->insert([
                    'delivery_id' => $deliveryId,
                    'so_item_id' => $item['so_item_id'],
                    'product_id' => $item['product_id'],
                    'lot_id' => $item['lot_id'] ?? null,
                    'quantity_delivered' => $item['quantity_delivered'],
                    'uom_id' => $item['uom_id'],
                    'condition' => $item['condition'],
                    'notes' => $item['notes'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update SO item quantity delivered
                DB::table('sales_order_items')
                    ->where('so_item_id', $item['so_item_id'])
                    ->increment('quantity_delivered', $item['quantity_delivered']);

                // Reserve inventory
                DB::table('inventory')
                    ->where('product_id', $item['product_id'])
                    ->where('warehouse_id', $validated['warehouse_id'])
                    ->where('lot_id', $item['lot_id'])
                    ->update([
                        'quantity_reserved' => DB::raw("quantity_reserved + {$item['quantity_delivered']}"),
                        'quantity_available' => DB::raw("quantity_available - {$item['quantity_delivered']}"),
                        'last_updated' => now(),
                    ]);
            }

            // Check if SO is fully delivered
            $soItems = DB::table('sales_order_items')
                ->where('so_id', $validated['so_id'])
                ->get();

            $fullyDelivered = true;
            foreach ($soItems as $soItem) {
                if ($soItem->quantity_delivered < $soItem->quantity_ordered) {
                    $fullyDelivered = false;
                    break;
                }
            }

            // Update SO status if fully delivered
            if ($fullyDelivered) {
                DB::table('sales_orders')
                    ->where('so_id', $validated['so_id'])
                    ->update([
                        'status' => 'Completed',
                        'updated_at' => now(),
                    ]);
            }

            // Log CREATE
            $this->logCreate(
                'Logistics - Delivery Management',
                'deliveries',
                $deliveryId,
                [
                    'delivery_code' => $deliveryCode,
                    'so_id' => $validated['so_id'],
                    'delivery_date' => $validated['delivery_date'],
                    'warehouse_id' => $validated['warehouse_id'],
                    'items_count' => count($validated['items']),
                ]
            );

            DB::commit();

            return redirect()
                ->route('logistics.deliveries.show', $deliveryCode)
                ->with('success', 'Delivery created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create delivery: ' . $e->getMessage());
        }
    }

    /**
     * Show delivery details
     */
    public function show($deliveryCode)
    {
        $delivery = DB::table('deliveries as d')
            ->join('sales_orders as so', 'd.so_id', '=', 'so.so_id')
            ->join('customers as c', 'd.customer_id', '=', 'c.customer_id')
            ->join('warehouses as w', 'd.warehouse_id', '=', 'w.warehouse_id')
            ->leftJoin('drivers as dr', 'd.driver_id', '=', 'dr.driver_id')
            ->leftJoin('employees as e', 'dr.employee_id', '=', 'e.employee_id')
            ->leftJoin('vehicles as v', 'd.vehicle_id', '=', 'v.vehicle_id')
            ->leftJoin('employees as del_by', 'd.delivered_by', '=', 'del_by.employee_id')
            ->where('d.delivery_code', $deliveryCode)
            ->select(
                'd.*',
                'so.so_code',
                'so.order_date',
                'c.customer_code',
                'c.customer_name',
                'c.contact_person',
                'c.email as customer_email',
                'c.phone as customer_phone',
                'w.warehouse_code',
                'w.warehouse_name',
                'dr.license_number',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as driver_name"),
                'e.phone as driver_phone',
                'v.vehicle_code',
                'v.license_plate',
                'v.vehicle_type',
                DB::raw("CONCAT(del_by.first_name, ' ', del_by.last_name) as delivered_by_name")
            )
            ->first();

        if (!$delivery) {
            abort(404, 'Delivery not found');
        }

        // Log VIEW
        $this->logView(
            'Logistics - Delivery Management',
            "Viewed delivery: {$delivery->delivery_code}"
        );

        // Get delivery items
        $items = DB::table('delivery_items as di')
            ->join('products as p', 'di.product_id', '=', 'p.product_id')
            ->join('units_of_measure as uom', 'di.uom_id', '=', 'uom.uom_id')
            ->leftJoin('lots as l', 'di.lot_id', '=', 'l.lot_id')
            ->where('di.delivery_id', $delivery->delivery_id)
            ->select(
                'di.*',
                'p.product_code',
                'p.product_name',
                'uom.uom_name',
                'l.lot_code'
            )
            ->get();

        // Get delivery confirmation if exists
        $confirmation = DB::table('delivery_confirmations')
            ->where('delivery_id', $delivery->delivery_id)
            ->first();

        return view('logistics.deliveries.show', compact('delivery', 'items', 'confirmation'));
    }

    /**
     * Show edit form
     */
    public function edit($deliveryCode)
    {
        $delivery = DB::table('deliveries')
            ->where('delivery_code', $deliveryCode)
            ->first();

        if (!$delivery) {
            abort(404, 'Delivery not found');
        }

        // Only allow editing Pending deliveries
        if ($delivery->status !== 'Pending') {
            return back()->with('error', 'Only pending deliveries can be edited.');
        }

        // Get warehouses
        $warehouses = DB::table('warehouses')
            ->where('is_active', 1)
            ->orderBy('warehouse_name')
            ->get(['warehouse_id', 'warehouse_code', 'warehouse_name']);

        // Get drivers
        $drivers = DB::table('drivers as d')
            ->join('employees as e', 'd.employee_id', '=', 'e.employee_id')
            ->where('d.is_available', 1)
            ->orderBy('e.first_name')
            ->get([
                'd.driver_id',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as driver_name")
            ]);

        // Get vehicles
        $vehicles = DB::table('vehicles')
            ->whereIn('status', ['Available', 'Active'])
            ->orderBy('vehicle_code')
            ->get(['vehicle_id', 'vehicle_code', 'license_plate']);

        // Get SO details
        $so = DB::table('sales_orders')
            ->where('so_id', $delivery->so_id)
            ->first();

        // Get delivery items
        $items = DB::table('delivery_items as di')
            ->join('products as p', 'di.product_id', '=', 'p.product_id')
            ->where('di.delivery_id', $delivery->delivery_id)
            ->select('di.*', 'p.product_code', 'p.product_name')
            ->get();

        return view('logistics.deliveries.edit', compact(
            'delivery',
            'warehouses',
            'drivers',
            'vehicles',
            'so',
            'items'
        ));
    }

    /**
     * Update delivery
     */
    public function update(Request $request, $deliveryCode)
    {
        $delivery = DB::table('deliveries')
            ->where('delivery_code', $deliveryCode)
            ->first();

        if (!$delivery) {
            abort(404, 'Delivery not found');
        }

        // Only allow editing Pending deliveries
        if ($delivery->status !== 'Pending') {
            return back()->with('error', 'Only pending deliveries can be edited.');
        }

        $validated = $request->validate([
            'delivery_date' => ['required', 'date'],
            'warehouse_id' => ['required', 'exists:warehouses,warehouse_id'],
            'driver_id' => ['nullable', 'exists:drivers,driver_id'],
            'vehicle_id' => ['nullable', 'exists:vehicles,vehicle_id'],
            'shipping_address' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'delivery_date' => $delivery->delivery_date,
                'warehouse_id' => $delivery->warehouse_id,
                'driver_id' => $delivery->driver_id,
                'vehicle_id' => $delivery->vehicle_id,
            ];

            // Update delivery
            DB::table('deliveries')
                ->where('delivery_id', $delivery->delivery_id)
                ->update([
                    'delivery_date' => $validated['delivery_date'],
                    'warehouse_id' => $validated['warehouse_id'],
                    'driver_id' => $validated['driver_id'] ?? null,
                    'vehicle_id' => $validated['vehicle_id'] ?? null,
                    'shipping_address' => $validated['shipping_address'],
                    'notes' => $validated['notes'] ?? null,
                    'updated_at' => now(),
                ]);

            // Log UPDATE
            $this->logUpdate(
                'Logistics - Delivery Management',
                'deliveries',
                $delivery->delivery_id,
                $oldData,
                [
                    'delivery_date' => $validated['delivery_date'],
                    'warehouse_id' => $validated['warehouse_id'],
                    'driver_id' => $validated['driver_id'] ?? null,
                    'vehicle_id' => $validated['vehicle_id'] ?? null,
                ]
            );

            DB::commit();

            return redirect()
                ->route('logistics.deliveries.show', $deliveryCode)
                ->with('success', 'Delivery updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update delivery: ' . $e->getMessage());
        }
    }

    /**
     * Delete delivery
     */
    public function destroy($deliveryCode)
    {
        $delivery = DB::table('deliveries')
            ->where('delivery_code', $deliveryCode)
            ->first();

        if (!$delivery) {
            abort(404, 'Delivery not found');
        }

        // Only allow deleting Pending or Cancelled deliveries
        if (!in_array($delivery->status, ['Pending', 'Cancelled'])) {
            return back()->with('error', 'Only pending or cancelled deliveries can be deleted.');
        }

        DB::beginTransaction();
        try {
            // Get delivery items to reverse
            $items = DB::table('delivery_items')
                ->where('delivery_id', $delivery->delivery_id)
                ->get();

            foreach ($items as $item) {
                // Reverse SO item quantity
                DB::table('sales_order_items')
                    ->where('so_item_id', $item->so_item_id)
                    ->decrement('quantity_delivered', $item->quantity_delivered);

                // Unreserve inventory
                DB::table('inventory')
                    ->where('product_id', $item->product_id)
                    ->where('warehouse_id', $delivery->warehouse_id)
                    ->where('lot_id', $item->lot_id)
                    ->update([
                        'quantity_reserved' => DB::raw("quantity_reserved - {$item->quantity_delivered}"),
                        'quantity_available' => DB::raw("quantity_available + {$item->quantity_delivered}"),
                        'last_updated' => now(),
                    ]);
            }

            // Capture old data
            $itemsCount = count($items);
            $oldData = [
                'delivery_code' => $delivery->delivery_code,
                'so_id' => $delivery->so_id,
                'delivery_date' => $delivery->delivery_date,
                'status' => $delivery->status,
                'items_count' => $itemsCount,
            ];

            // Delete delivery items
            DB::table('delivery_items')
                ->where('delivery_id', $delivery->delivery_id)
                ->delete();

            // Delete delivery confirmation if exists
            DB::table('delivery_confirmations')
                ->where('delivery_id', $delivery->delivery_id)
                ->delete();

            // Delete delivery
            DB::table('deliveries')
                ->where('delivery_id', $delivery->delivery_id)
                ->delete();

            // Update SO status back to In Process
            DB::table('sales_orders')
                ->where('so_id', $delivery->so_id)
                ->update([
                    'status' => 'In Process',
                    'updated_at' => now(),
                ]);

            // Log DELETE
            $this->logDelete(
                'Logistics - Delivery Management',
                'deliveries',
                $delivery->delivery_id,
                $oldData
            );

            DB::commit();

            return redirect()
                ->route('logistics.deliveries.index')
                ->with('success', 'Delivery deleted successfully. Inventory has been reversed.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete delivery: ' . $e->getMessage());
        }
    }

    /**
     * Dispatch delivery
     */
    public function dispatch($deliveryCode)
    {
        $delivery = DB::table('deliveries')
            ->where('delivery_code', $deliveryCode)
            ->first();

        if (!$delivery) {
            abort(404, 'Delivery not found');
        }

        if ($delivery->status !== 'Pending') {
            return back()->with('error', 'Only pending deliveries can be dispatched.');
        }

        if (!$delivery->driver_id || !$delivery->vehicle_id) {
            return back()->with('error', 'Please assign driver and vehicle before dispatching.');
        }

        DB::beginTransaction();
        try {
            DB::table('deliveries')
                ->where('delivery_id', $delivery->delivery_id)
                ->update([
                    'status' => 'Dispatched',
                    'departure_time' => now()->format('H:i:s'),
                    'updated_at' => now(),
                ]);

            // Update vehicle status
            DB::table('vehicles')
                ->where('vehicle_id', $delivery->vehicle_id)
                ->update(['status' => 'In Use']);

            // Update driver availability
            DB::table('drivers')
                ->where('driver_id', $delivery->driver_id)
                ->update(['is_available' => 0]);

            // Log activity
            $this->logActivity(
                'Dispatched',
                "Delivery {$delivery->delivery_code} dispatched",
                'Logistics - Delivery Management'
            );

            DB::commit();

            return back()->with('success', 'Delivery dispatched successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to dispatch delivery: ' . $e->getMessage());
        }
    }

    /**
     * Mark delivery as in transit
     */
    public function inTransit($deliveryCode)
    {
        $delivery = DB::table('deliveries')
            ->where('delivery_code', $deliveryCode)
            ->first();

        if (!$delivery) {
            abort(404, 'Delivery not found');
        }

        if ($delivery->status !== 'Dispatched') {
            return back()->with('error', 'Only dispatched deliveries can be marked as in transit.');
        }

        DB::beginTransaction();
        try {
            DB::table('deliveries')
                ->where('delivery_id', $delivery->delivery_id)
                ->update([
                    'status' => 'In Transit',
                    'updated_at' => now(),
                ]);

            // Log activity
            $this->logActivity(
                'In Transit',
                "Delivery {$delivery->delivery_code} is now in transit",
                'Logistics - Delivery Management'
            );

            DB::commit();

            return back()->with('success', 'Delivery is now in transit.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update delivery status: ' . $e->getMessage());
        }
    }

    /**
     * Complete delivery
     */
    public function complete($deliveryCode)
    {
        $delivery = DB::table('deliveries')
            ->where('delivery_code', $deliveryCode)
            ->first();

        if (!$delivery) {
            abort(404, 'Delivery not found');
        }

        if (!in_array($delivery->status, ['Dispatched', 'In Transit'])) {
            return back()->with('error', 'Only dispatched or in-transit deliveries can be completed.');
        }

        DB::beginTransaction();
        try {
            // Get delivery items
            $items = DB::table('delivery_items')
                ->where('delivery_id', $delivery->delivery_id)
                ->get();

            // Update inventory (convert reserved to actual deduction)
            foreach ($items as $item) {
                DB::table('inventory')
                    ->where('product_id', $item->product_id)
                    ->where('warehouse_id', $delivery->warehouse_id)
                    ->where('lot_id', $item->lot_id)
                    ->update([
                        'quantity_on_hand' => DB::raw("quantity_on_hand - {$item->quantity_delivered}"),
                        'quantity_reserved' => DB::raw("quantity_reserved - {$item->quantity_delivered}"),
                        'last_updated' => now(),
                    ]);

                // Create stock movement
                $movementCode = CodeGeneratorHelper::generateMovementCode();
                
                DB::table('stock_movements')->insert([
                    'movement_code' => $movementCode,
                    'movement_type' => 'Sales Delivery',
                    'product_id' => $item->product_id,
                    'from_warehouse_id' => $delivery->warehouse_id,
                    'to_warehouse_id' => null,
                    'from_location_id' => null,
                    'to_location_id' => null,
                    'lot_id' => $item->lot_id,
                    'quantity' => $item->quantity_delivered,
                    'uom_id' => $item->uom_id,
                    'movement_date' => now()->format('Y-m-d'),
                    'performed_by' => $delivery->delivered_by ?? Auth::user()->employee_id,
                    'reference_type' => 'Delivery',
                    'reference_id' => $delivery->delivery_id,
                    'notes' => "Delivery to customer: {$delivery->delivery_code}",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Update delivery
            DB::table('deliveries')
                ->where('delivery_id', $delivery->delivery_id)
                ->update([
                    'status' => 'Completed',
                    'arrival_time' => now()->format('H:i:s'),
                    'delivered_by' => $delivery->delivered_by ?? Auth::user()->employee_id,
                    'updated_at' => now(),
                ]);

            // Update vehicle status
            if ($delivery->vehicle_id) {
                DB::table('vehicles')
                    ->where('vehicle_id', $delivery->vehicle_id)
                    ->update(['status' => 'Available']);
            }

            // Update driver availability
            if ($delivery->driver_id) {
                DB::table('drivers')
                    ->where('driver_id', $delivery->driver_id)
                    ->update(['is_available' => 1]);
            }

            // Log activity
            $this->logActivity(
                'Completed',
                "Delivery {$delivery->delivery_code} completed",
                'Logistics - Delivery Management'
            );

            DB::commit();

            return back()->with('success', 'Delivery completed successfully. Inventory has been updated.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to complete delivery: ' . $e->getMessage());
        }
    }

    /**
     * Confirm delivery (with signature/proof)
     */
    public function confirm(Request $request, $deliveryCode)
    {
        $delivery = DB::table('deliveries')
            ->where('delivery_code', $deliveryCode)
            ->first();

        if (!$delivery) {
            abort(404, 'Delivery not found');
        }

        if ($delivery->status !== 'Completed') {
            return back()->with('error', 'Only completed deliveries can be confirmed.');
        }

        $validated = $request->validate([
            'recipient_name' => ['required', 'string', 'max:150'],
            'recipient_position' => ['nullable', 'string', 'max:100'],
            'delivery_notes' => ['nullable', 'string'],
        ]);

        DB::beginTransaction();
        try {
            // Insert delivery confirmation
            DB::table('delivery_confirmations')->insert([
                'delivery_id' => $delivery->delivery_id,
                'delivery_date' => now()->format('Y-m-d'),
                'delivery_time' => now()->format('H:i:s'),
                'recipient_name' => $validated['recipient_name'],
                'recipient_position' => $validated['recipient_position'] ?? null,
                'signature_path' => null, // Will be implemented with file upload
                'delivery_notes' => $validated['delivery_notes'] ?? null,
                'proof_of_delivery_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Log activity
            $this->logActivity(
                'Confirmed',
                "Delivery {$delivery->delivery_code} confirmed by {$validated['recipient_name']}",
                'Logistics - Delivery Management'
            );

            DB::commit();

            return back()->with('success', 'Delivery confirmed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to confirm delivery: ' . $e->getMessage());
        }
    }

    /**
     * Export deliveries to CSV
     */
    public function export(Request $request)
    {
        $this->logExport('Logistics - Delivery Management', 'Exported deliveries to CSV');

        $query = DB::table('deliveries as d')
            ->join('sales_orders as so', 'd.so_id', '=', 'so.so_id')
            ->join('customers as c', 'd.customer_id', '=', 'c.customer_id')
            ->join('warehouses as w', 'd.warehouse_id', '=', 'w.warehouse_id')
            ->leftJoin('drivers as dr', 'd.driver_id', '=', 'dr.driver_id')
            ->leftJoin('employees as e', 'dr.employee_id', '=', 'e.employee_id')
            ->select(
                'd.delivery_code',
                'd.delivery_date',
                'so.so_code',
                'c.customer_name',
                'w.warehouse_name',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as driver_name"),
                'd.status',
                'd.departure_time',
                'd.arrival_time',
                'd.created_at'
            );

        // Apply filters
        if ($request->filled('status')) {
            $query->where('d.status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('d.delivery_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('d.delivery_date', '<=', $request->date_to);
        }

        $deliveries = $query->orderByDesc('d.delivery_date')
            ->limit(5000)
            ->get();

        $filename = 'deliveries_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($deliveries) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, [
                'Delivery Code',
                'Delivery Date',
                'SO Code',
                'Customer',
                'Warehouse',
                'Driver',
                'Status',
                'Departure Time',
                'Arrival Time',
                'Created At'
            ]);

            // Data
            foreach ($deliveries as $delivery) {
                fputcsv($file, [
                    $delivery->delivery_code,
                    $delivery->delivery_date,
                    $delivery->so_code,
                    $delivery->customer_name,
                    $delivery->warehouse_name,
                    $delivery->driver_name ?? '-',
                    $delivery->status,
                    $delivery->departure_time ?? '-',
                    $delivery->arrival_time ?? '-',
                    $delivery->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Print delivery note
     */
    public function print($deliveryCode)
    {
        $delivery = DB::table('deliveries as d')
            ->join('sales_orders as so', 'd.so_id', '=', 'so.so_id')
            ->join('customers as c', 'd.customer_id', '=', 'c.customer_id')
            ->join('warehouses as w', 'd.warehouse_id', '=', 'w.warehouse_id')
            ->leftJoin('drivers as dr', 'd.driver_id', '=', 'dr.driver_id')
            ->leftJoin('employees as e', 'dr.employee_id', '=', 'e.employee_id')
            ->where('d.delivery_code', $deliveryCode)
            ->select(
                'd.*',
                'so.so_code',
                'c.customer_name',
                'c.shipping_address',
                'w.warehouse_name',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as driver_name")
            )
            ->first();

        if (!$delivery) {
            abort(404, 'Delivery not found');
        }

        // Get items
        $items = DB::table('delivery_items as di')
            ->join('products as p', 'di.product_id', '=', 'p.product_id')
            ->join('units_of_measure as uom', 'di.uom_id', '=', 'uom.uom_id')
            ->where('di.delivery_id', $delivery->delivery_id)
            ->select('di.*', 'p.product_code', 'p.product_name', 'uom.uom_name')
            ->get();

        // Get company profile
        $company = DB::table('company_profile')->first();

        // Log PRINT
        $this->logPrint('Logistics - Delivery Management', "Printed delivery note: {$delivery->delivery_code}");

        return view('logistics.deliveries.print', compact('delivery', 'items', 'company'));
    }
}
