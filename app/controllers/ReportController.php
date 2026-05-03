<?php
/**
 * ReportController.php
 * Manages live campus reporting for students and administration.
 */

require_once ROOT_PATH . '/app/core/Controller.php';
require_once ROOT_PATH . '/app/models/CampusReport.php';
require_once ROOT_PATH . '/app/models/Notification.php';
require_once ROOT_PATH . '/app/models/User.php';

class ReportController extends Controller
{
    private CampusReport $reportModel;
    private Notification $notifModel;

    public function __construct()
    {
        parent::__construct();
        $this->reportModel = new CampusReport();
        $this->notifModel   = new Notification();
    }

    /**
     * GET /reports
     * Show report dashboard (differs for student vs admin).
     */
    public function index(): void
    {
        $userId   = Auth::id();
        $tenantId = Auth::tenantId();
        $role     = Auth::role();

        if ($role === 'student') {
            $reports = $this->reportModel->getDetailed($tenantId, ['student_id' => $userId]);
        } else {
            // VC, Dean, HOD, Superadmin see everything
            $reports = $this->reportModel->getDetailed($tenantId);
        }

        $this->view('reports.index', [
            'reports' => $reports,
            'role'    => $role
        ]);
    }

    /**
     * POST /reports/create
     * Student submits a new report.
     */
    public function store(): void
    {
        Auth::requireRole(['student']);
        if (!$this->isPost()) $this->redirect('/reports');

        $userId   = Auth::id();
        $tenantId = Auth::tenantId();

        $data = [
            'tenant_id'   => $tenantId,
            'student_id'  => $userId,
            'title'       => $this->post('title'),
            'description' => $this->post('description'),
            'category'    => $this->post('category'),
            'urgency'     => $this->post('urgency', 'medium'),
            'location'    => $this->post('location'),
            'status'      => 'pending'
        ];

        if (empty($data['title']) || empty($data['description'])) {
            $this->flash('error', 'Please fill in the title and description.');
            $this->redirect('/reports');
        }

        $reportId = $this->reportModel->create($data);

        if ($reportId) {
            // Notify Leadership (VC, Dean, HOD)
            $leaders = (new User())->raw("
                SELECT id FROM users 
                WHERE tenant_id = :tid AND role IN ('vc', 'dean', 'hod', 'superadmin')
            ", [':tid' => $tenantId]);

            foreach ($leaders as $leader) {
                $this->notifModel->send(
                    (int)$leader['id'],
                    $tenantId,
                    'New Campus Report',
                    "A new {$data['urgency']} urgency report has been submitted: {$data['title']}",
                    $data['urgency'] === 'critical' ? 'warning' : 'info',
                    '/reports'
                );
            }

            $this->flash('success', 'Report submitted successfully. Authorities have been notified.');
        }

        $this->redirect('/reports');
    }

    /**
     * POST /reports/update-status
     * Admin (VC/Dean/HOD) updates report status.
     */
    public function updateStatus(): void
    {
        Auth::requireRole(['superadmin', 'vc', 'dean', 'hod']);
        if (!$this->isPost()) $this->redirect('/reports');

        $id        = (int)$this->post('id');
        $status    = $this->post('status');
        $note      = $this->post('resolution_note');
        $tenantId  = Auth::tenantId();

        $report = $this->reportModel->findWhere(['id' => $id, 'tenant_id' => $tenantId]);
        if (!$report) {
            $this->flash('error', 'Report not found.');
            $this->redirect('/reports');
        }

        $this->reportModel->query("
            UPDATE campus_reports 
            SET status = :status, resolution_note = :note 
            WHERE id = :id AND tenant_id = :tid
        ", [':status' => $status, ':note' => $note, ':id' => $id, ':tid' => $tenantId]);

        // Notify Student
        $this->notifModel->send(
            (int)$report['student_id'],
            $tenantId,
            'Report Status Updated',
            "The status of your report '{$report['title']}' is now: " . ucfirst($status),
            $status === 'resolved' ? 'success' : 'info',
            '/reports'
        );

        $this->flash('success', 'Report status updated.');
        $this->redirect('/reports');
    }
}
