<?php
/**
 * NotificationController.php
 * Handles viewing and managing user notifications.
 */

require_once ROOT_PATH . '/app/core/Controller.php';
require_once ROOT_PATH . '/app/models/Notification.php';

class NotificationController extends Controller
{
    private Notification $notifModel;

    public function __construct()
    {
        parent::__construct();
        $this->notifModel = new Notification();
    }

    /**
     * GET /notifications
     * Show all notifications for the current user.
     */
    public function index(): void
    {
        $userId   = Auth::id();
        $tenantId = Auth::tenantId();

        $notifications = $this->notifModel->where(['user_id' => $userId, 'tenant_id' => $tenantId], 'created_at DESC');

        $this->view('notifications.index', [
            'notifications' => $notifications
        ]);
    }

    /**
     * POST /notifications/read
     * Mark a specific notification as read.
     */
    public function markRead(): void
    {
        if (!$this->isPost()) $this->redirect('/notifications');

        $id       = $this->post('id');
        $userId   = Auth::id();
        $tenantId = Auth::tenantId();

        if ($id) {
            $this->notifModel->markAsRead((int)$id, $userId, $tenantId);
        }

        // Return JSON if AJAX, otherwise redirect
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode(['success' => true]);
            exit;
        }

        $this->redirect($this->post('redirect', '/notifications'));
    }

    /**
     * POST /notifications/read-all
     * Mark all notifications as read.
     */
    public function markAllRead(): void
    {
        if (!$this->isPost()) $this->redirect('/notifications');

        $userId   = Auth::id();
        $tenantId = Auth::tenantId();

        $this->notifModel->markAllAsRead($userId, $tenantId);

        $this->flash('success', 'All notifications marked as read.');
        $this->redirect('/notifications');
    }
}
