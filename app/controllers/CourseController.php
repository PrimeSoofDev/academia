<?php
/**
 * CourseController.php
 * Handles courses CRUD and enrollments
 */

require_once ROOT_PATH . '/app/core/Controller.php';
require_once ROOT_PATH . '/app/models/Course.php';
require_once ROOT_PATH . '/app/models/Department.php';
require_once ROOT_PATH . '/app/models/User.php';
require_once ROOT_PATH . '/app/models/Enrollment.php';

class CourseController extends Controller
{
    private Course $courseModel;
    private Department $deptModel;
    private User $userModel;
    private Enrollment $enrollModel;

    public function __construct()
    {
        parent::__construct();
        $this->courseModel = new Course();
        $this->deptModel = new Department();
        $this->userModel = new User();
        $this->enrollModel = new Enrollment();
    }

    /**
     * GET /courses
     * List all courses
     */
    public function index(): void
    {
        $tenantId = Auth::tenantId();
        $courses = $this->courseModel->allWithDetails($tenantId);

        $lecturers = [];
        if (in_array(Auth::role(), ['superadmin', 'vc', 'dean', 'hod'])) {
            $lecturers = $this->userModel->getByRole('lecturer', $tenantId);
        }

        $this->view('courses.index', [
            'courses'   => $courses,
            'lecturers' => $lecturers
        ]);
    }

    /**
     * GET /courses/create
     * Show create form
     */
    public function create(): void
    {
        Auth::authorize(['superadmin', 'vc', 'dean', 'hod']);
        
        $tenantId = Auth::tenantId();
        $departments = $this->deptModel->all($tenantId);
        $potentialLecturers = $this->userModel->getByRole('lecturer', $tenantId);
        
        // Pre-select department if passed via query string
        $selectedDeptId = (int) $this->get('department_id');

        $this->view('courses.create', [
            'departments' => $departments,
            'potentialLecturers' => $potentialLecturers,
            'selectedDeptId' => $selectedDeptId
        ]);
    }

    /**
     * POST /courses/create
     * Store new course
     */
    public function store(): void
    {
        Auth::authorize(['superadmin', 'vc', 'dean', 'hod']);
        if (!$this->isPost()) $this->redirect('/courses');

        $title = $this->post('title');
        $code = $this->post('code');
        $departmentId = (int) $this->post('department_id');
        $lecturerId = $this->post('lecturer_id') ? (int) $this->post('lecturer_id') : null;
        $creditUnits = (int) $this->post('credit_units');

        if ($title && $code && $departmentId && $creditUnits > 0) {
            $this->courseModel->create([
                'tenant_id'     => Auth::tenantId(),
                'department_id' => $departmentId,
                'lecturer_id'   => $lecturerId,
                'code'          => strtoupper(str_replace(' ', '', $code)), // e.g. CSC101
                'title'         => $title,
                'description'   => $this->post('description'),
                'credit_units'  => $creditUnits,
                'level'         => $this->post('level', '100'),
                'semester'      => $this->post('semester', 'first'),
                'status'        => 'active'
            ]);
            $this->flash('success', "Course '{$code}' created successfully.");
            $this->redirect("/departments/{$departmentId}");
        } else {
            $this->flash('error', 'Please fill all required fields correctly.');
            $this->redirect('/courses/create');
        }
    }

    /**
     * POST /courses/assign
     * HOD assigns a course to a lecturer
     */
    public function assign(): void
    {
        Auth::authorize(['superadmin', 'vc', 'dean', 'hod']);
        if (!$this->isPost()) $this->redirect('/courses');

        $courseId   = (int)$this->post('course_id');
        $lecturerId = $this->post('lecturer_id');
        $tenantId   = Auth::tenantId();

        if (!$courseId) {
            $this->flash('error', 'Invalid course.');
            $this->redirect('/courses');
        }

        $course = $this->courseModel->findWhere(['id' => $courseId, 'tenant_id' => $tenantId]);
        if (!$course) {
            $this->flash('error', 'Course not found.');
            $this->redirect('/courses');
        }

        // Update the course
        $this->courseModel->query("
            UPDATE courses SET lecturer_id = :lid WHERE id = :cid AND tenant_id = :tid
        ", [':lid' => $lecturerId ?: null, ':cid' => $courseId, ':tid' => $tenantId]);

        if ($lecturerId) {
            // Notify the lecturer
            require_once ROOT_PATH . '/app/models/Notification.php';
            (new Notification())->send(
                (int)$lecturerId,
                $tenantId,
                'New Course Assignment',
                "You have been assigned to teach: {$course['code']} - {$course['title']}",
                'info',
                "/results"
            );
        }

        $this->flash('success', 'Lecturer assigned successfully.');
        $this->redirect($this->post('redirect', '/courses'));
    }

    /**
     * GET /courses/{id}
     * Show single course and enrolled students
     */
    public function show(int $id): void
    {
        $tenantId = Auth::tenantId();
        
        // Custom query to get the course with its department and lecturer name
        $course = $this->courseModel->raw("
            SELECT c.*, d.name as department_name, u.name as lecturer_name 
            FROM courses c
            LEFT JOIN departments d ON c.department_id = d.id
            LEFT JOIN users u ON c.lecturer_id = u.id
            WHERE c.id = :id AND c.tenant_id = :tenant_id
        ", [':id' => $id, ':tenant_id' => $tenantId])[0] ?? false;

        if (!$course) {
            $this->flash('error', 'Course not found.');
            $this->redirect('/courses');
        }

        // Get enrolled students via raw query
        $students = $this->courseModel->raw("
            SELECT u.id, u.name, u.matric_number, u.email, e.enrolled_at, e.grade, e.score
            FROM enrollments e
            JOIN users u ON e.student_id = u.id
            WHERE e.course_id = :course_id AND e.tenant_id = :tenant_id
            ORDER BY u.name ASC
        ", [':course_id' => $id, ':tenant_id' => $tenantId]);

        $this->view('courses.show', [
            'course' => $course,
            'students' => $students
        ]);
    }

    /**
     * GET /courses/{id}/edit
     * Show edit form
     */
    public function edit(int $id): void
    {
        Auth::authorize(['superadmin', 'vc', 'dean', 'hod']);
        
        $tenantId = Auth::tenantId();
        $course = $this->courseModel->find($id, $tenantId);
        
        if (!$course) {
            $this->redirect('/courses');
        }

        $departments = $this->deptModel->all($tenantId);
        $potentialLecturers = $this->userModel->getByRole('lecturer', $tenantId);

        $this->view('courses.edit', [
            'course' => $course,
            'departments' => $departments,
            'potentialLecturers' => $potentialLecturers
        ]);
    }

    /**
     * POST /courses/{id}/edit
     * Update course
     */
    public function update(int $id): void
    {
        Auth::authorize(['superadmin', 'vc', 'dean', 'hod']);
        if (!$this->isPost()) $this->redirect("/courses/{$id}/edit");

        $title = $this->post('title');
        $code = $this->post('code');
        $departmentId = (int) $this->post('department_id');
        $lecturerId = $this->post('lecturer_id') ? (int) $this->post('lecturer_id') : null;
        $creditUnits = (int) $this->post('credit_units');

        if ($title && $code && $departmentId && $creditUnits > 0) {
            $this->courseModel->update($id, [
                'department_id' => $departmentId,
                'lecturer_id'   => $lecturerId,
                'code'          => strtoupper(str_replace(' ', '', $code)),
                'title'         => $title,
                'description'   => $this->post('description'),
                'credit_units'  => $creditUnits,
                'level'         => $this->post('level', '100'),
                'semester'      => $this->post('semester', 'first'),
                'status'        => $this->post('status', 'active')
            ], Auth::tenantId());
            
            $this->flash('success', 'Course updated successfully.');
            $this->redirect("/courses/{$id}");
        }

        $this->flash('error', 'Please fill all required fields correctly.');
        $this->redirect("/courses/{$id}/edit");
    }

    /**
     * POST /courses/{id}/delete
     */
    public function destroy(int $id): void
    {
        Auth::authorize(['superadmin', 'vc', 'dean', 'hod']);
        
        $this->courseModel->delete($id, Auth::tenantId());
        $this->flash('success', 'Course deleted successfully.');
        $this->redirect('/courses');
    }

    /**
     * GET /my-courses
     * Show student's enrolled courses
     */
    public function myCourses(): void
    {
        $tenantId = Auth::tenantId();
        $userId   = Auth::id();

        $enrolledCourses = $this->enrollModel->getStudentCourses($userId, $tenantId);

        $this->view('courses.my_courses', [
            'enrolledCourses' => $enrolledCourses
        ]);
    }

    /**
     * POST /courses/{id}/enroll
     * Enroll the logged-in student into a course
     */
    public function enroll(int $id): void
    {
        if (!$this->isPost()) $this->redirect('/my-courses');

        $tenantId = Auth::tenantId();
        $userId   = Auth::id();

        $course = $this->courseModel->find($id, $tenantId);
        if (!$course) {
            $this->flash('error', 'Course not found.');
            $this->redirect('/my-courses');
        }

        $success = $this->enrollModel->enroll($userId, $id, $tenantId);

        if ($success) {
            $this->flash('success', "Enrolled in '{$course['code']}' successfully.");
        } else {
            $this->flash('info', 'You are already enrolled in this course.');
        }

        $this->redirect('/my-courses');
    }

    /**
     * POST /courses/{id}/drop
     * Drop a course enrollment
     */
    public function drop(int $id): void
    {
        if (!$this->isPost()) $this->redirect('/my-courses');

        $tenantId = Auth::tenantId();
        $userId   = Auth::id();

        $this->enrollModel->drop($userId, $id, $tenantId);
        $this->flash('success', 'Course dropped successfully.');
        $this->redirect('/my-courses');
    }
}
