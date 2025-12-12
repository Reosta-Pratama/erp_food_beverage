<?php

namespace App\Http\Controllers\Products;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    //
     use LogsActivity;

    /**
     * List product categories 
     */
    public function index(Request $request)
    {
        $query = DB::table('product_categories as pc')
            ->leftJoin('product_categories as parent', 'pc.parent_category_id', '=', 'parent.category_id')
            ->leftJoin('products', 'pc.category_id', '=', 'products.category_id')
            ->select(
                'pc.category_id',
                'pc.category_code',
                'pc.category_name',
                'pc.parent_category_id',
                'pc.description',
                'pc.created_at',
                'parent.category_name as parent_name',
                DB::raw('COUNT(DISTINCT products.product_id) as products_count')
            )
            ->groupBy(
                'pc.category_id',
                'pc.category_code',
                'pc.category_name',
                'pc.parent_category_id',
                'pc.description',
                'pc.created_at',
                'parent.category_name'
            );
        
        // Search filter (multi-column)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('pc.category_name', 'like', "%{$search}%")
                ->orWhere('pc.category_code', 'like', "%{$search}%")
                ->orWhere('pc.description', 'like', "%{$search}%")
                ->orWhere('parent.category_name', 'like', "%{$search}%");
            });
        }
        
        // Filter by parent category
        if ($request->filled('parent')) {
            if ($request->input('parent') === 'root') {
                // Root categories (no parent)
                $query->whereNull('pc.parent_category_id');
            } elseif ($request->input('parent') === 'child') {
                // Child categories (has parent)
                $query->whereNotNull('pc.parent_category_id');
            } elseif (is_numeric($request->input('parent'))) {
                // Specific parent category
                $query->where('pc.parent_category_id', $request->input('parent'));
            }
        }
        
        // Filter by product count
        if ($request->filled('product_filter')) {
            switch ($request->input('product_filter')) {
                case 'empty': // No products
                    $query->havingRaw('COUNT(DISTINCT products.product_id) = 0');
                    break;
                case 'has_products': // Has products
                    $query->havingRaw('COUNT(DISTINCT products.product_id) > 0');
                    break;
                case 'active': // Has 5+ products (active category)
                    $query->havingRaw('COUNT(DISTINCT products.product_id) >= 5');
                    break;
            }
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'hierarchy');
        $sortOrder = $request->get('sort_order', 'asc');
        
        // Validate sort order
        $sortOrder = \in_array(strtolower($sortOrder), ['asc', 'desc']) ? strtolower($sortOrder) : 'asc';
        
        // Whitelist allowed sort columns
        $allowedSort = [
            'category_code',
            'category_name',
            'parent_name',
            'products_count',
            'created_at',
            'hierarchy'
        ];
        
        if (\in_array($sortBy, $allowedSort)) {
            if ($sortBy === 'hierarchy') {
                // Special: Hierarchical sorting (parent first, then children)
                $query->orderByRaw('COALESCE(pc.parent_category_id, pc.category_id)')
                    ->orderBy('pc.category_name', 'asc');
            } elseif ($sortBy === 'category_code') {
                $query->orderBy('pc.category_code', $sortOrder);
            } elseif ($sortBy === 'category_name') {
                $query->orderBy('pc.category_name', $sortOrder);
            } elseif ($sortBy === 'parent_name') {
                // Computed column - NULL handling
                $query->orderBy(DB::raw('COALESCE(parent.category_name, "")'), $sortOrder);
            } elseif ($sortBy === 'products_count') {
                // Aggregated column
                $query->orderByRaw("COUNT(DISTINCT products.product_id) {$sortOrder}");
            } elseif ($sortBy === 'created_at') {
                $query->orderBy('pc.created_at', $sortOrder);
            }
        } else {
            // Fallback to default hierarchical sorting
            $query->orderByRaw('COALESCE(pc.parent_category_id, pc.category_id)')
                ->orderBy('pc.category_name', 'asc');
        }
        
        // Add secondary sort for consistency
        if ($sortBy !== 'hierarchy' && $sortBy !== 'category_name') {
            $query->orderBy('pc.category_name', 'asc');
        }
        
        $categories = $query->paginate(10)->withQueryString();
        
        $parentCategories = DB::table('product_categories')
            ->whereNull('parent_category_id')
            ->select('category_id', 'category_code', 'category_name')
            ->orderBy('category_name')
            ->get();
        
        return view('admin.products-materials.categories.index', compact('categories', 'parentCategories'));
    }

    /**
     * Create form
     */
    public function create()
    {
        $parentCategories = DB::table('product_categories')
            ->whereNull('parent_category_id')
            ->select('category_id', 'category_code', 'category_name')
            ->orderBy('category_name')
            ->get();
        
        return view('admin.products-materials.categories.create', compact('parentCategories'));
    }

    /**
     * Store product category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => ['required', 'string', 'max:150', 'unique:product_categories,category_name'],
            'parent_category_id' => ['nullable', 'exists:product_categories,category_id'],
            'description' => ['nullable', 'string'],
        ], [
            'category_name.required' => 'Please enter a category name.',
            'category_name.string' => 'Category name must be a valid text.',
            'category_name.max' => 'Category name may not be longer than 150 characters.',
            'category_name.unique' => 'This category name is already in use.',
            'parent_category_id.exists' => 'The selected parent category does not exist.',
            'description.string' => 'Description must be a valid text.',
        ]);

        DB::beginTransaction();
        try {
            $categoryCode = CodeGeneratorHelper::generateProductCategoryCode();

            $categoryId = DB::table('product_categories')->insertGetId([
                'category_code' => $categoryCode,
                'category_name' => $validated['category_name'],
                'parent_category_id' => $validated['parent_category_id'] ?? null,
                'description' => $validated['description'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Log CREATE
            $this->logCreate(
                'Products - Categories',
                'product_categories',
                $categoryId,
                $validated
            );

            DB::commit();
            
            return redirect()
                ->route('admin.products-materials.categories.index')
                ->with('success', 'Product category created successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create category: ' . $e->getMessage());
        }
    }

    /**
     * Show category details 
     */
    public function show($categoryCode)
    {
        $category = DB::table('product_categories as pc')
            ->leftJoin('product_categories as parent', 'pc.parent_category_id', '=', 'parent.category_id')
            ->where('pc.category_code', $categoryCode)
            ->select(
                'pc.*',
                'parent.category_name as parent_name',
                'parent.category_code as parent_code'
            )
            ->first();
        
        if (!$category) {
            abort(404, 'Product category not found');
        }

        // Log VIEW
        $this->logView(
            'Products - Categories',
            "Viewed category: {$category->category_name} (Code: {$categoryCode})"
        );

        // Get products in this category
        $products = DB::table('products')
            ->join('units_of_measure', 'products.uom_id', '=', 'units_of_measure.uom_id')
            ->where('products.category_id', $category->category_id)
            ->select(
                'products.product_id',
                'products.product_code',
                'products.product_name',
                'products.product_type',
                'products.standard_cost',
                'products.selling_price',
                'products.is_active',
                'units_of_measure.uom_name'
            )
            ->orderBy('products.product_name')
            ->get();

        // Get subcategories
        $subcategories = DB::table('product_categories')
            ->where('parent_category_id', $category->category_id)
            ->orderBy('category_name')
            ->get();
        
        return view('admin.products-materials.categories.show', compact('category', 'products', 'subcategories'));
    }

    /**
     * Edit category
     */
    public function edit($categoryCode)
    {
        $category = DB::table('product_categories')
            ->where('category_code', $categoryCode)
            ->first();
        
        if (!$category) {
            abort(404, 'Product category not found');
        }

        // Get parent categories (exclude self and descendants to prevent circular reference)
        $parentCategories = DB::table('product_categories')
            ->whereNull('parent_category_id')
            ->where('category_id', '!=', $category->category_id)
            ->orderBy('category_name')
            ->get();
        
        return view('admin.products-materials.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update category
     */
    public function update(Request $request, $categoryCode)
    {
        $category = DB::table('product_categories')
            ->where('category_code', $categoryCode)
            ->first();
        
        if (!$category) {
            abort(404, 'Product category not found');
        }

        $validated = $request->validate([
            'category_name' => [
                'required',
                'string',
                'max:150',
                'unique:product_categories,category_name,' . $category->category_id . ',category_id'
            ],
            'parent_category_id' => ['nullable', 'exists:product_categories,category_id'],
            'description' => ['nullable', 'string'],
        ], [
            'category_name.required' => 'Please enter a category name.',
            'category_name.unique' => 'The category name is already in use.',
            'category_name.max' => 'The category name may not be longer than 150 characters.',
            'parent_category_id.exists' => 'The selected parent category is not valid.',
            'description.string' => 'The description must be a valid text.',
        ]);


        // Prevent setting self as parent
        if (isset($validated['parent_category_id']) && $validated['parent_category_id'] == $category->category_id) {
            return back()
                ->withInput()
                ->with('error', 'Category cannot be its own parent');
        }

        DB::beginTransaction();
        try {
            $oldData = [
                'category_name' => $category->category_name,
                'parent_category_id' => $category->parent_category_id,
                'description' => $category->description,
            ];

            DB::table('product_categories')
                ->where('category_id', $category->category_id)
                ->update([
                    'category_name' => $validated['category_name'],
                    'parent_category_id' => $validated['parent_category_id'] ?? null,
                    'description' => $validated['description'] ?? null,
                    'updated_at' => now(),
                ]);

            // Log UPDATE
            $this->logUpdate(
                'Products - Categories',
                'product_categories',
                $category->category_id,
                $oldData,
                $validated
            );

            DB::commit();
            
            return redirect()
                ->route('admin.products-materials.categories.index')
                ->with('success', 'Product category updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update category: ' . $e->getMessage());
        }
    }

    /**
     * Delete category
     */
    public function destroy($categoryCode)
    {
        $category = DB::table('product_categories')
            ->where('category_code', $categoryCode)
            ->first();
        
        if (!$category) {
            abort(404, 'Product category not found');
        }

        // Check if category has products
        $productsCount = DB::table('products')
            ->where('category_id', $category->category_id)
            ->count();

        if ($productsCount > 0) {
            return back()->with('error', 'Cannot delete category that has products assigned');
        }

        // Check if category has subcategories
        $subcategoriesCount = DB::table('product_categories')
            ->where('parent_category_id', $category->category_id)
            ->count();

        if ($subcategoriesCount > 0) {
            return back()->with('error', 'Cannot delete category that has subcategories');
        }

        DB::beginTransaction();
        try {
            $oldData = [
                'category_code' => $category->category_code,
                'category_name' => $category->category_name,
                'parent_category_id' => $category->parent_category_id,
                'description' => $category->description,
            ];

            DB::table('product_categories')
                ->where('category_id', $category->category_id)
                ->delete();

            // Log DELETE
            $this->logDelete(
                'Products - Categories',
                'product_categories',
                $category->category_id,
                $oldData
            );

            DB::commit();
            
            return redirect()
                ->route('admin.products-materials.categories.index')
                ->with('success', 'Product category deleted successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete category: ' . $e->getMessage());
        }
    }
}
