<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Seed the database with the default user roles.
     */
    public function run(): void
    {
        // Define default roles to be created
        $defaultRoles = [
            [
                'role_name'   => 'Administrator',
                'role_code'   => 'admin',
                'description' => 'Full system access — Owner or administrator with access to all modules.',
                'created_at'  => now(),
            ],
            [
                'role_name'   => 'Operational Staff',
                'role_code'   => 'operator',
                'description' => 'Access to production, inventory, and operations — staff responsible for daily operations.',
                'created_at'  => now(),
            ],
            [
                'role_name'   => 'Finance & HR Staff',
                'role_code'   => 'finance_hr',
                'description' => 'Access to finance, HR, and administrative modules — staff managing financial and human resource activities.',
                'created_at'  => now(),
            ],
        ];

        DB::table('roles')->insert($defaultRoles);
        $this->command->info('✅ default roles have been added successfully!');
    }
}
