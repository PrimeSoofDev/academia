<?php
/**
 * Result.php — Results Model
 *
 * Handles academic result records (CA + Exam scores → Grade).
 */

require_once ROOT_PATH . '/app/core/Model.php';

class Result extends Model
{
    protected string $table = 'results';

    /**
     * Grade calculation map (Nigerian 5-point GPA system)
     */
    public static array $gradeMap = [
        ['min' => 70, 'grade' => 'A',  'point' => 5.0],
        ['min' => 60, 'grade' => 'B',  'point' => 4.0],
        ['min' => 50, 'grade' => 'C',  'point' => 3.0],
        ['min' => 45, 'grade' => 'D',  'point' => 2.0],
        ['min' => 40, 'grade' => 'E',  'point' => 1.0],
        ['min' =>  0, 'grade' => 'F',  'point' => 0.0],
    ];

    /**
     * Compute grade & grade point from total score.
     */
    public static function computeGrade(float $total): array
    {
        foreach (self::$gradeMap as $g) {
            if ($total >= $g['min']) {
                return ['grade' => $g['grade'], 'point' => $g['point']];
            }
        }
        return ['grade' => 'F', 'point' => 0.0];
    }

    /**
     * Get results for a specific student, with course details.
     */
    public function getStudentResults(int $studentId, int $tenantId, ?int $sessionId = null): array
    {
        $sql = "SELECT r.*, c.code as course_code, c.title as course_title, c.credit_units,
                       d.name as department_name, s.name as session_name
                FROM {$this->table} r
                JOIN courses c ON r.course_id = c.id
                LEFT JOIN departments d ON c.department_id = d.id
                LEFT JOIN academic_sessions s ON r.session_id = s.id
                WHERE r.student_id = :student_id AND r.tenant_id = :tenant_id";

        $bindings = [':student_id' => $studentId, ':tenant_id' => $tenantId];

        if ($sessionId) {
            $sql .= " AND r.session_id = :session_id";
            $bindings[':session_id'] = $sessionId;
        }

        $sql .= " AND r.published = 1 ORDER BY s.name DESC, c.code ASC";

        return $this->db->fetchAll($sql, $bindings);
    }

    /**
     * Compute a student's GPA for a given session.
     */
    public function computeGPA(int $studentId, int $tenantId, int $sessionId): float
    {
        $results = $this->db->fetchAll("
            SELECT r.grade_point, c.credit_units
            FROM {$this->table} r
            JOIN courses c ON r.course_id = c.id
            WHERE r.student_id = :sid AND r.tenant_id = :tid AND r.session_id = :sess AND r.published = 1
        ", [':sid' => $studentId, ':tid' => $tenantId, ':sess' => $sessionId]);

        if (empty($results)) return 0.0;

        $totalPoints = 0.0;
        $totalUnits  = 0;
        foreach ($results as $r) {
            $totalPoints += $r['grade_point'] * $r['credit_units'];
            $totalUnits  += $r['credit_units'];
        }

        return $totalUnits > 0 ? round($totalPoints / $totalUnits, 2) : 0.0;
    }

    /**
     * Get all results for a course (for lecturer/admin).
     */
    public function getCourseResults(int $courseId, int $tenantId, ?int $sessionId = null): array
    {
        $sql = "SELECT r.*, u.name as student_name, u.matric_number, u.email
                FROM {$this->table} r
                JOIN users u ON r.student_id = u.id
                WHERE r.course_id = :course_id AND r.tenant_id = :tenant_id";

        $bindings = [':course_id' => $courseId, ':tenant_id' => $tenantId];

        if ($sessionId) {
            $sql .= " AND r.session_id = :session_id";
            $bindings[':session_id'] = $sessionId;
        }

        $sql .= " ORDER BY u.name ASC";

        return $this->db->fetchAll($sql, $bindings);
    }

    /**
     * Upsert (insert or update) a result record.
     */
    public function upsert(array $data): void
    {
        $existing = $this->db->fetchOne("
            SELECT id FROM {$this->table}
            WHERE student_id = :sid AND course_id = :cid AND session_id = :sess
        ", [':sid' => $data['student_id'], ':cid' => $data['course_id'], ':sess' => $data['session_id']]);

        if ($existing) {
            $this->update($existing['id'], $data, $data['tenant_id']);
        } else {
            $this->create($data);
        }
    }

    /**
     * Publish all results for a given course + session.
     */
    public function publishForCourse(int $courseId, int $sessionId, int $tenantId): void
    {
        $this->db->query("
            UPDATE {$this->table}
            SET published = 1
            WHERE course_id = :cid AND session_id = :sess AND tenant_id = :tid
        ", [':cid' => $courseId, ':sess' => $sessionId, ':tid' => $tenantId]);
    }
}
