<?php
/**
 * Auth.php — Authentication & Session Manager
 *
 * Handles login, logout, session management, and role checks
 * for the multi-tenant Academia system.
 */

class Auth
{
    // ─────────────────────────────────────────────
    // SESSION MANAGEMENT
    // ─────────────────────────────────────────────

    /**
     * Log a user in by storing their data in the session.
     *
     * @param array $user The user record from the database
     */
    public static function login(array $user): void
    {
        // Regenerate session ID to prevent session fixation attacks
        session_regenerate_id(true);

        $_SESSION['auth'] = [
            'user_id'   => $user['id'],
            'tenant_id' => $user['tenant_id'],
            'role'      => $user['role'],
            'unit_id'   => $user['unit_id'] ?? null,
            'name'      => $user['name'],
            'email'     => $user['email'],
        ];
    }

    /**
     * Destroy the session and log the user out.
     */
    public static function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }

        session_destroy();
    }

    // ─────────────────────────────────────────────
    // CHECKS
    // ─────────────────────────────────────────────

    /**
     * Check whether a user is currently logged in.
     */
    public static function check(): bool
    {
        return isset($_SESSION['auth']['user_id']);
    }

    /**
     * Require the user to be logged in; redirect to login if not.
     */
    public static function requireLogin(string $redirect = '/login'): void
    {
        if (!self::check()) {
            $url = BASE_URL . $redirect;
            header("Location: {$url}");
            exit;
        }
    }

    /**
     * Require the user NOT to be logged in; redirect to dashboard if already authed.
     */
    public static function requireGuest(string $redirect = '/dashboard'): void
    {
        if (self::check()) {
            $url = BASE_URL . $redirect;
            header("Location: {$url}");
            exit;
        }
    }

    // ─────────────────────────────────────────────
    // SESSION ACCESSORS
    // ─────────────────────────────────────────────

    /**
     * Get the full auth session array, or a specific key.
     */
    public static function user(?string $key = null): mixed
    {
        if (!self::check()) {
            return null;
        }
        if ($key !== null) {
            return $_SESSION['auth'][$key] ?? null;
        }
        return $_SESSION['auth'];
    }

    /**
     * Get the currently authenticated user's ID.
     */
    public static function id(): ?int
    {
        return self::user('user_id');
    }

    /**
     * Get the currently authenticated user's tenant ID.
     */
    public static function tenantId(): ?int
    {
        return self::user('tenant_id');
    }

    /**
     * Get the currently authenticated user's role.
     */
    public static function role(): ?string
    {
        return self::user('role');
    }

    // ─────────────────────────────────────────────
    // ROLE CHECKS
    // ─────────────────────────────────────────────

    /**
     * Check if the user has a specific role.
     */
    public static function is(string $role): bool
    {
        return self::role() === $role;
    }

    /**
     * Check if the user has one of the given roles.
     *
     * @param string[] $roles
     */
    public static function hasRole(array $roles): bool
    {
        return in_array(self::role(), $roles, true);
    }

    /**
     * Authorize access for given roles. Abort with 403 if not authorized.
     *
     * @param string[] $roles
     */
    public static function authorize(array $roles): void
    {
        self::requireLogin();

        if (!self::hasRole($roles)) {
            http_response_code(403);
            $url = BASE_URL . '/dashboard';
            echo "<!DOCTYPE html><html><body style='font-family:sans-serif;padding:2rem'>
                <h1 style='color:#e53e3e'>403 — Forbidden</h1>
                <p>You do not have permission to access this page.</p>
                <a href='{$url}'>← Back to Dashboard</a>
            </body></html>";
            exit;
        }
    }

    /**
     * Check if the user is a superadmin or VC (top-level).
     */
    public static function isAdmin(): bool
    {
        return self::hasRole(['superadmin', 'vc']);
    }

    // ─────────────────────────────────────────────
    // PASSWORD UTILITIES
    // ─────────────────────────────────────────────

    /**
     * Hash a plain-text password securely.
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Verify a plain-text password against a hash.
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
