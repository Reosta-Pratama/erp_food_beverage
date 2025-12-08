<?php

namespace App\Http\Controllers\Inventory;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    //
    use LogsActivity;
    
    /**
     * Display a listing of warehouses
     */
    public function index(Request $request)
    {
        $query = DB::table('warehouses as w')
            ->leftJoin('employees as e', 'w.manager_id', '=', 'e.employee_id')
            ->select(
                'w.warehouse_id',
                'w.warehouse_code',
                'w.warehouse_name',
                'w.warehouse_type',
                'w.address',
                'w.city',
                'w.capacity',
                'w.is_active',
                'w.created_at',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as manager_name"),
                'e.employee_code',
                DB::raw('(SELECT COUNT(*) FROM warehouse_locations WHERE warehouse_id = w.warehouse_id) as locations_count'),
                DB::raw('(SELECT COUNT(DISTINCT product_id) FROM inventory WHERE warehouse_id = w.warehouse_id) as products_count'),
                DB::raw('(SELECT SUM(quantity_on_hand) FROM inventory WHERE warehouse_id = w.warehouse_id) as total_stock')
            );
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('w.warehouse_code', 'like', "%{$search}%")
                  ->orWhere('w.warehouse_name', 'like', "%{$search}%")
                  ->orWhere('w.city', 'like', "%{$search}%");
            });
        }
        
        // Filter by type
        if ($request->filled('type')) {
            $query->where('w.warehouse_type', $request->type);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('w.is_active', $request->status === 'active' ? 1 : 0);
        }
        
        $warehouses = $query->orderByDesc('w.is_active')
            ->orderBy('w.warehouse_name')
            ->paginate(20);
        
        return view('inventory.warehouses.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new warehouse
     */
    public function create()
    {
        // Get employees for manager assignment
        $employees = DB::table('employees')
            ->where('employment_status', 'Active')
            ->select(
                'employee_id',
                'employee_code',
                DB::raw("CONCAT(first_name, ' ', last_name) as full_name")
            )
            ->orderBy('first_name')
            ->get();
        
        return view('inventory.warehouses.create', compact('employees'));
    }

    /**
     * Store a newly created warehouse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'warehouse_name' => ['required', 'string', 'max:200'],
            'warehouse_type' => ['required', 'in:Main Warehouse,Finished Goods,Raw Materials,Cold Storage,Quarantine'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'manager_id' => ['nullable', 'exists:employees,employee_id'],
            'capacity' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
        ], [
            'warehouse_name.required' => 'Warehouse name is required.',
            'warehouse_name.max' => 'Warehouse name cannot exceed 200 characters.',
            
            'warehouse_type.required' => 'Please select warehouse type.',
            'warehouse_type.in' => 'Invalid warehouse type selected.',
            
            'city.max' => 'City name cannot exceed 100 characters.',
            
            'manager_id.exists' => 'Selected manager is invalid.',
            
            'capacity.numeric' => 'Capacity must be a number.',
            'capacity.min' => 'Capacity must be 0 or greater.',
        ]);
        
        DB::beginTransaction();
        try {
            // Generate unique warehouse code
            $warehouseCode = CodeGeneratorHelper::generateWarehouseCode();
            
            // Get manager info if provided
            $manager = null;
            if ($validated['manager_id']) {
                $manager = DB::table('employees')
                    ->where('employee_id', $validated['manager_id'])
                    ->first();
            }
            
            // Insert Warehouse
            $warehouseId = DB::table('warehouses')->insertGetId([
                'warehouse_code' => $warehouseCode,
                'warehouse_name' => $validated['warehouse_name'],
                'warehouse_type' => $validated['warehouse_type'],
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
                'manager_id' => $validated['manager_id'] ?? null,
                'capacity' => $validated['capacity'] ?? null,
                'is_active' => $request->has('is_active') ? 1 : 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Log CREATE
            $this->logCreate(
                'Inventory - Warehouses',
                'warehouses',
                $warehouseId,
                [
                    'warehouse_code' => $warehouseCode,
                    'warehouse_name' => $validated['warehouse_name'],
                    'warehouse_type' => $validated['warehouse_type'],
                    'city' => $validated['city'] ?? null,
                    'capacity' => $validated['capacity'] ?? null,
                    'manager_name' => $manager ? $manager->first_name . ' ' . $manager->last_name : null,
                    'is_active' => $request->has('is_active') ? 1 : 0,
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('inventory.warehouses.show', $warehouseCode)
                ->with('success', 'Warehouse created successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create warehouse: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified warehouse
     */
    public function show($warehouseCode)
    {
        $warehouse = DB::table('warehouses as w')
            ->leftJoin('employees as e', 'w.manager_id', '=', 'e.employee_id')
            ->where('w.warehouse_code', $warehouseCode)
            ->select(
                'w.*',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as manager_name"),
                'e.employee_code',
                'e.email as manager_email',
                'e.phone as manager_phone'
            )
            ->first();
        
        if (!$warehouse) {
            abort(404, 'Warehouse not found');
        }
        
        // Log VIEW
        $this->logView(
            'Inventory - Warehouses',
            "Viewed warehouse: {$warehouse->warehouse_name} (Code: {$warehouse->warehouse_code})"
        );
        
        // Get warehouse locations
        $locations = DB::table('warehouse_locations')
            ->where('warehouse_id', $warehouse->warehouse_id)
            ->select(
                'location_id',
                'location_code',
                'location_name',
                'aisle',
                'rack',
                'bin'
            )
            ->orderBy('aisle')
            ->orderBy('rack')
            ->orderBy('bin')
            ->get();
        
        // Get inventory summary
        $inventorySummary = DB::table('inventory as inv')
            ->join('products as p', 'inv.product_id', '=', 'p.product_id')
            ->join('product_categories as pc', 'p.category_id', '=', 'pc.category_id')
            ->where('inv.warehouse_id', $warehouse->warehouse_id)
            ->select(
                'pc.category_name',
                DB::raw('COUNT(DISTINCT inv.product_id) as products_count'),
                DB::raw('SUM(inv.quantity_on_hand) as total_quantity'),
                DB::raw('SUM(inv.quantity_reserved) as total_reserved'),
                DB::raw('SUM(inv.quantity_available) as total_available')
            )
            ->groupBy('pc.category_name')
            ->get();
        
        // Get recent stock movements
        $recentMovements = DB::table('stock_movements as sm')
            ->join('products as p', 'sm.product_id', '=', 'p.product_id')
            ->leftJoin('employees as e', 'sm.performed_by', '=', 'e.employee_id')
            ->where(function($q) use ($warehouse) {
                $q->where('sm.from_warehouse_id', $warehouse->warehouse_id)
                  ->orWhere('sm.to_warehouse_id', $warehouse->warehouse_id);
            })
            ->select(
                'sm.movement_code',
                'sm.movement_type',
                'sm.movement_date',
                'sm.quantity',
                'p.product_name',
                'p.product_code',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as performed_by_name")
            )
            ->orderByDesc('sm.movement_date')
            ->limit(10)
            ->get();
        
        return view('inventory.warehouses.show', compact('warehouse', 'locations', 'inventorySummary', 'recentMovements'));
    }

    /**
     * Show the form for editing the specified warehouse
     */
    public function edit($warehouseCode)
    {
        $warehouse = DB::table('warehouses')
            ->where('warehouse_code', $warehouseCode)
            ->first();
        
        if (!$warehouse) {
            abort(404, 'Warehouse not found');
        }
        
        $employees = DB::table('employees')
            ->where('employment_status', 'Active')
            ->select(
                'employee_id',
                'employee_code',
                DB::raw("CONCAT(first_name, ' ', last_name) as full_name")
            )
            ->orderBy('first_name')
            ->get();
        
        return view('inventory.warehouses.edit', compact('warehouse', 'employees'));
    }

    /**
     * Update the specified warehouse
     */
    public function update(Request $request, $warehouseCode)
    {
        $warehouse = DB::table('warehouses')->where('warehouse_code', $warehouseCode)->first();
        
        if (!$warehouse) {
            abort(404, 'Warehouse not found');
        }
        
        $validated = $request->validate([
            'warehouse_name' => ['required', 'string', 'max:200'],
            'warehouse_type' => ['required', 'in:Main Warehouse,Finished Goods,Raw Materials,Cold Storage,Quarantine'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'manager_id' => ['nullable', 'exists:employees,employee_id'],
            'capacity' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
        ]);
        
        DB::beginTransaction();
        try {
            // Capture OLD data
            $oldWarehouse = DB::table('warehouses as w')
                ->leftJoin('employees as e', 'w.manager_id', '=', 'e.employee_id')
                ->where('w.warehouse_id', $warehouse->warehouse_id)
                ->select(
                    'w.*',
                    DB::raw("CONCAT(e.first_name, ' ', e.last_name) as manager_name")
                )
                ->first();
            
            // Get new manager info
            $newManager = null;
            if ($validated['manager_id']) {
                $newManager = DB::table('employees')
                    ->where('employee_id', $validated['manager_id'])
                    ->first();
            }
            
            // Update Warehouse
            DB::table('warehouses')
                ->where('warehouse_id', $warehouse->warehouse_id)
                ->update([
                    'warehouse_name' => $validated['warehouse_name'],
                    'warehouse_type' => $validated['warehouse_type'],
                    'address' => $validated['address'] ?? null,
                    'city' => $validated['city'] ?? null,
                    'manager_id' => $validated['manager_id'] ?? null,
                    'capacity' => $validated['capacity'] ?? null,
                    'is_active' => $request->has('is_active') ? 1 : 0,
                    'updated_at' => now(),
                ]);
            
            // Log UPDATE
            $this->logUpdate(
                'Inventory - Warehouses',
                'warehouses',
                $warehouse->warehouse_id,
                [
                    'warehouse_code' => $oldWarehouse->warehouse_code,
                    'warehouse_name' => $oldWarehouse->warehouse_name,
                    'warehouse_type' => $oldWarehouse->warehouse_type,
                    'city' => $oldWarehouse->city,
                    'capacity' => $oldWarehouse->capacity,
                    'manager_name' => $oldWarehouse->manager_name,
                    'is_active' => $oldWarehouse->is_active,
                ],
                [
                    'warehouse_code' => $warehouse->warehouse_code,
                    'warehouse_name' => $validated['warehouse_name'],
                    'warehouse_type' => $validated['warehouse_type'],
                    'city' => $validated['city'] ?? null,
                    'capacity' => $validated['capacity'] ?? null,
                    'manager_name' => $newManager ? $newManager->first_name . ' ' . $newManager->last_name : null,
                    'is_active' => $request->has('is_active') ? 1 : 0,
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('inventory.warehouses.show', $warehouseCode)
                ->with('success', 'Warehouse updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update warehouse: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified warehouse
     */
    public function destroy($warehouseCode)
    {
        $warehouse = DB::table('warehouses')
            ->where('warehouse_code', $warehouseCode)
            ->first();
        
        if (!$warehouse) {
            abort(404, 'Warehouse not found');
        }
        
        // Check if warehouse has inventory
        $inventoryCount = DB::table('inventory')
            ->where('warehouse_id', $warehouse->warehouse_id)
            ->where('quantity_on_hand', '>', 0)
            ->count();
        
        if ($inventoryCount > 0) {
            return back()->with('error', 'Cannot delete warehouse that has inventory stock');
        }
        
        // Check if warehouse has locations
        $locationsCount = DB::table('warehouse_locations')
            ->where('warehouse_id', $warehouse->warehouse_id)
            ->count();
        
        if ($locationsCount > 0) {
            return back()->with('error', 'Cannot delete warehouse that has locations. Delete locations first.');
        }
        
        DB::beginTransaction();
        try {
            // Delete warehouse
            DB::table('warehouses')->where('warehouse_id', $warehouse->warehouse_id)->delete();
            
            // Log DELETE
            $this->logDelete(
                'Inventory - Warehouses',
                'warehouses',
                $warehouse->warehouse_id,
                [
                    'warehouse_code' => $warehouse->warehouse_code,
                    'warehouse_name' => $warehouse->warehouse_name,
                    'warehouse_type' => $warehouse->warehouse_type,
                    'city' => $warehouse->city,
                    'capacity' => $warehouse->capacity,
                    'is_active' => $warehouse->is_active,
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('inventory.warehouses.index')
                ->with('success', 'Warehouse deleted successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete warehouse: ' . $e->getMessage());
        }
    }
    
    /**
     * Toggle warehouse active status
     */
    public function toggleStatus($warehouseCode)
    {
        $warehouse = DB::table('warehouses')
            ->where('warehouse_code', $warehouseCode)
            ->first();
        
        if (!$warehouse) {
            abort(404, 'Warehouse not found');
        }
        
        DB::beginTransaction();
        try {
            $newStatus = !$warehouse->is_active;
            
            DB::table('warehouses')
                ->where('warehouse_id', $warehouse->warehouse_id)
                ->update([
                    'is_active' => $newStatus,
                    'updated_at' => now(),
                ]);
            
            // Log status toggle
            $statusText = $newStatus ? 'activated' : 'deactivated';
            $this->logActivity(
                'Status Toggle',
                "Warehouse {$statusText}: {$warehouse->warehouse_name} ({$warehouse->warehouse_code})",
                'Inventory - Warehouses'
            );
            
            DB::commit();
            
            return back()->with('success', 'Warehouse status updated successfully');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }
    
    /**
     * Export warehouses
     */
    public function export()
    {
        $this->logExport('Inventory - Warehouses', 'Exported warehouses list');
        
        // Your export logic here
    }
}
