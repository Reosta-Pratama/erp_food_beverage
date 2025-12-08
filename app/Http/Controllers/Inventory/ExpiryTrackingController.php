<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\LogsActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpiryTrackingController extends Controller
{
    //
    use LogsActivity;

    /**
     * Display expiry tracking with filtering
     */
    public function index(Request $request)
    {
        $query = DB::table('expiry_tracking as et')
            ->join('products as p', 'et.product_id', '=', 'p.product_id')
            ->join('lots as l', 'et.lot_id', '=', 'l.lot_id')
            ->join('warehouses as w', 'et.warehouse_id', '=', 'w.warehouse_id')
            ->leftJoin('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->select(
                'et.expiry_id',
                'et.expiry_date',
                'et.alert_date',
                'et.quantity',
                'et.status',
                'et.created_at',
                'p.product_id',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'l.lot_id',
                'l.lot_code',
                'l.manufacture_date',
                'w.warehouse_id',
                'w.warehouse_code',
                'w.warehouse_name',
                'uom.uom_name',
                'uom.uom_code',
                DB::raw('DATEDIFF(et.expiry_date, CURDATE()) as days_until_expiry')
            );

        // Filter by status
        if ($request->filled('status')) {
            $query->where('et.status', $request->status);
        }

        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('et.product_id', $request->product_id);
        }

        // Filter by warehouse
        if ($request->filled('warehouse_id')) {
            $query->where('et.warehouse_id', $request->warehouse_id);
        }

        // Filter by expiry range
        if ($request->filled('expiry_from')) {
            $query->whereDate('et.expiry_date', '>=', $request->expiry_from);
        }
        if ($request->filled('expiry_to')) {
            $query->whereDate('et.expiry_date', '<=', $request->expiry_to);
        }

        // Filter by days until expiry
        if ($request->filled('days_range')) {
            switch ($request->days_range) {
                case 'expired':
                    $query->whereRaw('et.expiry_date < CURDATE()');
                    break;
                case '7':
                    $query->whereRaw('DATEDIFF(et.expiry_date, CURDATE()) BETWEEN 0 AND 7');
                    break;
                case '14':
                    $query->whereRaw('DATEDIFF(et.expiry_date, CURDATE()) BETWEEN 0 AND 14');
                    break;
                case '30':
                    $query->whereRaw('DATEDIFF(et.expiry_date, CURDATE()) BETWEEN 0 AND 30');
                    break;
                case '60':
                    $query->whereRaw('DATEDIFF(et.expiry_date, CURDATE()) BETWEEN 0 AND 60');
                    break;
                case '90':
                    $query->whereRaw('DATEDIFF(et.expiry_date, CURDATE()) BETWEEN 0 AND 90');
                    break;
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('p.product_name', 'like', "%{$search}%")
                  ->orWhere('p.product_code', 'like', "%{$search}%")
                  ->orWhere('l.lot_code', 'like', "%{$search}%")
                  ->orWhere('w.warehouse_name', 'like', "%{$search}%");
            });
        }

        $expiryItems = $query->orderBy('et.expiry_date')
            ->orderBy('et.created_at')
            ->paginate(20);

        // Get statistics
        $stats = [
            'total' => DB::table('expiry_tracking')->count(),
            'expired' => DB::table('expiry_tracking')
                ->whereRaw('expiry_date < CURDATE()')
                ->where('status', '!=', 'Disposed')
                ->count(),
            'near_expiry_7' => DB::table('expiry_tracking')
                ->whereRaw('DATEDIFF(expiry_date, CURDATE()) BETWEEN 0 AND 7')
                ->where('status', '!=', 'Disposed')
                ->count(),
            'near_expiry_30' => DB::table('expiry_tracking')
                ->whereRaw('DATEDIFF(expiry_date, CURDATE()) BETWEEN 0 AND 30')
                ->where('status', '!=', 'Disposed')
                ->count(),
            'disposed' => DB::table('expiry_tracking')
                ->where('status', 'Disposed')
                ->count(),
        ];

        // Get filter options
        $statuses = DB::table('expiry_tracking')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $products = DB::table('products')
            ->where('is_active', 1)
            ->orderBy('product_name')
            ->get(['product_id', 'product_code', 'product_name']);

        $warehouses = DB::table('warehouses')
            ->where('is_active', 1)
            ->orderBy('warehouse_name')
            ->get(['warehouse_id', 'warehouse_code', 'warehouse_name']);

        return view('inventory.expiry-tracking.index', compact(
            'expiryItems',
            'stats',
            'statuses',
            'products',
            'warehouses'
        ));
    }

    /**
     * Show create form
     */
    public function create()
    {
        // Get products
        $products = DB::table('products')
            ->where('is_active', 1)
            ->orderBy('product_name')
            ->get(['product_id', 'product_code', 'product_name']);

        // Get warehouses
        $warehouses = DB::table('warehouses')
            ->where('is_active', 1)
            ->orderBy('warehouse_name')
            ->get(['warehouse_id', 'warehouse_code', 'warehouse_name']);

        // Get lots with expiry date
        $lots = DB::table('lots as l')
            ->join('products as p', 'l.product_id', '=', 'p.product_id')
            ->whereNotNull('l.expiry_date')
            ->where('l.status', '!=', 'Expired')
            ->orderByDesc('l.created_at')
            ->limit(100)
            ->get([
                'l.lot_id',
                'l.lot_code',
                'l.product_id',
                'l.manufacture_date',
                'l.expiry_date',
                'l.quantity',
                'p.product_name'
            ]);

        return view('inventory.expiry-tracking.create', compact(
            'products',
            'warehouses',
            'lots'
        ));
    }

    /**
     * Store new expiry tracking
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,product_id'],
            'lot_id' => ['required', 'exists:lots,lot_id'],
            'warehouse_id' => ['required', 'exists:warehouses,warehouse_id'],
            'expiry_date' => ['required', 'date', 'after:today'],
            'quantity' => ['required', 'numeric', 'min:0.0001'],
            'alert_days' => ['nullable', 'integer', 'min:1', 'max:365'],
            'status' => ['required', 'string', 'in:Active,Near Expiry,Expired,Disposed'],
        ], [
            'product_id.required' => 'Please select a product.',
            'product_id.exists' => 'Selected product is invalid.',
            
            'lot_id.required' => 'Please select a lot.',
            'lot_id.exists' => 'Selected lot is invalid.',
            
            'warehouse_id.required' => 'Please select a warehouse.',
            'warehouse_id.exists' => 'Selected warehouse is invalid.',
            
            'expiry_date.required' => 'Expiry date is required.',
            'expiry_date.date' => 'Invalid expiry date format.',
            'expiry_date.after' => 'Expiry date must be in the future.',
            
            'quantity.required' => 'Quantity is required.',
            'quantity.numeric' => 'Quantity must be a number.',
            'quantity.min' => 'Quantity must be greater than zero.',
            
            'alert_days.integer' => 'Alert days must be a number.',
            'alert_days.min' => 'Alert days must be at least 1 day.',
            'alert_days.max' => 'Alert days cannot exceed 365 days.',
            
            'status.required' => 'Please select a status.',
            'status.in' => 'Invalid status selected.',
        ]);

        // Check if tracking already exists
        $exists = DB::table('expiry_tracking')
            ->where('product_id', $validated['product_id'])
            ->where('lot_id', $validated['lot_id'])
            ->where('warehouse_id', $validated['warehouse_id'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with('error', 'Expiry tracking for this product-lot-warehouse combination already exists.');
        }

        DB::beginTransaction();
        try {
            // Calculate alert date
            $alertDays = $validated['alert_days'] ?? 30; // Default 30 days before expiry
            $alertDate = Carbon::parse($validated['expiry_date'])->subDays($alertDays);

            // Insert expiry tracking
            $expiryId = DB::table('expiry_tracking')->insertGetId([
                'product_id' => $validated['product_id'],
                'lot_id' => $validated['lot_id'],
                'warehouse_id' => $validated['warehouse_id'],
                'expiry_date' => $validated['expiry_date'],
                'quantity' => $validated['quantity'],
                'status' => $validated['status'],
                'alert_date' => $alertDate,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Log CREATE
            $this->logCreate(
                'Inventory - Expiry Management',
                'expiry_tracking',
                $expiryId,
                [
                    'product_id' => $validated['product_id'],
                    'lot_id' => $validated['lot_id'],
                    'warehouse_id' => $validated['warehouse_id'],
                    'expiry_date' => $validated['expiry_date'],
                    'alert_date' => $alertDate,
                    'quantity' => $validated['quantity'],
                    'status' => $validated['status'],
                ]
            );

            DB::commit();

            return redirect()
                ->route('inventory.expiry-tracking.index')
                ->with('success', 'Expiry tracking created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create expiry tracking: ' . $e->getMessage());
        }
    }

    /**
     * Show expiry tracking details
     */
    public function show($expiryId)
    {
        $expiry = DB::table('expiry_tracking as et')
            ->join('products as p', 'et.product_id', '=', 'p.product_id')
            ->join('lots as l', 'et.lot_id', '=', 'l.lot_id')
            ->join('warehouses as w', 'et.warehouse_id', '=', 'w.warehouse_id')
            ->leftJoin('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->leftJoin('product_categories as pc', 'p.category_id', '=', 'pc.category_id')
            ->where('et.expiry_id', $expiryId)
            ->select(
                'et.*',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'p.description as product_description',
                'pc.category_name',
                'l.lot_code',
                'l.manufacture_date',
                'l.supplier_id',
                'w.warehouse_code',
                'w.warehouse_name',
                'w.warehouse_type',
                'uom.uom_name',
                'uom.uom_code',
                DB::raw('DATEDIFF(et.expiry_date, CURDATE()) as days_until_expiry'),
                DB::raw('DATEDIFF(CURDATE(), et.alert_date) as days_since_alert')
            )
            ->first();

        if (!$expiry) {
            abort(404, 'Expiry tracking not found');
        }

        // Log VIEW
        $this->logView(
            'Inventory - Expiry Management',
            "Viewed expiry tracking ID: {$expiryId}"
        );

        // Get related stock movements
        $movements = DB::table('stock_movements as sm')
            ->join('employees as e', 'sm.performed_by', '=', 'e.employee_id')
            ->where('sm.lot_id', $expiry->lot_id)
            ->where('sm.product_id', $expiry->product_id)
            ->select(
                'sm.movement_code',
                'sm.movement_type',
                'sm.movement_date',
                'sm.quantity',
                'sm.notes',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as performed_by_name")
            )
            ->orderByDesc('sm.movement_date')
            ->limit(10)
            ->get();

        return view('inventory.expiry-tracking.show', compact('expiry', 'movements'));
    }

    /**
     * Show edit form
     */
    public function edit($expiryId)
    {
        $expiry = DB::table('expiry_tracking')
            ->where('expiry_id', $expiryId)
            ->first();

        if (!$expiry) {
            abort(404, 'Expiry tracking not found');
        }

        // Get products
        $products = DB::table('products')
            ->where('is_active', 1)
            ->orderBy('product_name')
            ->get(['product_id', 'product_code', 'product_name']);

        // Get warehouses
        $warehouses = DB::table('warehouses')
            ->where('is_active', 1)
            ->orderBy('warehouse_name')
            ->get(['warehouse_id', 'warehouse_code', 'warehouse_name']);

        // Get lots
        $lots = DB::table('lots as l')
            ->join('products as p', 'l.product_id', '=', 'p.product_id')
            ->whereNotNull('l.expiry_date')
            ->orderByDesc('l.created_at')
            ->limit(100)
            ->get([
                'l.lot_id',
                'l.lot_code',
                'l.product_id',
                'l.manufacture_date',
                'l.expiry_date',
                'l.quantity',
                'p.product_name'
            ]);

        return view('inventory.expiry-tracking.edit', compact(
            'expiry',
            'products',
            'warehouses',
            'lots'
        ));
    }

    /**
     * Update expiry tracking
     */
    public function update(Request $request, $expiryId)
    {
        $expiry = DB::table('expiry_tracking')
            ->where('expiry_id', $expiryId)
            ->first();

        if (!$expiry) {
            abort(404, 'Expiry tracking not found');
        }

        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,product_id'],
            'lot_id' => ['required', 'exists:lots,lot_id'],
            'warehouse_id' => ['required', 'exists:warehouses,warehouse_id'],
            'expiry_date' => ['required', 'date'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'alert_days' => ['nullable', 'integer', 'min:1', 'max:365'],
            'status' => ['required', 'string', 'in:Active,Near Expiry,Expired,Disposed'],
        ], [
            'product_id.required' => 'Please select a product.',
            'product_id.exists' => 'Selected product is invalid.',
            
            'lot_id.required' => 'Please select a lot.',
            'lot_id.exists' => 'Selected lot is invalid.',
            
            'warehouse_id.required' => 'Please select a warehouse.',
            'warehouse_id.exists' => 'Selected warehouse is invalid.',
            
            'expiry_date.required' => 'Expiry date is required.',
            'expiry_date.date' => 'Invalid expiry date format.',
            
            'quantity.required' => 'Quantity is required.',
            'quantity.numeric' => 'Quantity must be a number.',
            'quantity.min' => 'Quantity cannot be negative.',
            
            'alert_days.integer' => 'Alert days must be a number.',
            'alert_days.min' => 'Alert days must be at least 1 day.',
            'alert_days.max' => 'Alert days cannot exceed 365 days.',
            
            'status.required' => 'Please select a status.',
            'status.in' => 'Invalid status selected.',
        ]);

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'product_id' => $expiry->product_id,
                'lot_id' => $expiry->lot_id,
                'warehouse_id' => $expiry->warehouse_id,
                'expiry_date' => $expiry->expiry_date,
                'alert_date' => $expiry->alert_date,
                'quantity' => $expiry->quantity,
                'status' => $expiry->status,
            ];

            // Calculate new alert date
            $alertDays = $validated['alert_days'] ?? 30;
            $alertDate = Carbon::parse($validated['expiry_date'])->subDays($alertDays);

            // Update expiry tracking
            DB::table('expiry_tracking')
                ->where('expiry_id', $expiryId)
                ->update([
                    'product_id' => $validated['product_id'],
                    'lot_id' => $validated['lot_id'],
                    'warehouse_id' => $validated['warehouse_id'],
                    'expiry_date' => $validated['expiry_date'],
                    'quantity' => $validated['quantity'],
                    'status' => $validated['status'],
                    'alert_date' => $alertDate,
                    'updated_at' => now(),
                ]);

            // Log UPDATE
            $this->logUpdate(
                'Inventory - Expiry Management',
                'expiry_tracking',
                $expiryId,
                $oldData,
                [
                    'product_id' => $validated['product_id'],
                    'lot_id' => $validated['lot_id'],
                    'warehouse_id' => $validated['warehouse_id'],
                    'expiry_date' => $validated['expiry_date'],
                    'alert_date' => $alertDate,
                    'quantity' => $validated['quantity'],
                    'status' => $validated['status'],
                ]
            );

            DB::commit();

            return redirect()
                ->route('inventory.expiry-tracking.index')
                ->with('success', 'Expiry tracking updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update expiry tracking: ' . $e->getMessage());
        }
    }

    /**
     * Delete expiry tracking
     */
    public function destroy($expiryId)
    {
        $expiry = DB::table('expiry_tracking')
            ->where('expiry_id', $expiryId)
            ->first();

        if (!$expiry) {
            abort(404, 'Expiry tracking not found');
        }

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'product_id' => $expiry->product_id,
                'lot_id' => $expiry->lot_id,
                'warehouse_id' => $expiry->warehouse_id,
                'expiry_date' => $expiry->expiry_date,
                'quantity' => $expiry->quantity,
                'status' => $expiry->status,
            ];

            // Delete expiry tracking
            DB::table('expiry_tracking')
                ->where('expiry_id', $expiryId)
                ->delete();

            // Log DELETE
            $this->logDelete(
                'Inventory - Expiry Management',
                'expiry_tracking',
                $expiryId,
                $oldData
            );

            DB::commit();

            return redirect()
                ->route('inventory.expiry-tracking.index')
                ->with('success', 'Expiry tracking deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Failed to delete expiry tracking: ' . $e->getMessage());
        }
    }

    /**
     * Show alerts dashboard
     */
    public function alerts()
    {
        // Get critical alerts (expired items)
        $expired = DB::table('expiry_tracking as et')
            ->join('products as p', 'et.product_id', '=', 'p.product_id')
            ->join('lots as l', 'et.lot_id', '=', 'l.lot_id')
            ->join('warehouses as w', 'et.warehouse_id', '=', 'w.warehouse_id')
            ->whereRaw('et.expiry_date < CURDATE()')
            ->where('et.status', '!=', 'Disposed')
            ->select(
                'et.*',
                'p.product_code',
                'p.product_name',
                'l.lot_code',
                'w.warehouse_name',
                DB::raw('ABS(DATEDIFF(et.expiry_date, CURDATE())) as days_expired')
            )
            ->orderByDesc('days_expired')
            ->get();

        // Get near expiry alerts (7 days)
        $nearExpiry7 = DB::table('expiry_tracking as et')
            ->join('products as p', 'et.product_id', '=', 'p.product_id')
            ->join('lots as l', 'et.lot_id', '=', 'l.lot_id')
            ->join('warehouses as w', 'et.warehouse_id', '=', 'w.warehouse_id')
            ->whereRaw('DATEDIFF(et.expiry_date, CURDATE()) BETWEEN 0 AND 7')
            ->where('et.status', '!=', 'Disposed')
            ->select(
                'et.*',
                'p.product_code',
                'p.product_name',
                'l.lot_code',
                'w.warehouse_name',
                DB::raw('DATEDIFF(et.expiry_date, CURDATE()) as days_until_expiry')
            )
            ->orderBy('days_until_expiry')
            ->get();

        // Get near expiry alerts (30 days)
        $nearExpiry30 = DB::table('expiry_tracking as et')
            ->join('products as p', 'et.product_id', '=', 'p.product_id')
            ->join('lots as l', 'et.lot_id', '=', 'l.lot_id')
            ->join('warehouses as w', 'et.warehouse_id', '=', 'w.warehouse_id')
            ->whereRaw('DATEDIFF(et.expiry_date, CURDATE()) BETWEEN 8 AND 30')
            ->where('et.status', '!=', 'Disposed')
            ->select(
                'et.*',
                'p.product_code',
                'p.product_name',
                'l.lot_code',
                'w.warehouse_name',
                DB::raw('DATEDIFF(et.expiry_date, CURDATE()) as days_until_expiry')
            )
            ->orderBy('days_until_expiry')
            ->get();

        return view('inventory.expiry-tracking.alerts', compact(
            'expired',
            'nearExpiry7',
            'nearExpiry30'
        ));
    }

    /**
     * Show critical alerts only
     */
    public function criticalAlerts()
    {
        $criticalAlerts = DB::table('expiry_tracking as et')
            ->join('products as p', 'et.product_id', '=', 'p.product_id')
            ->join('lots as l', 'et.lot_id', '=', 'l.lot_id')
            ->join('warehouses as w', 'et.warehouse_id', '=', 'w.warehouse_id')
            ->leftJoin('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->where(function($query) {
                $query->whereRaw('et.expiry_date < CURDATE()')
                      ->orWhereRaw('DATEDIFF(et.expiry_date, CURDATE()) <= 7');
            })
            ->where('et.status', '!=', 'Disposed')
            ->select(
                'et.*',
                'p.product_code',
                'p.product_name',
                'l.lot_code',
                'w.warehouse_code',
                'w.warehouse_name',
                'uom.uom_name',
                DB::raw('DATEDIFF(et.expiry_date, CURDATE()) as days_until_expiry'),
                DB::raw('CASE 
                    WHEN et.expiry_date < CURDATE() THEN "Expired"
                    WHEN DATEDIFF(et.expiry_date, CURDATE()) <= 3 THEN "Critical"
                    ELSE "Warning"
                END as alert_level')
            )
            ->orderBy('days_until_expiry')
            ->paginate(50);

        return view('inventory.expiry-tracking.critical-alerts', compact('criticalAlerts'));
    }

    /**
     * Show near expiry items
     */
    public function nearExpiry(Request $request)
    {
        $days = $request->input('days', 30); // Default 30 days

        $nearExpiryItems = DB::table('expiry_tracking as et')
            ->join('products as p', 'et.product_id', '=', 'p.product_id')
            ->join('lots as l', 'et.lot_id', '=', 'l.lot_id')
            ->join('warehouses as w', 'et.warehouse_id', '=', 'w.warehouse_id')
            ->leftJoin('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->whereRaw("DATEDIFF(et.expiry_date, CURDATE()) BETWEEN 0 AND {$days}")
            ->where('et.status', '!=', 'Disposed')
            ->select(
                'et.*',
                'p.product_code',
                'p.product_name',
                'l.lot_code',
                'w.warehouse_name',
                'uom.uom_name',
                DB::raw('DATEDIFF(et.expiry_date, CURDATE()) as days_until_expiry')
            )
            ->orderBy('days_until_expiry')
            ->paginate(50);
            
            return view('inventory.expiry-tracking.near-expiry', compact('nearExpiryItems', 'days'));
    }

    /**
     * Show expired items
     */
    public function expired()
    {
        $expiredItems = DB::table('expiry_tracking as et')
            ->join('products as p', 'et.product_id', '=', 'p.product_id')
            ->join('lots as l', 'et.lot_id', '=', 'l.lot_id')
            ->join('warehouses as w', 'et.warehouse_id', '=', 'w.warehouse_id')
            ->leftJoin('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->whereRaw('et.expiry_date < CURDATE()')
            ->where('et.status', '!=', 'Disposed')
            ->select(
                'et.*',
                'p.product_code',
                'p.product_name',
                'l.lot_code',
                'w.warehouse_name',
                'uom.uom_name',
                DB::raw('ABS(DATEDIFF(et.expiry_date, CURDATE())) as days_expired')
            )
            ->orderByDesc('days_expired')
            ->paginate(50);

        return view('inventory.expiry-tracking.expired', compact('expiredItems'));
    }

    /**
     * Bulk dispose expired items
     */
    public function bulkDispose(Request $request)
    {
        $validated = $request->validate([
            'expiry_ids' => ['required', 'array', 'min:1'],
            'expiry_ids.*' => ['exists:expiry_tracking,expiry_id'],
            'disposal_notes' => ['nullable', 'string'],
        ], [
            'expiry_ids.required' => 'Please select at least one item to dispose.',
            'expiry_ids.array' => 'Invalid selection format.',
            'expiry_ids.min' => 'Please select at least one item.',
            'expiry_ids.*.exists' => 'One or more selected items are invalid.',
        ]);

        DB::beginTransaction();
        try {
            $disposedCount = 0;

            foreach ($validated['expiry_ids'] as $expiryId) {
                DB::table('expiry_tracking')
                    ->where('expiry_id', $expiryId)
                    ->update([
                        'status' => 'Disposed',
                        'updated_at' => now(),
                    ]);

                $disposedCount++;
            }

            // Log bulk disposal
            $this->logActivity(
                'Bulk Disposal',
                "Disposed {$disposedCount} expired items. Notes: " . ($validated['disposal_notes'] ?? 'N/A'),
                'Inventory - Expiry Management'
            );

            DB::commit();

            return back()->with('success', "Successfully disposed {$disposedCount} items.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to dispose items: ' . $e->getMessage());
        }
    }

    /**
     * Bulk set alert date
     */
    public function bulkSetAlert(Request $request)
    {
        $validated = $request->validate([
            'expiry_ids' => ['required', 'array', 'min:1'],
            'expiry_ids.*' => ['exists:expiry_tracking,expiry_id'],
            'alert_days' => ['required', 'integer', 'min:1', 'max:365'],
        ], [
            'expiry_ids.required' => 'Please select at least one item.',
            'expiry_ids.array' => 'Invalid selection format.',
            'expiry_ids.min' => 'Please select at least one item.',
            'expiry_ids.*.exists' => 'One or more selected items are invalid.',
            'alert_days.required' => 'Alert days is required.',
            'alert_days.integer' => 'Alert days must be a number.',
            'alert_days.min' => 'Alert days must be at least 1 day.',
            'alert_days.max' => 'Alert days cannot exceed 365 days.',
        ]);

        DB::beginTransaction();
        try {
            $updatedCount = 0;

            foreach ($validated['expiry_ids'] as $expiryId) {
                $expiry = DB::table('expiry_tracking')
                    ->where('expiry_id', $expiryId)
                    ->first();

                if ($expiry) {
                    $alertDate = Carbon::parse($expiry->expiry_date)->subDays($validated['alert_days']);

                    DB::table('expiry_tracking')
                        ->where('expiry_id', $expiryId)
                        ->update([
                            'alert_date' => $alertDate,
                            'updated_at' => now(),
                        ]);

                    $updatedCount++;
                }
            }

            // Log bulk alert update
            $this->logActivity(
                'Bulk Alert Update',
                "Updated alert date for {$updatedCount} items to {$validated['alert_days']} days before expiry.",
                'Inventory - Expiry Management'
            );

            DB::commit();

            return back()->with('success', "Successfully updated alerts for {$updatedCount} items.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update alerts: ' . $e->getMessage());
        }
    }

    /**
     * Export expiry tracking to CSV
     */
    public function export(Request $request)
    {
        $this->logExport('Inventory - Expiry Management', 'Exported expiry tracking to CSV');

        $query = DB::table('expiry_tracking as et')
            ->join('products as p', 'et.product_id', '=', 'p.product_id')
            ->join('lots as l', 'et.lot_id', '=', 'l.lot_id')
            ->join('warehouses as w', 'et.warehouse_id', '=', 'w.warehouse_id')
            ->leftJoin('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->select(
                'et.expiry_id',
                'p.product_code',
                'p.product_name',
                'l.lot_code',
                'l.manufacture_date',
                'w.warehouse_code',
                'w.warehouse_name',
                'et.expiry_date',
                'et.alert_date',
                'et.quantity',
                'uom.uom_code',
                'et.status',
                DB::raw('DATEDIFF(et.expiry_date, CURDATE()) as days_until_expiry'),
                'et.created_at'
            );

        // Apply filters
        if ($request->filled('status')) {
            $query->where('et.status', $request->status);
        }
        if ($request->filled('warehouse_id')) {
            $query->where('et.warehouse_id', $request->warehouse_id);
        }
        if ($request->filled('days_range')) {
            switch ($request->days_range) {
                case 'expired':
                    $query->whereRaw('et.expiry_date < CURDATE()');
                    break;
                case '7':
                    $query->whereRaw('DATEDIFF(et.expiry_date, CURDATE()) BETWEEN 0 AND 7');
                    break;
                case '30':
                    $query->whereRaw('DATEDIFF(et.expiry_date, CURDATE()) BETWEEN 0 AND 30');
                    break;
            }
        }

        $items = $query->orderBy('et.expiry_date')
            ->limit(10000)
            ->get();

        $filename = 'expiry_tracking_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($items) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, [
                'Expiry ID',
                'Product Code',
                'Product Name',
                'Lot Code',
                'Manufacture Date',
                'Warehouse Code',
                'Warehouse Name',
                'Expiry Date',
                'Alert Date',
                'Quantity',
                'UOM',
                'Status',
                'Days Until Expiry',
                'Created At'
            ]);

            // Data
            foreach ($items as $item) {
                fputcsv($file, [
                    $item->expiry_id,
                    $item->product_code,
                    $item->product_name,
                    $item->lot_code,
                    $item->manufacture_date,
                    $item->warehouse_code,
                    $item->warehouse_name,
                    $item->expiry_date,
                    $item->alert_date,
                    $item->quantity,
                    $item->uom_code ?? '-',
                    $item->status,
                    $item->days_until_expiry,
                    $item->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
