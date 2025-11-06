<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $now = Carbon::now();

        // Get role IDs
        $adminRoleId = DB::table('roles')->where('role_code', 'admin')->value('role_id');
        $operatorRoleId = DB::table('roles')->where('role_code', 'operator')->value('role_id');
        $financeHrRoleId = DB::table('roles')->where('role_code', 'finance_hr')->value('role_id');

        $users = [
            [
                'full_name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@erp.com',
                'password_hash' => Hash::make('password123'),
                'role_id' => $adminRoleId,
                'is_active'     => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'full_name' => 'Production Operator',
                'username' => 'operator',
                'email' => 'operator@erp.com',
                'password_hash' => Hash::make('password123'),
                'role_id' => $operatorRoleId,
                'is_active'     => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'full_name' => 'Finance Staff',
                'username' => 'finance',
                'email' => 'finance@erp.com',
                'password_hash' => Hash::make('password123'),
                'role_id' => $financeHrRoleId,
                'is_active'     => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('users')->insert($users);
        $this->command->info('✅ default users have been added successfully!');
    }
}
