<?php
/**
 * Faculty.php — Faculty Model
 *
 * Represents a Faculty (e.g. Faculty of Engineering).
 * Each faculty belongs to a tenant and contains departments.
 */

require_once ROOT_PATH . '/app/core/Model.php';

class Faculty extends Model
{
    protected string $table = 'faculties';

    /**
     * Get all faculties with their department count.
     */
    public function allWithStats(int $tenantId): array
    {
        return $this->db->fetchAll(
            'SELECT f.*,
                    u.name as dean_name,
                    COUNT(DISTINCT d.id) as department_count,
                    COUNT(DISTINCT usr.id) as student_count
             FROM faculties f
             LEFT JOIN users u ON f.dean_id = u.id
             LEFT JOIN departments d ON d.faculty_id = f.id
             LEFT JOIN users usr ON usr.faculty_id = f.id AND usr.role = "student"
             WHERE f.tenant_id = :tenant_id
             GROUP BY f.id
             ORDER BY f.name ASC',
            [':tenant_id' => $tenantId]
        );
    }

    /**
     * Get a single faculty with its departments.
     */
    public function findWithDepartments(int $facultyId, int $tenantId): array|false
    {
        $faculty = $this->find($facultyId, $tenantId);
        if (!$faculty) return false;

        $faculty['departments'] = $this->db->fetchAll(
            'SELECT d.*, u.name as hod_name, COUNT(usr.id) as student_count
             FROM departments d
             LEFT JOIN users u ON d.hod_id = u.id
             LEFT JOIN users usr ON usr.department_id = d.id AND usr.role = "student"
             WHERE d.faculty_id = :faculty_id AND d.tenant_id = :tenant_id
             GROUP BY d.id
             ORDER BY d.name ASC',
            [':faculty_id' => $facultyId, ':tenant_id' => $tenantId]
        );

        return $faculty;
    }

    /**
     * Get the dean of a faculty.
     */
    public function getDean(int $facultyId, int $tenantId): array|false
    {
        return $this->db->fetchOne(
            'SELECT u.* FROM users u
             JOIN faculties f ON f.dean_id = u.id
             WHERE f.id = :faculty_id AND f.tenant_id = :tenant_id',
            [':faculty_id' => $facultyId, ':tenant_id' => $tenantId]
        );
    }
}
