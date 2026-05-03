<?php
/**
 * ResultController.php
 * Handles result entry by lecturers and result viewing by students.
 */

require_once ROOT_PATH . '/app/core/Controller.php';
require_once ROOT_PATH . '/app/models/Result.php';
require_once ROOT_PATH . '/app/models/Enrollment.php';
require_once ROOT_PATH . '/app/models/Course.php';
require_once ROOT_PATH . '/app/models/AcademicSession.php';

class ResultController extends Controller
{
    private Result $resultModel;
    private Enrollment $enrollModel;
    private Course $courseModel;
    private AcademicSession $sessionModel;

    public function __construct()
    {
        parent::__construct();
        $this->resultModel  = new Result();
        $this->enrollModel  = new Enrollment();
        $this->courseModel  = new Course();
        $this->sessionModel = new AcademicSession();
    }

    /**
     * GET /results
     * For students: view their own transcript.
     * For lecturers/admin: view course result entry panel.
     */
    public function index(): void
    {
        $tenantId   = Auth::tenantId();
        $userId     = Auth::id();
        $role       = Auth::role();
        $currentSess = $this->sessionModel->getCurrentSession($tenantId);

        if ($role === 'student') {
            // ── Student: view transcript ──
            $sessions  = $this->sessionModel->all($tenantId);
            $sessionId = $this->get('session_id') ? (int) $this->get('session_id') : ($currentSess['id'] ?? null);
            $results   = $sessionId ? $this->resultModel->getStudentResults($userId, $tenantId, $sessionId) : [];
            $gpa       = $sessionId ? $this->resultModel->computeGPA($userId, $tenantId, $sessionId) : 0;

            $this->view('results.student', [
                'results'    => $results,
                'sessions'   => $sessions,
                'sessionId'  => $sessionId,
                'gpa'        => $gpa,
                'currentSess'=> $currentSess
            ]);
        } else {
            // ── Staff/Lecturer/Admin: Course result management ──
            // List courses assigned to this lecturer (or all for admin)
            if (in_array($role, ['lecturer', 'hod'])) {
                $courses = $this->courseModel->getByLecturer($userId, $tenantId);
            } else {
                $courses = $this->courseModel->allWithDetails($tenantId);
            }

            $this->view('results.index', [
                'courses'     => $courses,
                'currentSess' => $currentSess
            ]);
        }
    }

    /**
     * GET /results/courses/{courseId}
     * Entry sheet for a specific course.
     */
    public function courseSheet(int $courseId): void
    {
        $tenantId   = Auth::tenantId();
        $currentSess = $this->sessionModel->getCurrentSession($tenantId);
        $sessionId  = $this->get('session_id') ? (int)$this->get('session_id') : ($currentSess['id'] ?? null);

        $course   = $this->courseModel->find($courseId, $tenantId);
        if (!$course) {
            $this->flash('error', 'Course not found.');
            $this->redirect('/results');
        }

        // Guard: only the assigned lecturer or admins can see this
        $role = Auth::role();
        if ($role === 'lecturer' && $course['lecturer_id'] != Auth::id()) {
            $this->flash('error', 'Access denied.');
            $this->redirect('/results');
        }

        $students  = $this->enrollModel->getCourseStudents($courseId, $tenantId);
        $existing  = $this->resultModel->getCourseResults($courseId, $tenantId, $sessionId);

        // Index existing results by student_id for easy lookup
        $resultMap = [];
        foreach ($existing as $r) {
            $resultMap[$r['student_id']] = $r;
        }

        $sessions  = $this->sessionModel->all($tenantId);

        $this->view('results.course_sheet', [
            'course'     => $course,
            'students'   => $students,
            'resultMap'  => $resultMap,
            'sessions'   => $sessions,
            'sessionId'  => $sessionId,
            'currentSess'=> $currentSess
        ]);
    }

    /**
     * POST /results/courses/{courseId}/save
     * Bulk save result scores for a course.
     */
    public function saveCourseResults(int $courseId): void
    {
        if (!$this->isPost()) $this->redirect("/results/courses/{$courseId}");

        $tenantId  = Auth::tenantId();
        $sessionId = (int) $this->post('session_id');

        $course = $this->courseModel->find($courseId, $tenantId);
        if (!$course) $this->redirect('/results');

        // Guard: only assigned lecturer or admin
        $role = Auth::role();
        if ($role === 'lecturer' && $course['lecturer_id'] != Auth::id()) {
            $this->flash('error', 'Access denied.');
            $this->redirect('/results');
        }

        $caScores   = $this->post('ca_score') ?? [];
        $examScores = $this->post('exam_score') ?? [];
        $studentIds = $this->post('student_ids') ?? [];

        $saved = 0;
        foreach ($studentIds as $studentId) {
            $studentId = (int) $studentId;
            $ca   = (float) ($caScores[$studentId] ?? 0);
            $exam = (float) ($examScores[$studentId] ?? 0);
            $total = min($ca + $exam, 100); // cap at 100

            $computed = Result::computeGrade($total);

            $this->resultModel->upsert([
                'tenant_id'   => $tenantId,
                'student_id'  => $studentId,
                'course_id'   => $courseId,
                'session_id'  => $sessionId,
                'ca_score'    => $ca,
                'exam_score'  => $exam,
                'grade'       => $computed['grade'],
                'grade_point' => $computed['point'],
                'published'   => 0
            ]);
            $saved++;
        }

        $this->flash('success', "Results saved for {$saved} student(s).");
        $this->redirect("/results/courses/{$courseId}?session_id={$sessionId}");
    }

    /**
     * POST /results/courses/{courseId}/publish
     * Publish (release) results for a course.
     */
    public function publishResults(int $courseId): void
    {
        if (!$this->isPost()) $this->redirect("/results/courses/{$courseId}");

        $tenantId  = Auth::tenantId();
        $sessionId = (int) $this->post('session_id');

        $this->resultModel->publishForCourse($courseId, $sessionId, $tenantId);
        $this->flash('success', 'Results have been published and are now visible to students.');
        $this->redirect("/results/courses/{$courseId}?session_id={$sessionId}");
    }
}
