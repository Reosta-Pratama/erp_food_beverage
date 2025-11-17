<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditLogController extends Controller
{
    //
    /**
     * Display audit logs with comprehensive filtering
     */
    public function index(Request $request)
    {
        $query = DB::table('audit_logs')
            ->join('users', 'audit_logs.user_id', '=', 'users.user_id')
            ->join('roles', 'users.role_id', '=', 'roles.role_id')
            ->select(
                'audit_logs.audit_id',
                'audit_logs.user_id',
                'audit_logs.action_type',
                'audit_logs.module_name',
                'audit_logs.table_name',
                'audit_logs.record_id',
                'audit_logs.ip_address',
                'audit_logs.action_timestamp',
                'users.username',
                'users.full_name',
                'roles.role_name',
                'roles.role_code'
            );

        // Extract date_from and date_to from daterange input
        if ($request->filled('daterange')) {
            [$dateFrom, $dateTo] = array_map('trim', explode('to', $request->daterange . ' to ')); 
            
            $request->merge([
                'date_from' => $dateFrom ?: null,
                'date_to' => $dateTo ?: null,
            ]);
        }
        
        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('audit_logs.user_id', $request->user_id);
        }
        
        // Filter by action type
        if ($request->filled('action_type')) {
            $query->where('audit_logs.action_type', $request->action_type);
        }
        
        // Filter by module
        if ($request->filled('module_name')) {
            $query->where('audit_logs.module_name', $request->module_name);
        }
        
        // Filter by table
        if ($request->filled('table_name')) {
            $query->where('audit_logs.table_name', $request->table_name);
        }
        
        // Filter by record ID
        if ($request->filled('record_id')) {
            $query->where('audit_logs.record_id', $request->record_id);
        }
        
        // Filter by IP address
        if ($request->filled('ip_address')) {
            $query->where('audit_logs.ip_address', 'like', "%{$request->ip_address}%");
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('audit_logs.action_timestamp', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('audit_logs.action_timestamp', '<=', $request->date_to);
        }
        
        $logs = $query->orderByDesc('audit_logs.action_timestamp')
            ->paginate(50);
        
        // Get filter options with optimized queries
        $actionTypes = DB::table('audit_logs')
            ->select('action_type', DB::raw('COUNT(*) as count'))
            ->groupBy('action_type')
            ->orderBy('action_type')
            ->pluck('count', 'action_type');
        
        $modules = DB::table('audit_logs')
            ->select('module_name', DB::raw('COUNT(*) as count'))
            ->groupBy('module_name')
            ->orderBy('module_name')
            ->pluck('count', 'module_name');
        
        $tables = DB::table('audit_logs')
            ->select('table_name', DB::raw('COUNT(*) as count'))
            ->groupBy('table_name')
            ->orderBy('table_name')
            ->pluck('count', 'table_name');
        
        $users = DB::table('users')
            ->join('audit_logs', 'users.user_id', '=', 'audit_logs.user_id')
            ->select(
                'users.user_id',
                'users.username',
                'users.full_name',
                DB::raw('COUNT(DISTINCT audit_logs.audit_id) as log_count')
            )
            ->groupBy('users.user_id', 'users.username', 'users.full_name')
            ->orderBy('users.username')
            ->get();
        
        return view('admin.logs.audit', compact('logs', 'actionTypes', 'modules', 'tables', 'users'));
    }
    
    /**
     * Show detailed audit log with old/new data comparison
     */
    public function show($auditId)
    {
        $auditLog = DB::table('audit_logs')
            ->join('users', 'audit_logs.user_id', '=', 'users.user_id')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.role_id')
            ->where('audit_logs.audit_id', $auditId)
            ->select(
                'audit_logs.*',
                'users.username',
                'users.full_name',
                'users.email',
                'roles.role_name'
            )
            ->first();
        
        if (!$auditLog) {
            abort(404, 'Audit log not found');
        }
        
        // Decode JSON data
        $auditLog->old_data = $auditLog->old_data ? json_decode($auditLog->old_data, true) : null;
        $auditLog->new_data = $auditLog->new_data ? json_decode($auditLog->new_data, true) : null;
        
        // dd(
        //     $auditLog->old_data,
        //     $auditLog->new_data
        // );

        // Get related records (previous and next audit for same record)
        $relatedLogs = DB::table('audit_logs')
            ->where('table_name', $auditLog->table_name)
            ->where('record_id', $auditLog->record_id)
            ->where('audit_id', '!=', $auditId)
            ->select(
                'audit_id',
                'action_type',
                'action_timestamp'
            )
            ->orderByDesc('action_timestamp')
            ->limit(5)
            ->get();

        return view('admin.logs.audit-detail', compact('auditLog', 'relatedLogs'));
    }
    
    /**
     * Clear old audit logs (optimized bulk delete)
     */
    public function clear(Request $request)
    {
        $validated = $request->validate([
            'days' => ['required', 'integer', 'min:1', 'max:365'],
        ]);
        
        $cutoffDate = now()->subDays($validated['days']);
        
        // Use direct SQL for better performance
        $deletedCount = DB::delete(
            'DELETE FROM audit_logs WHERE action_timestamp < ?',
            [$cutoffDate]
        );
        
        return back()->with('success', "Successfully deleted {$deletedCount} audit logs older than {$validated['days']} days");
    }
    
    /**
     * Get audit trail for specific record
     */
    // public function trail(Request $request)
    // {
    //     $validated = $request->validate([
    //         'table_name' => ['required', 'string'],
    //         'record_id' => ['required', 'integer'],
    //     ]);
        
    //     $trail = DB::table('audit_logs')
    //         ->join('users', 'audit_logs.user_id', '=', 'users.user_id')
    //         ->where('audit_logs.table_name', $validated['table_name'])
    //         ->where('audit_logs.record_id', $validated['record_id'])
    //         ->select(
    //             'audit_logs.audit_id',
    //             'audit_logs.action_type',
    //             'audit_logs.action_timestamp',
    //             'audit_logs.old_data',
    //             'audit_logs.new_data',
    //             'users.username',
    //             'users.full_name'
    //         )
    //         ->orderByDesc('audit_logs.action_timestamp')
    //         ->get();
        
    //     return response()->json([
    //         'success' => true,
    //         'trail' => $trail,
    //     ]);
    // }
    
    /**
     * Export audit logs to CSV
     */
    public function export(Request $request)
    {
        $query = DB::table('audit_logs')
            ->join('users', 'audit_logs.user_id', '=', 'users.user_id')
            ->select(
                'audit_logs.action_timestamp',
                'users.username',
                'audit_logs.action_type',
                'audit_logs.module_name',
                'audit_logs.table_name',
                'audit_logs.record_id',
                'audit_logs.ip_address'
            );
        
        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('audit_logs.user_id', $request->user_id);
        }
        if ($request->filled('action_type')) {
            $query->where('audit_logs.action_type', $request->action_type);
        }
        if ($request->filled('module_name')) {
            $query->where('audit_logs.module_name', $request->module_name);
        }
        if ($request->filled('table_name')) {
            $query->where('audit_logs.table_name', $request->table_name);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('audit_logs.action_timestamp', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('audit_logs.action_timestamp', '<=', $request->date_to);
        }
        
        $logs = $query->orderByDesc('audit_logs.action_timestamp')
            ->limit(10000)
            ->get();
        
        $filename = 'audit_logs_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['Timestamp', 'User', 'Action', 'Module', 'Table', 'Record ID', 'IP Address']);
            
            // Data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->action_timestamp,
                    $log->username,
                    $log->action_type,
                    $log->module_name,
                    $log->table_name,
                    $log->record_id,
                    $log->ip_address,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Get statistics for dashboard
     */
    public function statistics(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));
        
        // Actions by type
        $actionsByType = DB::table('audit_logs')
            ->whereBetween('action_timestamp', [$dateFrom, $dateTo])
            ->select(
                'action_type',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('action_type')
            ->get();
        
        // Actions by user (top 10)
        $actionsByUser = DB::table('audit_logs')
            ->join('users', 'audit_logs.user_id', '=', 'users.user_id')
            ->whereBetween('audit_logs.action_timestamp', [$dateFrom, $dateTo])
            ->select(
                'users.username',
                'users.full_name',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('users.user_id', 'users.username', 'users.full_name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        
        // Actions by table
        $actionsByTable = DB::table('audit_logs')
            ->whereBetween('action_timestamp', [$dateFrom, $dateTo])
            ->select(
                'table_name',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('table_name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        
        // Actions timeline (daily)
        $timeline = DB::table('audit_logs')
            ->whereBetween('action_timestamp', [$dateFrom, $dateTo])
            ->select(
                DB::raw('DATE(action_timestamp) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy(DB::raw('DATE(action_timestamp)'))
            ->orderBy('date')
            ->get();
        
        return response()->json([
            'success' => true,
            'statistics' => [
                'by_type' => $actionsByType,
                'by_user' => $actionsByUser,
                'by_table' => $actionsByTable,
                'timeline' => $timeline,
            ],
        ]);
    }
}
