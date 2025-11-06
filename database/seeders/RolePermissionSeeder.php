<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
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

        // Get all permission IDs
        $allPermissions = DB::table('permissions')->pluck('permission_id')->toArray();

        // ADMIN: Full Access to ALL permissions
        $adminPermissions = [];
        foreach ($allPermissions as $permissionId) {
            $adminPermissions[] = [
                'role_id' => $adminRoleId,
                'permission_id' => $permissionId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // OPERATOR: Access to production, inventory, quality, maintenance
        $operatorPermissionCodes = [
            'dashboard.view',
            'products.manage',
            'bom.manage',
            'warehouse.manage',
            'stock.view',
            'work_orders.manage',
            'production_planning.manage',
            'quality_control.manage',
            'sales_orders.manage',
            'delivery.manage',
            'maintenance.manage',
            'quality_assurance.manage',
            'logistics.manage',
            'announcements.view',
            'production_reports.view',
            'stock_reports.view',
        ];

        $operatorPermissions = DB::table('permissions')
            ->whereIn('permission_code', $operatorPermissionCodes)
            ->pluck('permission_id')
            ->map(function ($permissionId) use ($operatorRoleId, $now) {
                return [
                    'role_id' => $operatorRoleId,
                    'permission_id' => $permissionId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            })
            ->toArray();

         // FINANCE_HR: Access to finance, HR, announcements
        $financeHrPermissionCodes = [
            'dashboard.view',
            'employees.manage',
            'attendance.manage',
            'leave.manage',
            'payroll.manage',
            'purchase_orders.manage',
            'customers.manage',
            'accounts.manage',
            'payments.manage',
            'finance_reports.view',
            'announcements.manage',
            'announcements.view',
            'hr_reports.view',
        ];

        $financeHrPermissions = DB::table('permissions')
            ->whereIn('permission_code', $financeHrPermissionCodes)
            ->pluck('permission_id')
            ->map(function ($permissionId) use ($financeHrRoleId, $now) {
                return [
                    'role_id' => $financeHrRoleId,
                    'permission_id' => $permissionId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            })
            ->toArray();

        // Insert all role-permission assignments
        DB::table('role_permissions')->insert(array_merge(
            $adminPermissions,
            $operatorPermissions,
            $financeHrPermissions
        ));
        $this->command->info('✅ default role permissions have been added successfully!');
    }
}
