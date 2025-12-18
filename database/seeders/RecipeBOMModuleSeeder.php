<?php

namespace Database\Seeders;

use App\Helpers\CodeGeneratorHelper;
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
        
        // Get products - we'll use product names to get IDs
        $products = DB::table('products')->get()->keyBy('product_name');
        $uoms = DB::table('units_of_measure')->get()->keyBy('uom_name');
        
        // ====================================
        // BILL OF MATERIALS (BOM)
        // ====================================
        $bomsData = [
            // === FRUIT JUICES 1L ===
            [
                'product_name' => 'Orange Juice 1L',
                'version' => '1.0',
                'notes' => 'Standard BOM for Orange Juice 1L production',
                'materials' => [
                    ['material' => 'Fresh Orange', 'qty' => 5.00, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 10.00],
                    ['material' => 'White Sugar', 'qty' => 0.15, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Filtered Water', 'qty' => 0.30, 'uom' => 'Liter', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Citric Acid', 'qty' => 0.003, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Sodium Benzoate', 'qty' => 0.002, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'PET Bottle 1L', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 2.00],
                    ['material' => 'Product Label 1L', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                    ['material' => 'Screw Cap 38mm', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                ],
            ],
            [
                'product_name' => 'Apple Juice 1L',
                'version' => '1.0',
                'notes' => 'Standard BOM for Apple Juice 1L production',
                'materials' => [
                    ['material' => 'Fresh Apple', 'qty' => 6.00, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 15.00],
                    ['material' => 'White Sugar', 'qty' => 0.20, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Filtered Water', 'qty' => 0.25, 'uom' => 'Liter', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Ascorbic Acid (Vitamin C)', 'qty' => 0.002, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Sodium Benzoate', 'qty' => 0.002, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'PET Bottle 1L', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 2.00],
                    ['material' => 'Product Label 1L', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                    ['material' => 'Screw Cap 38mm', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                ],
            ],
            [
                'product_name' => 'Mango Juice 1L',
                'version' => '1.0',
                'notes' => 'Standard BOM for Mango Juice 1L production',
                'materials' => [
                    ['material' => 'Fresh Mango', 'qty' => 5.50, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 12.00],
                    ['material' => 'White Sugar', 'qty' => 0.18, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Filtered Water', 'qty' => 0.28, 'uom' => 'Liter', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Citric Acid', 'qty' => 0.003, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Sodium Benzoate', 'qty' => 0.002, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'PET Bottle 1L', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 2.00],
                    ['material' => 'Product Label 1L', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                    ['material' => 'Screw Cap 38mm', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                ],
            ],
            [
                'product_name' => 'Guava Juice 1L',
                'version' => '1.0',
                'notes' => 'Standard BOM for Guava Juice 1L production',
                'materials' => [
                    ['material' => 'Fresh Guava', 'qty' => 5.20, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 8.00],
                    ['material' => 'White Sugar', 'qty' => 0.16, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Filtered Water', 'qty' => 0.32, 'uom' => 'Liter', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Citric Acid', 'qty' => 0.003, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Sodium Benzoate', 'qty' => 0.002, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'PET Bottle 1L', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 2.00],
                    ['material' => 'Product Label 1L', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                    ['material' => 'Screw Cap 38mm', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                ],
            ],
            [
                'product_name' => 'Pineapple Juice 1L',
                'version' => '1.0',
                'notes' => 'Standard BOM for Pineapple Juice 1L production',
                'materials' => [
                    ['material' => 'Fresh Pineapple', 'qty' => 4.80, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 20.00],
                    ['material' => 'White Sugar', 'qty' => 0.12, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Filtered Water', 'qty' => 0.35, 'uom' => 'Liter', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Citric Acid', 'qty' => 0.002, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Sodium Benzoate', 'qty' => 0.002, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'PET Bottle 1L', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 2.00],
                    ['material' => 'Product Label 1L', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                    ['material' => 'Screw Cap 38mm', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                ],
            ],

            // === FRUIT JUICES 250ML ===
            [
                'product_name' => 'Orange Juice 250ml',
                'version' => '1.0',
                'notes' => 'Standard BOM for Orange Juice 250ml production',
                'materials' => [
                    ['material' => 'Fresh Orange', 'qty' => 1.25, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 10.00],
                    ['material' => 'White Sugar', 'qty' => 0.04, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Filtered Water', 'qty' => 0.08, 'uom' => 'Liter', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Citric Acid', 'qty' => 0.001, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Sodium Benzoate', 'qty' => 0.0005, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'PET Bottle 250ml', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 2.00],
                    ['material' => 'Product Label 250ml', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                    ['material' => 'Screw Cap 28mm', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                ],
            ],
            [
                'product_name' => 'Apple Juice 250ml',
                'version' => '1.0',
                'notes' => 'Standard BOM for Apple Juice 250ml production',
                'materials' => [
                    ['material' => 'Fresh Apple', 'qty' => 1.50, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 15.00],
                    ['material' => 'White Sugar', 'qty' => 0.05, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Filtered Water', 'qty' => 0.06, 'uom' => 'Liter', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Ascorbic Acid (Vitamin C)', 'qty' => 0.0005, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Sodium Benzoate', 'qty' => 0.0005, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'PET Bottle 250ml', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 2.00],
                    ['material' => 'Product Label 250ml', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                    ['material' => 'Screw Cap 28mm', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                ],
            ],
            [
                'product_name' => 'Mango Juice 250ml',
                'version' => '1.0',
                'notes' => 'Standard BOM for Mango Juice 250ml production',
                'materials' => [
                    ['material' => 'Fresh Mango', 'qty' => 1.40, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 12.00],
                    ['material' => 'White Sugar', 'qty' => 0.045, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Filtered Water', 'qty' => 0.07, 'uom' => 'Liter', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Citric Acid', 'qty' => 0.001, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Sodium Benzoate', 'qty' => 0.0005, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'PET Bottle 250ml', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 2.00],
                    ['material' => 'Product Label 250ml', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                    ['material' => 'Screw Cap 28mm', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                ],
            ],
            [
                'product_name' => 'Strawberry Juice 250ml',
                'version' => '1.0',
                'notes' => 'Standard BOM for Strawberry Juice 250ml production',
                'materials' => [
                    ['material' => 'Fresh Strawberry', 'qty' => 1.80, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 10.00],
                    ['material' => 'White Sugar', 'qty' => 0.06, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Filtered Water', 'qty' => 0.08, 'uom' => 'Liter', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Citric Acid', 'qty' => 0.001, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Sodium Benzoate', 'qty' => 0.0005, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'PET Bottle 250ml', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 2.00],
                    ['material' => 'Product Label 250ml', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                    ['material' => 'Screw Cap 28mm', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                ],
            ],
            [
                'product_name' => 'Watermelon Juice 250ml',
                'version' => '1.0',
                'notes' => 'Standard BOM for Watermelon Juice 250ml production',
                'materials' => [
                    ['material' => 'Fresh Watermelon', 'qty' => 1.20, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 15.00],
                    ['material' => 'White Sugar', 'qty' => 0.03, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Filtered Water', 'qty' => 0.06, 'uom' => 'Liter', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Citric Acid', 'qty' => 0.001, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Sodium Benzoate', 'qty' => 0.0005, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'PET Bottle 250ml', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 2.00],
                    ['material' => 'Product Label 250ml', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                    ['material' => 'Screw Cap 28mm', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                ],
            ],

            // === VEGETABLE JUICES ===
            [
                'product_name' => 'Carrot Juice 250ml',
                'version' => '1.0',
                'notes' => 'Standard BOM for Carrot Juice 250ml production',
                'materials' => [
                    ['material' => 'Fresh Carrot', 'qty' => 1.60, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 12.00],
                    ['material' => 'Honey', 'qty' => 0.02, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Filtered Water', 'qty' => 0.08, 'uom' => 'Liter', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Ascorbic Acid (Vitamin C)', 'qty' => 0.0008, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Sodium Benzoate', 'qty' => 0.0005, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'PET Bottle 250ml', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 2.00],
                    ['material' => 'Product Label 250ml', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                    ['material' => 'Screw Cap 28mm', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                ],
            ],
            [
                'product_name' => 'Tomato Juice 250ml',
                'version' => '1.0',
                'notes' => 'Standard BOM for Tomato Juice 250ml production',
                'materials' => [
                    ['material' => 'Fresh Tomato', 'qty' => 1.70, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 10.00],
                    ['material' => 'White Sugar', 'qty' => 0.02, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Filtered Water', 'qty' => 0.05, 'uom' => 'Liter', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Citric Acid', 'qty' => 0.001, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Sodium Benzoate', 'qty' => 0.0005, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'PET Bottle 250ml', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 2.00],
                    ['material' => 'Product Label 250ml', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                    ['material' => 'Screw Cap 28mm', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                ],
            ],
            [
                'product_name' => 'Beetroot Juice 250ml',
                'version' => '1.0',
                'notes' => 'Standard BOM for Beetroot Juice 250ml production',
                'materials' => [
                    ['material' => 'Fresh Beetroot', 'qty' => 1.80, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 15.00],
                    ['material' => 'Honey', 'qty' => 0.03, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Filtered Water', 'qty' => 0.08, 'uom' => 'Liter', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Citric Acid', 'qty' => 0.001, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Sodium Benzoate', 'qty' => 0.0005, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'PET Bottle 250ml', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 2.00],
                    ['material' => 'Product Label 250ml', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                    ['material' => 'Screw Cap 28mm', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                ],
            ],

            // === MIXED BEVERAGES ===
            [
                'product_name' => 'Mixed Fruit Juice 1L',
                'version' => '1.0',
                'notes' => 'Standard BOM for Mixed Fruit Juice 1L production',
                'materials' => [
                    ['material' => 'Fresh Orange', 'qty' => 2.00, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 10.00],
                    ['material' => 'Fresh Apple', 'qty' => 2.00, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 15.00],
                    ['material' => 'Fresh Mango', 'qty' => 1.50, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 12.00],
                    ['material' => 'White Sugar', 'qty' => 0.18, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Filtered Water', 'qty' => 0.28, 'uom' => 'Liter', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Citric Acid', 'qty' => 0.003, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'Sodium Benzoate', 'qty' => 0.002, 'uom' => 'Kilogram', 'type' => 'Raw Material', 'scrap' => 0.00],
                    ['material' => 'PET Bottle 1L', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 2.00],
                    ['material' => 'Product Label 1L', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                    ['material' => 'Screw Cap 38mm', 'qty' => 1.00, 'uom' => 'Pieces', 'type' => 'Packaging', 'scrap' => 1.00],
                ],
            ],
        ];

        $bomCount = 0;
        $bomItemCount = 0;

        foreach ($bomsData as $bomData) {
            $product = $products->get($bomData['product_name']);
            
            if (!$product) {
                $this->command->warn("kipping BOM for: {$bomData['product_name']} (product not found)");
                continue;
            }

            // Generate unique BOM code
            $bomCode = CodeGeneratorHelper::generateBOMCode();

            // Insert BOM
            $bomId = DB::table('bill_of_materials')->insertGetId([
                'bom_code' => $bomCode,
                'product_id' => $product->product_id,
                'bom_version' => $bomData['version'],
                'effective_date' => $now->toDateString(),
                'is_active' => 1,
                'notes' => $bomData['notes'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $bomCount++;

            // Insert BOM items (materials)
            foreach ($bomData['materials'] as $materialData) {
                $material = $products->get($materialData['material']);
                $materialUom = $uoms->get($materialData['uom']);
                
                if (!$material || !$materialUom) {
                    $this->command->warn("kipping material: {$materialData['material']}");
                    continue;
                }

                DB::table('bom_items')->insert([
                    'bom_id' => $bomId,
                    'material_id' => $material->product_id,
                    'quantity_required' => $materialData['qty'],
                    'uom_id' => $materialUom->uom_id,
                    'item_type' => $materialData['type'],
                    'scrap_percentage' => $materialData['scrap'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $bomItemCount++;
            }
        }

        $this->command->info("{$bomCount} BOMs seeded");
        $this->command->info("{$bomItemCount} BOM items seeded");
    }

    /**
     * Seed Recipe
     */
    private function seedRecipes(): void {
        $now = Carbon::now();
        
        // Get products and UOMs
        $products = DB::table('products')
            ->select('product_id', 'product_name', 'product_code')
            ->get()
            ->keyBy('product_name');
        
        $uoms = DB::table('units_of_measure')
            ->select('uom_id', 'uom_name')
            ->get()
            ->keyBy('uom_name');

        // ====================================
        // RECIPES DATA
        // ====================================
        $recipesData = [
            // === FRUIT JUICES 1L ===
            [
                'product_name' => 'Orange Juice 1L',
                'recipe_name' => 'Fresh Orange Juice - Industrial Recipe',
                'version' => '1.0',
                'instructions' => "1. Wash and sort 500kg fresh oranges\n2. Extract juice using cold press extractor\n3. Filter juice through 100-micron filter\n4. Mix juice with filtered water and sugar syrup\n5. Add citric acid and preservative\n6. Pasteurize at 85°C for 15 seconds\n7. Cool rapidly to 4°C using plate heat exchanger\n8. Fill into sterile bottles\n9. Cap and label\n10. Final inspection and packaging",
                'batch_size' => 100.00, // 100 Liter per batch
                'uom' => 'Liter',
                'prep_time' => 20.00, // minutes
                'cook_time' => 35.00, // minutes
                'ingredients' => [
                    ['material' => 'Fresh Orange', 'qty' => 500.00, 'uom' => 'Kilogram', 'notes' => 'Grade A oranges only. Wash thoroughly', 'order' => 1],
                    ['material' => 'White Sugar', 'qty' => 15.00, 'uom' => 'Kilogram', 'notes' => 'Dissolve in warm water first', 'order' => 2],
                    ['material' => 'Filtered Water', 'qty' => 30.00, 'uom' => 'Liter', 'notes' => 'Use RO filtered water', 'order' => 3],
                    ['material' => 'Citric Acid', 'qty' => 0.30, 'uom' => 'Kilogram', 'notes' => 'Add during mixing', 'order' => 4],
                    ['material' => 'Sodium Benzoate', 'qty' => 0.20, 'uom' => 'Kilogram', 'notes' => 'Add before pasteurization', 'order' => 5],
                ],
            ],
            [
                'product_name' => 'Apple Juice 1L',
                'recipe_name' => 'Fresh Apple Juice - Industrial Recipe',
                'version' => '1.0',
                'instructions' => "1. Wash and inspect 600kg fresh apples\n2. Remove cores and defective parts\n3. Extract juice using hydraulic press\n4. Filter juice through dual filtration\n5. Mix with water, sugar, and vitamin C\n6. Add preservative\n7. Pasteurize at 85°C for 15 seconds\n8. Cool to 4°C\n9. Fill and seal bottles\n10. Quality check and pack",
                'batch_size' => 100.00,
                'uom' => 'Liter',
                'prep_time' => 25.00,
                'cook_time' => 35.00,
                'ingredients' => [
                    ['material' => 'Fresh Apple', 'qty' => 600.00, 'uom' => 'Kilogram', 'notes' => 'Remove cores and bad spots', 'order' => 1],
                    ['material' => 'White Sugar', 'qty' => 20.00, 'uom' => 'Kilogram', 'notes' => 'Dissolve completely', 'order' => 2],
                    ['material' => 'Filtered Water', 'qty' => 25.00, 'uom' => 'Liter', 'notes' => 'Pre-chilled water', 'order' => 3],
                    ['material' => 'Ascorbic Acid (Vitamin C)', 'qty' => 0.20, 'uom' => 'Kilogram', 'notes' => 'Prevent oxidation', 'order' => 4],
                    ['material' => 'Sodium Benzoate', 'qty' => 0.20, 'uom' => 'Kilogram', 'notes' => 'Food grade preservative', 'order' => 5],
                ],
            ],
            [
                'product_name' => 'Mango Juice 1L',
                'recipe_name' => 'Fresh Mango Juice - Industrial Recipe',
                'version' => '1.0',
                'instructions' => "1. Sort and wash 550kg ripe mangoes\n2. Peel and remove seeds\n3. Blend pulp to smooth consistency\n4. Mix with water and sugar syrup\n5. Add citric acid for tartness\n6. Add preservative\n7. Pasteurize at 85°C for 15 seconds\n8. Homogenize for smooth texture\n9. Cool to 4°C\n10. Fill, cap, and label",
                'batch_size' => 100.00,
                'uom' => 'Liter',
                'prep_time' => 30.00,
                'cook_time' => 40.00,
                'ingredients' => [
                    ['material' => 'Fresh Mango', 'qty' => 550.00, 'uom' => 'Kilogram', 'notes' => 'Use ripe mangoes', 'order' => 1],
                    ['material' => 'White Sugar', 'qty' => 18.00, 'uom' => 'Kilogram', 'notes' => 'Adjust to sweetness', 'order' => 2],
                    ['material' => 'Filtered Water', 'qty' => 28.00, 'uom' => 'Liter', 'notes' => 'Room temperature', 'order' => 3],
                    ['material' => 'Citric Acid', 'qty' => 0.30, 'uom' => 'Kilogram', 'notes' => 'Balance sweetness', 'order' => 4],
                    ['material' => 'Sodium Benzoate', 'qty' => 0.20, 'uom' => 'Kilogram', 'notes' => 'Extend shelf life', 'order' => 5],
                ],
            ],
            [
                'product_name' => 'Guava Juice 1L',
                'recipe_name' => 'Fresh Guava Juice - Industrial Recipe',
                'version' => '1.0',
                'instructions' => "1. Wash 520kg pink guavas\n2. Cut and remove seeds\n3. Blend with skin (for fiber)\n4. Strain through fine mesh\n5. Mix with water and sugar\n6. Add citric acid and preservative\n7. Pasteurize at 85°C for 15 seconds\n8. Cool to 4°C\n9. Fill into bottles\n10. Cap, label, and inspect",
                'batch_size' => 100.00,
                'uom' => 'Liter',
                'prep_time' => 25.00,
                'cook_time' => 35.00,
                'ingredients' => [
                    ['material' => 'Fresh Guava', 'qty' => 520.00, 'uom' => 'Kilogram', 'notes' => 'Pink variety preferred', 'order' => 1],
                    ['material' => 'White Sugar', 'qty' => 16.00, 'uom' => 'Kilogram', 'notes' => 'Sweeten to taste', 'order' => 2],
                    ['material' => 'Filtered Water', 'qty' => 32.00, 'uom' => 'Liter', 'notes' => 'Chilled water', 'order' => 3],
                    ['material' => 'Citric Acid', 'qty' => 0.30, 'uom' => 'Kilogram', 'notes' => 'Enhance flavor', 'order' => 4],
                    ['material' => 'Sodium Benzoate', 'qty' => 0.20, 'uom' => 'Kilogram', 'notes' => 'Preservative', 'order' => 5],
                ],
            ],
            [
                'product_name' => 'Pineapple Juice 1L',
                'recipe_name' => 'Fresh Pineapple Juice - Industrial Recipe',
                'version' => '1.0',
                'instructions' => "1. Peel and core 480kg fresh pineapples\n2. Chop into chunks\n3. Extract juice using screw press\n4. Filter to remove fiber\n5. Mix with water and sugar\n6. Add citric acid for balance\n7. Add preservative\n8. Pasteurize at 85°C for 15 seconds\n9. Cool rapidly\n10. Bottle and seal",
                'batch_size' => 100.00,
                'uom' => 'Liter',
                'prep_time' => 30.00,
                'cook_time' => 35.00,
                'ingredients' => [
                    ['material' => 'Fresh Pineapple', 'qty' => 480.00, 'uom' => 'Kilogram', 'notes' => 'Remove crown and skin', 'order' => 1],
                    ['material' => 'White Sugar', 'qty' => 12.00, 'uom' => 'Kilogram', 'notes' => 'Light sweetening', 'order' => 2],
                    ['material' => 'Filtered Water', 'qty' => 35.00, 'uom' => 'Liter', 'notes' => 'Dilute concentrate', 'order' => 3],
                    ['material' => 'Citric Acid', 'qty' => 0.20, 'uom' => 'Kilogram', 'notes' => 'Preserve vitamin C', 'order' => 4],
                    ['material' => 'Sodium Benzoate', 'qty' => 0.20, 'uom' => 'Kilogram', 'notes' => 'Antimicrobial', 'order' => 5],
                ],
            ],

            // === VEGETABLE JUICES ===
            [
                'product_name' => 'Carrot Juice 250ml',
                'recipe_name' => 'Fresh Carrot Juice - Small Batch',
                'version' => '1.0',
                'instructions' => "1. Wash and peel 40kg carrots\n2. Cut into pieces\n3. Extract juice using centrifugal juicer\n4. Add honey for natural sweetness\n5. Mix with filtered water\n6. Add vitamin C and preservative\n7. Pasteurize at 80°C for 20 seconds\n8. Cool to 4°C\n9. Fill 250ml bottles\n10. Cap and label",
                'batch_size' => 25.00,
                'uom' => 'Liter',
                'prep_time' => 20.00,
                'cook_time' => 30.00,
                'ingredients' => [
                    ['material' => 'Fresh Carrot', 'qty' => 40.00, 'uom' => 'Kilogram', 'notes' => 'Organic carrots preferred', 'order' => 1],
                    ['material' => 'Honey', 'qty' => 0.50, 'uom' => 'Kilogram', 'notes' => 'Natural sweetener', 'order' => 2],
                    ['material' => 'Filtered Water', 'qty' => 2.00, 'uom' => 'Liter', 'notes' => 'Minimal water', 'order' => 3],
                    ['material' => 'Ascorbic Acid (Vitamin C)', 'qty' => 0.02, 'uom' => 'Kilogram', 'notes' => 'Fortification', 'order' => 4],
                    ['material' => 'Sodium Benzoate', 'qty' => 0.01, 'uom' => 'Kilogram', 'notes' => 'Preservation', 'order' => 5],
                ],
            ],
            [
                'product_name' => 'Tomato Juice 250ml',
                'recipe_name' => 'Fresh Tomato Juice - Small Batch',
                'version' => '1.0',
                'instructions' => "1. Sort and wash 42.5kg ripe tomatoes\n2. Blanch briefly to remove skin\n3. Extract juice through press\n4. Add minimal sugar\n5. Mix with filtered water\n6. Add citric acid and preservative\n7. Pasteurize at 85°C for 15 seconds\n8. Cool quickly\n9. Fill bottles\n10. Seal and inspect",
                'batch_size' => 25.00,
                'uom' => 'Liter',
                'prep_time' => 18.00,
                'cook_time' => 28.00,
                'ingredients' => [
                    ['material' => 'Fresh Tomato', 'qty' => 42.50, 'uom' => 'Kilogram', 'notes' => 'Red ripe tomatoes', 'order' => 1],
                    ['material' => 'White Sugar', 'qty' => 0.50, 'uom' => 'Kilogram', 'notes' => 'Minimal sweetness', 'order' => 2],
                    ['material' => 'Filtered Water', 'qty' => 1.25, 'uom' => 'Liter', 'notes' => 'Light dilution', 'order' => 3],
                    ['material' => 'Citric Acid', 'qty' => 0.025, 'uom' => 'Kilogram', 'notes' => 'pH balance', 'order' => 4],
                    ['material' => 'Sodium Benzoate', 'qty' => 0.0125, 'uom' => 'Kilogram', 'notes' => 'Shelf life', 'order' => 5],
                ],
            ],
            [
                'product_name' => 'Beetroot Juice 250ml',
                'recipe_name' => 'Fresh Beetroot Juice - Small Batch',
                'version' => '1.0',
                'instructions' => "1. Wash and peel 45kg fresh beetroots\n2. Chop into chunks\n3. Extract juice using cold press\n4. Add honey for sweetness\n5. Dilute with filtered water\n6. Add citric acid and preservative\n7. Pasteurize at 80°C for 20 seconds\n8. Cool to 4°C\n9. Bottle immediately\n10. Cap and label",
                'batch_size' => 25.00,
                'uom' => 'Liter',
                'prep_time' => 25.00,
                'cook_time' => 32.00,
                'ingredients' => [
                    ['material' => 'Fresh Beetroot', 'qty' => 45.00, 'uom' => 'Kilogram', 'notes' => 'Deep red beets', 'order' => 1],
                    ['material' => 'Honey', 'qty' => 0.75, 'uom' => 'Kilogram', 'notes' => 'Balance earthy flavor', 'order' => 2],
                    ['material' => 'Filtered Water', 'qty' => 2.00, 'uom' => 'Liter', 'notes' => 'Dilute concentration', 'order' => 3],
                    ['material' => 'Citric Acid', 'qty' => 0.025, 'uom' => 'Kilogram', 'notes' => 'Brighten flavor', 'order' => 4],
                    ['material' => 'Sodium Benzoate', 'qty' => 0.0125, 'uom' => 'Kilogram', 'notes' => 'Preserve color', 'order' => 5],
                ],
            ],

            // === MIXED BEVERAGES ===
            [
                'product_name' => 'Mixed Fruit Juice 1L',
                'recipe_name' => 'Tropical Mix Juice - Industrial Recipe',
                'version' => '1.0',
                'instructions' => "1. Prepare 200kg oranges, 200kg apples, 150kg mangoes\n2. Extract juice from each fruit separately\n3. Filter all juices\n4. Blend in ratio 40:40:20 (orange:apple:mango)\n5. Mix with water and sugar\n6. Add citric acid and preservative\n7. Homogenize blend\n8. Pasteurize at 85°C for 15 seconds\n9. Cool rapidly to 4°C\n10. Fill, seal, and package",
                'batch_size' => 100.00,
                'uom' => 'Liter',
                'prep_time' => 35.00,
                'cook_time' => 40.00,
                'ingredients' => [
                    ['material' => 'Fresh Orange', 'qty' => 200.00, 'uom' => 'Kilogram', 'notes' => 'Sweet variety', 'order' => 1],
                    ['material' => 'Fresh Apple', 'qty' => 200.00, 'uom' => 'Kilogram', 'notes' => 'Crisp apples', 'order' => 2],
                    ['material' => 'Fresh Mango', 'qty' => 150.00, 'uom' => 'Kilogram', 'notes' => 'Ripe mangoes', 'order' => 3],
                    ['material' => 'White Sugar', 'qty' => 18.00, 'uom' => 'Kilogram', 'notes' => 'Balance blend', 'order' => 4],
                    ['material' => 'Filtered Water', 'qty' => 28.00, 'uom' => 'Liter', 'notes' => 'Final dilution', 'order' => 5],
                    ['material' => 'Citric Acid', 'qty' => 0.30, 'uom' => 'Kilogram', 'notes' => 'Enhance flavor', 'order' => 6],
                    ['material' => 'Sodium Benzoate', 'qty' => 0.20, 'uom' => 'Kilogram', 'notes' => 'Preservation', 'order' => 7],
                ],
            ],
        ];

        $recipeCount = 0;
        $ingredientCount = 0;

        foreach ($recipesData as $recipeData) {
            $product = $products->get($recipeData['product_name']);
            $uom = $uoms->get($recipeData['uom']);
            
            if (!$product || !$uom) {
                $this->command->warn("Skipping recipe for: {$recipeData['product_name']}");
                continue;
            }

            // Generate unique recipe code
            $recipeCode = CodeGeneratorHelper::generateRecipeCode();

            // Insert recipe
            $recipeId = DB::table('recipes')->insertGetId([
                'recipe_code' => $recipeCode,
                'product_id' => $product->product_id,
                'recipe_name' => $recipeData['recipe_name'],
                'recipe_version' => $recipeData['version'],
                'instructions' => $recipeData['instructions'],
                'batch_size' => $recipeData['batch_size'],
                'uom_id' => $uom->uom_id,
                'preparation_time' => $recipeData['prep_time'],
                'cooking_time' => $recipeData['cook_time'],
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $recipeCount++;

            // Insert recipe ingredients
            foreach ($recipeData['ingredients'] as $ingredientData) {
                $material = $products->get($ingredientData['material']);
                $ingredientUom = $uoms->get($ingredientData['uom']);
                
                if (!$material || !$ingredientUom) {
                    $this->command->warn("Skipping ingredient: {$ingredientData['material']}");
                    continue;
                }

                DB::table('recipe_ingredients')->insert([
                    'recipe_id' => $recipeId,
                    'material_id' => $material->product_id,
                    'quantity' => $ingredientData['qty'],
                    'uom_id' => $ingredientUom->uom_id,
                    'preparation_notes' => $ingredientData['notes'],
                    'sequence_order' => $ingredientData['order'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $ingredientCount++;
            }
        }

        $this->command->info("{$recipeCount} recipes seeded");
        $this->command->info("{$ingredientCount} recipe ingredients seeded");
    }
}
