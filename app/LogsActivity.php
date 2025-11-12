<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    //
    /**
     * Log activity (simple user actions)
     * 
     * @param string $activityType - Type of activity (e.g., 'Login', 'View Report', 'Export Data')
     * @param string $description - Human-readable description
     * @param string $moduleName - Module name (e.g., 'User Management', 'Inventory')
     * @return void
     */
    protected function logActivity(string $activityType, string $description, string $moduleName): void
    {
        if (!Auth::check()) {
            return;
        }

        DB::table('activity_logs')->insert([
            'user_id' => Auth::id(),
            'activity_type' => $activityType,
            'description' => $description,
            'module_name' => $moduleName,
            'activity_timestamp' => now(),
        ]);
    }

    /**
     * Log audit (data changes with old/new values)
     * 
     * @param string $actionType - CREATE, UPDATE, DELETE, LOGIN, LOGOUT
     * @param string $moduleName - Module name (e.g., 'User Management')
     * @param string $tableName - Database table name
     * @param int|null $recordId - Record ID that was affected
     * @param array|null $oldData - Old data before change (for UPDATE/DELETE)
     * @param array|null $newData - New data after change (for CREATE/UPDATE)
     * @return void
     */
    protected function logAudit(
        string $actionType,
        string $moduleName,
        string $tableName,
        ?int $recordId = null,
        ?array $oldData = null,
        ?array $newData = null
    ): void {
        if (!Auth::check()) {
            return;
        }

        // Remove sensitive fields
        $oldData = $this->sanitizeData($oldData);
        $newData = $this->sanitizeData($newData);

        DB::table('audit_logs')->insert([
            'user_id' => Auth::id(),
            'action_type' => $actionType,
            'module_name' => $moduleName,
            'table_name' => $tableName,
            'record_id' => $recordId,
            'old_data' => $oldData ? json_encode($oldData) : null,
            'new_data' => $newData ? json_encode($newData) : null,
            'ip_address' => Request::ip(),
            'action_timestamp' => now(),
        ]);
    }

    /**
     * Remove sensitive fields from data
     * 
     * @param array|null $data
     * @return array|null
     */
    private function sanitizeData(?array $data): ?array
    {
        if (!$data) {
            return null;
        }

        $sensitiveFields = [
            'password',
            'password_hash',
            'remember_token',
            'api_token',
            'token',
            'secret',
            'credit_card',
            'cvv',
            'pin',
        ];

        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '***REDACTED***';
            }
        }

        return $data;
    }

    /**
     * Log CREATE action
     */
    protected function logCreate(string $moduleName, string $tableName, int $recordId, array $newData): void
    {
        $this->logAudit('CREATE', $moduleName, $tableName, $recordId, null, $newData);
        $this->logActivity('Created', "Created new record in {$tableName} (ID: {$recordId})", $moduleName);
    }

    /**
     * Log UPDATE action
     */
    protected function logUpdate(string $moduleName, string $tableName, int $recordId, array $oldData, array $newData): void
    {
        $this->logAudit('UPDATE', $moduleName, $tableName, $recordId, $oldData, $newData);
        $this->logActivity('Updated', "Updated record in {$tableName} (ID: {$recordId})", $moduleName);
    }

    /**
     * Log DELETE action
     */
    protected function logDelete(string $moduleName, string $tableName, int $recordId, array $oldData): void
    {
        $this->logAudit('DELETE', $moduleName, $tableName, $recordId, $oldData, null);
        $this->logActivity('Deleted', "Deleted record from {$tableName} (ID: {$recordId})", $moduleName);
    }

    /**
     * Log LOGIN action
     */
    protected function logLogin(): void
    {
        $this->logAudit('LOGIN', 'Authentication', 'users', Auth::id(), null, [
            'login_time' => now()->toDateTimeString(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
        $this->logActivity('Login', 'User logged in successfully', 'Authentication');
    }

    /**
     * Log LOGOUT action
     */
    protected function logLogout(): void
    {
        $this->logAudit('LOGOUT', 'Authentication', 'users', Auth::id(), null, [
            'logout_time' => now()->toDateTimeString(),
            'ip_address' => Request::ip(),
        ]);
        $this->logActivity('Logout', 'User logged out', 'Authentication');
    }

    /**
     * Log VIEW action (for sensitive data access)
     */
    protected function logView(string $moduleName, string $description): void
    {
        $this->logActivity('Viewed', $description, $moduleName);
    }

    /**
     * Log EXPORT action
     */
    protected function logExport(string $moduleName, string $description): void
    {
        $this->logActivity('Exported', $description, $moduleName);
    }

    /**
     * Log IMPORT action
     */
    protected function logImport(string $moduleName, string $description, int $recordsCount = 0): void
    {
        $this->logActivity('Imported', "{$description} ({$recordsCount} records)", $moduleName);
    }

    /**
     * Log PRINT action
     */
    protected function logPrint(string $moduleName, string $description): void
    {
        $this->logActivity('Printed', $description, $moduleName);
    }

    /**
     * Log APPROVE/REJECT action (for workflow)
     */
    protected function logApproval(string $moduleName, string $tableName, int $recordId, string $action, ?string $notes = null): void
    {
        $description = ucfirst($action) . " record in {$tableName} (ID: {$recordId})";
        if ($notes) {
            $description .= " - Notes: {$notes}";
        }

        $this->logAudit(strtoupper($action), $moduleName, $tableName, $recordId, null, [
            'action' => $action,
            'notes' => $notes,
            'timestamp' => now()->toDateTimeString(),
        ]);
        $this->logActivity(ucfirst($action), $description, $moduleName);
    }
}
