<?php

namespace App\Http\Controllers\Inventory;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecipeController extends Controller
{
    //
    use LogsActivity;
    
    /**
     * Display a listing of recipes
     */
    public function index(Request $request)
    {
        $query = DB::table('recipes as r')
            ->join('products as p', 'r.product_id', '=', 'p.product_id')
            ->join('units_of_measure as uom', 'r.uom_id', '=', 'uom.uom_id')
            ->select(
                'r.recipe_id',
                'r.recipe_code',
                'r.recipe_name',
                'r.recipe_version',
                'r.batch_size',
                'r.preparation_time',
                'r.cooking_time',
                'r.is_active',
                'r.created_at',
                'p.product_id',
                'p.product_code',
                'p.product_name',
                'uom.uom_code',
                DB::raw('(SELECT COUNT(*) FROM recipe_ingredients WHERE recipe_id = r.recipe_id) as ingredients_count'),
                DB::raw('(COALESCE(r.preparation_time, 0) + COALESCE(r.cooking_time, 0)) as total_time')
            );
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('r.recipe_code', 'like', "%{$search}%")
                ->orWhere('r.recipe_name', 'like', "%{$search}%")
                ->orWhere('p.product_name', 'like', "%{$search}%")
                ->orWhere('p.product_code', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('r.is_active', $request->status === 'active' ? 1 : 0);
        }
        
        // Filter by time range
        if ($request->filled('time_filter')) {
            switch ($request->time_filter) {
                case 'quick': // Under 30 mins
                    $query->havingRaw('(COALESCE(r.preparation_time, 0) + COALESCE(r.cooking_time, 0)) < 30');
                    break;
                case 'medium': // 30-60 mins
                    $query->havingRaw('(COALESCE(r.preparation_time, 0) + COALESCE(r.cooking_time, 0)) BETWEEN 30 AND 60');
                    break;
                case 'long': // Over 60 mins
                    $query->havingRaw('(COALESCE(r.preparation_time, 0) + COALESCE(r.cooking_time, 0)) > 60');
                    break;
            }
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'recipe_name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        // Validate sort order
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? strtolower($sortOrder) : 'desc';
        
        // Whitelist allowed sort columns
        $allowedSort = [
            'recipe_code',
            'recipe_name', 
            'product_name',
            'total_time',
            'ingredients_count',
            'created_at'
        ];
        
        if (in_array($sortBy, $allowedSort)) {
            if ($sortBy === 'total_time') {
                $query->orderByRaw("(COALESCE(r.preparation_time, 0) + COALESCE(r.cooking_time, 0)) {$sortOrder}");
            } elseif ($sortBy === 'product_name') {
                $query->orderBy('p.product_name', $sortOrder);
            } elseif ($sortBy === 'recipe_code') {
                $query->orderBy('r.recipe_code', $sortOrder);
            } elseif ($sortBy === 'recipe_name') {
                $query->orderBy('r.recipe_name', $sortOrder);
            } elseif ($sortBy === 'created_at') {
                $query->orderBy('r.created_at', $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            $query->orderByDesc('r.created_at');
        }
        
        $recipes = $query->paginate(20)->withQueryString();
        
        return view('inventory.recipes.index', compact('recipes'));
    }

    /**
     * Show the form for creating a new recipe
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
        
        // Get raw materials and semi-finished for ingredients
        $materials = DB::table('products')
            ->join('units_of_measure as uom', 'products.uom_id', '=', 'uom.uom_id')
            ->whereIn('products.product_type', ['Raw Material', 'Semi-Finished'])
            ->where('products.is_active', 1)
            ->select(
                'products.product_id',
                'products.product_code',
                'products.product_name',
                'products.product_type',
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
        
        return view('inventory.recipes.create', compact('products', 'materials', 'uoms'));
    }

    /**
     * Store a newly created recipe
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,product_id'],
            'recipe_name' => ['required', 'string', 'max:200'],
            'recipe_version' => ['required', 'string', 'max:20'],
            'instructions' => ['nullable', 'string'],
            'batch_size' => ['required', 'numeric', 'min:0.0001'],
            'uom_id' => ['required', 'exists:units_of_measure,uom_id'],
            'preparation_time' => ['nullable', 'numeric', 'min:0'],
            'cooking_time' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
            
            // Recipe Ingredients
            'ingredients' => ['required', 'array', 'min:1'],
            'ingredients.*.material_id' => ['required', 'exists:products,product_id'],
            'ingredients.*.quantity' => ['required', 'numeric', 'min:0.0001'],
            'ingredients.*.uom_id' => ['required', 'exists:units_of_measure,uom_id'],
            'ingredients.*.preparation_notes' => ['nullable', 'string'],
            'ingredients.*.sequence_order' => ['nullable', 'integer', 'min:1'],
        ], [
            'product_id.required' => 'Please select a product.',
            'product_id.exists' => 'Selected product is invalid.',
            
            'recipe_name.required' => 'Recipe name is required.',
            'recipe_name.max' => 'Recipe name cannot exceed 200 characters.',
            
            'recipe_version.required' => 'Recipe version is required.',
            
            'batch_size.required' => 'Batch size is required.',
            'batch_size.numeric' => 'Batch size must be a number.',
            'batch_size.min' => 'Batch size must be greater than 0.',
            
            'uom_id.required' => 'Unit of measure is required.',
            'uom_id.exists' => 'Selected unit is invalid.',
            
            'ingredients.required' => 'Recipe must have at least one ingredient.',
            'ingredients.min' => 'Recipe must have at least one ingredient.',
            
            'ingredients.*.material_id.required' => 'Material is required for each ingredient.',
            'ingredients.*.material_id.exists' => 'Selected material is invalid.',
            
            'ingredients.*.quantity.required' => 'Quantity is required for each ingredient.',
            'ingredients.*.quantity.numeric' => 'Quantity must be a number.',
            'ingredients.*.quantity.min' => 'Quantity must be greater than 0.',
            
            'ingredients.*.uom_id.required' => 'Unit of measure is required for each ingredient.',
            'ingredients.*.uom_id.exists' => 'Selected unit is invalid.',
        ]);
        
        DB::beginTransaction();
        try {
            // Generate unique Recipe code
            $recipeCode = CodeGeneratorHelper::generateRecipeCode();
            
            // Get product info for logging
            $product = DB::table('products')->where('product_id', $validated['product_id'])->first();
            
            // Insert Recipe
            $recipeId = DB::table('recipes')->insertGetId([
                'recipe_code' => $recipeCode,
                'product_id' => $validated['product_id'],
                'recipe_name' => $validated['recipe_name'],
                'recipe_version' => $validated['recipe_version'],
                'instructions' => $validated['instructions'] ?? null,
                'batch_size' => $validated['batch_size'],
                'uom_id' => $validated['uom_id'],
                'preparation_time' => $validated['preparation_time'] ?? 0,
                'cooking_time' => $validated['cooking_time'] ?? 0,
                'is_active' => $request->has('is_active') ? 1 : 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Insert Recipe Ingredients
            $ingredientsData = [];
            foreach ($validated['ingredients'] as $index => $ingredient) {
                $ingredientsData[] = [
                    'recipe_id' => $recipeId,
                    'material_id' => $ingredient['material_id'],
                    'quantity' => $ingredient['quantity'],
                    'uom_id' => $ingredient['uom_id'],
                    'preparation_notes' => $ingredient['preparation_notes'] ?? null,
                    'sequence_order' => $ingredient['sequence_order'] ?? ($index + 1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            DB::table('recipe_ingredients')->insert($ingredientsData);
            
            // Log CREATE
            $this->logCreate(
                'Inventory - Recipe',
                'recipes',
                $recipeId,
                [
                    'recipe_code' => $recipeCode,
                    'recipe_name' => $validated['recipe_name'],
                    'product_name' => $product->product_name,
                    'product_code' => $product->product_code,
                    'recipe_version' => $validated['recipe_version'],
                    'batch_size' => $validated['batch_size'],
                    'preparation_time' => $validated['preparation_time'] ?? 0,
                    'cooking_time' => $validated['cooking_time'] ?? 0,
                    'total_time' => ($validated['preparation_time'] ?? 0) + ($validated['cooking_time'] ?? 0),
                    'is_active' => $request->has('is_active') ? 1 : 0,
                    'ingredients_count' => count($validated['ingredients']),
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('inventory.recipes.show', $recipeCode)
                ->with('success', 'Recipe created successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create recipe: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified recipe
     */
    public function show($recipeCode)
    {
        $recipe = DB::table('recipes as r')
            ->join('products as p', 'r.product_id', '=', 'p.product_id')
            ->join('units_of_measure as uom', 'r.uom_id', '=', 'uom.uom_id')
            ->where('r.recipe_code', $recipeCode)
            ->select(
                'r.*',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'uom.uom_code',
                'uom.uom_name',
                DB::raw('(r.preparation_time + r.cooking_time) as total_time')
            )
            ->first();
        
        if (!$recipe) {
            abort(404, 'Recipe not found');
        }
        
        // Log VIEW
        $this->logView(
            'Inventory - Recipe',
            "Viewed recipe: {$recipe->recipe_name} (Code: {$recipe->recipe_name})"
        );
        
        // Get Recipe ingredients with material details
        $ingredients = DB::table('recipe_ingredients as ri')
            ->join('products as m', 'ri.material_id', '=', 'm.product_id')
            ->join('units_of_measure as uom', 'ri.uom_id', '=', 'uom.uom_id')
            ->where('ri.recipe_id', $recipe->recipe_id)
            ->select(
                'ri.*',
                'm.product_code as material_code',
                'm.product_name as material_name',
                'm.product_type as material_type',
                'uom.uom_code',
                'uom.uom_name'
            )
            ->orderBy('ri.sequence_order')
            ->get();
        
        return view('inventory.recipes.show', compact('recipe', 'ingredients'));
    }

    /**
     * Show the form for editing the specified recipe
     */
    public function edit($recipeCode)
    {
        $recipe = DB::table('recipes')
            ->where('recipe_code', $recipeCode)
            ->first();
        
        if (!$recipe) {
            abort(404, 'Recipe not found');
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
        
        // Get existing ingredients
        $recipeIngredients = DB::table('recipe_ingredients')
            ->where('recipe_id', $recipe->recipe_id)
            ->orderBy('sequence_order')
            ->get();
        
        return view('inventory.recipes.edit', compact('recipe', 'products', 'materials', 'uoms', 'recipeIngredients'));
    }

    /**
     * Update the specified recipe
     */
    public function update(Request $request, $recipeCode)
    {
        $recipe = DB::table('recipes')->where('recipe_code', $recipeCode)->first();
        
        if (!$recipe) {
            abort(404, 'Recipe not found');
        }
        
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,product_id'],
            'recipe_name' => ['required', 'string', 'max:200'],
            'recipe_version' => ['required', 'string', 'max:20'],
            'instructions' => ['nullable', 'string'],
            'batch_size' => ['required', 'numeric', 'min:0.0001'],
            'uom_id' => ['required', 'exists:units_of_measure,uom_id'],
            'preparation_time' => ['nullable', 'numeric', 'min:0'],
            'cooking_time' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
            
            'ingredients' => ['required', 'array', 'min:1'],
            'ingredients.*.material_id' => ['required', 'exists:products,product_id'],
            'ingredients.*.quantity' => ['required', 'numeric', 'min:0.0001'],
            'ingredients.*.uom_id' => ['required', 'exists:units_of_measure,uom_id'],
            'ingredients.*.preparation_notes' => ['nullable', 'string'],
            'ingredients.*.sequence_order' => ['nullable', 'integer', 'min:1'],
        ]);
        
        DB::beginTransaction();
        try {
            // Capture OLD data
            $oldRecipe = DB::table('recipes as r')
                ->join('products as p', 'r.product_id', '=', 'p.product_id')
                ->where('r.recipe_id', $recipe->recipe_id)
                ->select('r.*', 'p.product_name', 'p.product_code')
                ->first();
            
            $oldIngredientsCount = DB::table('recipe_ingredients')
                ->where('recipe_id', $recipe->recipe_id)
                ->count();
            
            // Get new product info
            $newProduct = DB::table('products')->where('product_id', $validated['product_id'])->first();
            
            // Update Recipe
            DB::table('recipes')
                ->where('recipe_id', $recipe->recipe_id)
                ->update([
                    'product_id' => $validated['product_id'],
                    'recipe_name' => $validated['recipe_name'],
                    'recipe_version' => $validated['recipe_version'],
                    'instructions' => $validated['instructions'] ?? null,
                    'batch_size' => $validated['batch_size'],
                    'uom_id' => $validated['uom_id'],
                    'preparation_time' => $validated['preparation_time'] ?? 0,
                    'cooking_time' => $validated['cooking_time'] ?? 0,
                    'is_active' => $request->has('is_active') ? 1 : 0,
                    'updated_at' => now(),
                ]);
            
            // Delete old ingredients and insert new ones
            DB::table('recipe_ingredients')->where('recipe_id', $recipe->recipe_id)->delete();
            
            $ingredientsData = [];
            foreach ($validated['ingredients'] as $index => $ingredient) {
                $ingredientsData[] = [
                    'recipe_id' => $recipe->recipe_id,
                    'material_id' => $ingredient['material_id'],
                    'quantity' => $ingredient['quantity'],
                    'uom_id' => $ingredient['uom_id'],
                    'preparation_notes' => $ingredient['preparation_notes'] ?? null,
                    'sequence_order' => $ingredient['sequence_order'] ?? ($index + 1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            DB::table('recipe_ingredients')->insert($ingredientsData);
            
            // Log UPDATE
            $this->logUpdate(
                'Inventory - Recipe',
                'recipes',
                $recipe->recipe_id,
                [
                    'recipe_code' => $oldRecipe->recipe_code,
                    'recipe_name' => $oldRecipe->recipe_name,
                    'product_name' => $oldRecipe->product_name,
                    'product_code' => $oldRecipe->product_code,
                    'recipe_version' => $oldRecipe->recipe_version,
                    'batch_size' => $oldRecipe->batch_size,
                    'preparation_time' => $oldRecipe->preparation_time,
                    'cooking_time' => $oldRecipe->cooking_time,
                    'total_time' => $oldRecipe->preparation_time + $oldRecipe->cooking_time,
                    'is_active' => $oldRecipe->is_active,
                    'ingredients_count' => $oldIngredientsCount,
                ],
                [
                    'recipe_code' => $recipe->recipe_code,
                    'recipe_name' => $validated['recipe_name'],
                    'product_name' => $newProduct->product_name,
                    'product_code' => $newProduct->product_code,
                    'recipe_version' => $validated['recipe_version'],
                    'batch_size' => $validated['batch_size'],
                    'preparation_time' => $validated['preparation_time'] ?? 0,
                    'cooking_time' => $validated['cooking_time'] ?? 0,
                    'total_time' => ($validated['preparation_time'] ?? 0) + ($validated['cooking_time'] ?? 0),
                    'is_active' => $request->has('is_active') ? 1 : 0,
                    'ingredients_count' => count($validated['ingredients']),
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('inventory.recipes.show', $recipeCode)
                ->with('success', 'Recipe updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update recipe: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified recipe
     */
    public function destroy($recipeCode)
    {
        $recipe = DB::table('recipes as r')
            ->join('products as p', 'r.product_id', '=', 'p.product_id')
            ->where('r.recipe_code', $recipeCode)
            ->select('r.*', 'p.product_name', 'p.product_code')
            ->first();
        
        if (!$recipe) {
            abort(404, 'Recipe not found');
        }
        
        DB::beginTransaction();
        try {
            $ingredientsCount = DB::table('recipe_ingredients')
                ->where('recipe_id', $recipe->recipe_id)
                ->count();
            
            // Delete recipe ingredients first
            DB::table('recipe_ingredients')->where('recipe_id', $recipe->recipe_id)->delete();
            
            // Delete recipe
            DB::table('recipes')->where('recipe_id', $recipe->recipe_id)->delete();
            
            // Log DELETE
            $this->logDelete(
                'Inventory - Recipe',
                'recipes',
                $recipe->recipe_id,
                [
                    'recipe_code' => $recipe->recipe_code,
                    'recipe_name' => $recipe->recipe_name,
                    'product_name' => $recipe->product_name,
                    'product_code' => $recipe->product_code,
                    'recipe_version' => $recipe->recipe_version,
                    'batch_size' => $recipe->batch_size,
                    'preparation_time' => $recipe->preparation_time,
                    'cooking_time' => $recipe->cooking_time,
                    'total_time' => $recipe->preparation_time + $recipe->cooking_time,
                    'is_active' => $recipe->is_active,
                    'ingredients_count' => $ingredientsCount,
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('inventory.recipes.index')
                ->with('success', 'Recipe deleted successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete recipe: ' . $e->getMessage());
        }
    }
    
    /**
     * Export Recipes to CSV/Excel
     */
    public function export()
    {
        $this->logExport('Inventory - Recipe', 'Exported recipes list');
        
        // Your export logic here
    }
    
    /**
     * Print Recipe Card
     */
    public function print($recipeCode)
    {
        $recipe = DB::table('recipes as r')
            ->join('products as p', 'r.product_id', '=', 'p.product_id')
            ->join('units_of_measure as uom', 'r.uom_id', '=', 'uom.uom_id')
            ->where('r.recipe_code', $recipeCode)
            ->select('r.*', 'p.product_name', 'uom.uom_name')
            ->first();
        
        if (!$recipe) {
            abort(404, 'Recipe not found');
        }
        
        $this->logPrint('Inventory - Recipe', "Printed recipe card: {$recipe->recipe_code}");
        
        // Return print view
        return view('inventory.recipes.print', compact('recipe'));
    }
}
