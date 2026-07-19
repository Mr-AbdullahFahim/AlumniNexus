<?php

if (!function_exists('log_activity')) {
    /**
     * Log a user activity to the audit_logs table.
     *
     * @param string $action A description of the action (e.g. 'User Login', 'Profile Updated')
     * @param string|null $tableName The affected table name, if applicable
     * @param int|null $recordId The affected record ID, if applicable
     * @param array|null $oldValues Array of previous values before the change
     * @param array|null $newValues Array of new values after the change
     * @param int|null $userId Provide manually if session isn't available, else grabbed from session/request
     */
    function log_activity(string $action, ?string $tableName = null, ?int $recordId = null, ?array $oldValues = null, ?array $newValues = null, ?int $userId = null)
    {
        $db = \Config\Database::connect();
        
        // Resolve User ID
        if ($userId === null) {
            $session = session();
            if ($session->has('user_id')) {
                $userId = $session->get('user_id');
            } else {
                // If it's an API request, try getting from token if available globally
                $request = \Config\Services::request();
                if (isset($request->user) && isset($request->user->sub)) {
                    $userId = $request->user->sub;
                }
            }
        }

        $request = \Config\Services::request();
        $ipAddress = $request->getIPAddress();
        
        // If testing locally, attempt to get the real public IP
        if ($ipAddress === '::1' || $ipAddress === '127.0.0.1') {
            $context = stream_context_create(['http' => ['timeout' => 2]]);
            $publicIp = @file_get_contents('https://api.ipify.org', false, $context);
            if ($publicIp) {
                $ipAddress = $publicIp;
            }
        }
        
        $userAgent = $request->getUserAgent() ? $request->getUserAgent()->getAgentString() : null;

        $data = [
            'user_id'    => $userId,
            'action'     => $action,
            'table_name' => $tableName,
            'record_id'  => $recordId,
            'old_values' => $oldValues !== null ? json_encode($oldValues) : null,
            'new_values' => $newValues !== null ? json_encode($newValues) : null,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $db->table('audit_logs')->insert($data);
    }
}
