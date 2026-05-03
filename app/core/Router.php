<?php
/**
 * Router.php — Front Router
 *
 * Handles route registration (GET/POST) and dispatches
 * incoming requests to the correct Controller@method.
 */

class Router
{
    /** @var array Registered routes grouped by HTTP method */
    private array $routes = [
        'GET'  => [],
        'POST' => [],
    ];

    /** @var array Middleware stack for the next registered routes */
    private array $middlewareStack = [];

    // ─────────────────────────────────────────────
    // ROUTE REGISTRATION
    // ─────────────────────────────────────────────

    /**
     * Register a GET route.
     *
     * @param string $uri     e.g. '/dashboard'
     * @param string $action  e.g. 'DashboardController@index'
     */
    public function get(string $uri, string $action): self
    {
        $this->routes['GET'][$this->normalize($uri)] = [
            'action'     => $action,
            'middleware' => $this->middlewareStack,
        ];
        $this->middlewareStack = [];
        return $this;
    }

    /**
     * Register a POST route.
     */
    public function post(string $uri, string $action): self
    {
        $this->routes['POST'][$this->normalize($uri)] = [
            'action'     => $action,
            'middleware' => $this->middlewareStack,
        ];
        $this->middlewareStack = [];
        return $this;
    }

    /**
     * Attach middleware to the next route.
     * Usage: $router->middleware('auth')->get('/dashboard', '...');
     */
    public function middleware(string ...$middleware): self
    {
        $this->middlewareStack = $middleware;
        return $this;
    }

    // ─────────────────────────────────────────────
    // DISPATCH
    // ─────────────────────────────────────────────

    /**
     * Match the current request to a route and dispatch it.
     */
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = $this->getCurrentUri();

        // Find matching route (supports {param} placeholders)
        $matchedRoute  = null;
        $routeParams   = [];

        foreach ($this->routes[$method] ?? [] as $pattern => $route) {
            $params = [];
            if ($this->matchRoute($pattern, $uri, $params)) {
                $matchedRoute = $route;
                $routeParams  = $params;
                break;
            }
        }

        if ($matchedRoute === null) {
            http_response_code(404);
            $this->renderError(404, "Page not found: {$uri}");
            return;
        }

        // Run middleware
        foreach ($matchedRoute['middleware'] as $mw) {
            Middleware::handle($mw);
        }

        // Dispatch to controller
        $this->callAction($matchedRoute['action'], $routeParams);
    }

    // ─────────────────────────────────────────────
    // INTERNALS
    // ─────────────────────────────────────────────

    /**
     * Parse the current request URI, stripping base path and query string.
     * Works whether the app is at domain root OR a sub-folder (e.g. /academia/public/).
     */
    private function getCurrentUri(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Derive the base path dynamically from SCRIPT_NAME
        // e.g. /academia/public/index.php → base = /academia/public
        $scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

        if ($scriptDir && $scriptDir !== '/' && str_starts_with($uri, $scriptDir)) {
            $uri = substr($uri, strlen($scriptDir));
        }

        return $this->normalize($uri ?: '/');
    }

    /**
     * Normalize a URI: ensure it starts with / and has no trailing slash.
     */
    private function normalize(string $uri): string
    {
        $uri = '/' . trim($uri, '/');
        return ($uri === '/') ? '/' : rtrim($uri, '/');
    }

    /**
     * Match a route pattern against a URI, extracting named params.
     * Converts {param} → named capture group.
     */
    private function matchRoute(string $pattern, string $uri, array &$params): bool
    {
        // Convert {param} to regex named groups
        $regex = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';

        if (preg_match($regex, $uri, $matches)) {
            // Filter out numeric keys from matches
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $params[$key] = $value;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Instantiate the controller and call the method.
     *
     * @param string $action  'ControllerName@methodName'
     * @param array  $params  Route parameters
     */
    private function callAction(string $action, array $params): void
    {
        [$controllerName, $method] = explode('@', $action, 2);

        $controllerFile = ROOT_PATH . "/app/controllers/{$controllerName}.php";
        if (!file_exists($controllerFile)) {
            $this->renderError(500, "Controller not found: {$controllerName}");
            return;
        }

        require_once $controllerFile;

        if (!class_exists($controllerName)) {
            $this->renderError(500, "Class not found: {$controllerName}");
            return;
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $method)) {
            $this->renderError(500, "Method '{$method}' not found in {$controllerName}");
            return;
        }

        // Call the controller method, passing route params as arguments
        call_user_func_array([$controller, $method], $params);
    }

    /**
     * Render a simple HTML error page.
     */
    private function renderError(int $code, string $message): void
    {
        echo "<!DOCTYPE html><html><body style='font-family:sans-serif;padding:2rem'>
            <h1 style='color:#e53e3e'>{$code}</h1>
            <p>{$message}</p>
            <a href='/'>← Back to Home</a>
        </body></html>";
    }
}
