<?php
/**
 * Enrollment.php — Enrollment Model
 */

require_once ROOT_PATH . '/app/core/Model.php';

class Enrollment extends Model
{
    protected string $table = 'enrollments';

    /**
     * Get a student's enrolled courses for the current session.
     */
    public function getStudentCourses(int $studentId, int $tenantId): array
    {
        return $this->db->fetchAll("
            SELECT e.*, c.code, c.title, c.credit_units, c.level, c.semester,
                   c.lecturer_id, u.name as lecturer_name, d.name as department_name
            FROM {$this->table} e
            JOIN courses c ON e.course_id = c.id
            LEFT JOIN users u ON c.lecturer_id = u.id
            LEFT JOIN departments d ON c.department_id = d.id
            WHERE e.student_id = :sid AND e.tenant_id = :tid
            ORDER BY c.level ASC, c.code ASC
        ", [':sid' => $studentId, ':tid' => $tenantId]);
    }

    /**
     * Check if a student is enrolled in a course.
     */
    public function isEnrolled(int $studentId, int $courseId): bool
    {
        return (bool) $this->db->fetchOne("
            SELECT id FROM {$this->table}
            WHERE student_id = :sid AND course_id = :cid
        ", [':sid' => $studentId, ':cid' => $courseId]);
    }

    /**
     * Enroll a student in a course.
     */
    public function enroll(int $studentId, int $courseId, int $tenantId): bool
    {
        if ($this->isEnrolled($studentId, $courseId)) return false;

        $this->create([
            'tenant_id'   => $tenantId,
            'student_id'  => $studentId,
            'course_id'   => $courseId,
            'status'      => 'active',
            'enrolled_at' => date('Y-m-d H:i:s')
        ]);
        return true;
    }

    /**
     * Drop a course enrollment.
     */
    public function drop(int $studentId, int $courseId, int $tenantId): void
    {
        $this->db->query("
            DELETE FROM {$this->table}
            WHERE student_id = :sid AND course_id = :cid AND tenant_id = :tid
        ", [':sid' => $studentId, ':cid' => $courseId, ':tid' => $tenantId]);
    }

    /**
     * Get students enrolled in a specific course.
     */
    public function getCourseStudents(int $courseId, int $tenantId): array
    {
        return $this->db->fetchAll("
            SELECT e.*, u.name as student_name, u.matric_number, u.email
            FROM {$this->table} e
            JOIN users u ON e.student_id = u.id
            WHERE e.course_id = :cid AND e.tenant_id = :tid
            ORDER BY u.name ASC
        ", [':cid' => $courseId, ':tid' => $tenantId]);
    }
}
