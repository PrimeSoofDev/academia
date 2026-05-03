<?php
/**
 * SubmissionController.php
 * Handles academic submissions (Exam Questions, CA, Results)
 */

require_once ROOT_PATH . '/app/core/Controller.php';
require_once ROOT_PATH . '/app/models/AcademicSubmission.php';
require_once ROOT_PATH . '/app/models/Course.php';
require_once ROOT_PATH . '/app/models/User.php';
require_once ROOT_PATH . '/app/models/Department.php';
require_once ROOT_PATH . '/app/models/Notification.php';

class SubmissionController extends Controller
{
    private AcademicSubmission $submissionModel;
    private Course $courseModel;

    public function __construct()
    {
        parent::__construct();
        $this->submissionModel = new AcademicSubmission();
        $this->courseModel = new Course();
    }

    /**
     * List all submissions based on role
     */
    public function index(): void
    {
        Auth::requireLogin();
        $tenantId = Auth::tenantId();
        $role = Auth::role();
        $userId = Auth::id();

        $filters = [];
        if ($role === 'lecturer') {
            $filters['lecturer_id'] = $userId;
        } elseif ($role === 'hod') {
            $user = (new User())->find($userId, $tenantId);
            $filters['department_id'] = $user['department_id'];
        }
        // Deans and Admins see all for now (or could filter by faculty)

        $submissions = $this->submissionModel->getDetailed($tenantId, $filters);

        $this->view('submissions.index', [
            'submissions' => $submissions
        ]);
    }

    /**
     * Show form to submit new material
     */
    public function create(): void
    {
        Auth::authorize(['lecturer']);
        $tenantId = Auth::tenantId();
        $userId = Auth::id();

        // Get courses assigned to this lecturer
        $courses = $this->courseModel->where(['lecturer_id' => $userId, 'tenant_id' => $tenantId]);
        $user = (new User())->find($userId, $tenantId);

        $this->view('submissions.create', [
            'courses' => $courses,
            'user'    => $user
        ]);
    }

    /**
     * Store a new submission
     */
    public function store(): void
    {
        Auth::authorize(['lecturer']);
        if (!$this->isPost()) $this->redirect('/submissions');

        $tenantId = Auth::tenantId();
        $userId = Auth::id();
        $courseId = (int)$this->post('course_id');
        $type = $this->post('type');
        $content = $this->post('content');

        $filePath = null;
        if (!empty($_FILES['file']['name'])) {
            $uploadDir = ROOT_PATH . '/public/uploads/submissions/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $filename = 'sub_' . time() . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . $filename)) {
                $filePath = '/uploads/submissions/' . $filename;
            }
        }

        if ($courseId && $type) {
            // Determine lecturer signature
            $user = (new User())->find($userId, $tenantId);
            $sigPath = $user['signature_path'] ?? null;
            
            $drawnSig = $this->post('drawn_signature');
            if ($drawnSig) {
                $savedPath = $this->saveSignature($drawnSig);
                if ($savedPath) $sigPath = $savedPath;
            }

            $submissionId = $this->submissionModel->create([
                'tenant_id'          => $tenantId,
                'course_id'          => $courseId,
                'lecturer_id'        => $userId,
                'type'               => $type,
                'content'            => $content,
                'file_path'          => $filePath,
                'status'             => 'submitted',
                'lecturer_signed_at' => date('Y-m-d H:i:s'),
                'lecturer_sig_path'  => $sigPath
            ]);

            // Notify HOD
            $course = $this->courseModel->find($courseId, $tenantId);
            $dept = (new Department())->find((int)$course['department_id'], $tenantId);
            if ($dept && $dept['hod_id']) {
                (new Notification())->send(
                    (int)$dept['hod_id'],
                    $tenantId,
                    'New Submission for Review',
                    "Lecturer " . Auth::user()['name'] . " submitted {$type} for " . $course['code'],
                    'info',
                    "/submissions/{$submissionId}"
                );
            }

            $this->flash('success', 'Material submitted successfully for approval.');
            $this->redirect('/submissions');
        } else {
            $this->flash('error', 'Please fill all required fields.');
            $this->redirect('/submissions/create');
        }
    }

    /**
     * Show single submission details
     */
    public function show(int $id): void
    {
        Auth::requireLogin();
        $tenantId = Auth::tenantId();
        
        $submission = $this->submissionModel->raw("
            SELECT s.*, c.code as course_code, c.title as course_title, 
                   u.name as lecturer_name, 
                   h.name as hod_name,
                   d.name as dean_name
            FROM academic_submissions s
            JOIN courses c ON s.course_id = c.id
            JOIN users u ON s.lecturer_id = u.id
            LEFT JOIN users h ON s.hod_id = h.id
            LEFT JOIN users d ON s.dean_id = d.id
            WHERE s.id = :id AND s.tenant_id = :tenant_id
        ", [':id' => $id, ':tenant_id' => $tenantId])[0] ?? false;

        if (!$submission) {
            $this->flash('error', 'Submission not found.');
            $this->redirect('/submissions');
        }

        $this->view('submissions.show', [
            'submission' => $submission
        ]);
    }

    /**
     * Approve and Sign a submission
     */
    public function approve(int $id): void
    {
        Auth::authorize(['hod', 'dean', 'superadmin', 'vc']);
        if (!$this->isPost()) $this->redirect("/submissions/{$id}");

        $tenantId = Auth::tenantId();
        $userId = Auth::id();
        $role = Auth::role();
        $status = $this->post('action') === 'reject' ? 'rejected' : 'approved';
        $remarks = $this->post('remarks');

        // Determine reviewer signature
        $user = (new User())->find($userId, $tenantId);
        $sigPath = $user['signature_path'] ?? null;
        
        $drawnSig = $this->post('drawn_signature');
        if ($drawnSig) {
            $savedPath = $this->saveSignature($drawnSig);
            if ($savedPath) $sigPath = $savedPath;
        }

        $data = [
            'status' => $status,
            'remarks' => $remarks
        ];

        if ($role === 'hod') {
            $data['hod_id'] = $userId;
            $data['hod_signed_at'] = date('Y-m-d H:i:s');
            $data['hod_sig_path'] = $sigPath;
            $data['status'] = ($status === 'approved') ? 'reviewed' : 'rejected';
        } elseif ($role === 'dean' || $role === 'vc' || $role === 'superadmin') {
            $data['dean_id'] = $userId;
            $data['dean_signed_at'] = date('Y-m-d H:i:s');
            $data['dean_sig_path'] = $sigPath;
        }

        $this->submissionModel->update($id, $data, $tenantId);

        // Notify Lecturer
        $sub = $this->submissionModel->find($id, $tenantId);
        (new Notification())->send(
            (int)$sub['lecturer_id'],
            $tenantId,
            "Submission {$status}",
            "Your {$sub['type']} submission has been " . $status . ($remarks ? ": " . $remarks : ""),
            $status === 'approved' ? 'success' : 'error',
            "/submissions/{$id}"
        );

        $this->flash('success', "Submission " . ($status === 'approved' ? 'approved and signed' : 'rejected') . ".");
        $this->redirect("/submissions/{$id}");
    }

    /**
     * Helper to save base64 signature as file
     */
    private function saveSignature(string $base64): ?string
    {
        if (empty($base64) || !str_contains($base64, 'base64,')) return null;

        $data = explode(',', $base64);
        $content = base64_decode($data[1]);
        
        $filename = 'sig_' . time() . '_' . uniqid() . '.png';
        $path = ROOT_PATH . '/public/uploads/signatures/' . $filename;
        
        if (!is_dir(dirname($path))) mkdir(dirname($path), 0777, true);
        
        if (file_put_contents($path, $content)) {
            return '/uploads/signatures/' . $filename;
        }
        
        return null;
    }
}
