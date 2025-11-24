<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PositionController extends Controller
{
    //
    use LogsActivity;

    /**
     * List positions grouped by department
     */
    public function index(Request $request)
    {
        $this->logView('HRM - Positions', 'Viewed positions list');

        $query = DB::table('positions')
            ->join('departments', 'positions.department_id', '=', 'departments.department_id')
            ->leftJoin('employees', 'positions.position_id', '=', 'employees.position_id')
            ->select(
                'positions.position_id',
                'positions.position_code',
                'positions.position_name',
                'positions.job_description',
                'positions.created_at',
                'departments.department_id',
                'departments.department_name',
                'departments.department_code',
                DB::raw('COUNT(DISTINCT employees.employee_id) as employees_count')
            )
            ->groupBy(
                'positions.position_id',
                'positions.position_code',
                'positions.position_name',
                'positions.job_description',
                'positions.created_at',
                'departments.department_id',
                'departments.department_name',
                'departments.department_code'
            );

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('positions.position_name', 'like', "%{$search}%")
                  ->orWhere('positions.position_code', 'like', "%{$search}%")
                  ->orWhere('departments.department_name', 'like', "%{$search}%");
            });
        }

        // Filter by department
        if ($request->filled('department_id')) {
            $query->where('positions.department_id', $request->department_id);
        }

        // Employee count filter
        if ($request->filled('employee_status')) {
            if ($request->employee_status === 'empty') {
                $query->havingRaw('COUNT(DISTINCT employees.employee_id) = 0');
            } elseif ($request->employee_status === 'filled') {
                $query->havingRaw('COUNT(DISTINCT employees.employee_id) > 0');
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'department_name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $allowedSort = ['position_name', 'position_code', 'department_name', 'employees_count', 'created_at'];
        if (in_array($sortBy, $allowedSort)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Secondary sort
        if ($sortBy !== 'position_name') {
            $query->orderBy('position_name', 'asc');
        }

        $positions = $query->paginate(15)->withQueryString();

        // Get departments for filter
        $departments = DB::table('departments')
            ->orderBy('department_name')
            ->get();
        
        return view('admin.hrm.positions.index', compact('positions', 'departments'));
    }

    /**
     * Create form
     */
    public function create()
    {
        $departments = DB::table('departments')
            ->orderBy('department_name')
            ->get();
        
        return view('admin.hrm.positions.create', compact('departments'));
    }

    /**
     * Store position
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'position_name' => ['required', 'string', 'max:150', 'unique:positions,position_name'],
            'department_id' => ['required', 'exists:departments,department_id'],
            'job_description' => ['nullable', 'string'],
        ], [
            'position_name.required' => 'Position name is required.',
            'position_name.unique' => 'This position name already exists.',
            'department_id.required' => 'Department is required.',
            'department_id.exists' => 'Selected department is invalid.',
        ]);

        DB::beginTransaction();
        try {
            $positionId = DB::table('positions')->insertGetId([
                'position_code' => strtoupper(Str::random(10)),
                'position_name' => $validated['position_name'],
                'department_id' => $validated['department_id'],
                'job_description' => $validated['job_description'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Log CREATE
            $this->logCreate(
                'HRM - Positions',
                'positions',
                $positionId,
                $validated
            );

            DB::commit();
            
            return redirect()
                ->route('admin.hrm.positions.index')
                ->with('success', 'Position created successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create position: ' . $e->getMessage());
        }
    }

    /**
     * Show position details
     */
    public function show($positionCode)
    {
        $position = DB::table('positions')
            ->join('departments', 'positions.department_id', '=', 'departments.department_id')
            ->where('positions.position_code', $positionCode)
            ->select(
                'positions.*',
                'departments.department_name',
                'departments.department_code'
            )
            ->first();
        
        if (!$position) {
            abort(404, 'Position not found');
        }

        // Log VIEW
        $this->logView(
            'HRM - Positions',
            "Viewed position: {$position->position_name} (Code: {$positionCode})"
        );

        // Get employees in this position
        $employees = DB::table('employees')
            ->where('position_id', $position->position_id)
            ->select(
                'employee_id',
                'employee_code',
                'first_name',
                'last_name',
                'email',
                'employment_status',
                'join_date',
                'base_salary'
            )
            ->orderBy('first_name')
            ->get();
        
        return view('admin.hrm.positions.show', compact('position', 'employees'));
    }

    /**
     * Edit position
     */
    public function edit($positionCode)
    {
        $position = DB::table('positions')
            ->where('position_code', $positionCode)
            ->first();
        
        if (!$position) {
            abort(404, 'Position not found');
        }

        $departments = DB::table('departments')
            ->orderBy('department_name')
            ->get();
        
        return view('admin.hrm.positions.edit', compact('position', 'departments'));
    }

    /**
     * Update position
     */
    public function update(Request $request, $positionCode)
    {
        $position = DB::table('positions')
            ->where('position_code', $positionCode)
            ->first();
        
        if (!$position) {
            abort(404, 'Position not found');
        }

        $validated = $request->validate([
            'position_name' => ['required', 'string', 'max:150', 'unique:positions,position_name,' . $position->position_id . ',position_id'],
            'department_id' => ['required', 'exists:departments,department_id'],
            'job_description' => ['nullable', 'string'],
        ], [
            'position_name.required' => 'Position name is required.',
            'position_name.unique' => 'This position name already exists.',
            'department_id.required' => 'Department is required.',
            'department_id.exists' => 'Selected department is invalid.',
        ]);

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'position_name' => $position->position_name,
                'department_id' => $position->department_id,
                'job_description' => $position->job_description,
            ];

            DB::table('positions')
                ->where('position_id', $position->position_id)
                ->update([
                    'position_name' => $validated['position_name'],
                    'department_id' => $validated['department_id'],
                    'job_description' => $validated['job_description'] ?? null,
                    'updated_at' => now(),
                ]);

            // Log UPDATE
            $this->logUpdate(
                'HRM - Positions',
                'positions',
                $position->position_id,
                $oldData,
                $validated
            );

            DB::commit();
            
            return redirect()
                ->route('admin.hrm.positions.index')
                ->with('success', 'Position updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update position: ' . $e->getMessage());
        }
    }

    /**
     * Delete position
     */
    public function destroy($positionCode)
    {
        $position = DB::table('positions')
            ->where('position_code', $positionCode)
            ->first();
        
        if (!$position) {
            abort(404, 'Position not found');
        }

        // Check if position has employees
        $employeesCount = DB::table('employees')
            ->where('position_id', $position->position_id)
            ->count();

        if ($employeesCount > 0) {
            return back()->with('error', 'Cannot delete position that has employees assigned');
        }

        DB::beginTransaction();
        try {
            // Capture data before deletion
            $oldData = [
                'position_code' => $position->position_code,
                'position_name' => $position->position_name,
                'department_id' => $position->department_id,
                'job_description' => $position->job_description,
            ];

            DB::table('positions')
                ->where('position_id', $position->position_id)
                ->delete();

            // Log DELETE
            $this->logDelete(
                'HRM - Positions',
                'positions',
                $position->position_id,
                $oldData
            );

            DB::commit();
            
            return redirect()
                ->route('admin.hrm.positions.index')
                ->with('success', 'Position deleted successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete position: ' . $e->getMessage());
        }
    }
}
