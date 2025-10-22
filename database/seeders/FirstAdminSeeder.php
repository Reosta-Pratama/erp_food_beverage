<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\UserManagement\Role;
use App\Models\UserManagement\User;

class FirstAdminSeeder extends Seeder
{
    /**
     * Seed the database with the initial administrator account.
     */
    public function run(): void
    {
        // Try to find the Admin role record
        $adminRole = Role::where('role_code', 'admin')->first();

        // Ensure the admin role exists before creating an admin user
        if (!$adminRole) {
            $this->command->error('❌ The "admin" role was not found. Please run the RoleSeeder first.');
            return;
        }

        // Check if an admin user already exists
        $adminUser = User::where('username', 'admin')->first();

        if ($adminUser) {
            $this->command->warn('⚠️  The administrator account already exists. No new account was created.');
            return;
        }

        // Create the first administrator account
        User::create([
            'username'      => 'admin',
            'email'         => 'admin@erpfb.com',
            'password_hash' => Hash::make('12341234'),
            'full_name'     => 'System Administrator',
            'phone'         => '08123456789',
            'role_id'       => $adminRole->role_id,
            'employee_id'   => null,
            'is_active'     => true,
        ]);

        // Output success information in a clear, structured format
        $this->command->info('✅ Administrator account created successfully!');
        $this->command->line('');
        $this->command->info('=== Login Information ===');
        $this->command->line('URL      : http://localhost:8000/login');
        $this->command->line('Username : admin');
        $this->command->line('Password : 12341234');
        $this->command->line('');
        $this->command->warn('⚠️  IMPORTANT: Please change the password immediately after the first login.');
    }
}
