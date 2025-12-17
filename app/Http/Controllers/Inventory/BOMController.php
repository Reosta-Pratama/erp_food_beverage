<?php

namespace App\Http\Controllers\Inventory;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BOMController extends Controller
{
    //
    use LogsActivity;

    /**
     * List BOMs
     */
    public function index(Request $request)
    {        
        $query = DB::table('bill_of_materials as bom')
            ->join('products as p', 'bom.product_id', '=', 'p.product_id')
            ->leftJoin('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->select(
                'bom.bom_id',
                'bom.bom_code',
                'bom.bom_version',
                'bom.effective_date',
                'bom.is_active',
                'bom.created_at',
                'p.product_id',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'uom.uom_code',
                DB::raw('(SELECT COUNT(*) FROM bom_items WHERE bom_id = bom.bom_id) as items_count')
            );

        // Extract date_from and date_to from daterange input
        if ($request->filled('daterange')) {
            [$dateFrom, $dateTo] = array_map('trim', explode('to', $request->input('daterange') . ' to ')); 
            
            $request->merge([
                'date_from' => $dateFrom ?: null,
                'date_to' => $dateTo ?: null,
            ]);
        }
        
        // Search filter (multi-column)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('bom.bom_code', 'like', "%{$search}%")
                ->orWhere('p.product_name', 'like', "%{$search}%")
                ->orWhere('p.product_code', 'like', "%{$search}%")
                ->orWhere('bom.bom_version', 'like', "%{$search}%");
            });
        }
        
        // Filter by product type
        if ($request->filled('product_type')) {
            $query->where('p.product_type', $request->input('product_type'));
        }
        
        // Filter by status
        if ($request->filled('status')) {
            if ($request->input('status') === 'active') {
                $query->where('bom.is_active', 1);
            } elseif ($request->input('status') === 'inactive') {
                $query->where('bom.is_active', 0);
            }
        }
        
        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('bom.product_id', $request->input('product_id'));
        }
        
        // Filter by items count
        if ($request->filled('items_filter')) {
            switch ($request->input('items_filter')) {
                case 'empty': // No items
                    $query->havingRaw('(SELECT COUNT(*) FROM bom_items WHERE bom_id = bom.bom_id) = 0');
                    break;
                case 'simple': // 1-3 items
                    $query->havingRaw('(SELECT COUNT(*) FROM bom_items WHERE bom_id = bom.bom_id) BETWEEN 1 AND 3');
                    break;
                case 'standard': // 4-7 items
                    $query->havingRaw('(SELECT COUNT(*) FROM bom_items WHERE bom_id = bom.bom_id) BETWEEN 4 AND 7');
                    break;
                case 'complex': // 8+ items
                    $query->havingRaw('(SELECT COUNT(*) FROM bom_items WHERE bom_id = bom.bom_id) >= 8');
                    break;
            }
        }
        
        // Filter by effective date range
        if ($request->filled('date_from')) {
            $query->where('bom.effective_date', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('bom.effective_date', '<=', $request->input('date_to'));
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Validate sort order
        $sortOrder = \in_array(strtolower($sortOrder), ['asc', 'desc']) ? strtolower($sortOrder) : 'desc';
        
        // Whitelist allowed sort columns
        $allowedSort = [
            'bom_code',
            'product_name',
            'product_type',
            'bom_version',
            'effective_date',
            'items_count',
            'created_at'
        ];
        
        if (\in_array($sortBy, $allowedSort)) {
            if ($sortBy === 'bom_code') {
                $query->orderBy('bom.bom_code', $sortOrder);
            } elseif ($sortBy === 'product_name') {
                $query->orderBy('p.product_name', $sortOrder);
            } elseif ($sortBy === 'product_type') {
                $query->orderBy('p.product_type', $sortOrder);
            } elseif ($sortBy === 'bom_version') {
                $query->orderBy('bom.bom_version', $sortOrder);
            } elseif ($sortBy === 'effective_date') {
                $query->orderBy('bom.effective_date', $sortOrder);
            } elseif ($sortBy === 'created_at') {
                $query->orderBy('bom.created_at', $sortOrder);
            } elseif ($sortBy === 'items_count') {
                // Computed column - Subquery
                $query->orderByRaw("(SELECT COUNT(*) FROM bom_items WHERE bom_id = bom.bom_id) {$sortOrder}");
            }
        } else {
            // Fallback to default
            $query->orderByDesc('bom.created_at');
        }
        
        // Add secondary sort for consistency
        if ($sortBy !== 'bom_code' && $sortBy !== 'created_at') {
            $query->orderByDesc('bom.created_at');
        }
        
        $boms = $query->paginate(20)->withQueryString();
        
        // Get products for filter dropdown
        $products = DB::table('products')
            ->where('product_type', 'Finished Goods')
            ->where('is_active', 1)
            ->select('product_id', 'product_code', 'product_name')
            ->orderBy('product_name')
            ->get();
        
        return view('admin.bom-recipes.bom.index', compact('boms', 'products'));
    }

    /**
     * Form Create
     */
    public function create()
    {
        // Get finished goods products only
        $products = DB::table('products')
            ->join('product_categories as pc', 'products.category_id', '=', 'pc.category_id')
            ->join('units_of_measure as uom', 'products.uom_id', '=', 'uom.uom_id')
            ->where('products.product_type', 'Finished Goods')
            ->where('products.is_active', 1)
            ->select(
                'products.product_id',
                'products.product_code',
                'products.product_name',
                'pc.category_name',
                'uom.uom_code'
            )
            ->orderBy('products.product_name')
            ->get();
        
        // Get raw materials for BOM items
        $materials = DB::table('products')
            ->join('units_of_measure as uom', 'products.uom_id', '=', 'uom.uom_id')
            ->whereIn('products.product_type', ['Raw Material', 'Semi-Finished'])
            ->where('products.is_active', 1)
            ->select(
                'products.product_id',
                'products.product_code',
                'products.product_name',
                'products.product_type',
                'products.standard_cost',
                'uom.uom_id',
                'uom.uom_code',
                'uom.uom_name'
            )
            ->orderBy('products.product_name')
            ->get();
        
        $uoms = DB::table('units_of_measure')
            ->select('uom_id', 'uom_code', 'uom_name', 'uom_type')
            ->orderBy('uom_name')
            ->get();
        
        return view('admin.bom-recipes.bom.create', compact('products', 'materials', 'uoms'));
    }

    /**
     * Store new BOM
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,product_id'],
            'bom_version' => ['required', 'string', 'max:20'],
            'effective_date' => ['required', 'date'],
            'is_active' => ['boolean'],
            'notes' => ['nullable', 'string'],
            
            // BOM Items
            'items' => ['required', 'array', 'min:1'],
            'items.*.material_id' => ['required', 'exists:products,product_id'],
            'items.*.quantity_required' => ['required', 'numeric', 'min:0.0001'],
            'items.*.uom_id' => ['required', 'exists:units_of_measure,uom_id'],
            'items.*.item_type' => ['required', 'in:Raw Material,Semi-Finished,Packaging'],
            'items.*.scrap_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ], [
            'product_id.required' => 'Please select a product.',
            'product_id.exists' => 'Selected product is invalid.',
            'bom_version.required' => 'BOM version is required.',
            'effective_date.required' => 'Effective date is required.',
            'effective_date.date' => 'Please provide a valid date.',

            'items.required' => 'BOM must have at least one item.',
            'items.min' => 'BOM must have at least one item.',
            'items.*.material_id.required' => 'Material is required for each item.',
            'items.*.material_id.exists' => 'Selected material is invalid.',
            'items.*.quantity_required.required' => 'Quantity is required for each item.',
            'items.*.quantity_required.numeric' => 'Quantity must be a number.',
            'items.*.quantity_required.min' => 'Quantity must be greater than 0.',
            'items.*.uom_id.required' => 'Unit of measure is required for each item.',
            'items.*.uom_id.exists' => 'Selected unit is invalid.',
            'items.*.item_type.required' => 'Item type is required for each item.',
            'items.*.item_type.in' => 'Invalid item type selected.',
        ]);
        
        DB::beginTransaction();
        try {
            $bomCode = CodeGeneratorHelper::generateBOMCode();
            
            // Get product info for logging
            $product = DB::table('products')->where('product_id', $validated['product_id'])->first();
            
            // Insert BOM
            $bomId = DB::table('bill_of_materials')->insertGetId([
                'bom_code' => $bomCode,
                'product_id' => $validated['product_id'],
                'bom_version' => $validated['bom_version'],
                'effective_date' => $validated['effective_date'],
                'is_active' => $request->has('is_active') ? 1 : 0,
                'notes' => $validated['notes'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Insert BOM Items
            $bomItemsData = [];
            foreach ($validated['items'] as $item) {
                $bomItemsData[] = [
                    'bom_id' => $bomId,
                    'material_id' => $item['material_id'],
                    'quantity_required' => $item['quantity_required'],
                    'uom_id' => $item['uom_id'],
                    'item_type' => $item['item_type'],
                    'scrap_percentage' => $item['scrap_percentage'] ?? 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            DB::table('bom_items')->insert($bomItemsData);
            
            // Log CREATE
            $this->logCreate(
                'Inventory - BOM',
                'bill_of_materials',
                $bomId,
                [
                    'bom_code' => $bomCode,
                    'product_name' => $product->product_name,
                    'product_code' => $product->product_code,
                    'bom_version' => $validated['bom_version'],
                    'effective_date' => $validated['effective_date'],
                    'is_active' => $request->has('is_active') ? 1 : 0,
                    'items_count' => count($validated['items']),
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('inventory.bom.show', $bomCode)
                ->with('success', 'BOM created successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create BOM: ' . $e->getMessage());
        }
    }

    /**
     * Detail BOM
     */
    public function show($bomCode)
    {
        $bom = DB::table('bill_of_materials as bom')
            ->join('products as p', 'bom.product_id', '=', 'p.product_id')
            ->join('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->where('bom.bom_code', $bomCode)
            ->select(
                'bom.*',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'p.standard_cost',
                'uom.uom_code',
                'uom.uom_name'
            )
            ->first();
        
        if (!$bom) {
            abort(404, 'BOM not found');
        }
        
        // Log VIEW
        $this->logView(
            'Inventory - BOM',
            "Viewed BOM: {$bom->bom_code} for {$bom->product_name}"
        );
        
        // Get BOM items with material details
        $items = DB::table('bom_items as bi')
            ->join('products as m', 'bi.material_id', '=', 'm.product_id')
            ->join('units_of_measure as uom', 'bi.uom_id', '=', 'uom.uom_id')
            ->where('bi.bom_id', $bom->bom_id)
            ->select(
                'bi.*',
                'm.product_code as material_code',
                'm.product_name as material_name',
                'm.product_type as material_type',
                'm.standard_cost',
                'uom.uom_code',
                'uom.uom_name',
                DB::raw('(bi.quantity_required * m.standard_cost) as item_cost')
            )
            ->orderBy('bi.item_type')
            ->orderBy('m.product_name')
            ->get();
        
        // Calculate total cost
        $totalCost = $items->sum('item_cost');
        
        return view('admin.bom-recipes.bom.show', compact('bom', 'items', 'totalCost'));
    }

    /**
     * Form Edit
     */
    public function edit($bomCode)
    {
        $bom = DB::table('bill_of_materials')
            ->where('bom_code', $bomCode)
            ->first();
        
        if (!$bom) {
            abort(404, 'BOM not found');
        }
        
        $products = DB::table('products')
            ->join('product_categories as pc', 'products.category_id', '=', 'pc.category_id')
            ->join('units_of_measure as uom', 'products.uom_id', '=', 'uom.uom_id')
            ->where('products.product_type', 'Finished Goods')
            ->where('products.is_active', 1)
            ->select(
                'products.product_id',
                'products.product_code',
                'products.product_name',
                'pc.category_name',
                'uom.uom_code'
            )
            ->orderBy('products.product_name')
            ->get();
        
        $materials = DB::table('products')
            ->join('units_of_measure as uom', 'products.uom_id', '=', 'uom.uom_id')
            ->whereIn('products.product_type', ['Raw Material', 'Semi-Finished'])
            ->where('products.is_active', 1)
            ->select(
                'products.product_id',
                'products.product_code',
                'products.product_name',
                'products.product_type',
                'products.standard_cost',
                'uom.uom_id',
                'uom.uom_code',
                'uom.uom_name'
            )
            ->orderBy('products.product_name')
            ->get();
        
        $uoms = DB::table('units_of_measure')
            ->select('uom_id', 'uom_code', 'uom_name', 'uom_type')
            ->orderBy('uom_name')
            ->get();
        
        // Get existing BOM items
        $bomItems = DB::table('bom_items')
            ->where('bom_id', $bom->bom_id)
            ->get();
        
        return view('admin.bom-recipes.bom.edit', compact('bom', 'products', 'materials', 'uoms', 'bomItems'));
    }

    /**
     * Update the specified BOM
     */
    public function update(Request $request, $bomCode)
    {
        $bom = DB::table('bill_of_materials')->where('bom_code', $bomCode)->first();
        
        if (!$bom) {
            abort(404, 'BOM not found');
        }
        
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,product_id'],
            'bom_version' => ['required', 'string', 'max:20'],
            'effective_date' => ['required', 'date'],
            'is_active' => ['boolean'],
            'notes' => ['nullable', 'string'],
            
            'items' => ['required', 'array', 'min:1'],
            'items.*.material_id' => ['required', 'exists:products,product_id'],
            'items.*.quantity_required' => ['required', 'numeric', 'min:0.0001'],
            'items.*.uom_id' => ['required', 'exists:units_of_measure,uom_id'],
            'items.*.item_type' => ['required', 'in:Raw Material,Semi-Finished,Packaging'],
            'items.*.scrap_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);
        
        DB::beginTransaction();
        try {
            // Capture OLD data
            $oldBom = DB::table('bill_of_materials as bom')
                ->join('products as p', 'bom.product_id', '=', 'p.product_id')
                ->where('bom.bom_id', $bom->bom_id)
                ->select('bom.*', 'p.product_name', 'p.product_code')
                ->first();
            
            $oldItemsCount = DB::table('bom_items')
                ->where('bom_id', $bom->bom_id)
                ->count();
            
            // Get new product info
            $newProduct = DB::table('products')->where('product_id', $validated['product_id'])->first();
            
            // Update BOM
            DB::table('bill_of_materials')
                ->where('bom_id', $bom->bom_id)
                ->update([
                    'product_id' => $validated['product_id'],
                    'bom_version' => $validated['bom_version'],
                    'effective_date' => $validated['effective_date'],
                    'is_active' => $request->has('is_active') ? 1 : 0,
                    'notes' => $validated['notes'] ?? null,
                    'updated_at' => now(),
                ]);
            
            // Delete old items and insert new ones
            DB::table('bom_items')->where('bom_id', $bom->bom_id)->delete();
            
            $bomItemsData = [];
            foreach ($validated['items'] as $item) {
                $bomItemsData[] = [
                    'bom_id' => $bom->bom_id,
                    'material_id' => $item['material_id'],
                    'quantity_required' => $item['quantity_required'],
                    'uom_id' => $item['uom_id'],
                    'item_type' => $item['item_type'],
                    'scrap_percentage' => $item['scrap_percentage'] ?? 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            DB::table('bom_items')->insert($bomItemsData);
            
            // Log UPDATE
            $this->logUpdate(
                'Inventory - BOM',
                'bill_of_materials',
                $bom->bom_id,
                [
                    'bom_code' => $oldBom->bom_code,
                    'product_name' => $oldBom->product_name,
                    'product_code' => $oldBom->product_code,
                    'bom_version' => $oldBom->bom_version,
                    'effective_date' => $oldBom->effective_date,
                    'is_active' => $oldBom->is_active,
                    'items_count' => $oldItemsCount,
                ],
                [
                    'bom_code' => $bom->bom_code,
                    'product_name' => $newProduct->product_name,
                    'product_code' => $newProduct->product_code,
                    'bom_version' => $validated['bom_version'],
                    'effective_date' => $validated['effective_date'],
                    'is_active' => $request->has('is_active') ? 1 : 0,
                    'items_count' => count($validated['items']),
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('inventory.bom.show', $bomCode)
                ->with('success', 'BOM updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update BOM: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified BOM
     */
    public function destroy($bomCode)
    {
        $bom = DB::table('bill_of_materials as bom')
            ->join('products as p', 'bom.product_id', '=', 'p.product_id')
            ->where('bom.bom_code', $bomCode)
            ->select('bom.*', 'p.product_name', 'p.product_code')
            ->first();
        
        if (!$bom) {
            abort(404, 'BOM not found');
        }
        
        // Check if BOM is used in work orders
        $workOrdersCount = DB::table('work_orders')
            ->where('bom_id', $bom->bom_id)
            ->count();
        
        if ($workOrdersCount > 0) {
            return back()->with('error', 'Cannot delete BOM that is used in work orders');
        }
        
        DB::beginTransaction();
        try {
            $itemsCount = DB::table('bom_items')
                ->where('bom_id', $bom->bom_id)
                ->count();
            
            // Delete BOM items first
            DB::table('bom_items')->where('bom_id', $bom->bom_id)->delete();
            
            // Delete BOM
            DB::table('bill_of_materials')->where('bom_id', $bom->bom_id)->delete();
            
            // Log DELETE
            $this->logDelete(
                'Inventory - BOM',
                'bill_of_materials',
                $bom->bom_id,
                [
                    'bom_code' => $bom->bom_code,
                    'product_name' => $bom->product_name,
                    'product_code' => $bom->product_code,
                    'bom_version' => $bom->bom_version,
                    'effective_date' => $bom->effective_date,
                    'is_active' => $bom->is_active,
                    'items_count' => $itemsCount,
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('inventory.bom.index')
                ->with('success', 'BOM deleted successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete BOM: ' . $e->getMessage());
        }
    }
    
    /**
     * Export BOM to CSV/Excel
     */
    public function export(Request $request)
    {
        $this->logExport('Inventory - BOM', 'Exported BOM list');
        
        $query = DB::table('bill_of_materials as bom')
            ->join('products as p', 'bom.product_id', '=', 'p.product_id')
            ->leftJoin('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->select(
                'bom.bom_code',
                'bom.bom_version',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'uom.uom_code',
                'bom.effective_date',
                'bom.is_active',
                DB::raw('(SELECT COUNT(*) FROM bom_items WHERE bom_id = bom.bom_id) as items_count'),
                'bom.notes',
                'bom.created_at'
            );
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('bom.bom_code', 'like', "%{$search}%")
                ->orWhere('p.product_name', 'like', "%{$search}%")
                ->orWhere('p.product_code', 'like', "%{$search}%")
                ->orWhere('bom.bom_version', 'like', "%{$search}%");
            });
        }
        
        // Filter by product type
        if ($request->filled('product_type')) {
            $query->where('p.product_type', $request->input('product_type'));
        }
        
        // Filter by status
        if ($request->filled('status')) {
            if ($request->input('status') === 'active') {
                $query->where('bom.is_active', 1);
            } elseif ($request->input('status') === 'inactive') {
                $query->where('bom.is_active', 0);
            }
        }
        
        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('bom.product_id', $request->input('product_id'));
        }
        
        // Filter by items count
        if ($request->filled('items_filter')) {
            switch ($request->input('items_filter')) {
                case 'empty':
                    $query->havingRaw('(SELECT COUNT(*) FROM bom_items WHERE bom_id = bom.bom_id) = 0');
                    break;
                case 'simple':
                    $query->havingRaw('(SELECT COUNT(*) FROM bom_items WHERE bom_id = bom.bom_id) BETWEEN 1 AND 3');
                    break;
                case 'standard':
                    $query->havingRaw('(SELECT COUNT(*) FROM bom_items WHERE bom_id = bom.bom_id) BETWEEN 4 AND 7');
                    break;
                case 'complex':
                    $query->havingRaw('(SELECT COUNT(*) FROM bom_items WHERE bom_id = bom.bom_id) >= 8');
                    break;
            }
        }

        // Filter by effective date range
        if ($request->filled('date_from')) {
            $query->where('bom.effective_date', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('bom.effective_date', '<=', $request->input('date_to'));
        }
        
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $sortOrder = \in_array(strtolower($sortOrder), ['asc', 'desc']) ? strtolower($sortOrder) : 'desc';
        
        $allowedSort = [
            'bom_code', 'product_name', 'product_type', 'bom_version',
            'effective_date', 'items_count', 'created_at'
        ];
        
        if (\in_array($sortBy, $allowedSort)) {
            if ($sortBy === 'bom_code') {
                $query->orderBy('bom.bom_code', $sortOrder);
            } elseif ($sortBy === 'product_name') {
                $query->orderBy('p.product_name', $sortOrder);
            } elseif ($sortBy === 'product_type') {
                $query->orderBy('p.product_type', $sortOrder);
            } elseif ($sortBy === 'bom_version') {
                $query->orderBy('bom.bom_version', $sortOrder);
            } elseif ($sortBy === 'effective_date') {
                $query->orderBy('bom.effective_date', $sortOrder);
            } elseif ($sortBy === 'created_at') {
                $query->orderBy('bom.created_at', $sortOrder);
            } elseif ($sortBy === 'items_count') {
                $query->orderByRaw("(SELECT COUNT(*) FROM bom_items WHERE bom_id = bom.bom_id) {$sortOrder}");
            }
        } else {
            $query->orderByDesc('bom.created_at');
        }
        
        if ($sortBy !== 'bom_code' && $sortBy !== 'created_at') {
            $query->orderByDesc('bom.created_at');
        }
        
        $boms = $query->limit(10000)->get();
        
        $filename = 'bom_export_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($boms) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'BOM Code',
                'Version',
                'Product Code',
                'Product Name',
                'Product Type',
                'UOM',
                'Effective Date',
                'Status',
                'Items Count',
                'Notes',
                'Created At'
            ]);
            
            // Data rows
            foreach ($boms as $bom) {
                fputcsv($file, [
                    $bom->bom_code,
                    $bom->bom_version,
                    $bom->product_code,
                    $bom->product_name,
                    $bom->product_type,
                    $bom->uom_code,
                    $bom->effective_date,
                    $bom->is_active ? 'Active' : 'Inactive',
                    $bom->items_count,
                    $bom->notes ?? '-',
                    $bom->created_at,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
