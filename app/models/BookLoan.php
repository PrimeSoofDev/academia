<?php
/**
 * BookLoan.php — Library Model
 */

require_once ROOT_PATH . '/app/core/Model.php';

class BookLoan extends Model
{
    protected string $table = 'book_loans';

    /**
     * Get recent or active loans with book and user details.
     */
    public function getLoans(int $tenantId, string $filter = 'all', int $limit = 50): array
    {
        $sql = "SELECT l.*, b.title as book_title, b.isbn, u.name as user_name, u.role as user_role, u.matric_number, u.staff_id
                FROM {$this->table} l
                JOIN books b ON l.book_id = b.id
                JOIN users u ON l.user_id = u.id
                WHERE l.tenant_id = :tenant_id ";
        
        $bindings = [':tenant_id' => $tenantId];

        if ($filter === 'active') {
            $sql .= "AND l.status = 'active' ";
        } elseif ($filter === 'overdue') {
            $sql .= "AND l.status = 'overdue' ";
        }

        $sql .= "ORDER BY l.issued_at DESC LIMIT :limit";
        // Need to cast limit properly because PDO binds everything as string by default unless specified, but our DB class wraps it simply.
        // It's safer to interpolate limit for simplistic wrappers, or rely on strict modes.
        // Let's manually inject limit since it's an integer we control.
        $sql = str_replace(':limit', $limit, $sql);

        return $this->db->fetchAll($sql, $bindings);
    }

    /**
     * Count active loans.
     */
    public function countActive(int $tenantId): int
    {
        $result = $this->db->fetchOne(
            "SELECT COUNT(*) as cnt FROM {$this->table} WHERE tenant_id = :tenant_id AND status = 'active'",
            [':tenant_id' => $tenantId]
        );
        return (int)($result['cnt'] ?? 0);
    }

    /**
     * Count overdue loans.
     */
    public function countOverdue(int $tenantId): int
    {
        $result = $this->db->fetchOne(
            "SELECT COUNT(*) as cnt FROM {$this->table} WHERE tenant_id = :tenant_id AND status = 'overdue'",
            [':tenant_id' => $tenantId]
        );
        return (int)($result['cnt'] ?? 0);
    }
}
