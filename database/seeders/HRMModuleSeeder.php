<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HRMModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedDepartments();
        $this->seedPositions();
        $this->seedEmployees();
    }

    /**
     * Seed departments
     */
    private function seedDepartments(): void
    {
        $departments = [
            [
                'name' => 'Executive Management',
                'description' => 'Top-level management and strategic planning',
            ],
            [
                'name' => 'Production',
                'description' => 'Manufacturing and production operations',
            ],
            [
                'name' => 'Quality Control',
                'description' => 'Quality assurance and compliance',
            ],
            [
                'name' => 'Supply Chain & Logistics',
                'description' => 'Procurement, warehousing, and distribution',
            ],
            [
                'name' => 'Sales & Marketing',
                'description' => 'Sales operations and marketing activities',
            ],
            [
                'name' => 'Finance & Accounting',
                'description' => 'Financial management and reporting',
            ],
            [
                'name' => 'Human Resources',
                'description' => 'HR operations and employee management',
            ],
            [
                'name' => 'Research & Development',
                'description' => 'Product development and innovation',
            ],
            [
                'name' => 'IT & Systems',
                'description' => 'Information technology and system support',
            ],
            [
                'name' => 'Maintenance',
                'description' => 'Equipment and facility maintenance',
            ],
        ];

        foreach ($departments as $dept) {
            DB::table('departments')->insert([
                'department_code' => strtoupper(Str::random(10)),
                'department_name' => $dept['name'],
                'manager_id' => null, // Will be assigned later
                'description' => $dept['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info(count($departments) . ' departments seeded');
    }

    /**
     * Seed positions
     */
    private function seedPositions(): void
    {
        $departments = DB::table('departments')->get()->keyBy('department_name');

        $positions = [
            // Executive Management
            ['dept' => 'Executive Management', 'name' => 'Chief Executive Officer (CEO)', 'desc' => 'Overall company leadership'],
            ['dept' => 'Executive Management', 'name' => 'Chief Operations Officer (COO)', 'desc' => 'Operations management'],
            ['dept' => 'Executive Management', 'name' => 'Chief Financial Officer (CFO)', 'desc' => 'Financial oversight'],
            
            // Production
            ['dept' => 'Production', 'name' => 'Production Manager', 'desc' => 'Manage production operations'],
            ['dept' => 'Production', 'name' => 'Production Supervisor', 'desc' => 'Supervise production staff'],
            ['dept' => 'Production', 'name' => 'Production Operator', 'desc' => 'Execute production tasks'],
            ['dept' => 'Production', 'name' => 'Machine Operator', 'desc' => 'Operate production machinery'],
            
            // Quality Control
            ['dept' => 'Quality Control', 'name' => 'QC Manager', 'desc' => 'Manage quality control department'],
            ['dept' => 'Quality Control', 'name' => 'QC Inspector', 'desc' => 'Perform quality inspections'],
            ['dept' => 'Quality Control', 'name' => 'QC Analyst', 'desc' => 'Analyze quality data'],
            
            // Supply Chain & Logistics
            ['dept' => 'Supply Chain & Logistics', 'name' => 'Supply Chain Manager', 'desc' => 'Manage supply chain operations'],
            ['dept' => 'Supply Chain & Logistics', 'name' => 'Procurement Officer', 'desc' => 'Handle purchasing'],
            ['dept' => 'Supply Chain & Logistics', 'name' => 'Warehouse Supervisor', 'desc' => 'Supervise warehouse operations'],
            ['dept' => 'Supply Chain & Logistics', 'name' => 'Logistics Coordinator', 'desc' => 'Coordinate deliveries'],
            
            // Sales & Marketing
            ['dept' => 'Sales & Marketing', 'name' => 'Sales Manager', 'desc' => 'Manage sales team'],
            ['dept' => 'Sales & Marketing', 'name' => 'Sales Executive', 'desc' => 'Handle sales activities'],
            ['dept' => 'Sales & Marketing', 'name' => 'Marketing Manager', 'desc' => 'Manage marketing activities'],
            
            // Finance & Accounting
            ['dept' => 'Finance & Accounting', 'name' => 'Finance Manager', 'desc' => 'Manage finance department'],
            ['dept' => 'Finance & Accounting', 'name' => 'Accountant', 'desc' => 'Handle accounting tasks'],
            ['dept' => 'Finance & Accounting', 'name' => 'Finance Staff', 'desc' => 'Support finance operations'],
            
            // Human Resources
            ['dept' => 'Human Resources', 'name' => 'HR Manager', 'desc' => 'Manage HR department'],
            ['dept' => 'Human Resources', 'name' => 'HR Officer', 'desc' => 'Handle HR operations'],
            ['dept' => 'Human Resources', 'name' => 'Recruitment Specialist', 'desc' => 'Handle recruitment'],
            
            // R&D
            ['dept' => 'Research & Development', 'name' => 'R&D Manager', 'desc' => 'Manage R&D activities'],
            ['dept' => 'Research & Development', 'name' => 'Product Developer', 'desc' => 'Develop new products'],
            ['dept' => 'Research & Development', 'name' => 'Food Technologist', 'desc' => 'Food technology specialist'],
            
            // IT & Systems
            ['dept' => 'IT & Systems', 'name' => 'IT Manager', 'desc' => 'Manage IT department'],
            ['dept' => 'IT & Systems', 'name' => 'System Administrator', 'desc' => 'Manage systems'],
            ['dept' => 'IT & Systems', 'name' => 'IT Support', 'desc' => 'Provide IT support'],
            
            // Maintenance
            ['dept' => 'Maintenance', 'name' => 'Maintenance Manager', 'desc' => 'Manage maintenance department'],
            ['dept' => 'Maintenance', 'name' => 'Maintenance Technician', 'desc' => 'Perform maintenance tasks'],
            ['dept' => 'Maintenance', 'name' => 'Electrician', 'desc' => 'Handle electrical maintenance'],
        ];

        foreach ($positions as $pos) {
            $dept = $departments->get($pos['dept']);
            if (!$dept) continue;

            DB::table('positions')->insert([
                'position_code' => strtoupper(Str::random(10)),
                'position_name' => $pos['name'],
                'department_id' => $dept->department_id,
                'job_description' => $pos['desc'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info(count($positions) . ' positions seeded');
    }

    /**
     * Seed sample employees
     */
    private function seedEmployees(): void
    {
        $departments = DB::table('departments')->get()->keyBy('department_name');
        $positions = DB::table('positions')->get()->keyBy('position_name');

        $employees = [
            [
                'first' => 'John',
                'last' => 'Doe',
                'email' => 'john.doe@nutrana.co.id',
                'phone' => '+62 812 3456 7890',
                'gender' => 'Male',
                'dob' => '1985-03-15',
                'id_number' => '3201011503850001',
                'dept' => 'Executive Management',
                'pos' => 'Chief Executive Officer (CEO)',
                'join' => '2020-01-01',
                'status' => 'Permanent',
                'salary' => 50000000,
            ],
            [
                'first' => 'Jane',
                'last' => 'Smith',
                'email' => 'jane.smith@nutrana.co.id',
                'phone' => '+62 813 4567 8901',
                'gender' => 'Female',
                'dob' => '1988-07-22',
                'id_number' => '3201012207880002',
                'dept' => 'Production',
                'pos' => 'Production Manager',
                'join' => '2020-02-01',
                'status' => 'Permanent',
                'salary' => 20000000,
            ],
            [
                'first' => 'Ahmad',
                'last' => 'Ibrahim',
                'email' => 'ahmad.ibrahim@nutrana.co.id',
                'phone' => '+62 814 5678 9012',
                'gender' => 'Male',
                'dob' => '1990-05-10',
                'id_number' => '3201011005900003',
                'dept' => 'Quality Control',
                'pos' => 'QC Manager',
                'join' => '2020-03-01',
                'status' => 'Permanent',
                'salary' => 18000000,
            ],
            [
                'first' => 'Siti',
                'last' => 'Nurhaliza',
                'email' => 'siti.nur@nutrana.co.id',
                'phone' => '+62 815 6789 0123',
                'gender' => 'Female',
                'dob' => '1992-11-30',
                'id_number' => '3201013011920004',
                'dept' => 'Finance & Accounting',
                'pos' => 'Finance Manager',
                'join' => '2020-04-01',
                'status' => 'Permanent',
                'salary' => 22000000,
            ],
            [
                'first' => 'Budi',
                'last' => 'Santoso',
                'email' => 'budi.santoso@nutrana.co.id',
                'phone' => '+62 816 7890 1234',
                'gender' => 'Male',
                'dob' => '1993-09-18',
                'id_number' => '3201011809930005',
                'dept' => 'Human Resources',
                'pos' => 'HR Manager',
                'join' => '2021-01-15',
                'status' => 'Permanent',
                'salary' => 19000000,
            ],
            [
                'first' => 'Rina',
                'last' => 'Wijaya',
                'email' => 'rina.wijaya@nutrana.co.id',
                'phone' => '+62 817 8901 2345',
                'gender' => 'Female',
                'dob' => '1995-02-28',
                'id_number' => '3201012802950006',
                'dept' => 'Production',
                'pos' => 'Production Supervisor',
                'join' => '2021-06-01',
                'status' => 'Permanent',
                'salary' => 12000000,
            ],
            [
                'first' => 'Agus',
                'last' => 'Pratama',
                'email' => 'agus.pratama@nutrana.co.id',
                'phone' => '+62 818 9012 3456',
                'gender' => 'Male',
                'dob' => '1996-12-05',
                'id_number' => '3201011205960007',
                'dept' => 'IT & Systems',
                'pos' => 'IT Manager',
                'join' => '2021-08-01',
                'status' => 'Permanent',
                'salary' => 17000000,
            ],
            [
                'first' => 'Dewi',
                'last' => 'Lestari',
                'email' => 'dewi.lestari@nutrana.co.id',
                'phone' => '+62 819 0123 4567',
                'gender' => 'Female',
                'dob' => '1997-04-14',
                'id_number' => '3201011404970008',
                'dept' => 'Quality Control',
                'pos' => 'QC Inspector',
                'join' => '2022-01-10',
                'status' => 'Permanent',
                'salary' => 9000000,
            ],
        ];

        foreach ($employees as $emp) {
            $dept = $departments->get($emp['dept']);
            $pos = $positions->get($emp['pos']);

            if (!$dept || !$pos) continue;

            DB::table('employees')->insert([
                'employee_code' => strtoupper(Str::random(10)),
                'first_name' => $emp['first'],
                'last_name' => $emp['last'],
                'email' => $emp['email'],
                'phone' => $emp['phone'],
                'date_of_birth' => $emp['dob'],
                'gender' => $emp['gender'],
                'address' => 'Jakarta, Indonesia',
                'id_number' => $emp['id_number'],
                'department_id' => $dept->department_id,
                'position_id' => $pos->position_id,
                'join_date' => $emp['join'],
                'resign_date' => null,
                'employment_status' => $emp['status'],
                'base_salary' => $emp['salary'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info(count($employees) . ' employees seeded');
    }
}
