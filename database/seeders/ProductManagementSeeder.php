<?php

namespace Database\Seeders;

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
        //
        $this->seedProductCategories();
        $this->seedProducts();
    }

    /**
     * Seed product categories
     */
    private function seedProductCategories(): void
    {
        // Root categories
        $categories = [
            'Raw Materials' => [
                'Flour & Grains',
                'Sugar & Sweeteners',
                'Dairy Products',
                'Oils & Fats',
                'Flavoring & Additives',
                'Preservatives',
            ],
            'Packaging Materials' => [
                'Primary Packaging',
                'Secondary Packaging',
                'Labels & Stickers',
            ],
            'Finished Goods' => [
                'Beverages',
                'Snacks',
                'Confectionery',
                'Ready-to-Eat Meals',
            ],
            'Semi-Finished Goods' => [
                'Dough & Mixtures',
                'Sauces & Pastes',
            ],
            'Consumables' => [
                'Cleaning Supplies',
                'Office Supplies',
            ],
        ];

        foreach ($categories as $parent => $children) {
            $parentId = DB::table('product_categories')->insertGetId([
                'category_code' => strtoupper(Str::random(10)),
                'category_name' => $parent,
                'parent_category_id' => null,
                'description' => "Category for {$parent}",
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($children as $child) {
                DB::table('product_categories')->insert([
                    'category_code' => strtoupper(Str::random(10)),
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
            // Raw Materials
            [
                'name' => 'Wheat Flour Premium',
                'type' => 'Raw Material',
                'category' => 'Flour & Grains',
                'uom' => 'Kilogram',
                'cost' => 15000,
                'price' => 0,
            ],
            [
                'name' => 'White Sugar',
                'type' => 'Raw Material',
                'category' => 'Sugar & Sweeteners',
                'uom' => 'Kilogram',
                'cost' => 12000,
                'price' => 0,
            ],
            [
                'name' => 'Fresh Milk',
                'type' => 'Raw Material',
                'category' => 'Dairy Products',
                'uom' => 'Liter',
                'cost' => 18000,
                'price' => 0,
            ],
            [
                'name' => 'Palm Oil',
                'type' => 'Raw Material',
                'category' => 'Oils & Fats',
                'uom' => 'Liter',
                'cost' => 25000,
                'price' => 0,
            ],
            [
                'name' => 'Vanilla Extract',
                'type' => 'Raw Material',
                'category' => 'Flavoring & Additives',
                'uom' => 'Milliliter',
                'cost' => 150000,
                'price' => 0,
            ],

            // Packaging Materials
            [
                'name' => 'Plastic Bottle 500ml',
                'type' => 'Packaging',
                'category' => 'Primary Packaging',
                'uom' => 'Piece',
                'cost' => 2500,
                'price' => 0,
            ],
            [
                'name' => 'Cardboard Box Medium',
                'type' => 'Packaging',
                'category' => 'Secondary Packaging',
                'uom' => 'Piece',
                'cost' => 5000,
                'price' => 0,
            ],
            [
                'name' => 'Product Label Sticker',
                'type' => 'Packaging',
                'category' => 'Labels & Stickers',
                'uom' => 'Piece',
                'cost' => 500,
                'price' => 0,
            ],

            // Semi-Finished Goods
            [
                'name' => 'Cake Batter Mix',
                'type' => 'Semi-Finished',
                'category' => 'Dough & Mixtures',
                'uom' => 'Kilogram',
                'cost' => 35000,
                'price' => 0,
            ],
            [
                'name' => 'Chocolate Sauce Base',
                'type' => 'Semi-Finished',
                'category' => 'Sauces & Pastes',
                'uom' => 'Liter',
                'cost' => 45000,
                'price' => 0,
            ],

            // Finished Goods
            [
                'name' => 'Orange Juice 500ml',
                'type' => 'Finished Goods',
                'category' => 'Beverages',
                'uom' => 'Piece',
                'cost' => 8000,
                'price' => 15000,
            ],
            [
                'name' => 'Chocolate Milk 250ml',
                'type' => 'Finished Goods',
                'category' => 'Beverages',
                'uom' => 'Piece',
                'cost' => 6500,
                'price' => 12000,
            ],
            [
                'name' => 'Potato Chips Original 100g',
                'type' => 'Finished Goods',
                'category' => 'Snacks',
                'uom' => 'Pack',
                'cost' => 5000,
                'price' => 10000,
            ],
            [
                'name' => 'Chocolate Bar 50g',
                'type' => 'Finished Goods',
                'category' => 'Confectionery',
                'uom' => 'Piece',
                'cost' => 7000,
                'price' => 13000,
            ],
            [
                'name' => 'Instant Noodles Cup',
                'type' => 'Finished Goods',
                'category' => 'Ready-to-Eat Meals',
                'uom' => 'Piece',
                'cost' => 4500,
                'price' => 9000,
            ],

            // Consumables
            [
                'name' => 'Industrial Detergent',
                'type' => 'Consumable',
                'category' => 'Cleaning Supplies',
                'uom' => 'Liter',
                'cost' => 35000,
                'price' => 0,
            ],
        ];

        foreach ($products as $product) {
            $category = $categories->get($product['category']);
            $uom = $uoms->get($product['uom']);

            if (!$category || !$uom) {
                $this->command->warn("Skipping product: {$product['name']} (missing category or UOM)");
                continue;
            }

            DB::table('products')->insert([
                'product_code' => strtoupper(Str::random(10)),
                'product_name' => $product['name'],
                'product_type' => $product['type'],
                'category_id' => $category->category_id,
                'uom_id' => $uom->uom_id,
                'standard_cost' => $product['cost'],
                'selling_price' => $product['price'],
                'description' => "Sample product: {$product['name']}",
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info(count($products) . ' products seeded');
    }
}
