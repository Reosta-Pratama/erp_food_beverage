<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\UserManagement\ActivityLog;
use App\Models\UserManagement\Permission;
use App\Models\UserManagement\Role;
use App\Models\UserManagement\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_roles' => Role::count(),
            'total_permissions' => Permission::count(),
            'recent_activities' => ActivityLog::with('user')
                ->latest('activity_timestamp')
                ->take(10)
                ->get(),
            'pending_approvals' => 0, // TODO: Implement approval system
        ];
        
        return view('admin.dashboard', [
            'stats' => $stats
        ]);
    }

    /**
     * Operator dashboard
     */
    public function operator()
    {
        $stats = [
            'pending_work_orders' => 0, // TODO: Implement work orders
            'active_batches' => 0,
            'quality_inspections' => 0,
            'maintenance_requests' => 0,
        ];
        
        return view('operator.dashboard', [
            'stats' => $stats
        ]);
    }

    /**
     * Finance/HR dashboard
     */
    public function financeHr()
    {
        $stats = [
            'pending_payroll' => 0, // TODO: Implement payroll
            'pending_invoices' => 0,
            'total_employees' => 0,
            'pending_leaves' => 0,
        ];
        
        return view('finance_hr.dashboard', [
            'stats' => $stats
        ]);
    }
}
