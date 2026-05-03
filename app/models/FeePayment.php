<?php
/**
 * FeePayment.php — Bursary Model
 *
 * Manages all student fee payments, invoices, and revenue tracking.
 */

require_once ROOT_PATH . '/app/core/Model.php';

class FeePayment extends Model
{
    protected string $table = 'fee_payments';

    /**
     * Get total revenue for the given tenant.
     * Optionally scope by a specific status.
     */
    public function getTotalRevenue(int $tenantId, string $status = 'paid'): float
    {
        $result = $this->db->fetchOne(
            "SELECT SUM(amount) as total FROM {$this->table} WHERE tenant_id = :tenant_id AND status = :status",
            [':tenant_id' => $tenantId, ':status' => $status]
        );
        return (float) ($result['total'] ?? 0);
    }

    /**
     * Get total number of transactions by status.
     */
    public function getTransactionCount(int $tenantId, string $status = 'paid'): int
    {
        $result = $this->db->fetchOne(
            "SELECT COUNT(*) as count FROM {$this->table} WHERE tenant_id = :tenant_id AND status = :status",
            [':tenant_id' => $tenantId, ':status' => $status]
        );
        return (int) ($result['count'] ?? 0);
    }

    /**
     * Get recent payments with student details.
     */
    public function getRecentPayments(int $tenantId, int $limit = 10): array
    {
        return $this->db->fetchAll(
            "SELECT p.*, u.name as student_name, u.matric_number, s.name as session_name
             FROM {$this->table} p
             LEFT JOIN users u ON p.student_id = u.id
             LEFT JOIN academic_sessions s ON p.session_id = s.id
             WHERE p.tenant_id = :tenant_id
             ORDER BY p.created_at DESC
             LIMIT :limit",
            [':tenant_id' => $tenantId, ':limit' => $limit]
        );
    }
    
    /**
     * Get all payments with student details.
     */
    public function getAllPayments(int $tenantId): array
    {
        return $this->db->fetchAll(
            "SELECT p.*, u.name as student_name, u.matric_number, s.name as session_name
             FROM {$this->table} p
             LEFT JOIN users u ON p.student_id = u.id
             LEFT JOIN academic_sessions s ON p.session_id = s.id
             WHERE p.tenant_id = :tenant_id
             ORDER BY p.created_at DESC",
            [':tenant_id' => $tenantId]
        );
    }
}
