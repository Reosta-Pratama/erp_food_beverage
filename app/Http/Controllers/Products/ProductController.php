<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    //
    use LogsActivity;

    /**
     * List products with advanced filtering
     */
    public function index(Request $request)
    {
        $this->logView('Products - Management', 'Viewed products list');

        $query = DB::table('products')
            ->join('product_categories', 'products.category_id', '=', 'product_categories.category_id')
            ->join('units_of_measure', 'products.uom_id', '=', 'units_of_measure.uom_id')
            ->select(
                'products.product_id',
                'products.product_code',
                'products.product_name',
                'products.product_type',
                'products.standard_cost',
                'products.selling_price',
                'products.is_active',
                'products.created_at',
                'product_categories.category_name',
                'product_categories.category_code',
                'units_of_measure.uom_name'
            );

        // Filter by product type
        if ($request->filled('product_type')) {
            $query->where('products.product_type', $request->product_type);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('products.category_id', $request->category_id);
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('products.is_active', $request->boolean('is_active'));
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('products.product_name', 'like', "%{$search}%")
                  ->orWhere('products.product_code', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('products.product_name')
            ->paginate(50);

        // Get filter options
        $categories = DB::table('product_categories')
            ->orderBy('category_name')
            ->get();

        $productTypes = DB::table('products')
            ->select('product_type', DB::raw('COUNT(*) as count'))
            ->groupBy('product_type')
            ->get();
        
        return view('admin.products.index', compact('products', 'categories', 'productTypes'));
    }

    /**
     * Create form
     */
    public function create()
    {
        $categories = DB::table('product_categories')
            ->orderBy('category_name')
            ->get();

        $uoms = DB::table('units_of_measure')
            ->orderBy('uom_type')
            ->orderBy('uom_name')
            ->get();
        
        return view('admin.products.create', compact('categories', 'uoms'));
    }

    /**
     * Store product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => ['required', 'string', 'max:200'],
            'product_type' => ['required', 'in:Raw Material,Semi-Finished,Finished Goods,Packaging,Consumable'],
            'category_id' => ['required', 'exists:product_categories,category_id'],
            'uom_id' => ['required', 'exists:units_of_measure,uom_id'],
            'standard_cost' => ['nullable', 'numeric', 'min:0', 'max:9999999999999.99'],
            'selling_price' => ['nullable', 'numeric', 'min:0', 'max:9999999999999.99'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'product_name.required' => 'Product name is required.',
            'product_type.required' => 'Product type is required.',
            'product_type.in' => 'Invalid product type selected.',
            'category_id.required' => 'Category is required.',
            'category_id.exists' => 'Selected category is invalid.',
            'uom_id.required' => 'Unit of measure is required.',
            'uom_id.exists' => 'Selected unit of measure is invalid.',
            'standard_cost.numeric' => 'Standard cost must be a valid number.',
            'standard_cost.min' => 'Standard cost cannot be negative.',
            'selling_price.numeric' => 'Selling price must be a valid number.',
            'selling_price.min' => 'Selling price cannot be negative.',
        ]);

        DB::beginTransaction();
        try {
            $productId = DB::table('products')->insertGetId([
                'product_code' => strtoupper(Str::random(10)),
                'product_name' => $validated['product_name'],
                'product_type' => $validated['product_type'],
                'category_id' => $validated['category_id'],
                'uom_id' => $validated['uom_id'],
                'standard_cost' => $validated['standard_cost'] ?? 0.00,
                'selling_price' => $validated['selling_price'] ?? 0.00,
                'description' => $validated['description'] ?? null,
                'is_active' => $request->boolean('is_active', true),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Log CREATE
            $this->logCreate(
                'Products - Management',
                'products',
                $productId,
                $validated
            );

            DB::commit();
            
            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product created successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    /**
     * Show product details
     */
    public function show($productCode)
    {
        $product = DB::table('products')
            ->join('product_categories', 'products.category_id', '=', 'product_categories.category_id')
            ->join('units_of_measure', 'products.uom_id', '=', 'units_of_measure.uom_id')
            ->where('products.product_code', $productCode)
            ->select(
                'products.*',
                'product_categories.category_name',
                'product_categories.category_code',
                'units_of_measure.uom_name',
                'units_of_measure.uom_code',
                'units_of_measure.uom_type'
            )
            ->first();
        
        if (!$product) {
            abort(404, 'Product not found');
        }

        // Log VIEW
        $this->logView(
            'Products - Management',
            "Viewed product: {$product->product_name} (Code: {$productCode})"
        );

        // Get inventory summary (if exists)
        $inventorySummary = DB::table('inventory')
            ->where('product_id', $product->product_id)
            ->select(
                DB::raw('SUM(quantity_on_hand) as total_on_hand'),
                DB::raw('SUM(quantity_reserved) as total_reserved'),
                DB::raw('SUM(quantity_available) as total_available')
            )
            ->first();

        // Get BOM if exists (for finished goods)
        $hasBOM = DB::table('bill_of_materials')
            ->where('product_id', $product->product_id)
            ->where('is_active', true)
            ->exists();

        // Get Recipe if exists (for F&B products)
        $hasRecipe = DB::table('recipes')
            ->where('product_id', $product->product_id)
            ->where('is_active', true)
            ->exists();
        
        return view('admin.products.show', compact('product', 'inventorySummary', 'hasBOM', 'hasRecipe'));
    }

    /**
     * Edit product
     */
    public function edit($productCode)
    {
        $product = DB::table('products')
            ->where('product_code', $productCode)
            ->first();
        
        if (!$product) {
            abort(404, 'Product not found');
        }

        $categories = DB::table('product_categories')
            ->orderBy('category_name')
            ->get();

        $uoms = DB::table('units_of_measure')
            ->orderBy('uom_type')
            ->orderBy('uom_name')
            ->get();
        
        return view('admin.products.edit', compact('product', 'categories', 'uoms'));
    }

    /**
     * Update product
     */
    public function update(Request $request, $productCode)
    {
        $product = DB::table('products')
            ->where('product_code', $productCode)
            ->first();
        
        if (!$product) {
            abort(404, 'Product not found');
        }

        $validated = $request->validate([
            'product_name' => ['required', 'string', 'max:200'],
            'product_type' => ['required', 'in:Raw Material,Semi-Finished,Finished Goods,Packaging,Consumable'],
            'category_id' => ['required', 'exists:product_categories,category_id'],
            'uom_id' => ['required', 'exists:units_of_measure,uom_id'],
            'standard_cost' => ['nullable', 'numeric', 'min:0', 'max:9999999999999.99'],
            'selling_price' => ['nullable', 'numeric', 'min:0', 'max:9999999999999.99'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'product_name.required' => 'Product name is required.',
            'product_type.required' => 'Product type is required.',
            'product_type.in' => 'Invalid product type selected.',
            'category_id.required' => 'Category is required.',
            'category_id.exists' => 'Selected category is invalid.',
            'uom_id.required' => 'Unit of measure is required.',
            'uom_id.exists' => 'Selected unit of measure is invalid.',
            'standard_cost.numeric' => 'Standard cost must be a valid number.',
            'standard_cost.min' => 'Standard cost cannot be negative.',
            'selling_price.numeric' => 'Selling price must be a valid number.',
            'selling_price.min' => 'Selling price cannot be negative.',
        ]);

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'product_name' => $product->product_name,
                'product_type' => $product->product_type,
                'category_id' => $product->category_id,
                'uom_id' => $product->uom_id,
                'standard_cost' => $product->standard_cost,
                'selling_price' => $product->selling_price,
                'description' => $product->description,
                'is_active' => $product->is_active,
            ];

            DB::table('products')
                ->where('product_id', $product->product_id)
                ->update([
                    'product_name' => $validated['product_name'],
                    'product_type' => $validated['product_type'],
                    'category_id' => $validated['category_id'],
                    'uom_id' => $validated['uom_id'],
                    'standard_cost' => $validated['standard_cost'] ?? 0.00,
                    'selling_price' => $validated['selling_price'] ?? 0.00,
                    'description' => $validated['description'] ?? null,
                    'is_active' => $request->boolean('is_active'),
                    'updated_at' => now(),
                ]);

            // Log UPDATE
            $this->logUpdate(
                'Products - Management',
                'products',
                $product->product_id,
                $oldData,
                $validated
            );

            DB::commit();
            
            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    /**
     * Delete product
     */
    public function destroy($productCode)
    {
        $product = DB::table('products')
            ->where('product_code', $productCode)
            ->first();
        
        if (!$product) {
            abort(404, 'Product not found');
        }

        // Check dependencies
        $dependencies = [
            'inventory' => DB::table('inventory')->where('product_id', $product->product_id)->count(),
            'bom_items' => DB::table('bom_items')->where('material_id', $product->product_id)->count(),
            'work_orders' => DB::table('work_orders')->where('product_id', $product->product_id)->count(),
            'sales_order_items' => DB::table('sales_order_items')->where('product_id', $product->product_id)->count(),
            'purchase_order_items' => DB::table('purchase_order_items')->where('product_id', $product->product_id)->count(),
        ];

        $totalDependencies = array_sum($dependencies);

        if ($totalDependencies > 0) {
            return back()->with('error', 'Cannot delete product that has related records (inventory, orders, BOM, etc.)');
        }

        DB::beginTransaction();
        try {
            // Capture data before deletion
            $oldData = [
                'product_code' => $product->product_code,
                'product_name' => $product->product_name,
                'product_type' => $product->product_type,
                'category_id' => $product->category_id,
                'standard_cost' => $product->standard_cost,
                'selling_price' => $product->selling_price,
            ];

            DB::table('products')
                ->where('product_id', $product->product_id)
                ->delete();

            // Log DELETE
            $this->logDelete(
                'Products - Management',
                'products',
                $product->product_id,
                $oldData
            );

            DB::commit();
            
            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product deleted successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }

    /**
     * Toggle product status
     */
    public function toggleStatus($productCode)
    {
        $product = DB::table('products')
            ->where('product_code', $productCode)
            ->first();
        
        if (!$product) {
            abort(404, 'Product not found');
        }

        DB::beginTransaction();
        try {
            $newStatus = !$product->is_active;

            DB::table('products')
                ->where('product_id', $product->product_id)
                ->update([
                    'is_active' => $newStatus,
                    'updated_at' => now(),
                ]);

            // Log status change
            $this->logActivity(
                'Status Changed',
                "Product '{$product->product_name}' " . ($newStatus ? 'activated' : 'deactivated'),
                'Products - Management'
            );

            DB::commit();
            
            $status = $newStatus ? 'activated' : 'deactivated';
            return back()->with('success', "Product {$status} successfully");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update product status: ' . $e->getMessage());
        }
    }

    /**
     * Bulk update prices
     */
    public function bulkUpdatePrices(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => ['required', 'array', 'min:1'],
            'product_ids.*' => ['exists:products,product_id'],
            'price_type' => ['required', 'in:standard_cost,selling_price'],
            'adjustment_type' => ['required', 'in:percentage,fixed'],
            'adjustment_value' => ['required', 'numeric'],
        ]);

        DB::beginTransaction();
        try {
            $products = DB::table('products')
                ->whereIn('product_id', $validated['product_ids'])
                ->get();

            foreach ($products as $product) {
                $currentPrice = $product->{$validated['price_type']};
                
                if ($validated['adjustment_type'] === 'percentage') {
                    $newPrice = $currentPrice * (1 + ($validated['adjustment_value'] / 100));
                } else {
                    $newPrice = $currentPrice + $validated['adjustment_value'];
                }

                $newPrice = max(0, $newPrice); // Ensure non-negative

                DB::table('products')
                    ->where('product_id', $product->product_id)
                    ->update([
                        $validated['price_type'] => $newPrice,
                        'updated_at' => now(),
                    ]);

                // Log each price update
                $this->logActivity(
                    'Bulk Price Update',
                    "Updated {$validated['price_type']} for '{$product->product_name}' from {$currentPrice} to {$newPrice}",
                    'Products - Management'
                );
            }

            DB::commit();
            
            return back()->with('success', 'Prices updated successfully for ' . count($products) . ' products');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update prices: ' . $e->getMessage());
        }
    }

    /**
     * Export products to CSV
     */
    public function export(Request $request)
    {
        $this->logExport('Products - Management', 'Exported products list to CSV');

        $query = DB::table('products')
            ->join('product_categories', 'products.category_id', '=', 'product_categories.category_id')
            ->join('units_of_measure', 'products.uom_id', '=', 'units_of_measure.uom_id')
            ->select(
                'products.product_code',
                'products.product_name',
                'products.product_type',
                'product_categories.category_name',
                'units_of_measure.uom_name',
                'products.standard_cost',
                'products.selling_price',
                'products.is_active'
            );

        // Apply same filters as index
        if ($request->filled('product_type')) {
            $query->where('products.product_type', $request->product_type);
        }
        if ($request->filled('category_id')) {
            $query->where('products.category_id', $request->category_id);
        }
        if ($request->filled('is_active')) {
            $query->where('products.is_active', $request->boolean('is_active'));
        }

        $products = $query->orderBy('products.product_name')
            ->limit(10000)
            ->get();

        $filename = 'products_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'Product Code',
                'Product Name',
                'Type',
                'Category',
                'UOM',
                'Standard Cost',
                'Selling Price',
                'Status'
            ]);
            
            // Data
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->product_code,
                    $product->product_name,
                    $product->product_type,
                    $product->category_name,
                    $product->uom_name,
                    $product->standard_cost,
                    $product->selling_price,
                    $product->is_active ? 'Active' : 'Inactive',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
