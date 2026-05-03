<?php
/**
 * App.php — Application Bootstrap
 *
 * Bootstraps the application: sets up constants, includes
 * core classes, starts the session, and dispatches the router.
 */

class App
{
    private Router $router;

    public function __construct()
    {
        $this->bootstrap();
        $this->router = new Router();
    }

    /**
     * Bootstrap the application environment.
     */
    private function bootstrap(): void
    {
        // Load app config
        $config = require ROOT_PATH . '/config/app.php';

        // Set timezone
        date_default_timezone_set($config['timezone']);

        // Error reporting based on environment
        if ($config['env'] === 'development' && $config['debug']) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        } else {
            error_reporting(0);
            ini_set('display_errors', '0');
        }

        // Start session with secure settings
        $sessionName = $config['session']['name'] ?? 'academia_session';
        session_name($sessionName);

        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => $config['session']['lifetime'] ?? 3600,
                'path'     => '/',
                'secure'   => false,  // Set to true in production with HTTPS
                'httponly' => true,
                'samesite' => 'Strict',
            ]);
            session_start();
        }

        // Auto-load core classes from app/core/
        $this->autoloadCore();
    }

    /**
     * Require all core class files.
     */
    private function autoloadCore(): void
    {
        $coreFiles = [
            'Database',
            'Model',
            'Controller',
            'Auth',
            'Middleware',
            'Router',
        ];

        foreach ($coreFiles as $class) {
            $file = ROOT_PATH . "/app/core/{$class}.php";
            if (file_exists($file)) {
                require_once $file;
            }
        }
    }

    /**
     * Load routes and dispatch the current request.
     */
    public function run(): void
    {
        // Load route definitions
        $router = $this->router;
        require ROOT_PATH . '/routes/web.php';

        // Dispatch the request to the appropriate controller
        $router->dispatch();
    }
}
