<?php
/**
 * SettingsController.php
 * Handles user account settings and preferences.
 */

require_once ROOT_PATH . '/app/core/Controller.php';
require_once ROOT_PATH . '/app/core/Auth.php';
require_once ROOT_PATH . '/app/models/User.php';

class SettingsController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        Auth::requireAuth();
        $this->userModel = new User();
    }

    /**
     * GET /settings
     * Display settings page.
     */
    public function index(): void
    {
        $userId = $_SESSION['auth']['id'];
        $user = $this->userModel->findById($userId);

        $this->view('settings.index', [
            'pageTitle' => 'Account Settings',
            'user'      => $user,
            'breadcrumb' => [
                ['label' => 'Dashboard', 'href' => '/dashboard'],
                ['label' => 'Settings']
            ]
        ]);
    }

    /**
     * POST /settings/password
     * Update user password.
     */
    public function updatePassword(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/settings');
        }

        $userId = $_SESSION['auth']['id'];
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword     = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $user = $this->userModel->findById($userId);

        // Validation
        if (!Auth::verifyPassword($currentPassword, $user['password'])) {
            $this->flash('error', 'Current password incorrect.');
            $this->redirect('/settings');
        }

        if (strlen($newPassword) < 6) {
            $this->flash('error', 'New password must be at least 6 characters.');
            $this->redirect('/settings');
        }

        if ($newPassword !== $confirmPassword) {
            $this->flash('error', 'New passwords do not match.');
            $this->redirect('/settings');
        }

        // Hash and Update
        $tenantId = $_SESSION['auth']['tenant_id'];
        $this->userModel->changePassword($userId, $newPassword, $tenantId);

        $this->flash('success', 'Password updated successfully.');
        $this->redirect('/settings');
    }

    /**
     * POST /settings/preferences
     * Update user preferences (placeholder for future implementation).
     */
    public function updatePreferences(): void
    {
        $this->flash('success', 'Preferences updated successfully.');
        $this->redirect('/settings');
    }
}
