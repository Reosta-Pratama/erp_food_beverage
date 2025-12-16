<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class DashboardWidgetHelper
{
    /**
     * Get production chart data (last 7 days)
     */
    public static function getProductionChartData(): array
    {
        $data = DB::table('batches')
            ->select(
                DB::raw('DATE(production_date) as date'),
                DB::raw('COUNT(*) as batch_count'),
                DB::raw('SUM(quantity_produced) as total_quantity'),
                DB::raw('SUM(CASE WHEN status = "Approved" THEN 1 ELSE 0 END) as approved_count'),
                DB::raw('SUM(CASE WHEN status = "Rejected" THEN 1 ELSE 0 END) as rejected_count')
            )
            ->whereBetween('production_date', [now()->subDays(6), now()])
            ->groupBy(DB::raw('DATE(production_date)'))
            ->orderBy('production_date', 'asc')
            ->get();

        // Fill missing dates with zeros
        $dates = [];
        for ($i = 6; $i >= 0; $i--) {
            $dates[] = now()->subDays($i)->format('Y-m-d');
        }

        $result = [
            'labels' => [],
            'datasets' => [
                'produced' => [],
                'approved' => [],
                'rejected' => [],
            ],
        ];

        foreach ($dates as $date) {
            $dayData = $data->firstWhere('date', $date);
            
            $result['labels'][] = now()->parse($date)->format('M d');
            $result['datasets']['produced'][] = $dayData->batch_count ?? 0;
            $result['datasets']['approved'][] = $dayData->approved_count ?? 0;
            $result['datasets']['rejected'][] = $dayData->rejected_count ?? 0;
        }

        return $result;
    }

    /**
     * Get stock level chart data (by category)
     */
    public static function getStockLevelChartData(): array
    {
        $data = DB::table('inventory as i')
            ->join('products as p', 'i.product_id', '=', 'p.product_id')
            ->join('product_categories as pc', 'p.category_id', '=', 'pc.category_id')
            ->select(
                'pc.category_name',
                DB::raw('SUM(i.quantity_on_hand) as total_quantity')
            )
            ->groupBy('pc.category_id', 'pc.category_name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();

        return [
            'labels' => $data->pluck('category_name')->toArray(),
            'values' => $data->pluck('total_quantity')->toArray(),
        ];
    }

    /**
     * Get sales vs purchase trend (last 12 months)
     */
    public static function getSalesPurchaseTrendData(): array
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $months[] = now()->subMonths($i)->format('Y-m');
        }

        $sales = DB::table('sales_orders')
            ->select(
                DB::raw('DATE_FORMAT(order_date, "%Y-%m") as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->whereIn('status', ['Completed', 'Delivered'])
            ->whereBetween('order_date', [now()->subMonths(11)->startOfMonth(), now()])
            ->groupBy(DB::raw('DATE_FORMAT(order_date, "%Y-%m")'))
            ->pluck('total', 'month')
            ->toArray();

        $purchases = DB::table('purchase_orders')
            ->select(
                DB::raw('DATE_FORMAT(order_date, "%Y-%m") as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->whereIn('status', ['Completed', 'Received'])
            ->whereBetween('order_date', [now()->subMonths(11)->startOfMonth(), now()])
            ->groupBy(DB::raw('DATE_FORMAT(order_date, "%Y-%m")'))
            ->pluck('total', 'month')
            ->toArray();

        $result = [
            'labels' => [],
            'datasets' => [
                'sales' => [],
                'purchases' => [],
            ],
        ];

        foreach ($months as $month) {
            $result['labels'][] = now()->parse($month)->format('M Y');
            $result['datasets']['sales'][] = $sales[$month] ?? 0;
            $result['datasets']['purchases'][] = $purchases[$month] ?? 0;
        }

        return $result;
    }

    /**
     * Get order status distribution
     */
    public static function getOrderStatusDistribution(): array
    {
        // Sales Orders
        $salesOrders = DB::table('sales_orders')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Purchase Orders
        $purchaseOrders = DB::table('purchase_orders')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'sales' => $salesOrders,
            'purchase' => $purchaseOrders,
        ];
    }

    /**
     * Get top selling products (this month)
     */
    public static function getTopSellingProducts(int $limit = 10): array
    {
        return DB::table('sales_order_items as soi')
            ->join('sales_orders as so', 'soi.so_id', '=', 'so.so_id')
            ->join('products as p', 'soi.product_id', '=', 'p.product_id')
            ->select(
                'p.product_code',
                'p.product_name',
                DB::raw('SUM(soi.quantity_ordered) as total_quantity'),
                DB::raw('SUM(soi.line_total) as total_sales')
            )
            ->whereDate('so.order_date', '>=', now()->startOfMonth())
            ->whereIn('so.status', ['Approved', 'Processing', 'Completed'])
            ->groupBy('p.product_id', 'p.product_code', 'p.product_name')
            ->orderBy('total_sales', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get QC pass rate trend (last 30 days)
     */
    public static function getQCPassRateTrend(): array
    {
        $data = DB::table('quality_control')
            ->select(
                DB::raw('DATE(inspection_date) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN result = "Passed" THEN 1 ELSE 0 END) as passed')
            )
            ->whereBetween('inspection_date', [now()->subDays(29), now()])
            ->whereIn('result', ['Passed', 'Failed'])
            ->groupBy(DB::raw('DATE(inspection_date)'))
            ->orderBy('inspection_date', 'asc')
            ->get();

        $dates = [];
        for ($i = 29; $i >= 0; $i--) {
            $dates[] = now()->subDays($i)->format('Y-m-d');
        }

        $result = [
            'labels' => [],
            'pass_rates' => [],
        ];

        foreach ($dates as $date) {
            $dayData = $data->firstWhere('date', $date);
            
            $result['labels'][] = now()->parse($date)->format('M d');
            
            if ($dayData && $dayData->total > 0) {
                $passRate = round(($dayData->passed / $dayData->total) * 100, 2);
                $result['pass_rates'][] = $passRate;
            } else {
                $result['pass_rates'][] = null; // No data for this day
            }
        }

        return $result;
    }

    /**
     * Get attendance summary (current month)
     */
    public static function getAttendanceSummary(): array
    {
        $thisMonth = now()->startOfMonth();

        return [
            'total_working_days' => now()->diffInDaysFiltered(function($date) use ($thisMonth) {
                return $date->isWeekday() && $date >= $thisMonth && $date <= now();
            }, $thisMonth),
            
            'present' => DB::table('attendance')
                ->whereDate('attendance_date', '>=', $thisMonth)
                ->whereIn('status', ['Present', 'Late'])
                ->count(),
            
            'absent' => DB::table('attendance')
                ->whereDate('attendance_date', '>=', $thisMonth)
                ->where('status', 'Absent')
                ->count(),
            
            'on_leave' => DB::table('leaves')
                ->where('status', 'Approved')
                ->where(function($q) use ($thisMonth) {
                    $q->whereBetween('start_date', [$thisMonth, now()])
                      ->orWhereBetween('end_date', [$thisMonth, now()]);
                })
                ->count(),
            
            'average_attendance_rate' => self::calculateAverageAttendanceRate($thisMonth),
        ];
    }

    /**
     * Calculate average attendance rate
     */
    private static function calculateAverageAttendanceRate($fromDate): float
    {
        $totalEmployees = DB::table('employees')
            ->where('employment_status', '!=', 'Resigned')
            ->count();

        if ($totalEmployees === 0) {
            return 0;
        }

        $workingDays = now()->diffInDaysFiltered(function($date) use ($fromDate) {
            return $date->isWeekday() && $date >= $fromDate && $date <= now();
        }, $fromDate);

        if ($workingDays === 0) {
            return 0;
        }

        $totalPresent = DB::table('attendance')
            ->whereDate('attendance_date', '>=', $fromDate)
            ->whereIn('status', ['Present', 'Late'])
            ->count();

        $expectedAttendance = $totalEmployees * $workingDays;
        
        return round(($totalPresent / $expectedAttendance) * 100, 2);
    }

    /**
     * Get delivery performance (current month)
     */
    public static function getDeliveryPerformance(): array
    {
        $thisMonth = now()->startOfMonth();

        $deliveries = DB::table('deliveries')
            ->whereDate('delivery_date', '>=', $thisMonth)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $onTimeDeliveries = DB::table('deliveries as d')
            ->join('sales_orders as so', 'd.so_id', '=', 'so.so_id')
            ->whereDate('d.delivery_date', '>=', $thisMonth)
            ->where('d.status', 'Delivered')
            ->whereRaw('d.delivery_date <= so.requested_delivery')
            ->count();

        $totalDelivered = $deliveries['Delivered'] ?? 0;
        $onTimeRate = $totalDelivered > 0 ? round(($onTimeDeliveries / $totalDelivered) * 100, 2) : 0;

        return [
            'total' => array_sum($deliveries),
            'by_status' => $deliveries,
            'on_time_deliveries' => $onTimeDeliveries,
            'on_time_rate' => $onTimeRate,
        ];
    }

    /**
     * Get maintenance statistics
     */
    public static function getMaintenanceStatistics(): array
    {
        $thisMonth = now()->startOfMonth();

        return [
            'scheduled' => DB::table('maintenance_schedules')
                ->where('status', 'Scheduled')
                ->count(),
            
            'completed_this_month' => DB::table('maintenance_history')
                ->whereDate('maintenance_date', '>=', $thisMonth)
                ->where('status', 'Completed')
                ->count(),
            
            'pending_requests' => DB::table('maintenance_requests')
                ->whereIn('status', ['Pending', 'Assigned'])
                ->count(),
            
            'overdue' => DB::table('maintenance_schedules')
                ->where('status', 'Scheduled')
                ->whereDate('next_maintenance', '<', now())
                ->count(),
            
            'total_downtime_hours' => DB::table('downtime_tracking')
                ->whereDate('start_time', '>=', $thisMonth)
                ->whereNotNull('end_time')
                ->sum('duration_hours') ?? 0,
        ];
    }

    /**
     * Get financial summary
     */
    public static function getFinancialSummary(): array
    {
        $thisMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();

        $thisMonthRevenue = DB::table('sales_orders')
            ->whereIn('status', ['Completed', 'Delivered'])
            ->whereDate('order_date', '>=', $thisMonth)
            ->sum('total_amount') ?? 0;

        $lastMonthRevenue = DB::table('sales_orders')
            ->whereIn('status', ['Completed', 'Delivered'])
            ->whereBetween('order_date', [$lastMonth, $thisMonth])
            ->sum('total_amount') ?? 0;

        $revenueGrowth = $lastMonthRevenue > 0 
            ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 2)
            : 0;

        return [
            'revenue' => [
                'this_month' => $thisMonthRevenue,
                'last_month' => $lastMonthRevenue,
                'growth_percentage' => $revenueGrowth,
            ],
            
            'expenses' => [
                'this_month' => DB::table('purchase_orders')
                    ->whereIn('status', ['Completed', 'Received'])
                    ->whereDate('order_date', '>=', $thisMonth)
                    ->sum('total_amount') ?? 0,
            ],
            
            'profit_margin' => self::calculateProfitMargin($thisMonth),
        ];
    }

    /**
     * Calculate profit margin
     */
    private static function calculateProfitMargin($fromDate): float
    {
        $revenue = DB::table('sales_orders')
            ->whereIn('status', ['Completed', 'Delivered'])
            ->whereDate('order_date', '>=', $fromDate)
            ->sum('total_amount') ?? 0;

        $expenses = DB::table('purchase_orders')
            ->whereIn('status', ['Completed', 'Received'])
            ->whereDate('order_date', '>=', $fromDate)
            ->sum('total_amount') ?? 0;

        if ($revenue === 0) {
            return 0;
        }

        $profit = $revenue - $expenses;
        return round(($profit / $revenue) * 100, 2);
    }
}