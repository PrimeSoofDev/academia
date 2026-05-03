<?php
/**
 * Controller.php — Base Controller Class
 *
 * All controllers extend this to load views, redirect,
 * and access request data safely.
 */

abstract class Controller
{
    protected array $config;

    public function __construct()
    {
        $this->config = require ROOT_PATH . '/config/app.php';
    }

    // ─────────────────────────────────────────────
    // VIEW RENDERING
    // ─────────────────────────────────────────────

    /**
     * Render a view file inside a layout.
     *
     * @param string $view    Dot-notation path, e.g. 'dashboard.index'
     * @param array  $data    Variables to extract into view scope
     * @param string $layout  Layout file name (without .php), default 'main'
     */
    protected function view(string $view, array $data = [], string $layout = 'main'): void
    {
        // Convert dot notation to path: 'dashboard.index' → 'dashboard/index'
        $viewPath = str_replace('.', '/', $view);
        $viewFile = ROOT_PATH . "/app/views/{$viewPath}.php";

        if (!file_exists($viewFile)) {
            $this->abort(404, "View not found: {$view}");
        }

        // Extract data variables into scope
        extract($data, EXTR_SKIP);

        // Capture the view content into $content
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Load the layout (which will use $content)
        $layoutFile = ROOT_PATH . "/app/views/layouts/{$layout}.php";
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            // Fallback: render without layout
            echo $content;
        }
    }

    /**
     * Render a view without any layout (for AJAX, partials, etc.)
     */
    protected function partial(string $view, array $data = []): void
    {
        $viewPath = str_replace('.', '/', $view);
        $viewFile = ROOT_PATH . "/app/views/{$viewPath}.php";

        if (!file_exists($viewFile)) {
            $this->abort(404, "Partial not found: {$view}");
        }

        extract($data, EXTR_SKIP);
        require $viewFile;
    }

    // ─────────────────────────────────────────────
    // REQUEST HELPERS
    // ─────────────────────────────────────────────

    /**
     * Get a sanitized POST value.
     */
    protected function post(string $key, mixed $default = null): mixed
    {
        return isset($_POST[$key]) ? $this->sanitize($_POST[$key]) : $default;
    }

    /**
     * Get a sanitized GET value.
     */
    protected function get(string $key, mixed $default = null): mixed
    {
        return isset($_GET[$key]) ? $this->sanitize($_GET[$key]) : $default;
    }

    /**
     * Check if the current request is a POST.
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Basic input sanitization.
     */
    protected function sanitize(mixed $value): mixed
    {
        if (is_string($value)) {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }
        return $value;
    }

    // ─────────────────────────────────────────────
    // RESPONSE HELPERS
    // ─────────────────────────────────────────────

    /**
     * Redirect to a URL.
     */
    protected function redirect(string $url): void
    {
        // If it's a relative URL, prepend BASE_URL
        if (str_starts_with($url, '/')) {
            $url = BASE_URL . $url;
        }
        header("Location: {$url}");
        exit;
    }

    /**
     * Redirect back using the Referer header.
     */
    protected function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }

    /**
     * Send a JSON response and terminate.
     */
    protected function json(mixed $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Store a flash message in session.
     */
    protected function flash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    /**
     * Abort with an HTTP error code.
     */
    protected function abort(int $code, string $message = ''): void
    {
        http_response_code($code);
        $messages = [
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
        ];
        $title = $messages[$code] ?? 'Error';
        echo "<!DOCTYPE html><html><body><h1>{$code} — {$title}</h1><p>{$message}</p></body></html>";
        exit;
    }
}
