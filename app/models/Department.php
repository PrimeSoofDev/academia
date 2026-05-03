<?php
/**
 * Department.php — Department Model
 *
 * Represents an academic department within a Faculty.
 * Each department belongs to a Faculty and a Tenant.
 */

require_once ROOT_PATH . '/app/core/Model.php';

class Department extends Model
{
    protected string $table = 'departments';

    /**
     * Get all departments, joined with faculty and HOD info.
     */
    public function allWithDetails(int $tenantId): array
    {
        return $this->db->fetchAll(
            'SELECT d.*,
                    f.name as faculty_name,
                    u.name as hod_name,
                    COUNT(DISTINCT c.id) as course_count,
                    COUNT(DISTINCT usr.id) as student_count
             FROM departments d
             LEFT JOIN faculties f ON d.faculty_id = f.id
             LEFT JOIN users u ON d.hod_id = u.id
             LEFT JOIN courses c ON c.department_id = d.id
             LEFT JOIN users usr ON usr.department_id = d.id AND usr.role = "student"
             WHERE d.tenant_id = :tenant_id
             GROUP BY d.id
             ORDER BY f.name, d.name ASC',
            [':tenant_id' => $tenantId]
        );
    }

    /**
     * Get all departments within a specific faculty.
     */
    public function getByFaculty(int $facultyId, int $tenantId): array
    {
        return $this->db->fetchAll(
            'SELECT d.*, u.name as hod_name FROM departments d
             LEFT JOIN users u ON d.hod_id = u.id
             WHERE d.faculty_id = :faculty_id AND d.tenant_id = :tenant_id
             ORDER BY d.name ASC',
            [':faculty_id' => $facultyId, ':tenant_id' => $tenantId]
        );
    }

    /**
     * Get courses in this department.
     */
    public function getCourses(int $departmentId, int $tenantId): array
    {
        return $this->db->fetchAll(
            'SELECT c.*, u.name as lecturer_name FROM courses c
             LEFT JOIN users u ON c.lecturer_id = u.id
             WHERE c.department_id = :dept_id AND c.tenant_id = :tenant_id
             ORDER BY c.code ASC',
            [':dept_id' => $departmentId, ':tenant_id' => $tenantId]
        );
    }
}
