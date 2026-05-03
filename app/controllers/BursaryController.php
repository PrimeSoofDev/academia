<?php
/**
 * BursaryController.php
 * Handles student fees, revenue tracking, and financial records.
 */

require_once ROOT_PATH . '/app/core/Controller.php';
require_once ROOT_PATH . '/app/models/FeePayment.php';
require_once ROOT_PATH . '/app/models/User.php';
require_once ROOT_PATH . '/app/models/AcademicSession.php';

class BursaryController extends Controller
{
    private FeePayment $paymentModel;
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->paymentModel = new FeePayment();
        $this->userModel = new User();
    }

    /**
     * GET /bursary
     * Bursary Dashboard Overview
     */
    public function index(): void
    {
        $tenantId = Auth::tenantId();
        
        $stats = [
            'total_revenue'   => $this->paymentModel->getTotalRevenue($tenantId, 'paid'),
            'pending_revenue' => $this->paymentModel->getTotalRevenue($tenantId, 'pending'),
            'paid_count'      => $this->paymentModel->getTransactionCount($tenantId, 'paid'),
            'recent_payments' => $this->paymentModel->getRecentPayments($tenantId, 10),
        ];

        $this->view('bursary.index', [
            'stats' => $stats
        ]);
    }

    /**
     * GET /bursary/payments
     * List all fee payments
     */
    public function payments(): void
    {
        $tenantId = Auth::tenantId();
        $payments = $this->paymentModel->getAllPayments($tenantId);
        $students = $this->userModel->getByRole('student', $tenantId);
        
        // Fetch current session for the modal form
        $sessionModel = new AcademicSession();
        $currentSession = $sessionModel->getCurrentSession();

        $this->view('bursary.payments', [
            'payments' => $payments,
            'students' => $students,
            'currentSession' => $currentSession
        ]);
    }

    /**
     * POST /bursary/record
     * Manually record a fee payment
     */
    public function record(): void
    {
        if (!$this->isPost()) $this->redirect('/bursary/payments');

        $tenantId = Auth::tenantId();
        $studentId = (int) $this->post('student_id');
        $amount = (float) $this->post('amount');
        $feeType = $this->post('fee_type');
        $status = $this->post('status', 'paid');
        $sessionId = (int) $this->post('session_id');
        
        if (!$sessionId) {
            $currentSession = (new AcademicSession())->getCurrentSession();
            $sessionId = $currentSession ? $currentSession['id'] : null;
        }

        if ($studentId && $amount > 0 && $feeType) {
            $reference = 'REF-' . strtoupper(uniqid());
            
            $this->paymentModel->create([
                'tenant_id'  => $tenantId,
                'student_id' => $studentId,
                'session_id' => $sessionId,
                'amount'     => $amount,
                'fee_type'   => $feeType,
                'reference'  => $reference,
                'status'     => $status,
                'paid_at'    => $status === 'paid' ? date('Y-m-d H:i:s') : null,
                'recorded_by'=> Auth::id()
            ]);
            
            $this->flash('success', "Payment of ₦" . number_format($amount, 2) . " recorded successfully for " . $feeType);
        } else {
            $this->flash('error', 'Please fill all required fields correctly.');
        }

        $this->redirect('/bursary/payments');
    }
}
