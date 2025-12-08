<?php

namespace App\Http\Controllers\Inventory;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    //
    use LogsActivity;
    
    /**
     * Display a listing of stock movements
     */
    public function index(Request $request)
    {
        $query = DB::table('stock_movements as sm')
            ->join('products as p', 'sm.product_id', '=', 'p.product_id')
            ->join('units_of_measure as uom', 'sm.uom_id', '=', 'uom.uom_id')
            ->leftJoin('warehouses as wf', 'sm.from_warehouse_id', '=', 'wf.warehouse_id')
            ->leftJoin('warehouses as wt', 'sm.to_warehouse_id', '=', 'wt.warehouse_id')
            ->leftJoin('warehouse_locations as lf', 'sm.from_location_id', '=', 'lf.location_id')
            ->leftJoin('warehouse_locations as lt', 'sm.to_location_id', '=', 'lt.location_id')
            ->leftJoin('lots as l', 'sm.lot_id', '=', 'l.lot_id')
            ->leftJoin('employees as e', 'sm.performed_by', '=', 'e.employee_id')
            ->select(
                'sm.movement_id',
                'sm.movement_code',
                'sm.movement_type',
                'sm.movement_date',
                'sm.quantity',
                'sm.created_at',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'uom.uom_code',
                'wf.warehouse_name as from_warehouse',
                'wt.warehouse_name as to_warehouse',
                'lf.location_name as from_location',
                'lt.location_name as to_location',
                'l.lot_code',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as performed_by_name")
            );
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('sm.movement_code', 'like', "%{$search}%")
                  ->orWhere('p.product_name', 'like', "%{$search}%")
                  ->orWhere('p.product_code', 'like', "%{$search}%")
                  ->orWhere('l.lot_code', 'like', "%{$search}%");
            });
        }
        
        // Filter by movement type
        if ($request->filled('movement_type')) {
            $query->where('sm.movement_type', $request->movement_type);
        }
        
        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('sm.product_id', $request->product_id);
        }
        
        // Filter by warehouse
        if ($request->filled('warehouse_id')) {
            $query->where(function($q) use ($request) {
                $q->where('sm.from_warehouse_id', $request->warehouse_id)
                  ->orWhere('sm.to_warehouse_id', $request->warehouse_id);
            });
        }
        
        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('sm.movement_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('sm.movement_date', '<=', $request->end_date);
        }
        
        $movements = $query->orderByDesc('sm.movement_date')
            ->orderByDesc('sm.created_at')
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
        
        return view('inventory.movements.index', compact('movements', 'products', 'warehouses'));
    }

    /**
     * Show the form for creating a new stock movement
     */
    public function create()
    {
        // Get products with current stock
        $products = DB::table('products as p')
            ->join('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->where('p.is_active', 1)
            ->select(
                'p.product_id',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'uom.uom_id',
                'uom.uom_code'
            )
            ->orderBy('p.product_name')
            ->get();
        
        $warehouses = DB::table('warehouses')
            ->where('is_active', 1)
            ->select('warehouse_id', 'warehouse_code', 'warehouse_name', 'warehouse_type')
            ->orderBy('warehouse_name')
            ->get();
        
        $employees = DB::table('employees')
            ->where('employment_status', 'Active')
            ->select(
                'employee_id',
                'employee_code',
                DB::raw("CONCAT(first_name, ' ', last_name) as full_name")
            )
            ->orderBy('first_name')
            ->get();
        
        return view('inventory.movements.create', compact('products', 'warehouses', 'employees'));
    }

    /**
     * Store a newly created stock movement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'movement_type' => ['required', 'in:Receipt,Issue,Transfer,Adjustment,Return,Production,Consumption,Scrap'],
            'product_id' => ['required', 'exists:products,product_id'],
            'from_warehouse_id' => ['nullable', 'exists:warehouses,warehouse_id'],
            'to_warehouse_id' => ['nullable', 'exists:warehouses,warehouse_id'],
            'from_location_id' => ['nullable', 'exists:warehouse_locations,location_id'],
            'to_location_id' => ['nullable', 'exists:warehouse_locations,location_id'],
            'lot_id' => ['nullable', 'exists:lots,lot_id'],
            'quantity' => ['required', 'numeric', 'min:0.0001'],
            'uom_id' => ['required', 'exists:units_of_measure,uom_id'],
            'movement_date' => ['required', 'date', 'before_or_equal:today'],
            'reference_type' => ['nullable', 'string', 'max:30'],
            'reference_id' => ['nullable', 'integer'],
            'notes' => ['nullable', 'string'],
        ], [
            'movement_type.required' => 'Please select movement type.',
            'movement_type.in' => 'Invalid movement type selected.',
            
            'product_id.required' => 'Please select a product.',
            'product_id.exists' => 'Selected product is invalid.',
            
            'from_warehouse_id.exists' => 'Selected source warehouse is invalid.',
            'to_warehouse_id.exists' => 'Selected destination warehouse is invalid.',
            'from_location_id.exists' => 'Selected source location is invalid.',
            'to_location_id.exists' => 'Selected destination location is invalid.',
            
            'lot_id.exists' => 'Selected lot is invalid.',
            
            'quantity.required' => 'Quantity is required.',
            'quantity.numeric' => 'Quantity must be a number.',
            'quantity.min' => 'Quantity must be greater than 0.',
            
            'uom_id.required' => 'Unit of measure is required.',
            'uom_id.exists' => 'Selected unit is invalid.',
            
            'movement_date.required' => 'Movement date is required.',
            'movement_date.before_or_equal' => 'Movement date cannot be in the future.',
        ]);
        
        // Validate movement type logic
        if (in_array($validated['movement_type'], ['Transfer']) && !$validated['from_warehouse_id']) {
            return back()->withInput()->with('error', 'Source warehouse is required for transfer movements');
        }
        
        if (in_array($validated['movement_type'], ['Transfer', 'Issue']) && !$validated['to_warehouse_id']) {
            return back()->withInput()->with('error', 'Destination warehouse is required for this movement type');
        }
        
        DB::beginTransaction();
        try {
            // Generate unique movement code
            $movementCode = CodeGeneratorHelper::generateMovementCode();
            
            // Get product info
            $product = DB::table('products')->where('product_id', $validated['product_id'])->first();
            
            // Get warehouse and location info
            $fromWarehouse = $validated['from_warehouse_id'] ? 
                DB::table('warehouses')->where('warehouse_id', $validated['from_warehouse_id'])->first() : null;
            $toWarehouse = $validated['to_warehouse_id'] ? 
                DB::table('warehouses')->where('warehouse_id', $validated['to_warehouse_id'])->first() : null;
            
            $lot = $validated['lot_id'] ? 
                DB::table('lots')->where('lot_id', $validated['lot_id'])->first() : null;
            
            // Insert Stock Movement
            $movementId = DB::table('stock_movements')->insertGetId([
                'movement_code' => $movementCode,
                'movement_type' => $validated['movement_type'],
                'product_id' => $validated['product_id'],
                'from_warehouse_id' => $validated['from_warehouse_id'] ?? null,
                'to_warehouse_id' => $validated['to_warehouse_id'] ?? null,
                'from_location_id' => $validated['from_location_id'] ?? null,
                'to_location_id' => $validated['to_location_id'] ?? null,
                'lot_id' => $validated['lot_id'] ?? null,
                'quantity' => $validated['quantity'],
                'uom_id' => $validated['uom_id'],
                'movement_date' => $validated['movement_date'],
                'performed_by' => Auth::id(),
                'reference_type' => $validated['reference_type'] ?? null,
                'reference_id' => $validated['reference_id'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Update inventory based on movement type
            $this->updateInventory($validated);
            
            // Log CREATE
            $this->logCreate(
                'Inventory - Stock Movements',
                'stock_movements',
                $movementId,
                [
                    'movement_code' => $movementCode,
                    'movement_type' => $validated['movement_type'],
                    'product_name' => $product->product_name,
                    'product_code' => $product->product_code,
                    'quantity' => $validated['quantity'],
                    'from_warehouse' => $fromWarehouse->warehouse_name ?? 'External',
                    'to_warehouse' => $toWarehouse->warehouse_name ?? 'External',
                    'lot_code' => $lot->lot_code ?? null,
                    'movement_date' => $validated['movement_date'],
                    'performed_by' => Auth::user()->full_name,
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('inventory.movements.show', $movementCode)
                ->with('success', 'Stock movement recorded successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to record movement: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified stock movement
     */
    public function show($movementCode)
    {
        $movement = DB::table('stock_movements as sm')
            ->join('products as p', 'sm.product_id', '=', 'p.product_id')
            ->join('units_of_measure as uom', 'sm.uom_id', '=', 'uom.uom_id')
            ->leftJoin('warehouses as wf', 'sm.from_warehouse_id', '=', 'wf.warehouse_id')
            ->leftJoin('warehouses as wt', 'sm.to_warehouse_id', '=', 'wt.warehouse_id')
            ->leftJoin('warehouse_locations as lf', 'sm.from_location_id', '=', 'lf.location_id')
            ->leftJoin('warehouse_locations as lt', 'sm.to_location_id', '=', 'lt.location_id')
            ->leftJoin('lots as l', 'sm.lot_id', '=', 'l.lot_id')
            ->leftJoin('employees as e', 'sm.performed_by', '=', 'e.employee_id')
            ->where('sm.movement_code', $movementCode)
            ->select(
                'sm.*',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'uom.uom_code',
                'uom.uom_name',
                'wf.warehouse_code as from_warehouse_code',
                'wf.warehouse_name as from_warehouse_name',
                'wt.warehouse_code as to_warehouse_code',
                'wt.warehouse_name as to_warehouse_name',
                'lf.location_code as from_location_code',
                'lf.location_name as from_location_name',
                'lt.location_code as to_location_code',
                'lt.location_name as to_location_name',
                'l.lot_code',
                'l.expiry_date',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as performed_by_name"),
                'e.employee_code'
            )
            ->first();
        
        if (!$movement) {
            abort(404, 'Stock movement not found');
        }
        
        // Log VIEW
        $this->logView(
            'Inventory - Stock Movements',
            "Viewed stock movement: {$movement->movement_code} - {$movement->movement_type} of {$movement->product_name}"
        );
        
        return view('inventory.movements.show', compact('movement'));
    }

    /**
     * Remove the specified stock movement
     */
    public function destroy($movementCode)
    {
        $movement = DB::table('stock_movements as sm')
            ->join('products as p', 'sm.product_id', '=', 'p.product_id')
            ->where('sm.movement_code', $movementCode)
            ->select('sm.*', 'p.product_name', 'p.product_code')
            ->first();
        
        if (!$movement) {
            abort(404, 'Stock movement not found');
        }
        
        // Only allow deletion of movements from today
        if ($movement->movement_date != date('Y-m-d')) {
            return back()->with('error', 'Can only delete movements from today');
        }
        
        DB::beginTransaction();
        try {
            // Reverse inventory changes
            $this->reverseInventory($movement);
            
            // Delete movement
            DB::table('stock_movements')->where('movement_id', $movement->movement_id)->delete();
            
            // Log DELETE
            $this->logDelete(
                'Inventory - Stock Movements',
                'stock_movements',
                $movement->movement_id,
                [
                    'movement_code' => $movement->movement_code,
                    'movement_type' => $movement->movement_type,
                    'product_name' => $movement->product_name,
                    'product_code' => $movement->product_code,
                    'quantity' => $movement->quantity,
                    'movement_date' => $movement->movement_date,
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('inventory.movements.index')
                ->with('success', 'Stock movement deleted successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete movement: ' . $e->getMessage());
        }
    }
    
    /**
     * Update inventory based on movement type
     */
    private function updateInventory($data)
    {
        switch ($data['movement_type']) {
            case 'Receipt':
                // Increase stock in destination warehouse
                $this->adjustInventory(
                    $data['product_id'],
                    $data['to_warehouse_id'],
                    $data['to_location_id'] ?? null,
                    $data['lot_id'] ?? null,
                    $data['quantity'],
                    'increase'
                );
                break;
                
            case 'Issue':
                // Decrease stock from source warehouse
                $this->adjustInventory(
                    $data['product_id'],
                    $data['from_warehouse_id'],
                    $data['from_location_id'] ?? null,
                    $data['lot_id'] ?? null,
                    $data['quantity'],
                    'decrease'
                );
                break;
                
            case 'Transfer':
                // Decrease from source
                $this->adjustInventory(
                    $data['product_id'],
                    $data['from_warehouse_id'],
                    $data['from_location_id'] ?? null,
                    $data['lot_id'] ?? null,
                    $data['quantity'],
                    'decrease'
                );
                // Increase in destination
                $this->adjustInventory(
                    $data['product_id'],
                    $data['to_warehouse_id'],
                    $data['to_location_id'] ?? null,
                    $data['lot_id'] ?? null,
                    $data['quantity'],
                    'increase'
                );
                break;
                
            case 'Adjustment':
                // Can be increase or decrease based on quantity sign
                // For simplicity, treating as destination
                if ($data['to_warehouse_id']) {
                    $this->adjustInventory(
                        $data['product_id'],
                        $data['to_warehouse_id'],
                        $data['to_location_id'] ?? null,
                        $data['lot_id'] ?? null,
                        abs($data['quantity']),
                        $data['quantity'] > 0 ? 'increase' : 'decrease'
                    );
                }
                break;
        }
    }
    
    /**
     * Adjust inventory quantity
     */
    private function adjustInventory($productId, $warehouseId, $locationId, $lotId, $quantity, $operation)
    {
        // Find or create inventory record
        $inventory = DB::table('inventory')
            ->where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->where(function($q) use ($locationId) {
                if ($locationId) {
                    $q->where('location_id', $locationId);
                } else {
                    $q->whereNull('location_id');
                }
            })
            ->where(function($q) use ($lotId) {
                if ($lotId) {
                    $q->where('lot_id', $lotId);
                } else {
                    $q->whereNull('lot_id');
                }
            })
            ->first();
        
        if ($inventory) {
            // Update existing
            if ($operation === 'increase') {
                DB::table('inventory')
                    ->where('inventory_id', $inventory->inventory_id)
                    ->increment('quantity_on_hand', $quantity);
                DB::table('inventory')
                    ->where('inventory_id', $inventory->inventory_id)
                    ->update([
                        'quantity_available' => DB::raw('quantity_on_hand - quantity_reserved'),
                        'last_updated' => now()
                    ]);
            } else {
                DB::table('inventory')
                    ->where('inventory_id', $inventory->inventory_id)
                    ->decrement('quantity_on_hand', $quantity);
                DB::table('inventory')
                    ->where('inventory_id', $inventory->inventory_id)
                    ->update([
                        'quantity_available' => DB::raw('quantity_on_hand - quantity_reserved'),
                        'last_updated' => now()
                    ]);
            }
        } else if ($operation === 'increase') {
            // Create new inventory record
            DB::table('inventory')->insert([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'location_id' => $locationId,
                'lot_id' => $lotId,
                'quantity_on_hand' => $quantity,
                'quantity_reserved' => 0,
                'quantity_available' => $quantity,
                'last_updated' => now()
            ]);
        }
    }
    
    /**
     * Reverse inventory changes (for deletion)
     */
    private function reverseInventory($movement)
    {
        // Reverse logic based on movement type
        $reverseOperation = [
            'increase' => 'decrease',
            'decrease' => 'increase'
        ];
        
        switch ($movement->movement_type) {
            case 'Receipt':
                $this->adjustInventory(
                    $movement->product_id,
                    $movement->to_warehouse_id,
                    $movement->to_location_id,
                    $movement->lot_id,
                    $movement->quantity,
                    'decrease'
                );
                break;
                
            case 'Issue':
                $this->adjustInventory(
                    $movement->product_id,
                    $movement->from_warehouse_id,
                    $movement->from_location_id,
                    $movement->lot_id,
                    $movement->quantity,
                    'increase'
                );
                break;
                
            case 'Transfer':
                $this->adjustInventory(
                    $movement->product_id,
                    $movement->from_warehouse_id,
                    $movement->from_location_id,
                    $movement->lot_id,
                    $movement->quantity,
                    'increase'
                );
                $this->adjustInventory(
                    $movement->product_id,
                    $movement->to_warehouse_id,
                    $movement->to_location_id,
                    $movement->lot_id,
                    $movement->quantity,
                    'decrease'
                );
                break;
        }
    }
    
    /**
     * Export movements
     */
    public function export()
    {
        $this->logExport('Inventory - Stock Movements', 'Exported stock movements list');
        
        // Your export logic here
    }
}
