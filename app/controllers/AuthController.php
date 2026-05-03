<?php
/**
 * AuthController.php — Handles Login & Logout
 *
 * Uses the multi-tenant login flow: user enters their university slug,
 * email, and password. System finds the tenant first, then verifies credentials.
 */

require_once ROOT_PATH . '/app/core/Controller.php';
require_once ROOT_PATH . '/app/core/Auth.php';
require_once ROOT_PATH . '/app/models/User.php';

class AuthController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }

    // ─────────────────────────────────────────────
    // SHOW LOGIN FORM
    // ─────────────────────────────────────────────

    /**
     * GET /login
     * Display the login page. Redirect to dashboard if already logged in.
     */
    public function login(): void
    {
        Auth::requireGuest('/dashboard');
        $this->view('auth.login', [], 'auth');
    }

    // ─────────────────────────────────────────────
    // PROCESS LOGIN
    // ─────────────────────────────────────────────

    /**
     * POST /login
     * Authenticate the user against the correct tenant.
     */
    public function authenticate(): void
    {
        Auth::requireGuest('/dashboard');

        if (!$this->isPost()) {
            $this->redirect('/login');
        }

        // 1. Collect and validate inputs
        $email    = $this->post('email', '');
        $password = $this->post('password', '');   // raw for verify (not sanitized double)
        $slug     = $this->post('tenant_slug', '');

        $errors = [];
        if (empty($email))    $errors[] = 'Email is required.';
        if (empty($password)) $errors[] = 'Password is required.';
        if (empty($slug))     $errors[] = 'University code is required.';

        if (!empty($errors)) {
            $this->view('auth.login', ['errors' => $errors, 'old' => compact('email', 'slug')], 'auth');
            return;
        }

        // 2. Find the tenant by slug
        $tenant = $this->userModel->findTenantBySlug($slug);
        if (!$tenant) {
            $this->view('auth.login', [
                'errors' => ['University code not found. Please check and try again.'],
                'old'    => compact('email', 'slug'),
            ], 'auth');
            return;
        }

        // 3. Find the user in that tenant
        $rawPassword = $_POST['password'] ?? ''; // use raw POST (not sanitized) for verification
        $user = $this->userModel->findByEmail($email, (int)$tenant['id']);

        if (!$user || !Auth::verifyPassword($rawPassword, $user['password'])) {
            $this->view('auth.login', [
                'errors' => ['Invalid email or password.'],
                'old'    => compact('email', 'slug'),
            ], 'auth');
            return;
        }

        // 4. Log the user in
        Auth::login($user);

        // 5. Redirect to intended URL or dashboard
        $intended = $_SESSION['intended'] ?? '/dashboard';
        unset($_SESSION['intended']);
        $this->redirect($intended);
    }

    // ─────────────────────────────────────────────
    // LOGOUT
    // ─────────────────────────────────────────────

    /**
     * GET /logout
     * Destroy the session and redirect to login.
     */
    public function logout(): void
    {
        Auth::logout();
        $this->flash('success', 'You have been logged out successfully.');
        $this->redirect('/login');
    }
}
