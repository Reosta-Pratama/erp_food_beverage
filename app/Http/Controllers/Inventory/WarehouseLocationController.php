<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseLocationController extends Controller
{
    //
    use LogsActivity;
    
    /**
     * Display a listing of warehouse locations
     */
    public function index(Request $request)
    {
        $query = DB::table('warehouse_locations as wl')
            ->join('warehouses as w', 'wl.warehouse_id', '=', 'w.warehouse_id')
            ->select(
                'wl.location_id',
                'wl.location_code',
                'wl.location_name',
                'wl.aisle',
                'wl.rack',
                'wl.bin',
                'wl.created_at',
                'w.warehouse_code',
                'w.warehouse_name',
                'w.warehouse_type',
                DB::raw('(SELECT COUNT(DISTINCT product_id) FROM inventory WHERE location_id = wl.location_id) as products_count'),
                DB::raw('(SELECT SUM(quantity_on_hand) FROM inventory WHERE location_id = wl.location_id) as total_stock')
            );
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('wl.location_code', 'like', "%{$search}%")
                  ->orWhere('wl.location_name', 'like', "%{$search}%")
                  ->orWhere('wl.aisle', 'like', "%{$search}%")
                  ->orWhere('w.warehouse_name', 'like', "%{$search}%");
            });
        }
        
        // Filter by warehouse
        if ($request->filled('warehouse_id')) {
            $query->where('wl.warehouse_id', $request->warehouse_id);
        }
        
        $locations = $query->orderBy('w.warehouse_name')
            ->orderBy('wl.aisle')
            ->orderBy('wl.rack')
            ->orderBy('wl.bin')
            ->paginate(20);
        
        // Get warehouses for filter
        $warehouses = DB::table('warehouses')
            ->where('is_active', 1)
            ->select('warehouse_id', 'warehouse_code', 'warehouse_name')
            ->orderBy('warehouse_name')
            ->get();
        
        return view('inventory.locations.index', compact('locations', 'warehouses'));
    }

    /**
     * Show the form for creating a new location
     */
    public function create()
    {
        $warehouses = DB::table('warehouses')
            ->where('is_active', 1)
            ->select('warehouse_id', 'warehouse_code', 'warehouse_name', 'warehouse_type')
            ->orderBy('warehouse_name')
            ->get();
        
        return view('inventory.locations.create', compact('warehouses'));
    }

    /**
     * Store a newly created location
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'warehouse_id' => ['required', 'exists:warehouses,warehouse_id'],
            'location_name' => ['required', 'string', 'max:150'],
            'aisle' => ['nullable', 'string', 'max:20'],
            'rack' => ['nullable', 'string', 'max:20'],
            'bin' => ['nullable', 'string', 'max:20'],
        ], [
            'warehouse_id.required' => 'Please select a warehouse.',
            'warehouse_id.exists' => 'Selected warehouse is invalid.',
            
            'location_name.required' => 'Location name is required.',
            'location_name.max' => 'Location name cannot exceed 150 characters.',
            
            'aisle.max' => 'Aisle cannot exceed 20 characters.',
            'rack.max' => 'Rack cannot exceed 20 characters.',
            'bin.max' => 'Bin cannot exceed 20 characters.',
        ]);
        
        DB::beginTransaction();
        try {
            // Generate location code based on warehouse + sequence
            $warehouse = DB::table('warehouses')
                ->where('warehouse_id', $validated['warehouse_id'])
                ->first();
            
            $lastLocation = DB::table('warehouse_locations')
                ->where('warehouse_id', $validated['warehouse_id'])
                ->orderByDesc('location_id')
                ->first();
            
            $sequence = $lastLocation ? (intval(substr($lastLocation->location_code, -4)) + 1) : 1;
            $locationCode = $warehouse->warehouse_code . '-' . sprintf('%04d', $sequence);
            
            // Insert Location
            $locationId = DB::table('warehouse_locations')->insertGetId([
                'warehouse_id' => $validated['warehouse_id'],
                'location_code' => $locationCode,
                'location_name' => $validated['location_name'],
                'aisle' => $validated['aisle'] ?? null,
                'rack' => $validated['rack'] ?? null,
                'bin' => $validated['bin'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Log CREATE
            $this->logCreate(
                'Inventory - Warehouse Locations',
                'warehouse_locations',
                $locationId,
                [
                    'location_code' => $locationCode,
                    'location_name' => $validated['location_name'],
                    'warehouse_name' => $warehouse->warehouse_name,
                    'warehouse_code' => $warehouse->warehouse_code,
                    'aisle' => $validated['aisle'] ?? null,
                    'rack' => $validated['rack'] ?? null,
                    'bin' => $validated['bin'] ?? null,
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('inventory.locations.show', $locationCode)
                ->with('success', 'Warehouse location created successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create location: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified location
     */
    public function show($locationCode)
    {
        $location = DB::table('warehouse_locations as wl')
            ->join('warehouses as w', 'wl.warehouse_id', '=', 'w.warehouse_id')
            ->where('wl.location_code', $locationCode)
            ->select(
                'wl.*',
                'w.warehouse_code',
                'w.warehouse_name',
                'w.warehouse_type',
                'w.address',
                'w.city'
            )
            ->first();
        
        if (!$location) {
            abort(404, 'Location not found');
        }
        
        // Log VIEW
        $this->logView(
            'Inventory - Warehouse Locations',
            "Viewed location: {$location->location_name} (Code: {$location->location_code}) at {$location->warehouse_name}"
        );
        
        // Get inventory at this location
        $inventory = DB::table('inventory as inv')
            ->join('products as p', 'inv.product_id', '=', 'p.product_id')
            ->join('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->leftJoin('lots as l', 'inv.lot_id', '=', 'l.lot_id')
            ->where('inv.location_id', $location->location_id)
            ->select(
                'inv.inventory_id',
                'inv.quantity_on_hand',
                'inv.quantity_reserved',
                'inv.quantity_available',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'uom.uom_code',
                'l.lot_code',
                'l.expiry_date',
                DB::raw('CASE 
                    WHEN l.expiry_date IS NULL THEN "No Expiry"
                    WHEN DATEDIFF(l.expiry_date, CURDATE()) < 0 THEN "Expired"
                    WHEN DATEDIFF(l.expiry_date, CURDATE()) <= 30 THEN "Expiring Soon"
                    ELSE "Good"
                END as expiry_status')
            )
            ->orderBy('p.product_name')
            ->get();
        
        // Calculate totals
        $totalQuantity = $inventory->sum('quantity_on_hand');
        $totalReserved = $inventory->sum('quantity_reserved');
        $totalAvailable = $inventory->sum('quantity_available');
        
        return view('inventory.locations.show', compact('location', 'inventory', 'totalQuantity', 'totalReserved', 'totalAvailable'));
    }

    /**
     * Show the form for editing the specified location
     */
    public function edit($locationCode)
    {
        $location = DB::table('warehouse_locations')
            ->where('location_code', $locationCode)
            ->first();
        
        if (!$location) {
            abort(404, 'Location not found');
        }
        
        $warehouses = DB::table('warehouses')
            ->where('is_active', 1)
            ->select('warehouse_id', 'warehouse_code', 'warehouse_name', 'warehouse_type')
            ->orderBy('warehouse_name')
            ->get();
        
        return view('inventory.locations.edit', compact('location', 'warehouses'));
    }

    /**
     * Update the specified location
     */
    public function update(Request $request, $locationCode)
    {
        $location = DB::table('warehouse_locations')->where('location_code', $locationCode)->first();
        
        if (!$location) {
            abort(404, 'Location not found');
        }
        
        $validated = $request->validate([
            'warehouse_id' => ['required', 'exists:warehouses,warehouse_id'],
            'location_name' => ['required', 'string', 'max:150'],
            'aisle' => ['nullable', 'string', 'max:20'],
            'rack' => ['nullable', 'string', 'max:20'],
            'bin' => ['nullable', 'string', 'max:20'],
        ]);
        
        DB::beginTransaction();
        try {
            // Capture OLD data
            $oldLocation = DB::table('warehouse_locations as wl')
                ->join('warehouses as w', 'wl.warehouse_id', '=', 'w.warehouse_id')
                ->where('wl.location_id', $location->location_id)
                ->select('wl.*', 'w.warehouse_name', 'w.warehouse_code')
                ->first();
            
            // Get new warehouse info
            $newWarehouse = DB::table('warehouses')
                ->where('warehouse_id', $validated['warehouse_id'])
                ->first();
            
            // Update Location
            DB::table('warehouse_locations')
                ->where('location_id', $location->location_id)
                ->update([
                    'warehouse_id' => $validated['warehouse_id'],
                    'location_name' => $validated['location_name'],
                    'aisle' => $validated['aisle'] ?? null,
                    'rack' => $validated['rack'] ?? null,
                    'bin' => $validated['bin'] ?? null,
                    'updated_at' => now(),
                ]);
            
            // Log UPDATE
            $this->logUpdate(
                'Inventory - Warehouse Locations',
                'warehouse_locations',
                $location->location_id,
                [
                    'location_code' => $oldLocation->location_code,
                    'location_name' => $oldLocation->location_name,
                    'warehouse_name' => $oldLocation->warehouse_name,
                    'aisle' => $oldLocation->aisle,
                    'rack' => $oldLocation->rack,
                    'bin' => $oldLocation->bin,
                ],
                [
                    'location_code' => $location->location_code,
                    'location_name' => $validated['location_name'],
                    'warehouse_name' => $newWarehouse->warehouse_name,
                    'aisle' => $validated['aisle'] ?? null,
                    'rack' => $validated['rack'] ?? null,
                    'bin' => $validated['bin'] ?? null,
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('inventory.locations.show', $locationCode)
                ->with('success', 'Warehouse location updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update location: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified location
     */
    public function destroy($locationCode)
    {
        $location = DB::table('warehouse_locations as wl')
            ->join('warehouses as w', 'wl.warehouse_id', '=', 'w.warehouse_id')
            ->where('wl.location_code', $locationCode)
            ->select('wl.*', 'w.warehouse_name')
            ->first();
        
        if (!$location) {
            abort(404, 'Location not found');
        }
        
        // Check if location has inventory
        $inventoryCount = DB::table('inventory')
            ->where('location_id', $location->location_id)
            ->where('quantity_on_hand', '>', 0)
            ->count();
        
        if ($inventoryCount > 0) {
            return back()->with('error', 'Cannot delete location that has inventory stock');
        }
        
        DB::beginTransaction();
        try {
            // Delete location
            DB::table('warehouse_locations')->where('location_id', $location->location_id)->delete();
            
            // Log DELETE
            $this->logDelete(
                'Inventory - Warehouse Locations',
                'warehouse_locations',
                $location->location_id,
                [
                    'location_code' => $location->location_code,
                    'location_name' => $location->location_name,
                    'warehouse_name' => $location->warehouse_name,
                    'aisle' => $location->aisle,
                    'rack' => $location->rack,
                    'bin' => $location->bin,
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('inventory.locations.index')
                ->with('success', 'Warehouse location deleted successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete location: ' . $e->getMessage());
        }
    }
    
    /**
     * Export locations
     */
    public function export()
    {
        $this->logExport('Inventory - Warehouse Locations', 'Exported warehouse locations list');
        
        // Your export logic here
    }
}
