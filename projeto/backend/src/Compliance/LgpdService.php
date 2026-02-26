<?php

namespace App\Compliance;

class LgpdService
{
    private $db;

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    public function anonymizeInactiveUsers(int $months = 12): int
    {
        $date = date('Y-m-d H:i:s', strtotime("-$months months"));
        $sql = "UPDATE users SET name = 'Anonimizado', email = CONCAT('anon_', id, '@deleted.com'), document = '00000000000' WHERE last_login < ? AND is_anonymized = FALSE";
        // Execute SQL...
        return 0; // returns affected rows
    }

    public function requestRightToOblivion(int $userId): void
    {
        // Logical deletion in 30 days
        $sqlLogical = "UPDATE users SET deleted_at = NOW() + INTERVAL '30 days' WHERE id = ?";
        // Schedule event for physical deletion in 180 days
        $sqlPhysical = "INSERT INTO deletion_queue (user_id, execute_at) VALUES (?, NOW() + INTERVAL '180 days')";
    // Execute queries...
    }

    public function exportData(int $userId, string $format = 'JSON'): string
    {
        // Fetch user data...
        $data = ['id' => $userId, 'name' => 'Data', 'consent' => true];
        if ($format === 'CSV') {
            return "id,name,consent\n{$userId},Data,true";
        }
        return json_encode($data);
    }
}
