<?php

namespace App\Http\Controllers\Inventory;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LotController extends Controller
{
    //
    use LogsActivity;
    
    /**
     * Display a listing of lots
     */
    public function index(Request $request)
    {
        $this->logView('Inventory - Lot Tracking', 'Viewed lot tracking list');
        
        $query = DB::table('lots as l')
            ->join('products as p', 'l.product_id', '=', 'p.product_id')
            ->leftJoin('suppliers as s', 'l.supplier_id', '=', 's.supplier_id')
            ->select(
                'l.lot_id',
                'l.lot_code',
                'l.manufacture_date',
                'l.expiry_date',
                'l.quantity',
                'l.status',
                'l.created_at',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                's.supplier_code',
                's.supplier_name',
                DB::raw('DATEDIFF(l.expiry_date, CURDATE()) as days_to_expiry'),
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
                $q->where('l.lot_code', 'like', "%{$search}%")
                  ->orWhere('p.product_name', 'like', "%{$search}%")
                  ->orWhere('p.product_code', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('l.status', $request->status);
        }
        
        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('l.product_id', $request->product_id);
        }
        
        // Filter by expiry status
        if ($request->filled('expiry_status')) {
            switch ($request->expiry_status) {
                case 'expired':
                    $query->whereNotNull('l.expiry_date')
                          ->whereRaw('DATEDIFF(l.expiry_date, CURDATE()) < 0');
                    break;
                case 'expiring_soon':
                    $query->whereNotNull('l.expiry_date')
                          ->whereRaw('DATEDIFF(l.expiry_date, CURDATE()) BETWEEN 0 AND 30');
                    break;
                case 'good':
                    $query->where(function($q) {
                        $q->whereNull('l.expiry_date')
                          ->orWhereRaw('DATEDIFF(l.expiry_date, CURDATE()) > 30');
                    });
                    break;
            }
        }
        
        $lots = $query->orderByDesc('l.created_at')
            ->paginate(20);
        
        // Get products for filter
        $products = DB::table('products')
            ->where('is_active', 1)
            ->select('product_id', 'product_code', 'product_name', 'product_type')
            ->orderBy('product_name')
            ->get();
        
        return view('inventory.lots.index', compact('lots', 'products'));
    }

    /**
     * Show the form for creating a new lot
     */
    public function create()
    {
        // Get all products
        $products = DB::table('products')
            ->join('units_of_measure as uom', 'products.uom_id', '=', 'uom.uom_id')
            ->where('products.is_active', 1)
            ->select(
                'products.product_id',
                'products.product_code',
                'products.product_name',
                'products.product_type',
                'uom.uom_code'
            )
            ->orderBy('products.product_name')
            ->get();
        
        // Get suppliers (for external lots)
        $suppliers = DB::table('suppliers')
            ->where('is_active', 1)
            ->select('supplier_id', 'supplier_code', 'supplier_name')
            ->orderBy('supplier_name')
            ->get();
        
        return view('inventory.lots.create', compact('products', 'suppliers'));
    }

    /**
     * Store a newly created lot
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,product_id'],
            'manufacture_date' => ['required', 'date', 'before_or_equal:today'],
            'expiry_date' => ['nullable', 'date', 'after:manufacture_date'],
            'quantity' => ['required', 'numeric', 'min:0.0001'],
            'supplier_id' => ['nullable', 'exists:suppliers,supplier_id'],
            'notes' => ['nullable', 'string'],
        ], [
            'product_id.required' => 'Please select a product.',
            'product_id.exists' => 'Selected product is invalid.',
            
            'manufacture_date.required' => 'Manufacture date is required.',
            'manufacture_date.date' => 'Please provide a valid manufacture date.',
            'manufacture_date.before_or_equal' => 'Manufacture date cannot be in the future.',
            
            'expiry_date.date' => 'Please provide a valid expiry date.',
            'expiry_date.after' => 'Expiry date must be after manufacture date.',
            
            'quantity.required' => 'Quantity is required.',
            'quantity.numeric' => 'Quantity must be a number.',
            'quantity.min' => 'Quantity must be greater than 0.',
            
            'supplier_id.exists' => 'Selected supplier is invalid.',
        ]);
        
        DB::beginTransaction();
        try {
            // Generate unique lot code
            $lotCode = CodeGeneratorHelper::generateLotCode();
            
            // Get product info
            $product = DB::table('products')->where('product_id', $validated['product_id'])->first();
            
            // Get supplier info if provided
            $supplier = null;
            if ($validated['supplier_id']) {
                $supplier = DB::table('suppliers')
                    ->where('supplier_id', $validated['supplier_id'])
                    ->first();
            }
            
            // Insert Lot
            $lotId = DB::table('lots')->insertGetId([
                'lot_code' => $lotCode,
                'product_id' => $validated['product_id'],
                'manufacture_date' => $validated['manufacture_date'],
                'expiry_date' => $validated['expiry_date'] ?? null,
                'quantity' => $validated['quantity'],
                'status' => 'Active',
                'supplier_id' => $validated['supplier_id'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Create expiry tracking if expiry date exists
            if ($validated['expiry_date']) {
                // You can add warehouse_id if needed
                $alertDate = date('Y-m-d', strtotime($validated['expiry_date'] . ' -30 days'));
                
                DB::table('expiry_tracking')->insert([
                    'product_id' => $validated['product_id'],
                    'lot_id' => $lotId,
                    'warehouse_id' => 1, // Default warehouse, adjust as needed
                    'expiry_date' => $validated['expiry_date'],
                    'quantity' => $validated['quantity'],
                    'status' => 'Active',
                    'alert_date' => $alertDate,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Log CREATE
            $this->logCreate(
                'Inventory - Lot Tracking',
                'lots',
                $lotId,
                [
                    'lot_code' => $lotCode,
                    'product_name' => $product->product_name,
                    'product_code' => $product->product_code,
                    'manufacture_date' => $validated['manufacture_date'],
                    'expiry_date' => $validated['expiry_date'] ?? 'No Expiry',
                    'quantity' => $validated['quantity'],
                    'supplier_name' => $supplier->supplier_name ?? 'Internal Production',
                    'status' => 'Active',
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('inventory.lots.show', $lotCode)
                ->with('success', 'Lot created successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create lot: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified lot
     */
    public function show($lotCode)
    {
        $lot = DB::table('lots as l')
            ->join('products as p', 'l.product_id', '=', 'p.product_id')
            ->join('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->leftJoin('suppliers as s', 'l.supplier_id', '=', 's.supplier_id')
            ->where('l.lot_code', $lotCode)
            ->select(
                'l.*',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'uom.uom_code',
                'uom.uom_name',
                's.supplier_code',
                's.supplier_name',
                DB::raw('DATEDIFF(l.expiry_date, CURDATE()) as days_to_expiry'),
                DB::raw('CASE 
                    WHEN l.expiry_date IS NULL THEN "No Expiry"
                    WHEN DATEDIFF(l.expiry_date, CURDATE()) < 0 THEN "Expired"
                    WHEN DATEDIFF(l.expiry_date, CURDATE()) <= 30 THEN "Expiring Soon"
                    ELSE "Good"
                END as expiry_status')
            )
            ->first();
        
        if (!$lot) {
            abort(404, 'Lot not found');
        }
        
        // Log VIEW
        $this->logView(
            'Inventory - Lot Tracking',
            "Viewed lot: {$lot->lot_code} for {$lot->product_name}"
        );
        
        // Get inventory locations for this lot
        $inventoryLocations = DB::table('inventory as inv')
            ->join('warehouses as w', 'inv.warehouse_id', '=', 'w.warehouse_id')
            ->leftJoin('warehouse_locations as wl', 'inv.location_id', '=', 'wl.location_id')
            ->where('inv.lot_id', $lot->lot_id)
            ->select(
                'inv.inventory_id',
                'inv.quantity_on_hand',
                'inv.quantity_reserved',
                'inv.quantity_available',
                'w.warehouse_code',
                'w.warehouse_name',
                'wl.location_code',
                'wl.location_name'
            )
            ->get();
        
        // Get stock movements for this lot
        $stockMovements = DB::table('stock_movements as sm')
            ->leftJoin('warehouses as wf', 'sm.from_warehouse_id', '=', 'wf.warehouse_id')
            ->leftJoin('warehouses as wt', 'sm.to_warehouse_id', '=', 'wt.warehouse_id')
            ->leftJoin('employees as e', 'sm.performed_by', '=', 'e.employee_id')
            ->where('sm.lot_id', $lot->lot_id)
            ->select(
                'sm.movement_code',
                'sm.movement_type',
                'sm.movement_date',
                'sm.quantity',
                'wf.warehouse_name as from_warehouse',
                'wt.warehouse_name as to_warehouse',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as performed_by_name")
            )
            ->orderByDesc('sm.movement_date')
            ->limit(20)
            ->get();
        
        // Get batches linked to this lot
        $batches = DB::table('batches')
            ->where('product_id', $lot->product_id)
            ->where('production_date', $lot->manufacture_date)
            ->select('batch_id', 'batch_code', 'quantity_produced', 'status')
            ->get();
        
        return view('inventory.lots.show', compact('lot', 'inventoryLocations', 'stockMovements', 'batches'));
    }

    /**
     * Show the form for editing the specified lot
     */
    public function edit($lotCode)
    {
        $lot = DB::table('lots')->where('lot_code', $lotCode)->first();
        
        if (!$lot) {
            abort(404, 'Lot not found');
        }
        
        // Only active lots can be edited
        if ($lot->status !== 'Active') {
            return back()->with('error', 'Only active lots can be edited');
        }
        
        $products = DB::table('products')
            ->join('units_of_measure as uom', 'products.uom_id', '=', 'uom.uom_id')
            ->where('products.is_active', 1)
            ->select(
                'products.product_id',
                'products.product_code',
                'products.product_name',
                'products.product_type',
                'uom.uom_code'
            )
            ->orderBy('products.product_name')
            ->get();
        
        $suppliers = DB::table('suppliers')
            ->where('is_active', 1)
            ->select('supplier_id', 'supplier_code', 'supplier_name')
            ->orderBy('supplier_name')
            ->get();
        
        return view('inventory.lots.edit', compact('lot', 'products', 'suppliers'));
    }

    /**
     * Update the specified lot
     */
    public function update(Request $request, $lotCode)
    {
        $lot = DB::table('lots')->where('lot_code', $lotCode)->first();
        
        if (!$lot) {
            abort(404, 'Lot not found');
        }
        
        // Only active lots can be updated
        if ($lot->status !== 'Active') {
            return back()->with('error', 'Only active lots can be updated');
        }
        
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,product_id'],
            'manufacture_date' => ['required', 'date', 'before_or_equal:today'],
            'expiry_date' => ['nullable', 'date', 'after:manufacture_date'],
            'quantity' => ['required', 'numeric', 'min:0.0001'],
            'supplier_id' => ['nullable', 'exists:suppliers,supplier_id'],
            'notes' => ['nullable', 'string'],
        ]);
        
        DB::beginTransaction();
        try {
            // Capture OLD data
            $oldLot = DB::table('lots as l')
                ->join('products as p', 'l.product_id', '=', 'p.product_id')
                ->leftJoin('suppliers as s', 'l.supplier_id', '=', 's.supplier_id')
                ->where('l.lot_id', $lot->lot_id)
                ->select('l.*', 'p.product_name', 'p.product_code', 's.supplier_name')
                ->first();
            
            // Get new product and supplier info
            $newProduct = DB::table('products')->where('product_id', $validated['product_id'])->first();
            $newSupplier = null;
            if ($validated['supplier_id']) {
                $newSupplier = DB::table('suppliers')->where('supplier_id', $validated['supplier_id'])->first();
            }
            
            // Update Lot
            DB::table('lots')
                ->where('lot_id', $lot->lot_id)
                ->update([
                    'product_id' => $validated['product_id'],
                    'manufacture_date' => $validated['manufacture_date'],
                    'expiry_date' => $validated['expiry_date'] ?? null,
                    'quantity' => $validated['quantity'],
                    'supplier_id' => $validated['supplier_id'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                    'updated_at' => now(),
                ]);
            
            // Update expiry tracking if exists
            if ($validated['expiry_date']) {
                DB::table('expiry_tracking')
                    ->where('lot_id', $lot->lot_id)
                    ->update([
                        'expiry_date' => $validated['expiry_date'],
                        'quantity' => $validated['quantity'],
                        'alert_date' => date('Y-m-d', strtotime($validated['expiry_date'] . ' -30 days')),
                        'updated_at' => now(),
                    ]);
            }
            
            // Log UPDATE
            $this->logUpdate(
                'Inventory - Lot Tracking',
                'lots',
                $lot->lot_id,
                [
                    'lot_code' => $oldLot->lot_code,
                    'product_name' => $oldLot->product_name,
                    'product_code' => $oldLot->product_code,
                    'manufacture_date' => $oldLot->manufacture_date,
                    'expiry_date' => $oldLot->expiry_date ?? 'No Expiry',
                    'quantity' => $oldLot->quantity,
                    'supplier_name' => $oldLot->supplier_name ?? 'Internal Production',
                ],
                [
                    'lot_code' => $lot->lot_code,
                    'product_name' => $newProduct->product_name,
                    'product_code' => $newProduct->product_code,
                    'manufacture_date' => $validated['manufacture_date'],
                    'expiry_date' => $validated['expiry_date'] ?? 'No Expiry',
                    'quantity' => $validated['quantity'],
                    'supplier_name' => $newSupplier->supplier_name ?? 'Internal Production',
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('inventory.lots.show', $lotCode)
                ->with('success', 'Lot updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update lot: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified lot
     */
    public function destroy($lotCode)
    {
        $lot = DB::table('lots as l')
            ->join('products as p', 'l.product_id', '=', 'p.product_id')
            ->where('l.lot_code', $lotCode)
            ->select('l.*', 'p.product_name', 'p.product_code')
            ->first();
        
        if (!$lot) {
            abort(404, 'Lot not found');
        }
        
        // Check if lot is used in inventory
        $inventoryCount = DB::table('inventory')->where('lot_id', $lot->lot_id)->count();
        if ($inventoryCount > 0) {
            return back()->with('error', 'Cannot delete lot that is used in inventory');
        }
        
        // Check if lot is used in stock movements
        $movementsCount = DB::table('stock_movements')->where('lot_id', $lot->lot_id)->count();
        if ($movementsCount > 0) {
            return back()->with('error', 'Cannot delete lot that has stock movements');
        }
        
        DB::beginTransaction();
        try {
            // Delete expiry tracking first
            DB::table('expiry_tracking')->where('lot_id', $lot->lot_id)->delete();
            
            // Delete lot
            DB::table('lots')->where('lot_id', $lot->lot_id)->delete();
            
            // Log DELETE
            $this->logDelete(
                'Inventory - Lot Tracking',
                'lots',
                $lot->lot_id,
                [
                    'lot_code' => $lot->lot_code,
                    'product_name' => $lot->product_name,
                    'product_code' => $lot->product_code,
                    'manufacture_date' => $lot->manufacture_date,
                    'expiry_date' => $lot->expiry_date ?? 'No Expiry',
                    'quantity' => $lot->quantity,
                    'status' => $lot->status,
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('inventory.lots.index')
                ->with('success', 'Lot deleted successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete lot: ' . $e->getMessage());
        }
    }
    
    /**
     * Export lots
     */
    public function export()
    {
        $this->logExport('Inventory - Lot Tracking', 'Exported lot tracking list');
        
        // Your export logic here
    }
}
