<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    //
    use LogsActivity;

    /**
     * List employees with advanced filtering
     */
    public function index(Request $request)
    {
        $this->logView('HRM - Employees', 'Viewed employees directory');

        $query = DB::table('employees')
            ->join('departments', 'employees.department_id', '=', 'departments.department_id')
            ->join('positions', 'employees.position_id', '=', 'positions.position_id')
            ->select(
                'employees.employee_id',
                'employees.employee_code',
                'employees.first_name',
                'employees.last_name',
                'employees.email',
                'employees.phone',
                'employees.employment_status',
                'employees.join_date',
                'employees.base_salary',
                'employees.gender',
                'departments.department_name',
                'departments.department_code',
                'positions.position_name',
                'positions.position_code'
            );

        // Search - UPDATED
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('employees.first_name', 'like', "%{$search}%")
                ->orWhere('employees.last_name', 'like', "%{$search}%")
                ->orWhere('employees.employee_code', 'like', "%{$search}%")
                ->orWhere('employees.email', 'like', "%{$search}%")
                ->orWhere('employees.phone', 'like', "%{$search}%");
            });
        }

        // Filter by department
        if ($request->filled('department_id')) {
            $query->where('employees.department_id', $request->department_id);
        }

        // Filter by position
        if ($request->filled('position_id')) {
            $query->where('employees.position_id', $request->position_id);
        }

        // Filter by employment status
        if ($request->filled('employment_status')) {
            $query->where('employees.employment_status', $request->employment_status);
        }

        // NEW: Filter by gender
        if ($request->filled('gender')) {
            $query->where('employees.gender', $request->gender);
        }

        // NEW: Filter by join date range
        if ($request->filled('join_date_from')) {
            $query->whereDate('employees.join_date', '>=', $request->join_date_from);
        }
        if ($request->filled('join_date_to')) {
            $query->whereDate('employees.join_date', '<=', $request->join_date_to);
        }

        // NEW: Sorting
        $sortBy = $request->get('sort_by', 'first_name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $allowedSort = ['first_name', 'last_name', 'employee_code', 'department_name', 'position_name', 'employment_status', 'join_date', 'base_salary'];
        if (in_array($sortBy, $allowedSort)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Secondary sort
        if ($sortBy !== 'first_name') {
            $query->orderBy('first_name', 'asc');
        }
        if ($sortBy !== 'last_name') {
            $query->orderBy('last_name', 'asc');
        }

        // NEW: Pagination with query string
        $employees = $query->paginate(20)->withQueryString();

        // Get filter options
        $departments = DB::table('departments')
            ->orderBy('department_name')
            ->get();

        $positions = DB::table('positions')
            ->join('departments', 'positions.department_id', '=', 'departments.department_id')
            ->select(
                'positions.position_id',
                'positions.position_name',
                'departments.department_name'
            )
            ->orderBy('departments.department_name')
            ->orderBy('positions.position_name')
            ->get();

        $employmentStatuses = ['Probation', 'Permanent', 'Contract', 'Intern', 'Resigned'];
        
        return view('admin.hrm.employees.index', compact('employees', 'departments', 'positions', 'employmentStatuses'));
    }

    /**
     * Create form
     */
    public function create()
    {
        $departments = DB::table('departments')
            ->orderBy('department_name')
            ->get();

        $positions = DB::table('positions')
            ->join('departments', 'positions.department_id', '=', 'departments.department_id')
            ->select(
                'positions.position_id',
                'positions.position_name',
                'departments.department_name'
            )
            ->orderBy('departments.department_name')
            ->orderBy('positions.position_name')
            ->get();
        
        return view('admin.hrm.employees.create', compact('departments', 'positions'));
    }

    /**
     * Store employee
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150', 'unique:employees,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['required', 'in:Male,Female'],
            'address' => ['nullable', 'string'],
            'id_number' => ['nullable', 'string', 'max:50'],
            'department_id' => ['required', 'exists:departments,department_id'],
            'position_id' => ['required', 'exists:positions,position_id'],
            'join_date' => ['required', 'date'],
            'employment_status' => ['required', 'in:Probation,Permanent,Contract,Intern'],
            'base_salary' => ['nullable', 'numeric', 'min:0', 'max:9999999999999.99'],
        ], [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered.',
            'date_of_birth.before' => 'Date of birth must be before today.',
            'gender.required' => 'Gender is required.',
            'department_id.required' => 'Department is required.',
            'department_id.exists' => 'Selected department is invalid.',
            'position_id.required' => 'Position is required.',
            'position_id.exists' => 'Selected position is invalid.',
            'join_date.required' => 'Join date is required.',
            'employment_status.required' => 'Employment status is required.',
            'base_salary.numeric' => 'Base salary must be a valid number.',
        ]);

        DB::beginTransaction();
        try {
            $employeeId = DB::table('employees')->insertGetId([
                'employee_code' => strtoupper(Str::random(10)),
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'gender' => $validated['gender'],
                'address' => $validated['address'] ?? null,
                'id_number' => $validated['id_number'] ?? null,
                'department_id' => $validated['department_id'],
                'position_id' => $validated['position_id'],
                'join_date' => $validated['join_date'],
                'resign_date' => null,
                'employment_status' => $validated['employment_status'],
                'base_salary' => $validated['base_salary'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Remove sensitive data before logging
            $logData = $validated;
            unset($logData['base_salary']); // Don't log salary in plain text

            // Log CREATE
            $this->logCreate(
                'HRM - Employees',
                'employees',
                $employeeId,
                $logData
            );

            DB::commit();
            
            return redirect()
                ->route('admin.hrm.employees.index')
                ->with('success', 'Employee created successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create employee: ' . $e->getMessage());
        }
    }

    /**
     * Show employee details
     */
    public function show($employeeCode)
    {
        $employee = DB::table('employees')
            ->join('departments', 'employees.department_id', '=', 'departments.department_id')
            ->join('positions', 'employees.position_id', '=', 'positions.position_id')
            ->where('employees.employee_code', $employeeCode)
            ->select(
                'employees.*',
                'departments.department_name',
                'departments.department_code',
                'positions.position_name',
                'positions.position_code'
            )
            ->first();
        
        if (!$employee) {
            abort(404, 'Employee not found');
        }

        // Log VIEW
        $this->logView(
            'HRM - Employees',
            "Viewed employee: {$employee->first_name} {$employee->last_name} (Code: {$employeeCode})"
        );

        // Get user account if exists
        $userAccount = DB::table('users')
            ->where('employee_id', $employee->employee_id)
            ->first();

        // Get attendance summary (last 30 days)
        $attendanceSummary = DB::table('attendance')
            ->where('employee_id', $employee->employee_id)
            ->where('attendance_date', '>=', now()->subDays(30))
            ->select(
                DB::raw('COUNT(*) as total_days'),
                DB::raw('SUM(CASE WHEN status = "Present" THEN 1 ELSE 0 END) as present_days'),
                DB::raw('SUM(CASE WHEN status = "Absent" THEN 1 ELSE 0 END) as absent_days'),
                DB::raw('SUM(overtime_hours) as total_overtime')
            )
            ->first();

        // Get leave balance
        $leaveBalance = DB::table('leaves')
            ->join('leave_types', 'leaves.leave_type_id', '=', 'leave_types.leave_type_id')
            ->where('leaves.employee_id', $employee->employee_id)
            ->where('leaves.status', 'Approved')
            ->whereYear('leaves.start_date', now()->year)
            ->select(
                'leave_types.leave_type_name',
                DB::raw('SUM(leaves.total_days) as days_taken')
            )
            ->groupBy('leave_types.leave_type_id', 'leave_types.leave_type_name')
            ->get();
        
        return view('admin.hrm.employees.show', compact('employee', 'userAccount', 'attendanceSummary', 'leaveBalance'));
    }

    /**
     * Edit employee
     */
    public function edit($employeeCode)
    {
        $employee = DB::table('employees')
            ->where('employee_code', $employeeCode)
            ->first();
        
        if (!$employee) {
            abort(404, 'Employee not found');
        }

        $departments = DB::table('departments')
            ->orderBy('department_name')
            ->get();

        $positions = DB::table('positions')
            ->join('departments', 'positions.department_id', '=', 'departments.department_id')
            ->select(
                'positions.position_id',
                'positions.position_name',
                'departments.department_name'
            )
            ->orderBy('departments.department_name')
            ->orderBy('positions.position_name')
            ->get();
        
        return view('admin.hrm.employees.edit', compact('employee', 'departments', 'positions'));
    }

    /**
     * Update employee
     */
    public function update(Request $request, $employeeCode)
    {
        $employee = DB::table('employees')
            ->where('employee_code', $employeeCode)
            ->first();
        
        if (!$employee) {
            abort(404, 'Employee not found');
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150', 'unique:employees,email,' . $employee->employee_id . ',employee_id'],
            'phone' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['required', 'in:Male,Female'],
            'address' => ['nullable', 'string'],
            'id_number' => ['nullable', 'string', 'max:50'],
            'department_id' => ['required', 'exists:departments,department_id'],
            'position_id' => ['required', 'exists:positions,position_id'],
            'join_date' => ['required', 'date'],
            'employment_status' => ['required', 'in:Probation,Permanent,Contract,Intern,Resigned'],
            'base_salary' => ['nullable', 'numeric', 'min:0', 'max:9999999999999.99'],
        ]);

        DB::beginTransaction();
        try {
            // Capture old data (excluding sensitive info)
            $oldData = [
                'first_name' => $employee->first_name,
                'last_name' => $employee->last_name,
                'email' => $employee->email,
                'phone' => $employee->phone,
                'department_id' => $employee->department_id,
                'position_id' => $employee->position_id,
                'employment_status' => $employee->employment_status,
            ];

            DB::table('employees')
                ->where('employee_id', $employee->employee_id)
                ->update([
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'email' => $validated['email'] ?? null,
                    'phone' => $validated['phone'] ?? null,
                    'date_of_birth' => $validated['date_of_birth'] ?? null,
                    'gender' => $validated['gender'],
                    'address' => $validated['address'] ?? null,
                    'id_number' => $validated['id_number'] ?? null,
                    'department_id' => $validated['department_id'],
                    'position_id' => $validated['position_id'],
                    'join_date' => $validated['join_date'],
                    'employment_status' => $validated['employment_status'],
                    'base_salary' => $validated['base_salary'] ?? null,
                    'updated_at' => now(),
                ]);

            // Log UPDATE (excluding salary)
            $logData = $validated;
            unset($logData['base_salary']);

            $this->logUpdate(
                'HRM - Employees',
                'employees',
                $employee->employee_id,
                $oldData,
                $logData
            );

            DB::commit();
            
            return redirect()
                ->route('admin.hrm.employees.index')
                ->with('success', 'Employee updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update employee: ' . $e->getMessage());
        }
    }

    /**
     * Delete employee (soft delete - mark as resigned)
     */
    public function destroy($employeeCode)
    {
        $employee = DB::table('employees')
            ->where('employee_code', $employeeCode)
            ->first();
        
        if (!$employee) {
            abort(404, 'Employee not found');
        }

        // Check if employee is department manager
        $isDepartmentManager = DB::table('departments')
            ->where('manager_id', $employee->employee_id)
            ->exists();

        if ($isDepartmentManager) {
            return back()->with('error', 'Cannot delete employee who is a department manager. Please reassign the manager first.');
        }

        // Check if employee has user account
        $hasUserAccount = DB::table('users')
            ->where('employee_id', $employee->employee_id)
            ->exists();

        if ($hasUserAccount) {
            return back()->with('error', 'Cannot delete employee who has a user account. Please deactivate the user first.');
        }

        DB::beginTransaction();
        try {
            // Capture data before deletion
            $oldData = [
                'employee_code' => $employee->employee_code,
                'first_name' => $employee->first_name,
                'last_name' => $employee->last_name,
                'email' => $employee->email,
                'department_id' => $employee->department_id,
                'position_id' => $employee->position_id,
            ];

            // Actually delete (or you can soft delete by setting resign_date)
            DB::table('employees')
                ->where('employee_id', $employee->employee_id)
                ->delete();

            // Log DELETE
            $this->logDelete(
                'HRM - Employees',
                'employees',
                $employee->employee_id,
                $oldData
            );

            DB::commit();
            
            return redirect()
                ->route('admin.hrm.employees.index')
                ->with('success', 'Employee deleted successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete employee: ' . $e->getMessage());
        }
    }

    /**
     * Terminate employee (resign)
     */
    public function terminate(Request $request, $employeeCode)
    {
        $employee = DB::table('employees')
            ->where('employee_code', $employeeCode)
            ->first();
        
        if (!$employee) {
            abort(404, 'Employee not found');
        }

        $validated = $request->validate([
            'resign_date' => ['required', 'date', 'after_or_equal:' . $employee->join_date],
        ], [
            'resign_date.required' => 'Resignation date is required.',
            'resign_date.after_or_equal' => 'Resignation date must be after join date.',
        ]);

        DB::beginTransaction();
        try {
            DB::table('employees')
                ->where('employee_id', $employee->employee_id)
                ->update([
                    'resign_date' => $validated['resign_date'],
                    'employment_status' => 'Resigned',
                    'updated_at' => now(),
                ]);

            // Log termination
            $this->logActivity(
                'Employee Terminated',
                "Employee {$employee->first_name} {$employee->last_name} resigned on {$validated['resign_date']}",
                'HRM - Employees'
            );

            // Deactivate user account if exists
            DB::table('users')
                ->where('employee_id', $employee->employee_id)
                ->update(['is_active' => false]);

            DB::commit();
            
            return back()->with('success', 'Employee terminated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to terminate employee: ' . $e->getMessage());
        }
    }

    /**
     * Export employees to CSV
     */
    public function export(Request $request)
    {
        $this->logExport('HRM - Employees', 'Exported employees list to CSV');

        $query = DB::table('employees')
            ->join('departments', 'employees.department_id', '=', 'departments.department_id')
            ->join('positions', 'employees.position_id', '=', 'positions.position_id')
            ->select(
                'employees.employee_code',
                DB::raw('CONCAT(employees.first_name, " ", employees.last_name) as full_name'),
                'employees.email',
                'employees.phone',
                'departments.department_name',
                'positions.position_name',
                'employees.employment_status',
                'employees.join_date'
            );

        // Apply filters
        if ($request->filled('department_id')) {
            $query->where('employees.department_id', $request->department_id);
        }
        if ($request->filled('employment_status')) {
            $query->where('employees.employment_status', $request->employment_status);
        }

        $employees = $query->orderBy('employees.first_name')
            ->limit(10000)
            ->get();

        $filename = 'employees_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($employees) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'Employee Code',
                'Full Name',
                'Email',
                'Phone',
                'Department',
                'Position',
                'Status',
                'Join Date'
            ]);
            
            foreach ($employees as $employee) {
                fputcsv($file, [
                    $employee->employee_code,
                    $employee->full_name,
                    $employee->email,
                    $employee->phone,
                    $employee->department_name,
                    $employee->position_name,
                    $employee->employment_status,
                    $employee->join_date,
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
