<?php
/**
 * Course.php — Course Model
 *
 * Represents an academic course offered by a department.
 * Linked to a Lecturer (user) and supports enrollment.
 */

require_once ROOT_PATH . '/app/core/Model.php';

class Course extends Model
{
    protected string $table = 'courses';

    /**
     * Get all courses with department, faculty, and lecturer details.
     */
    public function allWithDetails(int $tenantId): array
    {
        return $this->db->fetchAll(
            'SELECT c.*,
                    d.name as department_name,
                    f.name as faculty_name,
                    u.name as lecturer_name,
                    COUNT(e.id) as enrolled_count
             FROM courses c
             LEFT JOIN departments d ON c.department_id = d.id
             LEFT JOIN faculties f ON d.faculty_id = f.id
             LEFT JOIN users u ON c.lecturer_id = u.id
             LEFT JOIN enrollments e ON e.course_id = c.id
             WHERE c.tenant_id = :tenant_id
             GROUP BY c.id
             ORDER BY c.code ASC',
            [':tenant_id' => $tenantId]
        );
    }

    /**
     * Get courses assigned to a specific lecturer.
     */
    public function getByLecturer(int $lecturerId, int $tenantId): array
    {
        return $this->db->fetchAll(
            'SELECT c.*, d.name as department_name, COUNT(e.id) as enrolled_count
             FROM courses c
             LEFT JOIN departments d ON c.department_id = d.id
             LEFT JOIN enrollments e ON e.course_id = c.id
             WHERE c.lecturer_id = :lecturer_id AND c.tenant_id = :tenant_id
             GROUP BY c.id
             ORDER BY c.code ASC',
            [':lecturer_id' => $lecturerId, ':tenant_id' => $tenantId]
        );
    }

    /**
     * Get courses a student is enrolled in.
     */
    public function getEnrolledCourses(int $studentId, int $tenantId): array
    {
        return $this->db->fetchAll(
            'SELECT c.*, d.name as department_name, u.name as lecturer_name, e.grade
             FROM enrollments e
             JOIN courses c ON e.course_id = c.id
             LEFT JOIN departments d ON c.department_id = d.id
             LEFT JOIN users u ON c.lecturer_id = u.id
             WHERE e.student_id = :student_id AND c.tenant_id = :tenant_id
             ORDER BY c.code ASC',
            [':student_id' => $studentId, ':tenant_id' => $tenantId]
        );
    }

    /**
     * Enroll a student in a course.
     */
    public function enroll(int $studentId, int $courseId, int $tenantId): bool
    {
        // Check if already enrolled
        $existing = $this->db->fetchOne(
            'SELECT id FROM enrollments WHERE student_id = :s AND course_id = :c',
            [':s' => $studentId, ':c' => $courseId]
        );
        if ($existing) return false;

        $this->db->query(
            'INSERT INTO enrollments (student_id, course_id, tenant_id, enrolled_at) VALUES (:s, :c, :t, NOW())',
            [':s' => $studentId, ':c' => $courseId, ':t' => $tenantId]
        );
        return true;
    }
}
