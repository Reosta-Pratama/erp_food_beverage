<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $now = Carbon::now();
        
        $permissions = [
            // ====================================
            // USER MANAGEMENT MODULE
            // ====================================
            [
                'module_name' => 'User Management',
                'permission_name' => 'Manage Users',
                'permission_code' => 'users.manage',
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => $now,
            ],
            [
                'module_name' => 'User Management',
                'permission_name' => 'Manage Roles',
                'permission_code' => 'roles.manage',
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => $now,
            ],
            [
                'module_name' => 'User Management',
                'permission_name' => 'Manage Permissions',
                'permission_code' => 'permissions.manage',
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => $now,
            ],
            [
                'module_name' => 'User Management',
                'permission_name' => 'View Activity Logs',
                'permission_code' => 'activity_logs.view',
                'can_create' => false,
                'can_read' => true,
                'can_update' => false,
                'can_delete' => true,
                'created_at' => $now,
            ],
            [
                'module_name' => 'User Management',
                'permission_name' => 'View Audit Logs',
                'permission_code' => 'audit_logs.view',
                'can_create' => false,
                'can_read' => true,
                'can_update' => false,
                'can_delete' => true,
                'created_at' => $now,
            ],

            // ====================================
            // HRM MODULE
            // ====================================
            [
                'module_name' => 'HRM',
                'permission_name' => 'Manage Employees',
                'permission_code' => 'employees.manage',
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => $now,
            ],
            [
                'module_name' => 'HRM',
                'permission_name' => 'Manage Attendance',
                'permission_code' => 'attendance.manage',
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => $now,
            ],
            [
                'module_name' => 'HRM',
                'permission_name' => 'Manage Leave',
                'permission_code' => 'leave.manage',
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => $now,
            ],
            [
                'module_name' => 'HRM',
                'permission_name' => 'Manage Payroll',
                'permission_code' => 'payroll.manage',
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => $now,
            ],

            // ====================================
            // INVENTORY MODULE
            // ====================================
            [
                'module_name' => 'Inventory',
                'permission_name' => 'Manage Products',
                'permission_code' => 'products.manage',
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => $now,
            ],
            [
                'module_name' => 'Inventory',
                'permission_name' => 'Manage BOM',
                'permission_code' => 'bom.manage',
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => $now,
            ],
            [
                'module_name' => 'Inventory',
                'permission_name' => 'Manage Warehouse',
                'permission_code' => 'warehouse.manage',
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => $now,
            ],
            [
                'module_name' => 'Inventory',
                'permission_name' => 'View Stock',
                'permission_code' => 'stock.view',
                'can_create' => false,
                'can_read' => true,
                'can_update' => false,
                'can_delete' => false,
                'created_at' => $now,
            ],

            // ====================================
            // PRODUCTION MODULE
            // ====================================
            [
                'module_name' => 'Production',
                'permission_name' => 'Manage Work Orders',
                'permission_code' => 'work_orders.manage',
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => $now,
            ],
            [
                'module_name' => 'Production',
                'permission_name' => 'Manage Production Planning',
                'permission_code' => 'production_planning.manage',
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => $now,
            ],
            [
                'module_name' => 'Production',
                'permission_name' => 'Manage Quality Control',
                'permission_code' => 'quality_control.manage',
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => $now,
            ],

            // ====================================
            // BUSINESS MANAGEMENT MODULE
            // ====================================
            [
                'module_name' => 'Business Management',
                'permission_name' => 'Manage Purchase Orders',
                'permission_code' => 'purchase_orders.manage',
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => $now,
            ],
            [
                'module_name' => 'Business Management',
                'permission_name' => 'Manage Sales Orders',
                'permission_code' => 'sales_orders.manage',
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => $now,
            ],
            [
                'module_name' => 'Business Management',
                'permission_name' => 'Manage Suppliers',
                'permission_code' => 'suppliers.manage',
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => $now,
            ],
            [
                'module_name' => 'Business Management',
                'permission_name' => 'Manage Customers',
                'permission_code' => 'customers.manage',
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => $now,
            ],
            [
                'module_name' => 'Business Management',
                'permission_name' => 'Manage Delivery',
                'permission_code' => 'delivery.manage',
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => $now,
            ],

            // ====================================
            // FINANCE MODULE
            // ====================================
            [
                'module_name' => 'Finance',
                'permission_name' => 'Manage Accounts',
                'permission_code' => 'accounts.manage',
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => $now,
            ],
            [
                'module_name' => 'Finance',
                'permission_name' => 'Manage Payments',
                'permission_code' => 'payments.manage',
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => $now,
            ],
            [
                'module_name' => 'Finance',
                'permission_name' => 'View Financial Reports',
                'permission_code' => 'finance_reports.view',
                'can_create' => false,
                'can_read' => true,
                'can_update' => false,
                'can_delete' => false,
                'created_at' => $now,
            ],

            // ====================================
            // MAINTENANCE MODULE
            // ====================================
            [
                'module_name' => 'Maintenance',
                'permission_name' => 'Manage Maintenance',
                'permission_code' => 'maintenance.manage',
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => $now,
            ],

            // ====================================
            // QUALITY ASSURANCE MODULE
            // ====================================
            [
                'module_name' => 'Quality Assurance',
                'permission_name' => 'Manage Quality Assurance',
                'permission_code' => 'quality_assurance.manage',
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => $now,
            ],

            // ====================================
            // LOGISTICS MODULE
            // ====================================
            [
                'module_name' => 'Logistics',
                'permission_name' => 'Manage Logistics',
                'permission_code' => 'logistics.manage',
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => $now,
            ],

            // ====================================
            // ANNOUNCEMENT MODULE
            // ====================================
            [
                'module_name' => 'Announcement',
                'permission_name' => 'Manage Announcements',
                'permission_code' => 'announcements.manage',
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => $now,
            ],
            [
                'module_name' => 'Announcement',
                'permission_name' => 'View Announcements',
                'permission_code' => 'announcements.view',
                'can_create' => false,
                'can_read' => true,
                'can_update' => false,
                'can_delete' => false,
                'created_at' => $now,
            ],

            // ====================================
            // REPORTS MODULE
            // ====================================
            [
                'module_name' => 'Reports',
                'permission_name' => 'View Production Reports',
                'permission_code' => 'production_reports.view',
                'can_create' => false,
                'can_read' => true,
                'can_update' => false,
                'can_delete' => false,
                'created_at' => $now,
            ],
            [
                'module_name' => 'Reports',
                'permission_name' => 'View Stock Reports',
                'permission_code' => 'stock_reports.view',
                'can_create' => false,
                'can_read' => true,
                'can_update' => false,
                'can_delete' => false,
                'created_at' => $now,
            ],
            [
                'module_name' => 'Reports',
                'permission_name' => 'View HR Reports',
                'permission_code' => 'hr_reports.view',
                'can_create' => false,
                'can_read' => true,
                'can_update' => false,
                'can_delete' => false,
                'created_at' => $now,
            ],

            // ====================================
            // SETTINGS MODULE
            // ====================================
            [
                'module_name' => 'Settings',
                'permission_name' => 'Manage Settings',
                'permission_code' => 'settings.manage',
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => $now,
            ],

            // ====================================
            // DASHBOARD
            // ====================================
            [
                'module_name' => 'Dashboard',
                'permission_name' => 'View Dashboard',
                'permission_code' => 'dashboard.view',
                'can_create' => false,
                'can_read' => true,
                'can_update' => false,
                'can_delete' => false,
                'created_at' => $now,
            ],
        ];

        DB::table('permissions')->insert($permissions);
        $this->command->info('✅ default permissions have been added successfully!');
    }
}
