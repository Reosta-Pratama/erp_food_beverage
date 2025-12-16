<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecipeBOMModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $this->seedBOMs();
        $this->seedRecipes();
    }

    /**
     * Seed BOM
     */
    private function seedBOMs(): void {
        $now = Carbon::now();
        
        // ====================================
        // 1. BILL OF MATERIALS (BOM)
        // ====================================
        $boms = [
            [
                'bom_id' => 1,
                'bom_code' => 'BOM-001',
                'product_id' => 1, // Orange Juice 1L (Finished Goods)
                'bom_version' => '1.0',
                'effective_date' => '2024-01-01',
                'is_active' => 1,
                'notes' => 'Standard BOM for Orange Juice 1L production',
                'created_at' => $now,
            ],
            [
                'bom_id' => 2,
                'bom_code' => 'BOM-002',
                'product_id' => 2, // Apple Juice 1L (Finished Goods)
                'bom_version' => '1.0',
                'effective_date' => '2024-01-01',
                'is_active' => 1,
                'notes' => 'Standard BOM for Apple Juice 1L production',
                'created_at' => $now,
            ],
            [
                'bom_id' => 3,
                'bom_code' => 'BOM-003',
                'product_id' => 3, // Mixed Fruit Juice 1L (Finished Goods)
                'bom_version' => '1.0',
                'effective_date' => '2024-01-01',
                'is_active' => 1,
                'notes' => 'Standard BOM for Mixed Fruit Juice 1L production',
                'created_at' => $now,
            ],
        ];
        
        DB::table('bill_of_materials')->insert($boms);
        
        // ====================================
        // 2. BOM ITEMS (Materials Required)
        // ====================================
        $bomItems = [
            // BOM-001: Orange Juice 1L
            [
                'bom_item_id' => 1,
                'bom_id' => 1,
                'material_id' => 4, // Fresh Orange (Raw Material)
                'quantity_required' => 5.00,
                'uom_id' => 2, // Kg
                'item_type' => 'Raw Material',
                'scrap_percentage' => 10.00,
                'created_at' => $now,
            ],
            [
                'bom_item_id' => 2,
                'bom_id' => 1,
                'material_id' => 5, // Sugar (Raw Material)
                'quantity_required' => 0.15,
                'uom_id' => 2, // Kg
                'item_type' => 'Raw Material',
                'scrap_percentage' => 0.00,
                'created_at' => $now,
            ],
            [
                'bom_item_id' => 3,
                'bom_id' => 1,
                'material_id' => 6, // Water (Raw Material)
                'quantity_required' => 0.30,
                'uom_id' => 1, // Liter
                'item_type' => 'Raw Material',
                'scrap_percentage' => 0.00,
                'created_at' => $now,
            ],
            [
                'bom_item_id' => 4,
                'bom_id' => 1,
                'material_id' => 7, // Preservative (Raw Material)
                'quantity_required' => 0.005,
                'uom_id' => 2, // Kg
                'item_type' => 'Raw Material',
                'scrap_percentage' => 0.00,
                'created_at' => $now,
            ],
            
            // BOM-002: Apple Juice 1L
            [
                'bom_item_id' => 5,
                'bom_id' => 2,
                'material_id' => 8, // Fresh Apple (Raw Material)
                'quantity_required' => 6.00,
                'uom_id' => 2, // Kg
                'item_type' => 'Raw Material',
                'scrap_percentage' => 15.00,
                'created_at' => $now,
            ],
            [
                'bom_item_id' => 6,
                'bom_id' => 2,
                'material_id' => 5, // Sugar
                'quantity_required' => 0.20,
                'uom_id' => 2, // Kg
                'item_type' => 'Raw Material',
                'scrap_percentage' => 0.00,
                'created_at' => $now,
            ],
            [
                'bom_item_id' => 7,
                'bom_id' => 2,
                'material_id' => 6, // Water
                'quantity_required' => 0.25,
                'uom_id' => 1, // Liter
                'item_type' => 'Raw Material',
                'scrap_percentage' => 0.00,
                'created_at' => $now,
            ],
            [
                'bom_item_id' => 8,
                'bom_id' => 2,
                'material_id' => 7, // Preservative
                'quantity_required' => 0.005,
                'uom_id' => 2, // Kg
                'item_type' => 'Raw Material',
                'scrap_percentage' => 0.00,
                'created_at' => $now,
            ],
            
            // BOM-003: Mixed Fruit Juice 1L
            [
                'bom_item_id' => 9,
                'bom_id' => 3,
                'material_id' => 4, // Fresh Orange
                'quantity_required' => 2.50,
                'uom_id' => 2, // Kg
                'item_type' => 'Raw Material',
                'scrap_percentage' => 10.00,
                'created_at' => $now,
            ],
            [
                'bom_item_id' => 10,
                'bom_id' => 3,
                'material_id' => 8, // Fresh Apple
                'quantity_required' => 2.50,
                'uom_id' => 2, // Kg
                'item_type' => 'Raw Material',
                'scrap_percentage' => 15.00,
                'created_at' => $now,
            ],
            [
                'bom_item_id' => 11,
                'bom_id' => 3,
                'material_id' => 5, // Sugar
                'quantity_required' => 0.18,
                'uom_id' => 2, // Kg
                'item_type' => 'Raw Material',
                'scrap_percentage' => 0.00,
                'created_at' => $now,
            ],
            [
                'bom_item_id' => 12,
                'bom_id' => 3,
                'material_id' => 6, // Water
                'quantity_required' => 0.28,
                'uom_id' => 1, // Liter
                'item_type' => 'Raw Material',
                'scrap_percentage' => 0.00,
                'created_at' => $now,
            ],
            [
                'bom_item_id' => 13,
                'bom_id' => 3,
                'material_id' => 7, // Preservative
                'quantity_required' => 0.005,
                'uom_id' => 2, // Kg
                'item_type' => 'Raw Material',
                'scrap_percentage' => 0.00,
                'created_at' => $now,
            ],
        ];
        
        DB::table('bom_items')->insert($bomItems);
    }

    /**
     * Seed Recipe
     */
    private function seedRecipes(): void {
        $now = Carbon::now();

        // ====================================
        // 3. RECIPES (F&B Specific)
        // ====================================
        $recipes = [
            [
                'recipe_id' => 1,
                'recipe_code' => 'RCP-001',
                'product_id' => 1, // Orange Juice 1L
                'recipe_name' => 'Fresh Orange Juice Recipe',
                'recipe_version' => '1.0',
                'instructions' => "1. Wash and sort fresh oranges\n2. Extract juice using cold press\n3. Filter juice to remove pulp\n4. Mix with water and sugar\n5. Add preservative\n6. Pasteurize at 85°C for 15 seconds\n7. Cool to 4°C\n8. Fill and seal bottles",
                'batch_size' => 100.00,
                'uom_id' => 1, // Liter
                'preparation_time' => 15.00, // minutes
                'cooking_time' => 30.00, // minutes (including pasteurization)
                'is_active' => 1,
                'created_at' => $now,
            ],
            [
                'recipe_id' => 2,
                'recipe_code' => 'RCP-002',
                'product_id' => 2, // Apple Juice 1L
                'recipe_name' => 'Fresh Apple Juice Recipe',
                'recipe_version' => '1.0',
                'instructions' => "1. Wash and sort fresh apples\n2. Remove cores and bad spots\n3. Extract juice using cold press\n4. Filter juice\n5. Mix with water and sugar\n6. Add preservative\n7. Pasteurize at 85°C for 15 seconds\n8. Cool to 4°C\n9. Fill and seal bottles",
                'batch_size' => 100.00,
                'uom_id' => 1, // Liter
                'preparation_time' => 20.00,
                'cooking_time' => 30.00,
                'is_active' => 1,
                'created_at' => $now,
            ],
            [
                'recipe_id' => 3,
                'recipe_code' => 'RCP-003',
                'product_id' => 3, // Mixed Fruit Juice 1L
                'recipe_name' => 'Mixed Fruit Juice Recipe',
                'recipe_version' => '1.0',
                'instructions' => "1. Prepare oranges and apples separately\n2. Extract juice from both fruits\n3. Filter both juices\n4. Mix orange and apple juice (50:50 ratio)\n5. Add water and sugar\n6. Add preservative\n7. Pasteurize at 85°C for 15 seconds\n8. Cool to 4°C\n9. Fill and seal bottles",
                'batch_size' => 100.00,
                'uom_id' => 1, // Liter
                'preparation_time' => 25.00,
                'cooking_time' => 35.00,
                'is_active' => 1,
                'created_at' => $now,
            ],
        ];
        
        DB::table('recipes')->insert($recipes);

        // ====================================
        // 4. RECIPE INGREDIENTS
        // ====================================
        $recipeIngredients = [
            // RCP-001: Orange Juice
            [
                'ingredient_id' => 1,
                'recipe_id' => 1,
                'material_id' => 4, // Fresh Orange
                'quantity' => 500.00,
                'uom_id' => 2, // Kg (for 100L batch)
                'preparation_notes' => 'Wash thoroughly and inspect for quality',
                'sequence_order' => 1,
                'created_at' => $now,
            ],
            [
                'ingredient_id' => 2,
                'recipe_id' => 1,
                'material_id' => 5, // Sugar
                'quantity' => 15.00,
                'uom_id' => 2, // Kg
                'preparation_notes' => 'Dissolve completely in warm water',
                'sequence_order' => 2,
                'created_at' => $now,
            ],
            [
                'ingredient_id' => 3,
                'recipe_id' => 1,
                'material_id' => 6, // Water
                'quantity' => 30.00,
                'uom_id' => 1, // Liter
                'preparation_notes' => 'Use filtered water',
                'sequence_order' => 3,
                'created_at' => $now,
            ],
            [
                'ingredient_id' => 4,
                'recipe_id' => 1,
                'material_id' => 7, // Preservative
                'quantity' => 0.50,
                'uom_id' => 2, // Kg
                'preparation_notes' => 'Add during mixing phase',
                'sequence_order' => 4,
                'created_at' => $now,
            ],
            
            // RCP-002: Apple Juice
            [
                'ingredient_id' => 5,
                'recipe_id' => 2,
                'material_id' => 8, // Fresh Apple
                'quantity' => 600.00,
                'uom_id' => 2, // Kg
                'preparation_notes' => 'Remove cores and bad spots',
                'sequence_order' => 1,
                'created_at' => $now,
            ],
            [
                'ingredient_id' => 6,
                'recipe_id' => 2,
                'material_id' => 5, // Sugar
                'quantity' => 20.00,
                'uom_id' => 2, // Kg
                'preparation_notes' => 'Dissolve completely',
                'sequence_order' => 2,
                'created_at' => $now,
            ],
            [
                'ingredient_id' => 7,
                'recipe_id' => 2,
                'material_id' => 6, // Water
                'quantity' => 25.00,
                'uom_id' => 1, // Liter
                'preparation_notes' => 'Use filtered water',
                'sequence_order' => 3,
                'created_at' => $now,
            ],
            [
                'ingredient_id' => 8,
                'recipe_id' => 2,
                'material_id' => 7, // Preservative
                'quantity' => 0.50,
                'uom_id' => 2, // Kg
                'preparation_notes' => 'Add during mixing phase',
                'sequence_order' => 4,
                'created_at' => $now,
            ],
            
            // RCP-003: Mixed Fruit Juice
            [
                'ingredient_id' => 9,
                'recipe_id' => 3,
                'material_id' => 4, // Fresh Orange
                'quantity' => 250.00,
                'uom_id' => 2, // Kg
                'preparation_notes' => 'Wash and inspect',
                'sequence_order' => 1,
                'created_at' => $now,
            ],
            [
                'ingredient_id' => 10,
                'recipe_id' => 3,
                'material_id' => 8, // Fresh Apple
                'quantity' => 250.00,
                'uom_id' => 2, // Kg
                'preparation_notes' => 'Remove cores',
                'sequence_order' => 2,
                'created_at' => $now,
            ],
            [
                'ingredient_id' => 11,
                'recipe_id' => 3,
                'material_id' => 5, // Sugar
                'quantity' => 18.00,
                'uom_id' => 2, // Kg
                'preparation_notes' => 'Dissolve completely',
                'sequence_order' => 3,
                'created_at' => $now,
            ],
            [
                'ingredient_id' => 12,
                'recipe_id' => 3,
                'material_id' => 6, // Water
                'quantity' => 28.00,
                'uom_id' => 1, // Liter
                'preparation_notes' => 'Use filtered water',
                'sequence_order' => 4,
                'created_at' => $now,
            ],
            [
                'ingredient_id' => 13,
                'recipe_id' => 3,
                'material_id' => 7, // Preservative
                'quantity' => 0.50,
                'uom_id' => 2, // Kg
                'preparation_notes' => 'Add during mixing phase',
                'sequence_order' => 5,
                'created_at' => $now,
            ],
        ];
        
        DB::table('recipe_ingredients')->insert($recipeIngredients);
    }
}
