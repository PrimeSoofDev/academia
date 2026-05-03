<?php
/**
 * Middleware.php — Route Middleware Handler
 *
 * Applies middleware checks before dispatching to controllers.
 * New middleware types can be added here without touching the Router.
 */

class Middleware
{
    /**
     * Registered middleware map.
     * Key   → middleware name (used in route definitions)
     * Value → callable or method name
     */
    private static array $map = [
        'auth'       => 'authMiddleware',
        'guest'      => 'guestMiddleware',
        'admin'      => 'adminMiddleware',
        'vc'         => 'vcMiddleware',
        'dean'       => 'deanMiddleware',
        'hod'        => 'hodMiddleware',
        'lecturer'   => 'lecturerMiddleware',
        'staff'      => 'staffMiddleware',
        'student'    => 'studentMiddleware',
        'registry'   => 'registryMiddleware',
        'bursary'    => 'bursaryMiddleware',
        'library'    => 'libraryMiddleware',
    ];

    /**
     * Run a named middleware. Terminates if the check fails.
     *
     * @param string $name  e.g. 'auth', 'admin'
     */
    public static function handle(string $name): void
    {
        // Support "authorize:role1,role2" syntax
        if (str_starts_with($name, 'authorize:')) {
            $roles = explode(',', substr($name, strlen('authorize:')));
            Auth::authorize($roles);
            return;
        }

        if (!isset(self::$map[$name])) {
            // Unknown middleware — log a warning but don't block
            error_log("Unknown middleware: {$name}");
            return;
        }

        $method = self::$map[$name];
        self::$method();
    }

    // ─────────────────────────────────────────────
    // MIDDLEWARE DEFINITIONS
    // ─────────────────────────────────────────────

    /**
     * Ensure the user is authenticated.
     */
    private static function authMiddleware(): void
    {
        if (!Auth::check()) {
            $_SESSION['intended'] = $_SERVER['REQUEST_URI'] ?? (BASE_URL . '/dashboard');
            $url = BASE_URL . '/login';
            header("Location: {$url}");
            exit;
        }
    }

    /**
     * Ensure the user is NOT authenticated (for login/register pages).
     */
    private static function guestMiddleware(): void
    {
        if (Auth::check()) {
            $url = BASE_URL . '/dashboard';
            header("Location: {$url}");
            exit;
        }
    }

    /**
     * Restrict to administrators (superadmin, vc).
     */
    private static function adminMiddleware(): void
    {
        self::authMiddleware();
        Auth::authorize(['superadmin', 'vc']);
    }

    /**
     * Restrict to Vice Chancellor role.
     */
    private static function vcMiddleware(): void
    {
        self::authMiddleware();
        Auth::authorize(['superadmin', 'vc']);
    }

    /**
     * Restrict to Dean and above.
     */
    private static function deanMiddleware(): void
    {
        self::authMiddleware();
        Auth::authorize(['superadmin', 'vc', 'dean']);
    }

    /**
     * Restrict to HOD and above.
     */
    private static function hodMiddleware(): void
    {
        self::authMiddleware();
        Auth::authorize(['superadmin', 'vc', 'dean', 'hod']);
    }

    /**
     * Restrict to Lecturer and above.
     */
    private static function lecturerMiddleware(): void
    {
        self::authMiddleware();
        Auth::authorize(['superadmin', 'vc', 'dean', 'hod', 'lecturer']);
    }

    /**
     * Restrict to Staff roles.
     */
    private static function staffMiddleware(): void
    {
        self::authMiddleware();
        Auth::authorize(['superadmin', 'vc', 'dean', 'hod', 'lecturer', 'staff']);
    }

    /**
     * Restrict to Students only.
     */
    private static function studentMiddleware(): void
    {
        self::authMiddleware();
        Auth::authorize(['student']);
    }

    /**
     * Restrict to Registry unit staff (or high-level management).
     */
    private static function registryMiddleware(): void
    {
        self::authMiddleware();
        $user = Auth::user();
        $allowedRoles = ['superadmin', 'vc'];
        if (!in_array($user['role'], $allowedRoles) && (int)($user['unit_id'] ?? 0) !== 1) {
            Auth::deny();
        }
    }

    /**
     * Restrict to Bursary unit staff.
     */
    private static function bursaryMiddleware(): void
    {
        self::authMiddleware();
        $user = Auth::user();
        $allowedRoles = ['superadmin', 'vc'];
        if (!in_array($user['role'], $allowedRoles) && (int)($user['unit_id'] ?? 0) !== 2) {
            Auth::deny();
        }
    }

    /**
     * Restrict to Library unit staff.
     */
    private static function libraryMiddleware(): void
    {
        self::authMiddleware();
        $user = Auth::user();
        $allowedRoles = ['superadmin', 'vc'];
        if (!in_array($user['role'], $allowedRoles) && (int)($user['unit_id'] ?? 0) !== 3) {
            Auth::deny();
        }
    }
}
