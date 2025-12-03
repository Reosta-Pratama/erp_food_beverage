<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeSelfServiceController extends Controller
{
    //
    use LogsActivity;

    /**
     * Show employee dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        if (!$user->employee_id) {
            abort(403, 'You are not linked to an employee record');
        }

        $this->logView('Employee Self Service', 'Viewed self service dashboard');

        $employee = DB::table('employees')
            ->join('departments', 'employees.department_id', '=', 'departments.department_id')
            ->join('positions', 'employees.position_id', '=', 'positions.position_id')
            ->where('employees.employee_id', $user->employee_id)
            ->select(
                'employees.*',
                'departments.department_name',
                'positions.position_name'
            )
            ->first();

        // Get today's attendance
        $todayAttendance = DB::table('attendance')
            ->where('employee_id', $employee->employee_id)
            ->whereDate('attendance_date', today())
            ->first();

        // Get this month attendance summary
        $monthAttendance = DB::table('attendance')
            ->where('employee_id', $employee->employee_id)
            ->whereMonth('attendance_date', now()->month)
            ->whereYear('attendance_date', now()->year)
            ->select(
                DB::raw('COUNT(*) as total_days'),
                DB::raw('SUM(CASE WHEN status = "Present" THEN 1 ELSE 0 END) as present_days'),
                DB::raw('SUM(CASE WHEN status = "Absent" THEN 1 ELSE 0 END) as absent_days'),
                DB::raw('SUM(CASE WHEN status = "Leave" THEN 1 ELSE 0 END) as leave_days'),
                DB::raw('SUM(overtime_hours) as total_overtime')
            )
            ->first();

        // Get pending leave requests
        $pendingLeaves = DB::table('leaves')
            ->join('leave_types', 'leaves.leave_type_id', '=', 'leave_types.leave_type_id')
            ->where('leaves.employee_id', $employee->employee_id)
            ->where('leaves.status', 'Pending')
            ->select(
                'leaves.*',
                'leave_types.leave_type_name'
            )
            ->orderByDesc('leaves.created_at')
            ->limit(5)
            ->get();

        // Get upcoming leaves
        $upcomingLeaves = DB::table('leaves')
            ->join('leave_types', 'leaves.leave_type_id', '=', 'leave_types.leave_type_id')
            ->where('leaves.employee_id', $employee->employee_id)
            ->where('leaves.status', 'Approved')
            ->where('leaves.start_date', '>=', today())
            ->select(
                'leaves.*',
                'leave_types.leave_type_name'
            )
            ->orderBy('leaves.start_date')
            ->limit(5)
            ->get();

        return view('employee.dashboard', compact(
            'employee',
            'todayAttendance',
            'monthAttendance',
            'pendingLeaves',
            'upcomingLeaves'
        ));
    }

    /**
     * View personal profile
     */
    public function profile()
    {
        $user = Auth::user();
        
        if (!$user->employee_id) {
            abort(403, 'You are not linked to an employee record');
        }

        $this->logView('Employee Self Service', 'Viewed personal profile');

        $employee = DB::table('employees')
            ->join('departments', 'employees.department_id', '=', 'departments.department_id')
            ->join('positions', 'employees.position_id', '=', 'positions.position_id')
            ->where('employees.employee_id', $user->employee_id)
            ->select(
                'employees.*',
                'departments.department_name',
                'departments.department_code',
                'positions.position_name',
                'positions.position_code'
            )
            ->first();

        // Get certifications
        $certifications = DB::table('certifications')
            ->where('employee_id', $employee->employee_id)
            ->orderByDesc('issue_date')
            ->get();

        // Get training history
        $trainings = DB::table('training_participants')
            ->join('training_programs', 'training_participants.training_id', '=', 'training_programs.training_id')
            ->where('training_participants.employee_id', $employee->employee_id)
            ->select(
                'training_programs.training_name',
                'training_programs.start_date',
                'training_programs.end_date',
                'training_participants.status',
                'training_participants.score',
                'training_participants.is_certified'
            )
            ->orderByDesc('training_programs.start_date')
            ->get();

        return view('employee.profile', compact('employee', 'certifications', 'trainings'));
    }

    /**
     * Update personal information
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->employee_id) {
            abort(403, 'You are not linked to an employee record');
        }

        $employee = DB::table('employees')
            ->where('employee_id', $user->employee_id)
            ->first();

        $validated = $request->validate([
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:150', 'unique:employees,email,' . $employee->employee_id . ',employee_id'],
        ], [
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already in use.',
        ]);

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'phone' => $employee->phone,
                'address' => $employee->address,
                'email' => $employee->email,
            ];

            DB::table('employees')
                ->where('employee_id', $employee->employee_id)
                ->update([
                    'phone' => $validated['phone'] ?? null,
                    'address' => $validated['address'] ?? null,
                    'email' => $validated['email'] ?? null,
                    'updated_at' => now(),
                ]);

            // Log UPDATE
            $this->logUpdate(
                'Employee Self Service',
                'employees',
                $employee->employee_id,
                $oldData,
                $validated
            );

            // Update user email if changed
            if (isset($validated['email']) && $validated['email'] !== $user->email) {
                DB::table('users')
                    ->where('user_id', $user->user_id)
                    ->update(['email' => $validated['email']]);
            }

            DB::commit();
            
            return back()->with('success', 'Profile updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    /**
     * View attendance history
     */
    public function attendance(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->employee_id) {
            abort(403, 'You are not linked to an employee record');
        }

        $this->logView('Employee Self Service', 'Viewed attendance history');

        $query = DB::table('attendance')
            ->leftJoin('shifts', 'attendance.shift_id', '=', 'shifts.shift_id')
            ->where('attendance.employee_id', $user->employee_id)
            ->select(
                'attendance.*',
                'shifts.shift_name'
            );

        // Filter by month and year
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        $query->whereMonth('attendance.attendance_date', $month)
            ->whereYear('attendance.attendance_date', $year);

        // Sorting
        $sortBy = $request->get('sort_by', 'attendance_date');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortFields = ['attendance_date', 'check_in_time', 'check_out_time', 'status'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy('attendance.' . $sortBy, $sortOrder);
        } else {
            $query->orderByDesc('attendance.attendance_date');
        }

        $attendances = $query->get();

        return view('employee.attendance', compact('attendances', 'month', 'year'));
    }

    /**
     * View leave history
     */
    public function leaves(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->employee_id) {
            abort(403, 'You are not linked to an employee record');
        }

        $this->logView('Employee Self Service', 'Viewed leave history');

        $query = DB::table('leaves')
            ->join('leave_types', 'leaves.leave_type_id', '=', 'leave_types.leave_type_id')
            ->leftJoin('employees as approver', 'leaves.approved_by', '=', 'approver.employee_id')
            ->where('leaves.employee_id', $user->employee_id)
            ->select(
                'leaves.*',
                'leave_types.leave_type_name',
                'leave_types.leave_type_id',
                DB::raw('CONCAT(approver.first_name, " ", approver.last_name) as approver_name')
            );

        // Filter by status
        if ($request->filled('status')) {
            $query->where('leaves.status', $request->status);
        }
        
        // Filter by leave type
        if ($request->filled('leave_type_id')) {
            $query->where('leaves.leave_type_id', $request->leave_type_id);
        }
        
        // Filter by year
        if ($request->filled('year')) {
            $query->whereYear('leaves.start_date', $request->year);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortFields = ['created_at', 'start_date', 'end_date', 'total_days', 'status'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy('leaves.' . $sortBy, $sortOrder);
        } else {
            $query->orderByDesc('leaves.created_at');
        }

        $leaves = $query->paginate(15)->withQueryString();

        // Get leave balance summary
        $leaveBalance = DB::table('leave_types')
            ->leftJoin('leaves', function($join) use ($user) {
                $join->on('leave_types.leave_type_id', '=', 'leaves.leave_type_id')
                    ->where('leaves.employee_id', '=', $user->employee_id)
                    ->where('leaves.status', '=', 'Approved')
                    ->whereYear('leaves.start_date', '=', now()->year);
            })
            ->select(
                'leave_types.leave_type_id',
                'leave_types.leave_type_name',
                'leave_types.max_days_per_year',
                DB::raw('COALESCE(SUM(leaves.total_days), 0) as days_taken'),
                DB::raw('leave_types.max_days_per_year - COALESCE(SUM(leaves.total_days), 0) as days_remaining')
            )
            ->groupBy('leave_types.leave_type_id', 'leave_types.leave_type_name', 'leave_types.max_days_per_year')
            ->get();

        return view('employee.leaves', compact('leaves', 'leaveBalance'));
    }

    /**
     * Request leave
     */
    public function requestLeave(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->employee_id) {
            abort(403, 'You are not linked to an employee record');
        }

        $validated = $request->validate([
            'leave_type_id' => ['required', 'exists:leave_types,leave_type_id'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['nullable', 'string', 'max:500'],
        ], [
            'leave_type_id.required' => 'Leave type is required.',
            'leave_type_id.exists' => 'Selected leave type is invalid.',
            'start_date.required' => 'Start date is required.',
            'start_date.after_or_equal' => 'Start date must be today or later.',
            'end_date.required' => 'End date is required.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
            'reason.max' => 'Reason must not exceed 500 characters.',
        ]);

        DB::beginTransaction();
        try {
            // Calculate total days
            $startDate = new \DateTime($validated['start_date']);
            $endDate = new \DateTime($validated['end_date']);
            $totalDays = $endDate->diff($startDate)->days + 1;

            // Check leave balance
            $leaveType = DB::table('leave_types')
                ->where('leave_type_id', $validated['leave_type_id'])
                ->first();

            $usedDays = DB::table('leaves')
                ->where('employee_id', $user->employee_id)
                ->where('leave_type_id', $validated['leave_type_id'])
                ->where('status', 'Approved')
                ->whereYear('start_date', now()->year)
                ->sum('total_days');

            $remainingDays = $leaveType->max_days_per_year - $usedDays;

            if ($totalDays > $remainingDays) {
                return back()
                    ->withInput()
                    ->with('error', "Insufficient leave balance. You have only {$remainingDays} day(s) remaining for {$leaveType->leave_type_name}.");
            }
            
            // Check for overlapping leaves
            $overlapping = DB::table('leaves')
                ->where('employee_id', $user->employee_id)
                ->whereIn('status', ['Pending', 'Approved'])
                ->where(function($query) use ($validated) {
                    $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                        ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                        ->orWhere(function($q) use ($validated) {
                            $q->where('start_date', '<=', $validated['start_date'])
                                ->where('end_date', '>=', $validated['end_date']);
                        });
                })
                ->exists();
                
            if ($overlapping) {
                return back()
                    ->withInput()
                    ->with('error', 'You already have a leave request for overlapping dates.');
            }

            DB::table('leaves')->insert([
                'employee_id' => $user->employee_id,
                'leave_type_id' => $validated['leave_type_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'total_days' => $totalDays,
                'reason' => $validated['reason'] ?? null,
                'status' => 'Pending',
                'approved_by' => null,
                'approval_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Log leave request
            $this->logActivity(
                'Leave Requested',
                "Requested {$leaveType->leave_type_name} from {$validated['start_date']} to {$validated['end_date']} ({$totalDays} days)",
                'Employee Self Service'
            );

            DB::commit();
            
            return redirect()->route('employee.leaves')
                ->with('success', "Leave request submitted successfully. Your request for {$totalDays} day(s) is pending approval.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to submit leave request: ' . $e->getMessage());
        }
    }

    /**
     * View payslips
     */
    public function payslips(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->employee_id) {
            abort(403, 'You are not linked to an employee record');
        }

        $this->logView('Employee Self Service', 'Viewed payslips');

        $query = DB::table('payroll')
            ->where('employee_id', $user->employee_id)
            ->select(
                'payroll_id',
                'month',
                'year',
                'base_salary',
                'overtime_pay',
                'allowances',
                'deductions',
                'gross_salary',
                'net_salary',
                'payment_date',
                'status'
            );

        // Filter by year
        $year = $request->get('year', now()->year);
        $query->where('year', $year);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by month
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'year');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if ($sortBy === 'year' || $sortBy === 'month') {
            $query->orderBy('year', $sortOrder)
                ->orderBy('month', $sortOrder);
        } elseif (in_array($sortBy, ['gross_salary', 'net_salary', 'payment_date'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderByDesc('year')->orderByDesc('month');
        }

        $payslips = $query->get();

        return view('employee.payslips', compact('payslips', 'year'));
    }
}