<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    use LogsActivity;
    
    /**
     * Auto-redirect to role-based dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $roleCode = $user->role->role_code ?? null;

        return match ($roleCode) {
            'admin' => redirect()->route('admin.dashboard'),
            'operator' => redirect()->route('operator.dashboard'),
            'finance_hr' => redirect()->route('finance_hr.dashboard'),
            default => abort(403, 'Invalid role'),
        };
    }

    /**
     * Admin Dashboard - Full Access
     */
    public function admin()
    {
        // Production Summary
        $productionSummary = $this->getProductionSummary();
        
        // Stock Levels
        $stockLevels = $this->getStockLevels();
        
        // Pending Orders (Purchase & Sales)
        $pendingOrders = $this->getPendingOrders();
        
        // Quick Alerts
        $alerts = $this->getQuickAlerts();
        
        // Financial Overview
        $financialOverview = $this->getFinancialOverview();

        // HR Summary
        $hrSummary = $this->getHRSummary();
        
        // Quality Control Summary
        $qcSummary = $this->getQCSummary();
        
        // Recent Activities
        $recentActivities = $this->getRecentActivities(10);

        // Log dashboard view
        $this->logView('Dashboard', 'Viewed admin dashboard');

        return view('admin.dashboard', compact(
            'productionSummary',
            'stockLevels',
            'pendingOrders',
            'alerts',
            'financialOverview',
            'hrSummary',
            'qcSummary',
            'recentActivities'
        ));
    }

    /**
     * Operator Dashboard - Production & Operations Focus
     */
    public function operator()
    {
        // Production Summary
        $productionSummary = $this->getProductionSummary();
        
        // Stock Levels
        $stockLevels = $this->getStockLevels();
        
        // Work Orders (Active & Pending)
        $workOrders = $this->getWorkOrdersSummary();
        
        // Quality Control Summary
        $qcSummary = $this->getQCSummary();
        
        // Pending Deliveries
        $pendingDeliveries = $this->getPendingDeliveries();
        
        // Quick Alerts (Production & Inventory Only)
        $alerts = $this->getQuickAlerts(['production', 'inventory', 'quality', 'maintenance']);
        
        // Maintenance Alerts
        $maintenanceAlerts = $this->getMaintenanceAlerts();

        // Log dashboard view
        $this->logView('Dashboard', 'Viewed operator dashboard');

        return view('operator.dashboard', compact(
            'productionSummary',
            'stockLevels',
            'workOrders',
            'qcSummary',
            'pendingDeliveries',
            'alerts',
            'maintenanceAlerts'
        ));
    }

    /**
     * Finance/HR Dashboard - Financial & HR Focus
     */
    public function financeHr()
    {
        // Financial Overview
        $financialOverview = $this->getFinancialOverview();
        
        // Pending Orders (Purchase & Sales)
        $pendingOrders = $this->getPendingOrders();
        
        // HR Summary
        $hrSummary = $this->getHRSummary();
        
        // Accounts Payable/Receivable Summary
        $accountsSummary = $this->getAccountsSummary();
        
        // Recent Payments
        $recentPayments = $this->getRecentPayments(10);
        
        // Leave Requests
        $leaveRequests = $this->getPendingLeaveRequests();
        
        // Quick Alerts (Finance & HR Only)
        $alerts = $this->getQuickAlerts(['finance', 'hr']);

        // Log dashboard view
        $this->logView('Dashboard', 'Viewed finance/HR dashboard');

        return view('finance-hr.dashboard', compact(
            'financialOverview',
            'pendingOrders',
            'hrSummary',
            'accountsSummary',
            'recentPayments',
            'leaveRequests',
            'alerts'
        ));
    }

    // ========================================
    // PRIVATE HELPER METHODS
    // ========================================

    /**
     * Get Production Summary
     */
    private function getProductionSummary(): array
    {
        $today = now()->toDateString();
        $thisMonth = now()->startOfMonth()->toDateString();

        return [
            // Today's production
            'today' => [
                'work_orders_active' => DB::table('work_orders')
                    ->where('status', 'In Progress')
                    ->whereDate('scheduled_start', '<=', $today)
                    ->whereDate('scheduled_end', '>=', $today)
                    ->count(),
                
                'batches_produced' => DB::table('batches')
                    ->whereDate('production_date', $today)
                    ->count(),
                
                'quantity_produced' => DB::table('batches')
                    ->whereDate('production_date', $today)
                    ->sum('quantity_produced') ?? 0,
                
                'qc_inspections' => DB::table('quality_control')
                    ->whereDate('inspection_date', $today)
                    ->count(),
            ],
            
            // This month's production
            'this_month' => [
                'total_batches' => DB::table('batches')
                    ->whereDate('production_date', '>=', $thisMonth)
                    ->count(),
                
                'total_quantity' => DB::table('batches')
                    ->whereDate('production_date', '>=', $thisMonth)
                    ->sum('quantity_produced') ?? 0,
                
                'approved_batches' => DB::table('batches')
                    ->whereDate('production_date', '>=', $thisMonth)
                    ->where('status', 'Approved')
                    ->count(),
                
                'rejected_batches' => DB::table('batches')
                    ->whereDate('production_date', '>=', $thisMonth)
                    ->where('status', 'Rejected')
                    ->count(),
            ],
            
            // Work Orders Status
            'work_orders' => [
                'pending' => DB::table('work_orders')->where('status', 'Pending')->count(),
                'in_progress' => DB::table('work_orders')->where('status', 'In Progress')->count(),
                'completed' => DB::table('work_orders')
                    ->where('status', 'Completed')
                    ->whereDate('actual_end', '>=', $thisMonth)
                    ->count(),
            ],
        ];
    }

    /**
     * Get Stock Levels
     */
    private function getStockLevels(): array
    {
        return [
            // Total inventory value
            'total_value' => DB::table('inventory as i')
                ->join('products as p', 'i.product_id', '=', 'p.product_id')
                ->selectRaw('SUM(i.quantity_on_hand * p.standard_cost) as total')
                ->value('total') ?? 0,
            
            // Low stock items (below reorder point)
            'low_stock_count' => DB::table('inventory')
                ->whereNotNull('reorder_point')
                ->whereRaw('quantity_available <= reorder_point')
                ->count(),
            
            // Out of stock
            'out_of_stock_count' => DB::table('inventory')
                ->where('quantity_on_hand', '<=', 0)
                ->count(),
            
            // Stock by type
            'by_type' => DB::table('inventory as i')
                ->join('products as p', 'i.product_id', '=', 'p.product_id')
                ->select('p.product_type', DB::raw('SUM(i.quantity_on_hand) as total_quantity'))
                ->groupBy('p.product_type')
                ->get()
                ->pluck('total_quantity', 'product_type')
                ->toArray(),
            
            // Stock movements today
            'movements_today' => DB::table('stock_movements')
                ->whereDate('movement_date', now()->toDateString())
                ->count(),
            
            // Expiring soon (within 30 days)
            'expiring_soon' => DB::table('expiry_tracking')
                ->where('status', 'Active')
                ->whereBetween('expiry_date', [now(), now()->addDays(30)])
                ->count(),
            
            // Expired items
            'expired_count' => DB::table('expiry_tracking')
                ->where('status', 'Expired')
                ->count(),
        ];
    }

    /**
     * Get Pending Orders Summary
     */
    private function getPendingOrders(): array
    {
        return [
            // Purchase Orders
            'purchase_orders' => [
                'pending' => DB::table('purchase_orders')
                    ->where('status', 'Pending')
                    ->count(),
                
                'approved' => DB::table('purchase_orders')
                    ->where('status', 'Approved')
                    ->count(),
                
                'total_value' => DB::table('purchase_orders')
                    ->whereIn('status', ['Pending', 'Approved'])
                    ->sum('total_amount') ?? 0,
                
                'overdue' => DB::table('purchase_orders')
                    ->whereIn('status', ['Approved', 'Partial'])
                    ->whereDate('expected_delivery', '<', now())
                    ->count(),
            ],
            
            // Sales Orders
            'sales_orders' => [
                'pending' => DB::table('sales_orders')
                    ->where('status', 'Pending')
                    ->count(),
                
                'approved' => DB::table('sales_orders')
                    ->where('status', 'Approved')
                    ->count(),
                
                'total_value' => DB::table('sales_orders')
                    ->whereIn('status', ['Pending', 'Approved', 'Processing'])
                    ->sum('total_amount') ?? 0,
                
                'overdue' => DB::table('sales_orders')
                    ->whereIn('status', ['Approved', 'Processing'])
                    ->whereDate('requested_delivery', '<', now())
                    ->count(),
            ],
        ];
    }

    /**
     * Get Quick Alerts
     */
    private function getQuickAlerts(?array $categories = null): array
    {
        $alerts = [];

        // Production Alerts
        if (!$categories || \in_array('production', $categories)) {
            // Overdue work orders
            $overdueWO = DB::table('work_orders')
                ->where('status', 'In Progress')
                ->whereDate('scheduled_end', '<', now())
                ->count();
            
            if ($overdueWO > 0) {
                $alerts[] = [
                    'type' => 'danger',
                    'category' => 'production',
                    'icon' => 'fa-exclamation-triangle',
                    'title' => 'Overdue Work Orders',
                    'message' => "{$overdueWO} work order(s) are past their scheduled end date",
                    'count' => $overdueWO,
                    'link' => route('production.work-orders.index', ['filter' => 'overdue']),
                ];
            }
        }

        // Inventory Alerts
        if (!$categories || \in_array('inventory', $categories)) {
            // Low stock
            $lowStock = DB::table('inventory')
                ->whereNotNull('reorder_point')
                ->whereRaw('quantity_available <= reorder_point')
                ->count();
            
            if ($lowStock > 0) {
                $alerts[] = [
                    'type' => 'warning',
                    'category' => 'inventory',
                    'icon' => 'fa-box',
                    'title' => 'Low Stock Items',
                    'message' => "{$lowStock} item(s) are below reorder point",
                    'count' => $lowStock,
                    'link' => route('inventory.stock.low-stock'),
                ];
            }

            // Expiring soon
            $expiringSoon = DB::table('expiry_tracking')
                ->where('status', 'Active')
                ->whereBetween('expiry_date', [now(), now()->addDays(30)])
                ->count();
            
            if ($expiringSoon > 0) {
                $alerts[] = [
                    'type' => 'warning',
                    'category' => 'inventory',
                    'icon' => 'fa-calendar-times',
                    'title' => 'Items Expiring Soon',
                    'message' => "{$expiringSoon} item(s) expiring within 30 days",
                    'count' => $expiringSoon,
                    'link' => route('inventory.expiry-tracking.near-expiry'),
                ];
            }

            // Expired items
            $expired = DB::table('expiry_tracking')
                ->where('status', 'Expired')
                ->count();
            
            if ($expired > 0) {
                $alerts[] = [
                    'type' => 'danger',
                    'category' => 'inventory',
                    'icon' => 'fa-exclamation-circle',
                    'title' => 'Expired Items',
                    'message' => "{$expired} item(s) have expired",
                    'count' => $expired,
                    'link' => route('inventory.expiry-tracking.expired'),
                ];
            }
        }

        // Quality Alerts
        if (!$categories || \in_array('quality', $categories)) {
            // Pending QC inspections
            $pendingQC = DB::table('quality_control')
                ->where('result', 'Pending')
                ->count();
            
            if ($pendingQC > 0) {
                $alerts[] = [
                    'type' => 'info',
                    'category' => 'quality',
                    'icon' => 'fa-clipboard-check',
                    'title' => 'Pending QC Inspections',
                    'message' => "{$pendingQC} inspection(s) awaiting review",
                    'count' => $pendingQC,
                    'link' => route('quality-control.inspections.index', ['result' => 'Pending']),
                ];
            }
        }

        // Maintenance Alerts
        if (!$categories || \in_array('maintenance', $categories)) {
            // Overdue maintenance
            $overdueMaintenance = DB::table('maintenance_schedules')
                ->where('status', 'Scheduled')
                ->whereDate('next_maintenance', '<', now())
                ->count();
            
            if ($overdueMaintenance > 0) {
                $alerts[] = [
                    'type' => 'danger',
                    'category' => 'maintenance',
                    'icon' => 'fa-tools',
                    'title' => 'Overdue Maintenance',
                    'message' => "{$overdueMaintenance} machine(s) need maintenance",
                    'count' => $overdueMaintenance,
                    'link' => '#', // TODO: Add maintenance route
                ];
            }
        }

        // Finance Alerts
        if (!$categories || \in_array('finance', $categories)) {
            // Overdue payables
            $overdueAP = DB::table('accounts_payable')
                ->whereIn('status', ['Pending', 'Partial'])
                ->whereDate('due_date', '<', now())
                ->count();
            
            if ($overdueAP > 0) {
                $alerts[] = [
                    'type' => 'danger',
                    'category' => 'finance',
                    'icon' => 'fa-file-invoice-dollar',
                    'title' => 'Overdue Payables',
                    'message' => "{$overdueAP} invoice(s) are past due date",
                    'count' => $overdueAP,
                    'link' => '#', // TODO: Add AP route
                ];
            }

            // Overdue receivables
            $overdueAR = DB::table('accounts_receivable')
                ->whereIn('status', ['Pending', 'Partial'])
                ->whereDate('due_date', '<', now())
                ->count();
            
            if ($overdueAR > 0) {
                $alerts[] = [
                    'type' => 'warning',
                    'category' => 'finance',
                    'icon' => 'fa-hand-holding-usd',
                    'title' => 'Overdue Receivables',
                    'message' => "{$overdueAR} invoice(s) are past due date",
                    'count' => $overdueAR,
                    'link' => '#', // TODO: Add AR route
                ];
            }
        }

        // HR Alerts
        if (!$categories || \in_array('hr', $categories)) {
            // Pending leave requests
            $pendingLeaves = DB::table('leaves')
                ->where('status', 'Pending')
                ->count();
            
            if ($pendingLeaves > 0) {
                $alerts[] = [
                    'type' => 'info',
                    'category' => 'hr',
                    'icon' => 'fa-calendar-alt',
                    'title' => 'Pending Leave Requests',
                    'message' => "{$pendingLeaves} leave request(s) awaiting approval",
                    'count' => $pendingLeaves,
                    'link' => '#', // TODO: Add leave route
                ];
            }
        }

        return $alerts;
    }

    /**
     * Get Financial Overview
     */
    private function getFinancialOverview(): array
    {
        $thisMonth = now()->startOfMonth();

        return [
            // Revenue (from sales orders)
            'revenue_this_month' => DB::table('sales_orders')
                ->where('status', 'Completed')
                ->whereDate('created_at', '>=', $thisMonth)
                ->sum('total_amount') ?? 0,
            
            // Expenses (from purchase orders)
            'expenses_this_month' => DB::table('purchase_orders')
                ->whereIn('status', ['Completed', 'Received'])
                ->whereDate('created_at', '>=', $thisMonth)
                ->sum('total_amount') ?? 0,
            
            // Accounts Payable
            'accounts_payable' => [
                'total' => DB::table('accounts_payable')
                    ->whereIn('status', ['Pending', 'Partial'])
                    ->sum('balance_amount') ?? 0,
                
                'overdue' => DB::table('accounts_payable')
                    ->whereIn('status', ['Pending', 'Partial'])
                    ->whereDate('due_date', '<', now())
                    ->sum('balance_amount') ?? 0,
            ],
            
            // Accounts Receivable
            'accounts_receivable' => [
                'total' => DB::table('accounts_receivable')
                    ->whereIn('status', ['Pending', 'Partial'])
                    ->sum('balance_amount') ?? 0,
                
                'overdue' => DB::table('accounts_receivable')
                    ->whereIn('status', ['Pending', 'Partial'])
                    ->whereDate('due_date', '<', now())
                    ->sum('balance_amount') ?? 0,
            ],
        ];
    }

    /**
     * Get HR Summary
     */
    private function getHRSummary(): array
    {
        $today = now()->toDateString();

        return [
            // Employee counts
            'total_employees' => DB::table('employees')
                ->where('employment_status', '!=', 'Resigned')
                ->count(),
            
            'active_employees' => DB::table('employees')
                ->where('employment_status', 'Active')
                ->count(),
            
            'probation_employees' => DB::table('employees')
                ->where('employment_status', 'Probation')
                ->count(),
            
            // Attendance today
            'attendance_today' => [
                'present' => DB::table('attendance')
                    ->whereDate('attendance_date', $today)
                    ->whereIn('status', ['Present', 'Late'])
                    ->count(),
                
                'absent' => DB::table('attendance')
                    ->whereDate('attendance_date', $today)
                    ->where('status', 'Absent')
                    ->count(),
                
                'on_leave' => DB::table('leaves')
                    ->where('status', 'Approved')
                    ->whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today)
                    ->count(),
            ],

            'leave_requests' => [
                'pending' => DB::table('leaves')->where('status', 'Pending')->count(),
                'approved_this_month' => DB::table('leaves')
                    ->where('status', 'Approved')
                    ->whereDate('approval_date', '>=', now()->startOfMonth())
                    ->count(),
            ],
        ];
    }

    /**
     * Get QC Summary
     */
    private function getQCSummary(): array
    {
        $thisMonth = now()->startOfMonth();

        return [
            'inspections_today' => DB::table('quality_control')
                ->whereDate('inspection_date', now()->toDateString())
                ->count(),
            
            'inspections_this_month' => DB::table('quality_control')
                ->whereDate('inspection_date', '>=', $thisMonth)
                ->count(),
            
            'pass_rate' => $this->calculatePassRate($thisMonth),
            
            'pending_inspections' => DB::table('quality_control')
                ->where('result', 'Pending')
                ->count(),
            
            'failed_inspections' => DB::table('quality_control')
                ->where('result', 'Failed')
                ->whereDate('inspection_date', '>=', $thisMonth)
                ->count(),
        ];
    }

    /**
     * Calculate QC pass rate
     */
    private function calculatePassRate($fromDate): float
    {
        $total = DB::table('quality_control')
            ->whereDate('inspection_date', '>=', $fromDate)
            ->whereIn('result', ['Passed', 'Failed'])
            ->count();
        
        if ($total === 0) {
            return 0;
        }

        $passed = DB::table('quality_control')
            ->whereDate('inspection_date', '>=', $fromDate)
            ->where('result', 'Passed')
            ->count();
        
        return round(($passed / $total) * 100, 2);
    }

    /**
     * Get Recent Activities
     */
    private function getRecentActivities(int $limit = 10): array
    {
        return DB::table('activity_logs')
            ->join('users', 'activity_logs.user_id', '=', 'users.user_id')
            ->select(
                'activity_logs.*',
                'users.full_name',
                'users.username'
            )
            ->orderBy('activity_logs.activity_timestamp', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get Work Orders Summary
     */
    private function getWorkOrdersSummary(): array
    {
        return [
            'pending' => DB::table('work_orders')->where('status', 'Pending')->count(),
            'in_progress' => DB::table('work_orders')->where('status', 'In Progress')->count(),
            'completed_today' => DB::table('work_orders')
                ->where('status', 'Completed')
                ->whereDate('actual_end', now()->toDateString())
                ->count(),
            'overdue' => DB::table('work_orders')
                ->where('status', 'In Progress')
                ->whereDate('scheduled_end', '<', now())
                ->count(),
        ];
    }

    /**
     * Get Pending Deliveries
     */
    private function getPendingDeliveries(): array
    {
        return [
            'scheduled_today' => DB::table('deliveries')
                ->whereDate('delivery_date', now()->toDateString())
                ->whereIn('status', ['Scheduled', 'In Transit'])
                ->count(),
            
            'in_transit' => DB::table('deliveries')
                ->where('status', 'In Transit')
                ->count(),
            
            'completed_today' => DB::table('deliveries')
                ->where('status', 'Delivered')
                ->whereDate('delivery_date', now()->toDateString())
                ->count(),
        ];
    }

    /**
     * Get Maintenance Alerts
     */
    private function getMaintenanceAlerts(): array
    {
        return [
            'due_today' => DB::table('maintenance_schedules')
                ->where('status', 'Scheduled')
                ->whereDate('next_maintenance', now()->toDateString())
                ->count(),
            
            'overdue' => DB::table('maintenance_schedules')
                ->where('status', 'Scheduled')
                ->whereDate('next_maintenance', '<', now())
                ->count(),
            
            'pending_requests' => DB::table('maintenance_requests')
                ->whereIn('status', ['Pending', 'Assigned'])
                ->count(),
        ];
    }

    /**
     * Get Accounts Summary
     */
    private function getAccountsSummary(): array
    {
        return [
            'payables' => [
                'total' => DB::table('accounts_payable')
                    ->whereIn('status', ['Pending', 'Partial'])
                    ->sum('balance_amount') ?? 0,
                
                'overdue' => DB::table('accounts_payable')
                    ->whereIn('status', ['Pending', 'Partial'])
                    ->whereDate('due_date', '<', now())
                    ->count(),
                
                'due_this_week' => DB::table('accounts_payable')
                    ->whereIn('status', ['Pending', 'Partial'])
                    ->whereBetween('due_date', [now(), now()->addDays(7)])
                    ->count(),
            ],
            
            'receivables' => [
                'total' => DB::table('accounts_receivable')
                    ->whereIn('status', ['Pending', 'Partial'])
                    ->sum('balance_amount') ?? 0,
                
                'overdue' => DB::table('accounts_receivable')
                    ->whereIn('status', ['Pending', 'Partial'])
                    ->whereDate('due_date', '<', now())
                    ->count(),
                
                'due_this_week' => DB::table('accounts_receivable')
                    ->whereIn('status', ['Pending', 'Partial'])
                    ->whereBetween('due_date', [now(), now()->addDays(7)])
                    ->count(),
            ],
        ];
    }

    /**
     * Get Recent Payments
     */
    private function getRecentPayments(int $limit = 10): array
    {
        return DB::table('payments as p')
            ->leftJoin('suppliers as s', 'p.supplier_id', '=', 's.supplier_id')
            ->leftJoin('customers as c', 'p.customer_id', '=', 'c.customer_id')
            ->select(
                'p.*',
                's.supplier_name',
                'c.customer_name'
            )
            ->orderBy('p.payment_date', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get Pending Leave Requests
     */
    private function getPendingLeaveRequests(): array
    {
        return DB::table('leaves as l')
            ->join('employees as e', 'l.employee_id', '=', 'e.employee_id')
            ->join('leave_types as lt', 'l.leave_type_id', '=', 'lt.leave_type_id')
            ->where('l.status', 'Pending')
            ->select(
                'l.*',
                DB::raw('CONCAT(e.first_name, " ", e.last_name) as employee_name'),
                'e.employee_code',
                'lt.leave_type_name'
            )
            ->orderBy('l.created_at', 'desc')
            ->get()
            ->toArray();
    }
}
