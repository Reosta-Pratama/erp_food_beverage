<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Sprint 1: User Management
            PermissionSeeder::class,
            RoleSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class, 

            // Sprint 2: Basic Master Data
            SettingsModuleSeeder::class, 
            // Sprint 2: Product Management
            ProductManagementSeeder::class,
            
            // Sprint 3: HR Foundation
            HRMModuleSeeder::class,

            // Sprint 4: Recipe & BOM
            RecipeBOMModuleSeeder::class
        ]);
    }
}
