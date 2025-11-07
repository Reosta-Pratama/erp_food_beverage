<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    /**
     * Redirect to role-specific dashboard
     */
    public function index()
    {
        $roleCode = Auth::user()->role->role_code;
        
        return match($roleCode) {
            'admin' => redirect()->route('admin.dashboard'),
            'operator' => redirect()->route('operator.dashboard'),
            'finance_hr' => redirect()->route('finance_hr.dashboard'),
            default => abort(403, 'Unauthorized access')
        };
    }

    /**
     * Admin dashboard
     */
    public function admin()
    {
        // Use DB::table for better performance on counts
        $stats = [
            'total_users' => DB::table('users')->count(),
            'active_users' => DB::table('users')->where('is_active', 1)->count(),
            'total_roles' => DB::table('roles')->count(),
            'total_permissions' => DB::table('permissions')->count(),
            
            // Optimized recent activities with JOIN
            'recent_activities' => DB::table('activity_logs')
                ->join('users', 'activity_logs.user_id', '=', 'users.user_id')
                ->select(
                    'activity_logs.activity_id',
                    'activity_logs.activity_type',
                    'activity_logs.description',
                    'activity_logs.module_name',
                    'activity_logs.activity_timestamp',
                    'users.username',
                    'users.full_name'
                )
                ->orderByDesc('activity_logs.activity_timestamp')
                ->limit(10)
                ->get(),
            
            'pending_approvals' => 0,
        ];
        
        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Operator dashboard
     */
    public function operator()
    {
        $stats = [
            'pending_work_orders' => DB::table('work_orders')
                ->where('status', 'PENDING')
                ->count(),
            'active_batches' => DB::table('batches')
                ->where('status', 'IN_PROGRESS')
                ->count(),
            'quality_inspections' => DB::table('quality_control')
                ->where('result', 'PENDING')
                ->count(),
            'maintenance_requests' => DB::table('maintenance_requests')
                ->where('status', 'OPEN')
                ->count(),
        ];
        
        return view('operator.dashboard', compact('stats'));
    }

    /**
     * Finance/HR dashboard
     */
    public function financeHr()
    {
        $stats = [
            'pending_payroll' => DB::table('payroll')
                ->where('status', 'PENDING')
                ->count(),
            'pending_invoices' => DB::table('accounts_receivable')
                ->where('status', 'UNPAID')
                ->count(),
            'total_employees' => DB::table('employees')
                ->where('employment_status', 'ACTIVE')
                ->count(),
            'pending_leaves' => DB::table('leaves')
                ->where('status', 'PENDING')
                ->count(),
        ];
        
        return view('finance_hr.dashboard', compact('stats'));
    }
}
