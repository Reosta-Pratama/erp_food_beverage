<?php

namespace App\Http\Controllers\HRM;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DepartmentController extends Controller
{
    //
    use LogsActivity;

    /**
     * List departments with employee count
     */
    public function index(Request $request)
    {
        $this->logView('HRM - Departments', 'Viewed departments list');

        $query = DB::table('departments')
            ->leftJoin('employees as manager', 'departments.manager_id', '=', 'manager.employee_id')
            ->leftJoin('employees', 'departments.department_id', '=', 'employees.department_id')
            ->select(
                'departments.department_id',
                'departments.department_code',
                'departments.department_name',
                'departments.description',
                'departments.manager_id',
                'departments.created_at',
                DB::raw('CONCAT(manager.first_name, " ", manager.last_name) as manager_name'),
                'manager.employee_code as manager_code',
                DB::raw('COUNT(DISTINCT employees.employee_id) as employees_count')
            )
            ->groupBy(
                'departments.department_id',
                'departments.department_code',
                'departments.department_name',
                'departments.description',
                'departments.manager_id',
                'departments.created_at',
                'manager.first_name',
                'manager.last_name',
                'manager.employee_code'
            );

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('departments.department_name', 'like', "%{$search}%")
                  ->orWhere('departments.department_code', 'like', "%{$search}%")
                  ->orWhere(DB::raw('CONCAT(manager.first_name, " ", manager.last_name)'), 'like', "%{$search}%");
            });
        }

        // Manager filter
        if ($request->filled('manager')) {
            if ($request->input('manager') === 'unassigned') {
                $query->whereNull('departments.manager_id');
            } elseif ($request->input('manager') === 'assigned') {
                $query->whereNotNull('departments.manager_id');
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'department_name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $allowedSort = ['department_name', 'department_code', 'manager_name', 'employees_count', 'created_at'];
        if (\in_array($sortBy, $allowedSort)) {
            if ($sortBy === 'manager_name') {
                $query->orderBy(DB::raw('CONCAT(manager.first_name, " ", manager.last_name)'), $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        }

        $departments = $query->paginate(10)->withQueryString();
        
        return view('admin.employee-management.departments.index', compact('departments'));
    }

    /**
     * Create form
     */
    public function create()
    {
        // Get available employees for manager selection
        $employees = DB::table('employees')
            ->select(
                'employee_id',
                'employee_code',
                DB::raw('CONCAT(first_name, " ", last_name) as full_name')
            )
            ->where('employment_status', '!=', 'Resigned')
            ->orderBy('first_name')
            ->get();
        
        return view('admin.employee-management.departments.create', compact('employees'));
    }

    /**
     * Store department
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_name' => ['required', 'string', 'max:150', 'unique:departments,department_name'],
            'manager_id' => ['nullable', 'exists:employees,employee_id'],
            'description' => ['nullable', 'string'],
        ], [
            'department_name.required' => 'Department name is required.',
            'department_name.unique' => 'This department name already exists.',
            'manager_id.exists' => 'Selected manager is invalid.',
        ]);


        DB::beginTransaction();
        try {
            $departmentCode = CodeGeneratorHelper::generateDepartmentCode();

            $departmentId = DB::table('departments')->insertGetId([
                'department_code' => $departmentCode,
                'department_name' => $validated['department_name'],
                'manager_id' => $validated['manager_id'] ?? null,
                'description' => $validated['description'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Log CREATE
            $this->logCreate(
                'HRM - Departments',
                'departments',
                $departmentId,
                $validated
            );

            DB::commit();
            
            return redirect()
                ->route('hrm.departments.index')
                ->with('success', 'Department created successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create department: ' . $e->getMessage());
        }
    }

    /**
     * Show department details
     */
    public function show($departmentCode)
    {
        $department = DB::table('departments')
            ->leftJoin('employees as manager', 'departments.manager_id', '=', 'manager.employee_id')
            ->where('departments.department_code', $departmentCode)
            ->select(
                'departments.*',
                DB::raw('CONCAT(manager.first_name, " ", manager.last_name) as manager_name'),
                'manager.employee_code as manager_code',
                'manager.email as manager_email'
            )
            ->first();
        
        if (!$department) {
            abort(404, 'Department not found');
        }

        // Log VIEW
        $this->logView(
            'HRM - Departments',
            "Viewed department: {$department->department_name} (Code: {$departmentCode})"
        );

        // Get employees in this department
        $employees = DB::table('employees')
            ->join('positions', 'employees.position_id', '=', 'positions.position_id')
            ->where('employees.department_id', $department->department_id)
            ->select(
                'employees.employee_id',
                'employees.employee_code',
                'employees.first_name',
                'employees.last_name',
                'employees.email',
                'employees.employment_status',
                'employees.join_date',
                'positions.position_name'
            )
            ->orderBy('employees.first_name')
            ->get();

        // Get positions in this department
        $positions = DB::table('positions')
            ->where('department_id', $department->department_id)
            ->orderBy('position_name')
            ->get();
        
        return view('admin.employee-management.departments.show', compact('department', 'employees', 'positions'));
    }

    /**
     * Edit department
     */
    public function edit($departmentCode)
    {
        $department = DB::table('departments')
            ->where('department_code', $departmentCode)
            ->first();
        
        if (!$department) {
            abort(404, 'Department not found');
        }

        // Get available employees for manager selection
        $employees = DB::table('employees')
            ->select(
                'employee_id',
                'employee_code',
                DB::raw('CONCAT(first_name, " ", last_name) as full_name')
            )
            ->where('employment_status', '!=', 'Resigned')
            ->orderBy('first_name')
            ->get();
        
        return view('admin.employee-management.departments.edit', compact('department', 'employees'));
    }

    /**
     * Update department
     */
    public function update(Request $request, $departmentCode)
    {
        $department = DB::table('departments')
            ->where('department_code', $departmentCode)
            ->first();
        
        if (!$department) {
            abort(404, 'Department not found');
        }

        $validated = $request->validate([
            'department_name' => ['required', 'string', 'max:150', 'unique:departments,department_name,' . $department->department_id . ',department_id'],
            'manager_id' => ['nullable', 'exists:employees,employee_id'],
            'description' => ['nullable', 'string'],
        ], [
            'department_name.required' => 'Department name is required.',
            'department_name.unique' => 'This department name already exists.',
            'manager_id.exists' => 'Selected manager is invalid.',
        ]);

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'department_name' => $department->department_name,
                'manager_id' => $department->manager_id,
                'description' => $department->description,
            ];

            DB::table('departments')
                ->where('department_id', $department->department_id)
                ->update([
                    'department_name' => $validated['department_name'],
                    'manager_id' => $validated['manager_id'] ?? null,
                    'description' => $validated['description'] ?? null,
                    'updated_at' => now(),
                ]);

            // Log UPDATE
            $this->logUpdate(
                'HRM - Departments',
                'departments',
                $department->department_id,
                $oldData,
                $validated
            );

            DB::commit();
            
            return redirect()
                ->route('hrm.departments.index')
                ->with('success', 'Department updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update department: ' . $e->getMessage());
        }
    }

    /**
     * Delete department
     */
    public function destroy($departmentCode)
    {
        $department = DB::table('departments')
            ->where('department_code', $departmentCode)
            ->first();

        if (!$department) {
            abort(404, 'Department not found');
        }

        // Check if department has employees
        $employeesCount = DB::table('employees')
            ->where('department_id', $department->department_id)
            ->count();

        if ($employeesCount > 0) {
            return back()->with('error', 'Cannot delete department that has employees assigned');
        }

        // Check if department has positions
        $positionsCount = DB::table('positions')
            ->where('department_id', $department->department_id)
            ->count();

        if ($positionsCount > 0) {
            return back()->with('error', 'Cannot delete department that has positions assigned');
        }

        DB::beginTransaction();
        try {
            // Capture data before deletion
            $oldData = [
                'department_code' => $department->department_code,
                'department_name' => $department->department_name,
                'manager_id' => $department->manager_id,
                'description' => $department->description,
            ];

            DB::table('departments')
                ->where('department_id', $department->department_id)
                ->delete();

            // Log DELETE
            $this->logDelete(
                'HRM - Departments',
                'departments',
                $department->department_id,
                $oldData
            );

            DB::commit();
            
            return redirect()
                ->route('hrm.departments.index')
                ->with('success', 'Department deleted successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete department: ' . $e->getMessage());
        }
    }

    /**
     * Assign manager to department
     */
    public function assignManager(Request $request, $departmentCode)
    {
        $department = DB::table('departments')
            ->where('department_code', $departmentCode)
            ->first();
        
        if (!$department) {
            abort(404, 'Department not found');
        }

        $validated = $request->validate([
            'manager_id' => ['required', 'exists:employees,employee_id'],
        ]);

        DB::beginTransaction();
        try {
            DB::table('departments')
                ->where('department_id', $department->department_id)
                ->update([
                    'manager_id' => $validated['manager_id'],
                    'updated_at' => now(),
                ]);

            // Get manager name for logging
            $manager = DB::table('employees')
                ->where('employee_id', $validated['manager_id'])
                ->first();

            // Log action
            $this->logActivity(
                'Manager Assigned',
                "Assigned {$manager->first_name} {$manager->last_name} as manager of {$department->department_name}",
                'HRM - Departments'
            );

            DB::commit();
            
            return back()->with('success', 'Manager assigned successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to assign manager: ' . $e->getMessage());
        }
    }
}
