<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    //
    use LogsActivity;
    
    /**
     * Display a listing of inventory
     */
    public function index(Request $request)
    {
        $this->logView('Inventory - Stock Tracking', 'Viewed inventory list');
        
        $query = DB::table('inventory as inv')
            ->join('products as p', 'inv.product_id', '=', 'p.product_id')
            ->join('warehouses as w', 'inv.warehouse_id', '=', 'w.warehouse_id')
            ->join('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->leftJoin('warehouse_locations as wl', 'inv.location_id', '=', 'wl.location_id')
            ->leftJoin('lots as l', 'inv.lot_id', '=', 'l.lot_id')
            ->select(
                'inv.inventory_id',
                'inv.quantity_on_hand',
                'inv.quantity_reserved',
                'inv.quantity_available',
                'inv.reorder_point',
                'inv.last_updated',
                'p.product_id',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'w.warehouse_code',
                'w.warehouse_name',
                'wl.location_code',
                'wl.location_name',
                'l.lot_code',
                'l.expiry_date',
                'uom.uom_code',
                DB::raw('CASE 
                    WHEN inv.reorder_point IS NOT NULL AND inv.quantity_available <= inv.reorder_point 
                    THEN "Low Stock"
                    WHEN inv.quantity_available = 0 THEN "Out of Stock"
                    ELSE "In Stock"
                END as stock_status'),
                DB::raw('CASE 
                    WHEN l.expiry_date IS NULL THEN "No Expiry"
                    WHEN DATEDIFF(l.expiry_date, CURDATE()) < 0 THEN "Expired"
                    WHEN DATEDIFF(l.expiry_date, CURDATE()) <= 30 THEN "Expiring Soon"
                    ELSE "Good"
                END as expiry_status')
            );
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('p.product_code', 'like', "%{$search}%")
                  ->orWhere('p.product_name', 'like', "%{$search}%")
                  ->orWhere('w.warehouse_name', 'like', "%{$search}%")
                  ->orWhere('l.lot_code', 'like', "%{$search}%");
            });
        }
        
        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('inv.product_id', $request->product_id);
        }
        
        // Filter by warehouse
        if ($request->filled('warehouse_id')) {
            $query->where('inv.warehouse_id', $request->warehouse_id);
        }
        
        // Filter by product type
        if ($request->filled('product_type')) {
            $query->where('p.product_type', $request->product_type);
        }
        
        // Filter by stock status
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'low_stock':
                    $query->whereNotNull('inv.reorder_point')
                          ->whereRaw('inv.quantity_available <= inv.reorder_point')
                          ->where('inv.quantity_available', '>', 0);
                    break;
                case 'out_of_stock':
                    $query->where('inv.quantity_available', '=', 0);
                    break;
                case 'in_stock':
                    $query->where('inv.quantity_available', '>', 0)
                          ->where(function($q) {
                              $q->whereNull('inv.reorder_point')
                                ->orWhereRaw('inv.quantity_available > inv.reorder_point');
                          });
                    break;
            }
        }
        
        $inventory = $query->orderBy('p.product_name')
            ->orderBy('w.warehouse_name')
            ->paginate(20);
        
        // Get filters data
        $products = DB::table('products')
            ->where('is_active', 1)
            ->select('product_id', 'product_code', 'product_name')
            ->orderBy('product_name')
            ->get();
        
        $warehouses = DB::table('warehouses')
            ->where('is_active', 1)
            ->select('warehouse_id', 'warehouse_code', 'warehouse_name')
            ->orderBy('warehouse_name')
            ->get();
        
        return view('inventory.stock.index', compact('inventory', 'products', 'warehouses'));
    }

    /**
     * Display the specified inventory item
     */
    public function show($inventoryId)
    {
        $inventory = DB::table('inventory as inv')
            ->join('products as p', 'inv.product_id', '=', 'p.product_id')
            ->join('warehouses as w', 'inv.warehouse_id', '=', 'w.warehouse_id')
            ->join('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->leftJoin('warehouse_locations as wl', 'inv.location_id', '=', 'wl.location_id')
            ->leftJoin('lots as l', 'inv.lot_id', '=', 'l.lot_id')
            ->where('inv.inventory_id', $inventoryId)
            ->select(
                'inv.*',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'p.standard_cost',
                'w.warehouse_code',
                'w.warehouse_name',
                'w.warehouse_type',
                'wl.location_code',
                'wl.location_name',
                'l.lot_code',
                'l.manufacture_date',
                'l.expiry_date',
                'uom.uom_code',
                'uom.uom_name',
                DB::raw('(inv.quantity_on_hand * p.standard_cost) as stock_value')
            )
            ->first();
        
        if (!$inventory) {
            abort(404, 'Inventory not found');
        }
        
        // Log VIEW
        $this->logView(
            'Inventory - Stock Tracking',
            "Viewed inventory: {$inventory->product_name} at {$inventory->warehouse_name}"
        );
        
        // Get recent stock movements
        $stockMovements = DB::table('stock_movements as sm')
            ->leftJoin('warehouses as wf', 'sm.from_warehouse_id', '=', 'wf.warehouse_id')
            ->leftJoin('warehouses as wt', 'sm.to_warehouse_id', '=', 'wt.warehouse_id')
            ->leftJoin('employees as e', 'sm.performed_by', '=', 'e.employee_id')
            ->where('sm.product_id', $inventory->product_id)
            ->where(function($q) use ($inventory) {
                $q->where('sm.from_warehouse_id', $inventory->warehouse_id)
                  ->orWhere('sm.to_warehouse_id', $inventory->warehouse_id);
            })
            ->select(
                'sm.movement_code',
                'sm.movement_type',
                'sm.movement_date',
                'sm.quantity',
                'wf.warehouse_name as from_warehouse',
                'wt.warehouse_name as to_warehouse',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as performed_by_name"),
                'sm.notes'
            )
            ->orderByDesc('sm.movement_date')
            ->limit(20)
            ->get();
        
        return view('inventory.stock.show', compact('inventory', 'stockMovements'));
    }

    /**
     * Show the form for editing inventory settings
     */
    public function edit($inventoryId)
    {
        $inventory = DB::table('inventory as inv')
            ->join('products as p', 'inv.product_id', '=', 'p.product_id')
            ->join('warehouses as w', 'inv.warehouse_id', '=', 'w.warehouse_id')
            ->leftJoin('warehouse_locations as wl', 'inv.location_id', '=', 'wl.location_id')
            ->where('inv.inventory_id', $inventoryId)
            ->select(
                'inv.*',
                'p.product_code',
                'p.product_name',
                'w.warehouse_code',
                'w.warehouse_name',
                'wl.location_code',
                'wl.location_name'
            )
            ->first();
        
        if (!$inventory) {
            abort(404, 'Inventory not found');
        }
        
        // Get locations in the same warehouse
        $locations = DB::table('warehouse_locations')
            ->where('warehouse_id', $inventory->warehouse_id)
            ->select('location_id', 'location_code', 'location_name', 'aisle', 'rack', 'bin')
            ->orderBy('aisle')
            ->orderBy('rack')
            ->get();
        
        return view('inventory.stock.edit', compact('inventory', 'locations'));
    }

    /**
     * Update inventory settings (reorder point, location)
     */
    public function update(Request $request, $inventoryId)
    {
        $inventory = DB::table('inventory')->where('inventory_id', $inventoryId)->first();
        
        if (!$inventory) {
            abort(404, 'Inventory not found');
        }
        
        $validated = $request->validate([
            'location_id' => ['nullable', 'exists:warehouse_locations,location_id'],
            'reorder_point' => ['nullable', 'numeric', 'min:0'],
            'reorder_quantity' => ['nullable', 'numeric', 'min:0'],
        ], [
            'location_id.exists' => 'Selected location is invalid.',
            'reorder_point.numeric' => 'Reorder point must be a number.',
            'reorder_point.min' => 'Reorder point must be 0 or greater.',
            'reorder_quantity.numeric' => 'Reorder quantity must be a number.',
            'reorder_quantity.min' => 'Reorder quantity must be 0 or greater.',
        ]);
        
        DB::beginTransaction();
        try {
            // Capture OLD data
            $oldInventory = DB::table('inventory as inv')
                ->leftJoin('warehouse_locations as wl', 'inv.location_id', '=', 'wl.location_id')
                ->where('inv.inventory_id', $inventoryId)
                ->select('inv.*', 'wl.location_code')
                ->first();
            
            // Get new location info
            $newLocation = null;
            if ($validated['location_id']) {
                $newLocation = DB::table('warehouse_locations')
                    ->where('location_id', $validated['location_id'])
                    ->first();
            }
            
            // Update Inventory
            DB::table('inventory')
                ->where('inventory_id', $inventoryId)
                ->update([
                    'location_id' => $validated['location_id'] ?? null,
                    'reorder_point' => $validated['reorder_point'] ?? null,
                    'reorder_quantity' => $validated['reorder_quantity'] ?? null,
                    'last_updated' => now(),
                ]);
            
            // Log UPDATE
            $this->logUpdate(
                'Inventory - Stock Tracking',
                'inventory',
                $inventoryId,
                [
                    'location_code' => $oldInventory->location_code,
                    'reorder_point' => $oldInventory->reorder_point,
                    'reorder_quantity' => $oldInventory->reorder_quantity,
                ],
                [
                    'location_code' => $newLocation->location_code ?? null,
                    'reorder_point' => $validated['reorder_point'] ?? null,
                    'reorder_quantity' => $validated['reorder_quantity'] ?? null,
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('inventory.stock.show', $inventoryId)
                ->with('success', 'Inventory settings updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update inventory: ' . $e->getMessage());
        }
    }
    
    /**
     * Export inventory
     */
    public function export()
    {
        $this->logExport('Inventory - Stock Tracking', 'Exported inventory list');
        
        // Your export logic here
    }
    
    /**
     * Low stock report
     */
    public function lowStockReport()
    {
        $this->logView('Inventory - Stock Tracking', 'Viewed low stock report');
        
        $lowStockItems = DB::table('inventory as inv')
            ->join('products as p', 'inv.product_id', '=', 'p.product_id')
            ->join('warehouses as w', 'inv.warehouse_id', '=', 'w.warehouse_id')
            ->join('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->whereNotNull('inv.reorder_point')
            ->whereRaw('inv.quantity_available <= inv.reorder_point')
            ->select(
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'w.warehouse_name',
                'inv.quantity_available',
                'inv.reorder_point',
                'inv.reorder_quantity',
                'uom.uom_code',
                DB::raw('(inv.reorder_point - inv.quantity_available) as shortage')
            )
            ->orderByDesc('shortage')
            ->get();
        
        return view('inventory.stock.low-stock', compact('lowStockItems'));
    }
    
    /**
     * Stock valuation report
     */
    public function valuationReport()
    {
        $this->logView('Inventory - Stock Tracking', 'Viewed stock valuation report');
        
        $valuation = DB::table('inventory as inv')
            ->join('products as p', 'inv.product_id', '=', 'p.product_id')
            ->join('product_categories as pc', 'p.category_id', '=', 'pc.category_id')
            ->join('warehouses as w', 'inv.warehouse_id', '=', 'w.warehouse_id')
            ->select(
                'pc.category_name',
                'p.product_type',
                'w.warehouse_name',
                DB::raw('COUNT(DISTINCT inv.product_id) as products_count'),
                DB::raw('SUM(inv.quantity_on_hand) as total_quantity'),
                DB::raw('SUM(inv.quantity_on_hand * p.standard_cost) as total_value')
            )
            ->groupBy('pc.category_name', 'p.product_type', 'w.warehouse_name')
            ->orderBy('total_value', 'desc')
            ->get();
        
        $grandTotal = $valuation->sum('total_value');
        
        return view('inventory.stock.valuation', compact('valuation', 'grandTotal'));
    }
}
