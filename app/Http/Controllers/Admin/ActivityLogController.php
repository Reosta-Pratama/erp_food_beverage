<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    //
    /**
     * Display activity logs with optimized filtering
     */
    public function index(Request $request)
    {
        $query = DB::table('activity_logs')
            ->join('users', 'activity_logs.user_id', '=', 'users.user_id')
            ->join('roles', 'users.role_id', '=', 'roles.role_id')
            ->select(
                'activity_logs.activity_id',
                'activity_logs.user_id',
                'activity_logs.activity_type',
                'activity_logs.description',
                'activity_logs.module_name',
                'activity_logs.activity_timestamp',
                'users.username',
                'users.full_name',
                'roles.role_name',
                'roles.role_code'
            );
        
        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('activity_logs.user_id', $request->user_id);
        }
        
        // Filter by activity type
        if ($request->filled('activity_type')) {
            $query->where('activity_logs.activity_type', $request->activity_type);
        }
        
        // Filter by module
        if ($request->filled('module_name')) {
            $query->where('activity_logs.module_name', $request->module_name);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('activity_logs.activity_timestamp', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('activity_logs.activity_timestamp', '<=', $request->date_to);
        }
        
        // Search in description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('activity_logs.description', 'like', "%{$search}%");
        }
        
        $logs = $query->orderByDesc('activity_logs.activity_timestamp')
            ->paginate(50);
        
        // Get filter options with counts (optimized)
        $activityTypes = DB::table('activity_logs')
            ->select('activity_type', DB::raw('COUNT(*) as count'))
            ->groupBy('activity_type')
            ->orderBy('activity_type')
            ->pluck('count', 'activity_type');
        
        $modules = DB::table('activity_logs')
            ->select('module_name', DB::raw('COUNT(*) as count'))
            ->groupBy('module_name')
            ->orderBy('module_name')
            ->pluck('count', 'module_name');
        
        $users = DB::table('users')
            ->join('activity_logs', 'users.user_id', '=', 'activity_logs.user_id')
            ->select(
                'users.user_id',
                'users.username',
                'users.full_name',
                DB::raw('COUNT(DISTINCT activity_logs.activity_id) as log_count')
            )
            ->groupBy('users.user_id', 'users.username', 'users.full_name')
            ->orderBy('users.username')
            ->get();
        
        return view('admin.logs.activity', compact('logs', 'activityTypes', 'modules', 'users'));
    }
    
    /**
     * Clear old activity logs (optimized bulk delete)
     */
    public function clear(Request $request)
    {
        $validated = $request->validate([
            'days' => ['required', 'integer', 'min:1', 'max:365'],
        ]);
        
        $cutoffDate = now()->subDays($validated['days']);

        DB::beginTransaction();
        try {
            // Use direct SQL for better performance on large datasets
            $deletedCount = DB::delete(
                'DELETE FROM activity_logs WHERE activity_timestamp < ?',
                [$cutoffDate]
            );

            DB::commit();
            
            return back()
                ->with('success', "Successfully deleted {$deletedCount} activity logs older than {$validated['days']} days");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete activity: ' . $e->getMessage());
        }
    }
    
    /**
     * Export activity logs to CSV (bonus feature)
     */
    public function export(Request $request)
    {
        $query = DB::table('activity_logs')
            ->join('users', 'activity_logs.user_id', '=', 'users.user_id')
            ->select(
                'activity_logs.activity_timestamp',
                'users.username',
                'activity_logs.activity_type',
                'activity_logs.module_name',
                'activity_logs.description'
            );
        
        // Apply same filters as index
        if ($request->filled('user_id')) {
            $query->where('activity_logs.user_id', $request->user_id);
        }
        if ($request->filled('activity_type')) {
            $query->where('activity_logs.activity_type', $request->activity_type);
        }
        if ($request->filled('module_name')) {
            $query->where('activity_logs.module_name', $request->module_name);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('activity_logs.activity_timestamp', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('activity_logs.activity_timestamp', '<=', $request->date_to);
        }
        
        $logs = $query->orderByDesc('activity_logs.activity_timestamp')
            ->limit(10000) // Limit export to prevent memory issues
            ->get();
        
        $filename = 'activity_logs_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['Timestamp', 'User', 'Type', 'Module', 'Description']);
            
            // Data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->activity_timestamp,
                    $log->username,
                    $log->activity_type,
                    $log->module_name,
                    $log->description,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
