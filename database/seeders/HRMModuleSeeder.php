<?php

namespace Database\Seeders;

use App\Helpers\CodeGeneratorHelper;
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
            $departmentCode = CodeGeneratorHelper::generateDepartmentCode();

            DB::table('departments')->insert([
                'department_code' => $departmentCode,
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
            ['dept' => 'Production', 'name' => 'Line Leader', 'desc' => 'Lead production line operations'],
            ['dept' => 'Production', 'name' => 'Juice Extractor Operator', 'desc' => 'Operate juice extraction equipment'],
            ['dept' => 'Production', 'name' => 'Pasteurization Specialist', 'desc' => 'Manage pasteurization process'],
            ['dept' => 'Production', 'name' => 'Filling Machine Operator', 'desc' => 'Operate bottle filling machines'],
            ['dept' => 'Production', 'name' => 'Packaging Operator', 'desc' => 'Handle product packaging'],
            
            // Quality Control
            ['dept' => 'Quality Control', 'name' => 'QC Manager', 'desc' => 'Manage quality control department'],
            ['dept' => 'Quality Control', 'name' => 'QC Inspector', 'desc' => 'Perform quality inspections'],
            ['dept' => 'Quality Control', 'name' => 'QC Analyst', 'desc' => 'Analyze quality data'],
            ['dept' => 'Quality Control', 'name' => 'Lab Technician', 'desc' => 'Perform laboratory tests'],
            ['dept' => 'Quality Control', 'name' => 'Microbiologist', 'desc' => 'Conduct microbiological testing'],
            ['dept' => 'Quality Control', 'name' => 'Food Safety Supervisor', 'desc' => 'Ensure food safety compliance'],
            
            // Supply Chain & Logistics
            ['dept' => 'Supply Chain & Logistics', 'name' => 'Supply Chain Manager', 'desc' => 'Manage supply chain operations'],
            ['dept' => 'Supply Chain & Logistics', 'name' => 'Procurement Officer', 'desc' => 'Handle purchasing'],
            ['dept' => 'Supply Chain & Logistics', 'name' => 'Warehouse Supervisor', 'desc' => 'Supervise warehouse operations'],
            ['dept' => 'Supply Chain & Logistics', 'name' => 'Logistics Coordinator', 'desc' => 'Coordinate deliveries'],
            ['dept' => 'Supply Chain & Logistics', 'name' => 'Warehouse Staff', 'desc' => 'Handle warehouse operations'],
            ['dept' => 'Supply Chain & Logistics', 'name' => 'Inventory Controller', 'desc' => 'Control inventory levels'],
            ['dept' => 'Supply Chain & Logistics', 'name' => 'Forklift Operator', 'desc' => 'Operate forklift for material handling'],
            ['dept' => 'Supply Chain & Logistics', 'name' => 'Delivery Driver', 'desc' => 'Deliver products to customers'],
            
            // Sales & Marketing
            ['dept' => 'Sales & Marketing', 'name' => 'Sales Manager', 'desc' => 'Manage sales team'],
            ['dept' => 'Sales & Marketing', 'name' => 'Sales Executive', 'desc' => 'Handle sales activities'],
            ['dept' => 'Sales & Marketing', 'name' => 'Marketing Manager', 'desc' => 'Manage marketing activities'],
            ['dept' => 'Sales & Marketing', 'name' => 'Account Manager', 'desc' => 'Manage key accounts'],
            ['dept' => 'Sales & Marketing', 'name' => 'Brand Executive', 'desc' => 'Handle brand management'],
            ['dept' => 'Sales & Marketing', 'name' => 'Customer Service Representative', 'desc' => 'Handle customer inquiries'],
            
            // Finance & Accounting
            ['dept' => 'Finance & Accounting', 'name' => 'Finance Manager', 'desc' => 'Manage finance department'],
            ['dept' => 'Finance & Accounting', 'name' => 'Accountant', 'desc' => 'Handle accounting tasks'],
            ['dept' => 'Finance & Accounting', 'name' => 'Finance Staff', 'desc' => 'Support finance operations'],
            ['dept' => 'Finance & Accounting', 'name' => 'Cost Accountant', 'desc' => 'Analyze product costing'],
            ['dept' => 'Finance & Accounting', 'name' => 'Accounts Payable Clerk', 'desc' => 'Process supplier payments'],
            ['dept' => 'Finance & Accounting', 'name' => 'Accounts Receivable Clerk', 'desc' => 'Manage customer receivables'],
            
            // Human Resources
            ['dept' => 'Human Resources', 'name' => 'HR Manager', 'desc' => 'Manage HR department'],
            ['dept' => 'Human Resources', 'name' => 'HR Officer', 'desc' => 'Handle HR operations'],
            ['dept' => 'Human Resources', 'name' => 'Recruitment Specialist', 'desc' => 'Handle recruitment'],
            ['dept' => 'Human Resources', 'name' => 'Payroll Administrator', 'desc' => 'Process employee payroll'],
            ['dept' => 'Human Resources', 'name' => 'Training Coordinator', 'desc' => 'Coordinate training programs'],
            
            // R&D
            ['dept' => 'Research & Development', 'name' => 'R&D Manager', 'desc' => 'Manage R&D activities'],
            ['dept' => 'Research & Development', 'name' => 'Product Developer', 'desc' => 'Develop new products'],
            ['dept' => 'Research & Development', 'name' => 'Food Technologist', 'desc' => 'Food technology specialist'],
            ['dept' => 'Research & Development', 'name' => 'Sensory Analyst', 'desc' => 'Conduct sensory testing'],
            ['dept' => 'Research & Development', 'name' => 'Nutritionist', 'desc' => 'Analyze nutritional content'],
            
            // IT & Systems
            ['dept' => 'IT & Systems', 'name' => 'IT Manager', 'desc' => 'Manage IT department'],
            ['dept' => 'IT & Systems', 'name' => 'System Administrator', 'desc' => 'Manage systems'],
            ['dept' => 'IT & Systems', 'name' => 'IT Support', 'desc' => 'Provide IT support'],
            ['dept' => 'IT & Systems', 'name' => 'Network Engineer', 'desc' => 'Manage network infrastructure'],
            ['dept' => 'IT & Systems', 'name' => 'ERP Administrator', 'desc' => 'Manage ERP system'],
            
            // Maintenance
            ['dept' => 'Maintenance', 'name' => 'Maintenance Manager', 'desc' => 'Manage maintenance department'],
            ['dept' => 'Maintenance', 'name' => 'Maintenance Technician', 'desc' => 'Perform maintenance tasks'],
            ['dept' => 'Maintenance', 'name' => 'Electrician', 'desc' => 'Handle electrical maintenance'],
            ['dept' => 'Maintenance', 'name' => 'Mechanical Technician', 'desc' => 'Handle mechanical repairs'],
            ['dept' => 'Maintenance', 'name' => 'Instrumentation Technician', 'desc' => 'Maintain control instruments'],
        ];

        foreach ($positions as $pos) {
            $dept = $departments->get($pos['dept']);
            if (!$dept) continue;

            $positionCode = CodeGeneratorHelper::generatePositionCode();

            DB::table('positions')->insert([
                'position_code' => $positionCode,
                'position_name' => $pos['name'],
                'department_id' => $dept->department_id,
                'job_description' => $pos['desc'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info(\count($positions) . ' positions seeded');
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
             // === EXECUTIVE MANAGEMENT ===
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
                'first' => 'Michael',
                'last' => 'Chen',
                'email' => 'michael.chen@nutrana.co.id',
                'phone' => '+62 813 1111 2222',
                'gender' => 'Male',
                'dob' => '1987-06-20',
                'id_number' => '3201012006870002',
                'dept' => 'Executive Management',
                'pos' => 'Chief Operations Officer (COO)',
                'join' => '2020-02-01',
                'status' => 'Permanent',
                'salary' => 45000000,
            ],
            [
                'first' => 'Sarah',
                'last' => 'Johnson',
                'email' => 'sarah.johnson@nutrana.co.id',
                'phone' => '+62 814 2222 3333',
                'gender' => 'Female',
                'dob' => '1986-09-12',
                'id_number' => '3201011209860003',
                'dept' => 'Executive Management',
                'pos' => 'Chief Financial Officer (CFO)',
                'join' => '2020-03-01',
                'status' => 'Permanent',
                'salary' => 45000000,
            ],

            // === PRODUCTION DEPARTMENT ===
            [
                'first' => 'Jane',
                'last' => 'Smith',
                'email' => 'jane.smith@nutrana.co.id',
                'phone' => '+62 813 4567 8901',
                'gender' => 'Female',
                'dob' => '1988-07-22',
                'id_number' => '3201012207880004',
                'dept' => 'Production',
                'pos' => 'Production Manager',
                'join' => '2020-04-01',
                'status' => 'Permanent',
                'salary' => 20000000,
            ],
            [
                'first' => 'Rina',
                'last' => 'Wijaya',
                'email' => 'rina.wijaya@nutrana.co.id',
                'phone' => '+62 817 8901 2345',
                'gender' => 'Female',
                'dob' => '1995-02-28',
                'id_number' => '3201012802950005',
                'dept' => 'Production',
                'pos' => 'Production Supervisor',
                'join' => '2021-06-01',
                'status' => 'Permanent',
                'salary' => 12000000,
            ],
            [
                'first' => 'Bambang',
                'last' => 'Suryanto',
                'email' => 'bambang.s@nutrana.co.id',
                'phone' => '+62 815 3333 4444',
                'gender' => 'Male',
                'dob' => '1992-11-10',
                'id_number' => '3201011011920006',
                'dept' => 'Production',
                'pos' => 'Line Leader',
                'join' => '2021-08-15',
                'status' => 'Permanent',
                'salary' => 9000000,
            ],
            [
                'first' => 'Dedi',
                'last' => 'Kurniawan',
                'email' => 'dedi.k@nutrana.co.id',
                'phone' => '+62 816 4444 5555',
                'gender' => 'Male',
                'dob' => '1994-05-18',
                'id_number' => '3201011805940007',
                'dept' => 'Production',
                'pos' => 'Production Operator',
                'join' => '2022-01-10',
                'status' => 'Permanent',
                'salary' => 6500000,
            ],
            [
                'first' => 'Eko',
                'last' => 'Prasetyo',
                'email' => 'eko.p@nutrana.co.id',
                'phone' => '+62 817 5555 6666',
                'gender' => 'Male',
                'dob' => '1993-08-25',
                'id_number' => '3201012508930008',
                'dept' => 'Production',
                'pos' => 'Machine Operator',
                'join' => '2022-03-01',
                'status' => 'Permanent',
                'salary' => 6800000,
            ],
            [
                'first' => 'Firman',
                'last' => 'Hidayat',
                'email' => 'firman.h@nutrana.co.id',
                'phone' => '+62 818 6666 7777',
                'gender' => 'Male',
                'dob' => '1996-03-14',
                'id_number' => '3201011403960009',
                'dept' => 'Production',
                'pos' => 'Juice Extractor Operator',
                'join' => '2022-05-15',
                'status' => 'Permanent',
                'salary' => 7000000,
            ],
            [
                'first' => 'Gita',
                'last' => 'Permata',
                'email' => 'gita.p@nutrana.co.id',
                'phone' => '+62 819 7777 8888',
                'gender' => 'Female',
                'dob' => '1995-12-08',
                'id_number' => '3201010812950010',
                'dept' => 'Production',
                'pos' => 'Pasteurization Specialist',
                'join' => '2022-07-01',
                'status' => 'Permanent',
                'salary' => 8500000,
            ],
            [
                'first' => 'Hadi',
                'last' => 'Susanto',
                'email' => 'hadi.s@nutrana.co.id',
                'phone' => '+62 812 8888 9999',
                'gender' => 'Male',
                'dob' => '1994-09-20',
                'id_number' => '3201012009940011',
                'dept' => 'Production',
                'pos' => 'Filling Machine Operator',
                'join' => '2022-09-01',
                'status' => 'Permanent',
                'salary' => 6800000,
            ],
            [
                'first' => 'Indah',
                'last' => 'Sari',
                'email' => 'indah.s@nutrana.co.id',
                'phone' => '+62 813 9999 0000',
                'gender' => 'Female',
                'dob' => '1997-04-16',
                'id_number' => '3201011604970012',
                'dept' => 'Production',
                'pos' => 'Packaging Operator',
                'join' => '2023-01-15',
                'status' => 'Permanent',
                'salary' => 6200000,
            ],

            // === QUALITY CONTROL ===
            [
                'first' => 'Ahmad',
                'last' => 'Ibrahim',
                'email' => 'ahmad.ibrahim@nutrana.co.id',
                'phone' => '+62 814 5678 9012',
                'gender' => 'Male',
                'dob' => '1990-05-10',
                'id_number' => '3201011005900013',
                'dept' => 'Quality Control',
                'pos' => 'QC Manager',
                'join' => '2020-05-01',
                'status' => 'Permanent',
                'salary' => 18000000,
            ],
            [
                'first' => 'Dewi',
                'last' => 'Lestari',
                'email' => 'dewi.lestari@nutrana.co.id',
                'phone' => '+62 819 0123 4567',
                'gender' => 'Female',
                'dob' => '1997-04-14',
                'id_number' => '3201011404970014',
                'dept' => 'Quality Control',
                'pos' => 'QC Inspector',
                'join' => '2022-01-10',
                'status' => 'Permanent',
                'salary' => 9000000,
            ],
            [
                'first' => 'Joko',
                'last' => 'Widodo',
                'email' => 'joko.w@nutrana.co.id',
                'phone' => '+62 815 1111 3333',
                'gender' => 'Male',
                'dob' => '1991-07-08',
                'id_number' => '3201010807910015',
                'dept' => 'Quality Control',
                'pos' => 'QC Analyst',
                'join' => '2021-09-01',
                'status' => 'Permanent',
                'salary' => 10000000,
            ],
            [
                'first' => 'Kartika',
                'last' => 'Putri',
                'email' => 'kartika.p@nutrana.co.id',
                'phone' => '+62 816 2222 4444',
                'gender' => 'Female',
                'dob' => '1993-10-22',
                'id_number' => '3201012210930016',
                'dept' => 'Quality Control',
                'pos' => 'Lab Technician',
                'join' => '2022-03-15',
                'status' => 'Permanent',
                'salary' => 8500000,
            ],
            [
                'first' => 'Linda',
                'last' => 'Safitri',
                'email' => 'linda.s@nutrana.co.id',
                'phone' => '+62 817 3333 5555',
                'gender' => 'Female',
                'dob' => '1992-01-30',
                'id_number' => '3201013001920017',
                'dept' => 'Quality Control',
                'pos' => 'Microbiologist',
                'join' => '2021-11-01',
                'status' => 'Permanent',
                'salary' => 11000000,
            ],
            [
                'first' => 'Maya',
                'last' => 'Anggraini',
                'email' => 'maya.a@nutrana.co.id',
                'phone' => '+62 818 4444 6666',
                'gender' => 'Female',
                'dob' => '1990-08-05',
                'id_number' => '3201010508900018',
                'dept' => 'Quality Control',
                'pos' => 'Food Safety Supervisor',
                'join' => '2021-07-15',
                'status' => 'Permanent',
                'salary' => 12000000,
            ],

            // === SUPPLY CHAIN & LOGISTICS ===
            [
                'first' => 'Novi',
                'last' => 'Kusuma',
                'email' => 'novi.k@nutrana.co.id',
                'phone' => '+62 819 5555 7777',
                'gender' => 'Female',
                'dob' => '1989-11-18',
                'id_number' => '3201011811890019',
                'dept' => 'Supply Chain & Logistics',
                'pos' => 'Supply Chain Manager',
                'join' => '2020-06-01',
                'status' => 'Permanent',
                'salary' => 19000000,
            ],
            [
                'first' => 'Oki',
                'last' => 'Setiawan',
                'email' => 'oki.s@nutrana.co.id',
                'phone' => '+62 812 6666 8888',
                'gender' => 'Male',
                'dob' => '1991-05-25',
                'id_number' => '3201012505910020',
                'dept' => 'Supply Chain & Logistics',
                'pos' => 'Procurement Officer',
                'join' => '2021-03-15',
                'status' => 'Permanent',
                'salary' => 11000000,
            ],
            [
                'first' => 'Putra',
                'last' => 'Mahendra',
                'email' => 'putra.m@nutrana.co.id',
                'phone' => '+62 813 7777 9999',
                'gender' => 'Male',
                'dob' => '1993-09-12',
                'id_number' => '3201011209930021',
                'dept' => 'Supply Chain & Logistics',
                'pos' => 'Warehouse Supervisor',
                'join' => '2021-08-01',
                'status' => 'Permanent',
                'salary' => 10000000,
            ],
            [
                'first' => 'Qori',
                'last' => 'Handayani',
                'email' => 'qori.h@nutrana.co.id',
                'phone' => '+62 814 8888 0000',
                'gender' => 'Female',
                'dob' => '1994-12-03',
                'id_number' => '3201010312940022',
                'dept' => 'Supply Chain & Logistics',
                'pos' => 'Logistics Coordinator',
                'join' => '2022-02-01',
                'status' => 'Permanent',
                'salary' => 9500000,
            ],
            [
                'first' => 'Rudi',
                'last' => 'Hartono',
                'email' => 'rudi.h@nutrana.co.id',
                'phone' => '+62 815 9999 1111',
                'gender' => 'Male',
                'dob' => '1995-06-28',
                'id_number' => '3201012806950023',
                'dept' => 'Supply Chain & Logistics',
                'pos' => 'Warehouse Staff',
                'join' => '2022-04-15',
                'status' => 'Permanent',
                'salary' => 6500000,
            ],
            [
                'first' => 'Sinta',
                'last' => 'Dewi',
                'email' => 'sinta.d@nutrana.co.id',
                'phone' => '+62 816 0000 2222',
                'gender' => 'Female',
                'dob' => '1996-02-14',
                'id_number' => '3201011402960024',
                'dept' => 'Supply Chain & Logistics',
                'pos' => 'Inventory Controller',
                'join' => '2022-06-01',
                'status' => 'Permanent',
                'salary' => 8000000,
            ],
            [
                'first' => 'Tono',
                'last' => 'Saputra',
                'email' => 'tono.s@nutrana.co.id',
                'phone' => '+62 817 1111 3333',
                'gender' => 'Male',
                'dob' => '1994-10-07',
                'id_number' => '3201010710940025',
                'dept' => 'Supply Chain & Logistics',
                'pos' => 'Forklift Operator',
                'join' => '2022-08-15',
                'status' => 'Permanent',
                'salary' => 7000000,
            ],
            [
                'first' => 'Umar',
                'last' => 'Bakri',
                'email' => 'umar.b@nutrana.co.id',
                'phone' => '+62 818 2222 4444',
                'gender' => 'Male',
                'dob' => '1992-04-19',
                'id_number' => '3201011904920026',
                'dept' => 'Supply Chain & Logistics',
                'pos' => 'Delivery Driver',
                'join' => '2022-10-01',
                'status' => 'Permanent',
                'salary' => 6800000,
            ],

            // === SALES & MARKETING ===
            [
                'first' => 'Vina',
                'last' => 'Melati',
                'email' => 'vina.m@nutrana.co.id',
                'phone' => '+62 819 3333 5555',
                'gender' => 'Female',
                'dob' => '1988-07-11',
                'id_number' => '3201011107880027',
                'dept' => 'Sales & Marketing',
                'pos' => 'Sales Manager',
                'join' => '2020-07-01',
                'status' => 'Permanent',
                'salary' => 17000000,
            ],
            [
                'first' => 'Wawan',
                'last' => 'Firmansyah',
                'email' => 'wawan.f@nutrana.co.id',
                'phone' => '+62 812 4444 6666',
                'gender' => 'Male',
                'dob' => '1991-09-23',
                'id_number' => '3201012309910028',
                'dept' => 'Sales & Marketing',
                'pos' => 'Sales Executive',
                'join' => '2021-05-15',
                'status' => 'Permanent',
                'salary' => 10000000,
            ],
            [
                'first' => 'Yuli',
                'last' => 'Rahmawati',
                'email' => 'yuli.r@nutrana.co.id',
                'phone' => '+62 813 5555 7777',
                'gender' => 'Female',
                'dob' => '1990-12-30',
                'id_number' => '3201013012900029',
                'dept' => 'Sales & Marketing',
                'pos' => 'Marketing Manager',
                'join' => '2021-02-01',
                'status' => 'Permanent',
                'salary' => 16000000,
            ],
        ];

        foreach ($employees as $emp) {
            $dept = $departments->get($emp['dept']);
            $pos = $positions->get($emp['pos']);

            if (!$dept || !$pos) continue;

            $employeeCode = CodeGeneratorHelper::generateEmployeeCode();

            DB::table('employees')->insert([
                'employee_code' => $employeeCode,
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
