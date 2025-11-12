<?php

namespace Database\Seeders;

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
            ['name' => 'Kilogram', 'type' => 'Weight'],
            ['name' => 'Gram', 'type' => 'Weight'],
            ['name' => 'Ton', 'type' => 'Weight'],
            ['name' => 'Milligram', 'type' => 'Weight'],
            
            // Volume
            ['name' => 'Liter', 'type' => 'Volume'],
            ['name' => 'Milliliter', 'type' => 'Volume'],
            ['name' => 'Gallon', 'type' => 'Volume'],
            ['name' => 'Cubic Meter', 'type' => 'Volume'],
            
            // Length
            ['name' => 'Meter', 'type' => 'Length'],
            ['name' => 'Centimeter', 'type' => 'Length'],
            ['name' => 'Millimeter', 'type' => 'Length'],
            ['name' => 'Kilometer', 'type' => 'Length'],
            
            // Quantity/Count
            ['name' => 'Piece', 'type' => 'Quantity'],
            ['name' => 'Box', 'type' => 'Quantity'],
            ['name' => 'Carton', 'type' => 'Quantity'],
            ['name' => 'Pack', 'type' => 'Quantity'],
            ['name' => 'Dozen', 'type' => 'Quantity'],
            ['name' => 'Unit', 'type' => 'Quantity'],
            
            // Time
            ['name' => 'Hour', 'type' => 'Time'],
            ['name' => 'Minute', 'type' => 'Time'],
            ['name' => 'Day', 'type' => 'Time'],
        ];

        foreach ($uoms as $uom) {
            do {
                $uomCode = strtoupper(Str::random(10));

                // Check if the code already exists in the database
                $exists = DB::table('units_of_measure')
                    ->where('uom_code', $uomCode)
                    ->exists();
            } while ($exists); // Repeat until we get a unique code

            // Insert new unique UOM record
            DB::table('units_of_measure')->insert([
                'uom_code' => $uomCode,
                'uom_name' => $uom['name'],
                'uom_type' => $uom['type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
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
            do {
                $taxCode = strtoupper(Str::random(10));

                // Check if the generated code already exists in the database
                $exists = DB::table('tax_rates')
                    ->where('tax_code', $taxCode)
                    ->exists();
            } while ($exists); // Repeat until a unique code is found

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
