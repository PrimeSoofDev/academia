<?php
/**
 * Book.php — Library Model
 */

require_once ROOT_PATH . '/app/core/Model.php';

class Book extends Model
{
    protected string $table = 'books';

    /**
     * Get basic stats for the library.
     */
    public function getLibraryStats(int $tenantId): array
    {
        $result = $this->db->fetchOne(
            "SELECT COUNT(id) as total_titles, SUM(copies_total) as total_copies, SUM(copies_avail) as available_copies 
             FROM {$this->table} WHERE tenant_id = :tenant_id",
            [':tenant_id' => $tenantId]
        );
        return [
            'total_titles'   => (int)($result['total_titles'] ?? 0),
            'total_copies'   => (int)($result['total_copies'] ?? 0),
            'available_copies'=> (int)($result['available_copies'] ?? 0),
        ];
    }
}
