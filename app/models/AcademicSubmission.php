<?php
/**
 * AcademicSubmission.php
 * Model for handling exam questions, CA, and final results submissions.
 */

require_once ROOT_PATH . '/app/core/Model.php';

class AcademicSubmission extends Model
{
    protected string $table = 'academic_submissions';

    /**
     * Get submissions for a tenant with details.
     */
    public function getDetailed(int $tenantId, array $filters = []): array
    {
        $sql = "SELECT s.*, c.code as course_code, c.title as course_title, 
                       u.name as lecturer_name, h.name as hod_name, d.name as dean_name
                FROM academic_submissions s
                JOIN courses c ON s.course_id = c.id
                JOIN users u ON s.lecturer_id = u.id
                LEFT JOIN users h ON s.hod_id = h.id
                LEFT JOIN users d ON s.dean_id = d.id
                WHERE s.tenant_id = :tenant_id";
        
        $params = [':tenant_id' => $tenantId];

        if (!empty($filters['lecturer_id'])) {
            $sql .= " AND s.lecturer_id = :lecturer_id";
            $params[':lecturer_id'] = $filters['lecturer_id'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND s.status = :status";
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['department_id'])) {
            $sql .= " AND c.department_id = :dept_id";
            $params[':dept_id'] = $filters['department_id'];
        }

        $sql .= " ORDER BY s.created_at DESC";

        return $this->raw($sql, $params);
    }
}
