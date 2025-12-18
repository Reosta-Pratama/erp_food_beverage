<?php

namespace Database\Seeders;

use App\Helpers\CodeGeneratorHelper;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SettingsModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $this->seedCompanyProfile();
        $this->seedUnitsOfMeasure();
        $this->seedCurrencies();
        $this->seedTaxRates();
    }

    /**
     * Seed company profile
     */
    private function seedCompanyProfile(): void
    {
        DB::table('company_profile')->insert([
            'company_name' => 'PT Nutrana Food & Beverage',
            'legal_name' => 'PT Nutrana Food & Beverage Manufacturing',
            'tax_id' => null,
            'address' => 'Jl. Antene IV',
            'city' => 'Jakarta Selatan',
            'country' => 'Indonesia',
            'postal_code' => '00000',
            'phone' => '+62 21 6907 6600',
            'email' => 'reosta.pane@gmail.com',
            'website' => 'https://www.nutrana.co.id',
            'logo_path' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('Company profile seeded');
    }

    /**
     * Seed units of measure
     */
    private function seedUnitsOfMeasure(): void
    {
        $uoms = [
            // Weight/Mass
            ['code' => 'KG', 'name' => 'Kilogram', 'type' => 'Weight'],
            ['code' => 'G', 'name' => 'Gram', 'type' => 'Weight'],
            ['code' => 'MG', 'name' => 'Milligram', 'type' => 'Weight'],
            ['code' => 'TON', 'name' => 'Ton', 'type' => 'Weight'],
            ['code' => 'MT', 'name' => 'Metric Ton', 'type' => 'Weight'],
            ['code' => 'LB', 'name' => 'Pound', 'type' => 'Weight'],
            ['code' => 'OZ', 'name' => 'Ounce', 'type' => 'Weight'],
            ['code' => 'QTL', 'name' => 'Quintal', 'type' => 'Weight'],
            
            // Volume
            ['code' => 'L', 'name' => 'Liter', 'type' => 'Volume'],
            ['code' => 'ML', 'name' => 'Milliliter', 'type' => 'Volume'],
            ['code' => 'KL', 'name' => 'Kiloliter', 'type' => 'Volume'],
            ['code' => 'GAL', 'name' => 'Gallon', 'type' => 'Volume'],
            ['code' => 'BBL', 'name' => 'Barrel', 'type' => 'Volume'],
            ['code' => 'M3', 'name' => 'Cubic Meter', 'type' => 'Volume'],
            ['code' => 'CC', 'name' => 'Cubic Centimeter', 'type' => 'Volume'],
            ['code' => 'FT3', 'name' => 'Cubic Foot', 'type' => 'Volume'],
            ['code' => 'YD3', 'name' => 'Cubic Yard', 'type' => 'Volume'],
            
            // Length
            ['code' => 'M', 'name' => 'Meter', 'type' => 'Length'],
            ['code' => 'CM', 'name' => 'Centimeter', 'type' => 'Length'],
            ['code' => 'MM', 'name' => 'Millimeter', 'type' => 'Length'],
            ['code' => 'KM', 'name' => 'Kilometer', 'type' => 'Length'],
            ['code' => 'IN', 'name' => 'Inch', 'type' => 'Length'],
            ['code' => 'FT', 'name' => 'Foot', 'type' => 'Length'],
            ['code' => 'YD', 'name' => 'Yard', 'type' => 'Length'],
            ['code' => 'MI', 'name' => 'Mile', 'type' => 'Length'],
            ['code' => 'LM', 'name' => 'Linear Meter', 'type' => 'Length'],
            ['code' => 'LF', 'name' => 'Linear Foot', 'type' => 'Length'],
            
            // Area
            ['code' => 'M2', 'name' => 'Square Meter', 'type' => 'Area'],
            ['code' => 'CM2', 'name' => 'Square Centimeter', 'type' => 'Area'],
            ['code' => 'KM2', 'name' => 'Square Kilometer', 'type' => 'Area'],
            ['code' => 'FT2', 'name' => 'Square Foot', 'type' => 'Area'],
            ['code' => 'HA', 'name' => 'Hectare', 'type' => 'Area'],
            ['code' => 'AC', 'name' => 'Acre', 'type' => 'Area'],
            ['code' => 'ARE', 'name' => 'Are', 'type' => 'Area'],
            
            // Quantity/Count
            ['code' => 'PCS', 'name' => 'Pieces', 'type' => 'Quantity'],
            ['code' => 'PC', 'name' => 'Piece', 'type' => 'Quantity'],
            ['code' => 'UNIT', 'name' => 'Unit', 'type' => 'Quantity'],
            ['code' => 'EA', 'name' => 'Each', 'type' => 'Quantity'],
            ['code' => 'ITM', 'name' => 'Item', 'type' => 'Quantity'],
            ['code' => 'PR', 'name' => 'Pair', 'type' => 'Quantity'],
            ['code' => 'SET', 'name' => 'Set', 'type' => 'Quantity'],
            
            // Packaging
            ['code' => 'BOX', 'name' => 'Box', 'type' => 'Packaging'],
            ['code' => 'CTN', 'name' => 'Carton', 'type' => 'Packaging'],
            ['code' => 'CS', 'name' => 'Case', 'type' => 'Packaging'],
            ['code' => 'PACK', 'name' => 'Pack', 'type' => 'Packaging'],
            ['code' => 'PKT', 'name' => 'Packet', 'type' => 'Packaging'],
            ['code' => 'DOZ', 'name' => 'Dozen', 'type' => 'Packaging'],
            ['code' => 'GR', 'name' => 'Gross', 'type' => 'Packaging'],
            ['code' => 'PLT', 'name' => 'Pallet', 'type' => 'Packaging'],
            ['code' => 'CNTR', 'name' => 'Container', 'type' => 'Packaging'],
            ['code' => 'BAG', 'name' => 'Bag', 'type' => 'Packaging'],
            ['code' => 'SACK', 'name' => 'Sack', 'type' => 'Packaging'],
            ['code' => 'PCH', 'name' => 'Pouch', 'type' => 'Packaging'],
            ['code' => 'CRT', 'name' => 'Crate', 'type' => 'Packaging'],
            
            // Roll & Bundle
            ['code' => 'RL', 'name' => 'Roll', 'type' => 'Roll'],
            ['code' => 'BDL', 'name' => 'Bundle', 'type' => 'Bundle'],
            ['code' => 'BALE', 'name' => 'Bale', 'type' => 'Bundle'],
            ['code' => 'COIL', 'name' => 'Coil', 'type' => 'Roll'],
            ['code' => 'REEL', 'name' => 'Reel', 'type' => 'Roll'],
            ['code' => 'SKID', 'name' => 'Skid', 'type' => 'Packaging'],
            
            // Container
            ['code' => 'BTL', 'name' => 'Bottle', 'type' => 'Container'],
            ['code' => 'CAN', 'name' => 'Can', 'type' => 'Container'],
            ['code' => 'JAR', 'name' => 'Jar', 'type' => 'Container'],
            ['code' => 'JUG', 'name' => 'Jug', 'type' => 'Container'],
            ['code' => 'DRM', 'name' => 'Drum', 'type' => 'Container'],
            ['code' => 'TNK', 'name' => 'Tank', 'type' => 'Container'],
            ['code' => 'TUBE', 'name' => 'Tube', 'type' => 'Container'],
            ['code' => 'VIAL', 'name' => 'Vial', 'type' => 'Container'],
            ['code' => 'AMP', 'name' => 'Ampoule', 'type' => 'Container'],
            
            // Time
            ['code' => 'HR', 'name' => 'Hour', 'type' => 'Time'],
            ['code' => 'MIN', 'name' => 'Minute', 'type' => 'Time'],
            ['code' => 'SEC', 'name' => 'Second', 'type' => 'Time'],
            ['code' => 'DAY', 'name' => 'Day', 'type' => 'Time'],
            ['code' => 'WK', 'name' => 'Week', 'type' => 'Time'],
            ['code' => 'MO', 'name' => 'Month', 'type' => 'Time'],
            ['code' => 'YR', 'name' => 'Year', 'type' => 'Time'],
            
            // Paper & Sheet
            ['code' => 'RM', 'name' => 'Ream', 'type' => 'Paper'],
            ['code' => 'SHT', 'name' => 'Sheet', 'type' => 'Paper'],
            ['code' => 'PG', 'name' => 'Page', 'type' => 'Paper'],
            ['code' => 'BK', 'name' => 'Book', 'type' => 'Paper'],
            ['code' => 'PAD', 'name' => 'Pad', 'type' => 'Paper'],
            
            // Food & Beverage
            ['code' => 'SLC', 'name' => 'Slice', 'type' => 'Food'],
            ['code' => 'LOAF', 'name' => 'Loaf', 'type' => 'Food'],
            ['code' => 'SRV', 'name' => 'Serving', 'type' => 'Food'],
            ['code' => 'POR', 'name' => 'Portion', 'type' => 'Food'],
            ['code' => 'TRY', 'name' => 'Tray', 'type' => 'Food'],
            ['code' => 'CUP', 'name' => 'Cup', 'type' => 'Food'],
            ['code' => 'GLS', 'name' => 'Glass', 'type' => 'Food'],
            
            // Medical & Pharmaceutical
            ['code' => 'TAB', 'name' => 'Tablet', 'type' => 'Medical'],
            ['code' => 'CAP', 'name' => 'Capsule', 'type' => 'Medical'],
            ['code' => 'STRIP', 'name' => 'Strip', 'type' => 'Medical'],
            ['code' => 'BLSTR', 'name' => 'Blister', 'type' => 'Medical'],
            ['code' => 'DOSE', 'name' => 'Dose', 'type' => 'Medical'],
            ['code' => 'INJ', 'name' => 'Injection', 'type' => 'Medical'],
            
            // Textile
            ['code' => 'MTR', 'name' => 'Meter of Cloth', 'type' => 'Textile'],
            ['code' => 'BOLT', 'name' => 'Bolt', 'type' => 'Textile'],
            ['code' => 'LEN', 'name' => 'Length', 'type' => 'Textile'],
            
            // Construction
            ['code' => 'BF', 'name' => 'Board Foot', 'type' => 'Construction'],
            
            // Energy & Power
            ['code' => 'W', 'name' => 'Watt', 'type' => 'Energy'],
            ['code' => 'KW', 'name' => 'Kilowatt', 'type' => 'Energy'],
            ['code' => 'MW', 'name' => 'Megawatt', 'type' => 'Energy'],
            ['code' => 'KWH', 'name' => 'Kilowatt Hour', 'type' => 'Energy'],
            ['code' => 'J', 'name' => 'Joule', 'type' => 'Energy'],
            ['code' => 'CAL', 'name' => 'Calorie', 'type' => 'Energy'],
            
            // Data & Digital
            ['code' => 'BIT', 'name' => 'Bit', 'type' => 'Data'],
            ['code' => 'B', 'name' => 'Byte', 'type' => 'Data'],
            ['code' => 'KB', 'name' => 'Kilobyte', 'type' => 'Data'],
            ['code' => 'MB', 'name' => 'Megabyte', 'type' => 'Data'],
            ['code' => 'GB', 'name' => 'Gigabyte', 'type' => 'Data'],
            ['code' => 'TB', 'name' => 'Terabyte', 'type' => 'Data'],
            
            // Others
            ['code' => 'LD', 'name' => 'Load', 'type' => 'Other'],
            ['code' => 'LOT', 'name' => 'Lot', 'type' => 'Other'],
            ['code' => 'BCH', 'name' => 'Batch', 'type' => 'Other'],
            ['code' => 'SMPL', 'name' => 'Sample', 'type' => 'Other'],
            ['code' => 'TRIP', 'name' => 'Trip', 'type' => 'Other'],
            ['code' => 'VST', 'name' => 'Visit', 'type' => 'Other'],
            ['code' => 'SESS', 'name' => 'Session', 'type' => 'Other'],
            ['code' => 'CYC', 'name' => 'Cycle', 'type' => 'Other'],
        ];

        foreach ($uoms as $uom) {
            // Check if the code already exists in the database
            $exists = DB::table('units_of_measure')
                ->where('uom_code', $uom['code'])
                ->exists();

            // Only insert if it doesn't exist
            if (!$exists) {
                DB::table('units_of_measure')->insert([
                    'uom_code' => $uom['code'],
                    'uom_name' => $uom['name'],
                    'uom_type' => $uom['type'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info(count($uoms) . ' units of measure seeded');
    }

    /**
     * Seed currencies
     */
    private function seedCurrencies(): void
    {
        $currencies = [
            [
                'code' => 'IDR',
                'name' => 'Indonesian Rupiah',
                'symbol' => 'Rp',
                'rate' => 1.000000,
                'is_base' => true,
            ],
            [
                'code' => 'USD',
                'name' => 'US Dollar',
                'symbol' => '$',
                'rate' => 0.000065,
                'is_base' => false,
            ],
            [
                'code' => 'EUR',
                'name' => 'Euro',
                'symbol' => '€',
                'rate' => 0.000060,
                'is_base' => false,
            ],
            [
                'code' => 'SGD',
                'name' => 'Singapore Dollar',
                'symbol' => 'S$',
                'rate' => 0.000087,
                'is_base' => false,
            ],
            [
                'code' => 'MYR',
                'name' => 'Malaysian Ringgit',
                'symbol' => 'RM',
                'rate' => 0.000299,
                'is_base' => false,
            ],
        ];

        foreach ($currencies as $currency) {
            DB::table('currencies')->insert([
                'currency_code' => $currency['code'],
                'currency_name' => $currency['name'],
                'symbol' => $currency['symbol'],
                'exchange_rate' => $currency['rate'],
                'is_base_currency' => $currency['is_base'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info(count($currencies) . ' currencies seeded');
    }

    /**
     * Seed tax rates
     */
    private function seedTaxRates(): void
    {
        $taxRates = [
            [
                'name' => 'PPN (Value Added Tax)',
                'percentage' => 11.00,
                'type' => 'VAT',
                'effective_date' => '2022-04-01',
                'is_active' => true,
            ],
            [
                'name' => 'PPh 21 (Income Tax - Employee)',
                'percentage' => 5.00,
                'type' => 'Income Tax',
                'effective_date' => '2021-01-01',
                'is_active' => true,
            ],
            [
                'name' => 'PPh 22 (Import Tax)',
                'percentage' => 2.50,
                'type' => 'Import Tax',
                'effective_date' => '2021-01-01',
                'is_active' => true,
            ],
            [
                'name' => 'PPh 23 (Income Tax - Services)',
                'percentage' => 2.00,
                'type' => 'Income Tax',
                'effective_date' => '2021-01-01',
                'is_active' => true,
            ],
            [
                'name' => 'PPh 4(2) Final Tax',
                'percentage' => 0.50,
                'type' => 'Final Tax',
                'effective_date' => '2021-01-01',
                'is_active' => true,
            ],
            [
                'name' => 'No Tax',
                'percentage' => 0.00,
                'type' => 'Exempt',
                'effective_date' => '2020-01-01',
                'is_active' => true,
            ],
        ];

        foreach ($taxRates as $tax) {
            $taxCode = CodeGeneratorHelper::generateTaxRateCode();

            DB::table('tax_rates')->insert([
                'tax_code' => $taxCode,
                'tax_name' => $tax['name'],
                'tax_percentage' => $tax['percentage'],
                'tax_type' => $tax['type'],
                'effective_date' => $tax['effective_date'],
                'is_active' => $tax['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info(count($taxRates) . ' tax rates seeded');
    }
}
