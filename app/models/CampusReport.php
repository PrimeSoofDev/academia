<?php
/**
 * CampusReport.php
 * Model for student incident reporting.
 */

require_once ROOT_PATH . '/app/core/Model.php';

class CampusReport extends Model
{
    protected string $table = 'campus_reports';

    /**
     * Get reports with student information.
     */
    public function getDetailed(int $tenantId, array $filters = []): array
    {
        $sql = "
            SELECT r.*, u.name as student_name, u.profile_image as student_image
            FROM {$this->table} r
            JOIN users u ON r.student_id = u.id
            WHERE r.tenant_id = :tid
        ";
        
        $bindings = [':tid' => $tenantId];

        if (!empty($filters['status'])) {
            $sql .= " AND r.status = :status";
            $bindings[':status'] = $filters['status'];
        }

        if (!empty($filters['student_id'])) {
            $sql .= " AND r.student_id = :sid";
            $bindings[':sid'] = $filters['student_id'];
        }

        $sql .= " ORDER BY r.created_at DESC";

        return $this->raw($sql, $bindings);
    }
}
