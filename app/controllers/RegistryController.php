<?php
/**
 * RegistryController.php
 * Handles student admissions, staff records, and academic sessions.
 */

require_once ROOT_PATH . '/app/core/Controller.php';
require_once ROOT_PATH . '/app/models/User.php';
require_once ROOT_PATH . '/app/models/AcademicSession.php';

class RegistryController extends Controller
{
    private User $userModel;
    private AcademicSession $sessionModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->sessionModel = new AcademicSession();
    }

    /**
     * GET /registry
     * Registry Dashboard Overview
     */
    public function index(): void
    {
        $tenantId = Auth::tenantId();
        $currentSession = $this->sessionModel->getCurrentSession();
        $roleCounts = $this->userModel->countByRole($tenantId);
        
        $stats = [
            'total_students' => $roleCounts['student'] ?? 0,
            'total_staff'    => $roleCounts['staff'] ?? 0,
            'total_lecturers'=> $roleCounts['lecturer'] ?? 0,
            'recent_users'   => $this->userModel->raw(
                "SELECT * FROM users WHERE tenant_id = :tenant_id ORDER BY created_at DESC LIMIT 10",
                [':tenant_id' => $tenantId]
            ),
            'current_session'=> $currentSession ? $currentSession['name'] : 'Not Set'
        ];

        $this->view('registry.index', [
            'stats' => $stats
        ]);
    }

    /**
     * GET /registry/students
     * List all students
     */
    public function students(): void
    {
        // For now, fetch all. In a real app, use pagination.
        $students = $this->userModel->getByRole('student', Auth::tenantId());
        
        $this->view('registry.students', [
            'students' => $students
        ]);
    }

    /**
     * GET /registry/staff
     * List all staff and lecturers
     */
    public function staff(): void
    {
        $tenantId = Auth::tenantId();

        $staff = $this->userModel->raw("
            SELECT u.*, d.name as department_name, u2.name as unit_name
            FROM users u
            LEFT JOIN departments d ON u.department_id = d.id
            LEFT JOIN units u2 ON u.unit_id = u2.id
            WHERE u.tenant_id = :tenant_id AND u.role IN ('staff', 'lecturer')
            ORDER BY u.name ASC
        ", [':tenant_id' => $tenantId]);

        $this->view('registry.staff', [
            'staff' => $staff
        ]);
    }

    /**
     * GET /registry/sessions
     * Manage academic sessions
     */
    public function sessions(): void
    {
        $tenantId = Auth::tenantId();
        $sessions = $this->sessionModel->raw(
            "SELECT * FROM academic_sessions WHERE tenant_id = :tenant_id ORDER BY start_date DESC",
            [':tenant_id' => $tenantId]
        );
        
        $this->view('registry.sessions', [
            'sessions' => $sessions
        ]);
    }

    /**
     * POST /registry/sessions
     * Create a new session
     */
    public function storeSession(): void
    {
        if (!$this->isPost()) $this->redirect('/registry/sessions');

        $name = $this->post('name');
        $startDate = $this->post('start_date');
        $endDate = $this->post('end_date');

        if ($name && $startDate && $endDate) {
            $this->sessionModel->create([
                'tenant_id'  => Auth::tenantId(),
                'name'       => $name,
                'start_date' => $startDate,
                'end_date'   => $endDate,
                'is_current' => 0
            ]);
            $this->flash('success', "Session {$name} created successfully.");
        }

        $this->redirect('/registry/sessions');
    }

    /**
     * POST /registry/sessions/set-current
     * Set a session as current
     */
    public function setCurrentSession(): void
    {
        if (!$this->isPost()) $this->redirect('/registry/sessions');

        $id = (int) $this->post('id');
        if ($id) {
            $this->sessionModel->setAsCurrent($id);
            $this->flash('success', 'Current academic session updated.');
        }

        $this->redirect('/registry/sessions');
    }
}
