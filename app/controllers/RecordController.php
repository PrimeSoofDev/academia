<?php
/**
 * RecordController.php
 * Handles student transcripts and certificates.
 */

require_once ROOT_PATH . '/app/core/Controller.php';
require_once ROOT_PATH . '/app/core/Auth.php';
require_once ROOT_PATH . '/app/core/Database.php';

class RecordController extends Controller
{
    /**
     * View Student Transcript
     */
    public function transcript(): void
    {
        Auth::requireLogin();
        $userId = Auth::id();
        $tenantId = Auth::tenantId();
        $user = Auth::user();

        // Fetch all results for this student
        $results = Database::getInstance()->fetchAll("
            SELECT r.*, c.code as course_code, c.title as course_title, c.credit_units, s.name as session_name
            FROM results r
            JOIN courses c ON r.course_id = c.id
            LEFT JOIN academic_sessions s ON r.session_id = s.id
            WHERE r.student_id = :student_id AND r.tenant_id = :tenant_id AND r.published = 1
            ORDER BY s.start_date ASC, c.level ASC, c.semester ASC
        ", [':student_id' => $userId, ':tenant_id' => $tenantId]);

        $this->view('records/transcript', [
            'results' => $results,
            'user' => $user
        ]);
    }

    /**
     * View Graduation Certificate
     */
    public function certificate(): void
    {
        Auth::requireLogin();
        $user = Auth::user();
        $tenantId = Auth::tenantId();

        // Check if student is graduated
        if ($user['role'] !== 'student' || $user['graduation_status'] !== 'graduated') {
            $this->flash('error', 'You are not eligible for a certificate yet.');
            $this->redirect('/dashboard');
        }

        // Fetch Registrar and VC signatures/stamps
        $university = Database::getInstance()->fetchAll("SELECT * FROM tenants WHERE id = :id", [':id' => $tenantId])[0] ?? [];
        
        // Find VC and Registrar
        $officials = Database::getInstance()->fetchAll("
            SELECT name, role, signature_path, stamp_path 
            FROM users 
            WHERE tenant_id = :tenant_id AND (role = 'vc' OR unit_id IN (SELECT id FROM units WHERE name = 'Registry'))
        ", [':tenant_id' => $tenantId]);

        $this->view('records/certificate', [
            'user' => $user,
            'university' => $university,
            'officials' => $officials
        ]);
    }

    // Removed incorrect Model instantiation helper
}
