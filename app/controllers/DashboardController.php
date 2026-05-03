<?php
/**
 * DashboardController.php — Role-Based Dashboard
 *
 * Loads the appropriate dashboard view based on the
 * authenticated user's role and tenant context.
 */

require_once ROOT_PATH . '/app/core/Controller.php';
require_once ROOT_PATH . '/app/core/Auth.php';
require_once ROOT_PATH . '/app/models/User.php';
require_once ROOT_PATH . '/app/models/Faculty.php';
require_once ROOT_PATH . '/app/models/Department.php';
require_once ROOT_PATH . '/app/models/Course.php';

class DashboardController extends Controller
{
    private User       $userModel;
    private Faculty    $facultyModel;
    private Department $departmentModel;
    private Course     $courseModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel       = new User();
        $this->facultyModel    = new Faculty();
        $this->departmentModel = new Department();
        $this->courseModel     = new Course();
    }

    // ─────────────────────────────────────────────
    // MAIN DASHBOARD
    // ─────────────────────────────────────────────

    /**
     * GET /dashboard
     * Dispatch to the correct dashboard based on user role.
     */
    public function index(): void
    {
        Auth::requireLogin();

        $role     = Auth::role();
        $tenantId = Auth::tenantId();
        $userId   = Auth::id();

        // Common stats available to all roles
        $stats = $this->buildStats($tenantId, $userId, $role);

        $this->view('dashboard.index', [
            'stats'    => $stats,
            'user'     => Auth::user(),
            'role'     => $role,
            'tenantId' => $tenantId,
        ]);
    }

    // ─────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────

    /**
     * Build dashboard stats based on the user's role.
     * Higher-level roles see more data.
     */
    private function buildStats(int $tenantId, int $userId, string $role): array
    {
        $stats = [];

        switch ($role) {
            case 'superadmin':
            case 'vc':
                // University-wide overview
                $roleCounts             = $this->userModel->countByRole($tenantId);
                $stats['total_students']   = $roleCounts['student']  ?? 0;
                $stats['total_lecturers']  = $roleCounts['lecturer'] ?? 0;
                $stats['total_deans']      = $roleCounts['dean']     ?? 0;
                $stats['total_hods']       = $roleCounts['hod']      ?? 0;
                $stats['total_staff']      = $roleCounts['staff']    ?? 0;
                $stats['total_faculties']  = $this->facultyModel->count($tenantId);
                $stats['total_departments']= $this->departmentModel->count($tenantId);
                $stats['total_courses']    = $this->courseModel->count($tenantId);
                $stats['faculties']        = $this->facultyModel->allWithStats($tenantId);
                break;

            case 'dean':
                // Show faculties/departments this dean manages
                $user = $this->userModel->find($userId, $tenantId);
                $facultyId = $user['faculty_id'] ?? null;
                if ($facultyId) {
                    $faculty = $this->facultyModel->findWithDepartments($facultyId, $tenantId);
                    $stats['faculty']     = $faculty;
                    $stats['departments'] = $faculty['departments'] ?? [];
                }
                $stats['total_departments'] = count($stats['departments'] ?? []);
                $roleCounts = $this->userModel->countByRole($tenantId);
                $stats['total_students']  = $roleCounts['student']  ?? 0;
                $stats['total_lecturers'] = $roleCounts['lecturer'] ?? 0;
                break;

            case 'hod':
                // Show department info
                $user = $this->userModel->find($userId, $tenantId);
                $deptId = $user['department_id'] ?? null;
                if ($deptId) {
                    $stats['department'] = $this->departmentModel->find($deptId, $tenantId);
                    $stats['courses']    = $this->departmentModel->getCourses($deptId, $tenantId);
                    $stats['students']   = $this->userModel->getStudentsByDepartment($deptId, $tenantId);
                }
                $stats['total_courses']  = count($stats['courses']  ?? []);
                $stats['total_students'] = count($stats['students'] ?? []);
                break;

            case 'lecturer':
                // Show courses taught by this lecturer
                $stats['courses']       = $this->courseModel->getByLecturer($userId, $tenantId);
                $stats['total_courses'] = count($stats['courses']);
                $stats['total_students'] = array_sum(array_column($stats['courses'], 'enrolled_count'));
                break;

            case 'student':
                // Show enrolled courses and basic profile
                $stats['enrolled_courses'] = $this->courseModel->getEnrolledCourses($userId, $tenantId);
                $stats['total_courses']    = count($stats['enrolled_courses']);
                break;

            case 'staff':
                // Administrative staff: basic overview
                $user = $this->userModel->find($userId, $tenantId);
                $stats['unit_id'] = $user['unit_id'] ?? null;
                break;
        }

        return $stats;
    }
}
