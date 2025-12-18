<?php

namespace Database\Seeders;

use App\Helpers\CodeGeneratorHelper;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedProductCategories();
        $this->seedProducts();
    }

    /**
     * Seed product categories
     */
    private function seedProductCategories(): void
    {
        // Root categories with subcategories
        $categories = [
            'Raw Materials' => [
                'Fruits',
                'Vegetables',
                'Sweeteners',
                'Additives & Preservatives',
                'Base Ingredients',
            ],
            'Packaging Materials' => [
                'Bottles & Containers',
                'Labels & Stickers',
                'Caps & Closures',
                'Cartons & Boxes',
            ],
            'Finished Goods' => [
                'Fruit Juices',
                'Vegetable Juices',
                'Mixed Beverages',
                'Health Drinks',
            ],
            'Semi-Finished Goods' => [
                'Juice Concentrates',
                'Pulps',
                'Syrups',
            ],
            'Consumables' => [
                'Cleaning Supplies',
                'Laboratory Supplies',
            ],
        ];

        foreach ($categories as $parent => $children) {
            $categoryCode = CodeGeneratorHelper::generateProductCategoryCode();

            $parentId = DB::table('product_categories')->insertGetId([
                'category_code' => $categoryCode,
                'category_name' => $parent,
                'parent_category_id' => null,
                'description' => "Category for {$parent}",
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($children as $child) {
                $childCategoryCode = CodeGeneratorHelper::generateProductCategoryCode();

                DB::table('product_categories')->insert([
                    'category_code' => $childCategoryCode,
                    'category_name' => $child,
                    'parent_category_id' => $parentId,
                    'description' => "Subcategory for {$child}",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Product categories seeded');
    }

    /**
     * Seed sample products
     */
    private function seedProducts(): void
    {
        // Get category and UOM IDs
        $categories = DB::table('product_categories')
            ->whereNotNull('parent_category_id')
            ->get()
            ->keyBy('category_name');

        $uoms = DB::table('units_of_measure')
            ->get()
            ->keyBy('uom_name');

        $products = [
            // === RAW MATERIALS - FRUITS ===
            ['name' => 'Fresh Orange', 'type' => 'Raw Material', 'category' => 'Fruits', 'uom' => 'Kilogram', 'cost' => 15000, 'price' => 0],
            ['name' => 'Fresh Apple', 'type' => 'Raw Material', 'category' => 'Fruits', 'uom' => 'Kilogram', 'cost' => 25000, 'price' => 0],
            ['name' => 'Fresh Mango', 'type' => 'Raw Material', 'category' => 'Fruits', 'uom' => 'Kilogram', 'cost' => 20000, 'price' => 0],
            ['name' => 'Fresh Strawberry', 'type' => 'Raw Material', 'category' => 'Fruits', 'uom' => 'Kilogram', 'cost' => 45000, 'price' => 0],
            ['name' => 'Fresh Pineapple', 'type' => 'Raw Material', 'category' => 'Fruits', 'uom' => 'Kilogram', 'cost' => 12000, 'price' => 0],
            ['name' => 'Fresh Guava', 'type' => 'Raw Material', 'category' => 'Fruits', 'uom' => 'Kilogram', 'cost' => 18000, 'price' => 0],
            ['name' => 'Fresh Lemon', 'type' => 'Raw Material', 'category' => 'Fruits', 'uom' => 'Kilogram', 'cost' => 22000, 'price' => 0],
            ['name' => 'Fresh Watermelon', 'type' => 'Raw Material', 'category' => 'Fruits', 'uom' => 'Kilogram', 'cost' => 8000, 'price' => 0],

            // === RAW MATERIALS - VEGETABLES ===
            ['name' => 'Fresh Carrot', 'type' => 'Raw Material', 'category' => 'Vegetables', 'uom' => 'Kilogram', 'cost' => 10000, 'price' => 0],
            ['name' => 'Fresh Tomato', 'type' => 'Raw Material', 'category' => 'Vegetables', 'uom' => 'Kilogram', 'cost' => 12000, 'price' => 0],
            ['name' => 'Fresh Cucumber', 'type' => 'Raw Material', 'category' => 'Vegetables', 'uom' => 'Kilogram', 'cost' => 9000, 'price' => 0],
            ['name' => 'Fresh Beetroot', 'type' => 'Raw Material', 'category' => 'Vegetables', 'uom' => 'Kilogram', 'cost' => 15000, 'price' => 0],

            // === RAW MATERIALS - SWEETENERS ===
            ['name' => 'White Sugar', 'type' => 'Raw Material', 'category' => 'Sweeteners', 'uom' => 'Kilogram', 'cost' => 12000, 'price' => 0],
            ['name' => 'Brown Sugar', 'type' => 'Raw Material', 'category' => 'Sweeteners', 'uom' => 'Kilogram', 'cost' => 15000, 'price' => 0],
            ['name' => 'Honey', 'type' => 'Raw Material', 'category' => 'Sweeteners', 'uom' => 'Kilogram', 'cost' => 80000, 'price' => 0],
            ['name' => 'Stevia Extract', 'type' => 'Raw Material', 'category' => 'Sweeteners', 'uom' => 'Kilogram', 'cost' => 250000, 'price' => 0],

            // === RAW MATERIALS - ADDITIVES & PRESERVATIVES ===
            ['name' => 'Citric Acid', 'type' => 'Raw Material', 'category' => 'Additives & Preservatives', 'uom' => 'Kilogram', 'cost' => 35000, 'price' => 0],
            ['name' => 'Ascorbic Acid (Vitamin C)', 'type' => 'Raw Material', 'category' => 'Additives & Preservatives', 'uom' => 'Kilogram', 'cost' => 120000, 'price' => 0],
            ['name' => 'Sodium Benzoate', 'type' => 'Raw Material', 'category' => 'Additives & Preservatives', 'uom' => 'Kilogram', 'cost' => 45000, 'price' => 0],
            ['name' => 'Natural Flavor - Orange', 'type' => 'Raw Material', 'category' => 'Additives & Preservatives', 'uom' => 'Liter', 'cost' => 200000, 'price' => 0],
            ['name' => 'Natural Flavor - Apple', 'type' => 'Raw Material', 'category' => 'Additives & Preservatives', 'uom' => 'Liter', 'cost' => 200000, 'price' => 0],

            // === RAW MATERIALS - BASE INGREDIENTS ===
            ['name' => 'Filtered Water', 'type' => 'Raw Material', 'category' => 'Base Ingredients', 'uom' => 'Liter', 'cost' => 500, 'price' => 0],
            ['name' => 'Coconut Water', 'type' => 'Raw Material', 'category' => 'Base Ingredients', 'uom' => 'Liter', 'cost' => 15000, 'price' => 0],
            ['name' => 'Milk', 'type' => 'Raw Material', 'category' => 'Base Ingredients', 'uom' => 'Liter', 'cost' => 12000, 'price' => 0],

            // === PACKAGING MATERIALS - BOTTLES ===
            ['name' => 'PET Bottle 250ml', 'type' => 'Packaging', 'category' => 'Bottles & Containers', 'uom' => 'Pieces', 'cost' => 800, 'price' => 0],
            ['name' => 'PET Bottle 500ml', 'type' => 'Packaging', 'category' => 'Bottles & Containers', 'uom' => 'Pieces', 'cost' => 1200, 'price' => 0],
            ['name' => 'PET Bottle 1L', 'type' => 'Packaging', 'category' => 'Bottles & Containers', 'uom' => 'Pieces', 'cost' => 1800, 'price' => 0],
            ['name' => 'Glass Bottle 250ml', 'type' => 'Packaging', 'category' => 'Bottles & Containers', 'uom' => 'Pieces', 'cost' => 2500, 'price' => 0],
            ['name' => 'Tetra Pack 1L', 'type' => 'Packaging', 'category' => 'Bottles & Containers', 'uom' => 'Pieces', 'cost' => 2200, 'price' => 0],

            // === PACKAGING MATERIALS - LABELS ===
            ['name' => 'Product Label 250ml', 'type' => 'Packaging', 'category' => 'Labels & Stickers', 'uom' => 'Pieces', 'cost' => 300, 'price' => 0],
            ['name' => 'Product Label 500ml', 'type' => 'Packaging', 'category' => 'Labels & Stickers', 'uom' => 'Pieces', 'cost' => 400, 'price' => 0],
            ['name' => 'Product Label 1L', 'type' => 'Packaging', 'category' => 'Labels & Stickers', 'uom' => 'Pieces', 'cost' => 500, 'price' => 0],

            // === PACKAGING MATERIALS - CAPS ===
            ['name' => 'Screw Cap 28mm', 'type' => 'Packaging', 'category' => 'Caps & Closures', 'uom' => 'Pieces', 'cost' => 200, 'price' => 0],
            ['name' => 'Screw Cap 38mm', 'type' => 'Packaging', 'category' => 'Caps & Closures', 'uom' => 'Pieces', 'cost' => 250, 'price' => 0],

            // === PACKAGING MATERIALS - CARTONS ===
            ['name' => 'Corrugated Box 12pcs', 'type' => 'Packaging', 'category' => 'Cartons & Boxes', 'uom' => 'Pieces', 'cost' => 3500, 'price' => 0],
            ['name' => 'Corrugated Box 24pcs', 'type' => 'Packaging', 'category' => 'Cartons & Boxes', 'uom' => 'Pieces', 'cost' => 5500, 'price' => 0],
            ['name' => 'Shrink Wrap Film', 'type' => 'Packaging', 'category' => 'Cartons & Boxes', 'uom' => 'Kilogram', 'cost' => 25000, 'price' => 0],

            // === FINISHED GOODS - FRUIT JUICES ===
            ['name' => 'Orange Juice 250ml', 'type' => 'Finished Goods', 'category' => 'Fruit Juices', 'uom' => 'Pieces', 'cost' => 8500, 'price' => 12000],
            ['name' => 'Orange Juice 1L', 'type' => 'Finished Goods', 'category' => 'Fruit Juices', 'uom' => 'Pieces', 'cost' => 28000, 'price' => 38000],
            ['name' => 'Apple Juice 250ml', 'type' => 'Finished Goods', 'category' => 'Fruit Juices', 'uom' => 'Pieces', 'cost' => 10000, 'price' => 14000],
            ['name' => 'Apple Juice 1L', 'type' => 'Finished Goods', 'category' => 'Fruit Juices', 'uom' => 'Pieces', 'cost' => 35000, 'price' => 45000],
            ['name' => 'Mango Juice 250ml', 'type' => 'Finished Goods', 'category' => 'Fruit Juices', 'uom' => 'Pieces', 'cost' => 9500, 'price' => 13500],
            ['name' => 'Mango Juice 1L', 'type' => 'Finished Goods', 'category' => 'Fruit Juices', 'uom' => 'Pieces', 'cost' => 32000, 'price' => 42000],
            ['name' => 'Strawberry Juice 250ml', 'type' => 'Finished Goods', 'category' => 'Fruit Juices', 'uom' => 'Pieces', 'cost' => 12000, 'price' => 17000],
            ['name' => 'Pineapple Juice 1L', 'type' => 'Finished Goods', 'category' => 'Fruit Juices', 'uom' => 'Pieces', 'cost' => 26000, 'price' => 36000],
            ['name' => 'Guava Juice 1L', 'type' => 'Finished Goods', 'category' => 'Fruit Juices', 'uom' => 'Pieces', 'cost' => 30000, 'price' => 40000],
            ['name' => 'Watermelon Juice 250ml', 'type' => 'Finished Goods', 'category' => 'Fruit Juices', 'uom' => 'Pieces', 'cost' => 7500, 'price' => 11000],

            // === FINISHED GOODS - VEGETABLE JUICES ===
            ['name' => 'Carrot Juice 250ml', 'type' => 'Finished Goods', 'category' => 'Vegetable Juices', 'uom' => 'Pieces', 'cost' => 8000, 'price' => 12000],
            ['name' => 'Tomato Juice 250ml', 'type' => 'Finished Goods', 'category' => 'Vegetable Juices', 'uom' => 'Pieces', 'cost' => 8500, 'price' => 12500],
            ['name' => 'Beetroot Juice 250ml', 'type' => 'Finished Goods', 'category' => 'Vegetable Juices', 'uom' => 'Pieces', 'cost' => 9500, 'price' => 14000],

            // === FINISHED GOODS - MIXED BEVERAGES ===
            ['name' => 'Mixed Fruit Juice 1L', 'type' => 'Finished Goods', 'category' => 'Mixed Beverages', 'uom' => 'Pieces', 'cost' => 30000, 'price' => 40000],
            ['name' => 'Orange-Mango Mix 500ml', 'type' => 'Finished Goods', 'category' => 'Mixed Beverages', 'uom' => 'Pieces', 'cost' => 16000, 'price' => 22000],
            ['name' => 'Apple-Strawberry Mix 500ml', 'type' => 'Finished Goods', 'category' => 'Mixed Beverages', 'uom' => 'Pieces', 'cost' => 18000, 'price' => 25000],
            ['name' => 'Tropical Blend 1L', 'type' => 'Finished Goods', 'category' => 'Mixed Beverages', 'uom' => 'Pieces', 'cost' => 32000, 'price' => 43000],

            // === FINISHED GOODS - HEALTH DRINKS ===
            ['name' => 'Immunity Boost Juice 250ml', 'type' => 'Finished Goods', 'category' => 'Health Drinks', 'uom' => 'Pieces', 'cost' => 11000, 'price' => 16000],
            ['name' => 'Detox Green Juice 250ml', 'type' => 'Finished Goods', 'category' => 'Health Drinks', 'uom' => 'Pieces', 'cost' => 12500, 'price' => 18000],
            ['name' => 'Energy Boost Drink 500ml', 'type' => 'Finished Goods', 'category' => 'Health Drinks', 'uom' => 'Pieces', 'cost' => 18000, 'price' => 25000],

            // === SEMI-FINISHED GOODS - CONCENTRATES ===
            ['name' => 'Orange Concentrate 70%', 'type' => 'Semi-Finished', 'category' => 'Juice Concentrates', 'uom' => 'Liter', 'cost' => 85000, 'price' => 0],
            ['name' => 'Apple Concentrate 70%', 'type' => 'Semi-Finished', 'category' => 'Juice Concentrates', 'uom' => 'Liter', 'cost' => 95000, 'price' => 0],
            ['name' => 'Mango Concentrate 70%', 'type' => 'Semi-Finished', 'category' => 'Juice Concentrates', 'uom' => 'Liter', 'cost' => 90000, 'price' => 0],

            // === SEMI-FINISHED GOODS - PULPS ===
            ['name' => 'Mango Pulp', 'type' => 'Semi-Finished', 'category' => 'Pulps', 'uom' => 'Kilogram', 'cost' => 35000, 'price' => 0],
            ['name' => 'Strawberry Pulp', 'type' => 'Semi-Finished', 'category' => 'Pulps', 'uom' => 'Kilogram', 'cost' => 65000, 'price' => 0],
            ['name' => 'Guava Pulp', 'type' => 'Semi-Finished', 'category' => 'Pulps', 'uom' => 'Kilogram', 'cost' => 30000, 'price' => 0],

            // === SEMI-FINISHED GOODS - SYRUPS ===
            ['name' => 'Simple Syrup 65 Brix', 'type' => 'Semi-Finished', 'category' => 'Syrups', 'uom' => 'Liter', 'cost' => 18000, 'price' => 0],
            ['name' => 'Flavored Syrup - Lemon', 'type' => 'Semi-Finished', 'category' => 'Syrups', 'uom' => 'Liter', 'cost' => 25000, 'price' => 0],

            // === CONSUMABLES - CLEANING ===
            ['name' => 'Alkaline Cleaner', 'type' => 'Consumable', 'category' => 'Cleaning Supplies', 'uom' => 'Liter', 'cost' => 35000, 'price' => 0],
            ['name' => 'Sanitizer Food Grade', 'type' => 'Consumable', 'category' => 'Cleaning Supplies', 'uom' => 'Liter', 'cost' => 45000, 'price' => 0],
            ['name' => 'CIP Detergent', 'type' => 'Consumable', 'category' => 'Cleaning Supplies', 'uom' => 'Kilogram', 'cost' => 55000, 'price' => 0],

            // === CONSUMABLES - LABORATORY ===
            ['name' => 'pH Test Strips', 'type' => 'Consumable', 'category' => 'Laboratory Supplies', 'uom' => 'Pieces', 'cost' => 150000, 'price' => 0],
            ['name' => 'Brix Refractometer Solution', 'type' => 'Consumable', 'category' => 'Laboratory Supplies', 'uom' => 'Liter', 'cost' => 250000, 'price' => 0],
        ];

        $insertedCount = 0;
        foreach ($products as $product) {
            $category = $categories->get($product['category']);
            $uom = $uoms->get($product['uom']);

            if (!$category || !$uom) {
                $this->command->warn("Skipping product: {$product['name']} (missing category or UOM)");
                continue;
            }

            $productCode = CodeGeneratorHelper::generateProductCode();

            DB::table('products')->insert([
                'product_code' => $productCode,
                'product_name' => $product['name'],
                'product_type' => $product['type'],
                'category_id' => $category->category_id,
                'uom_id' => $uom->uom_id,
                'standard_cost' => $product['cost'],
                'selling_price' => $product['price'],
                'description' => "Product: {$product['name']}",
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $insertedCount++;
        }

        $this->command->info("{$insertedCount} products seeded successfully");
    }
}