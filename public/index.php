<?php
/**
 * public/index.php — Front Controller
 *
 * ALL HTTP requests are funnelled through this single file.
 * Apache rewrites every request here via .htaccess.
 *
 * Execution flow:
 *   1. Define constants
 *   2. Require the App bootstrap (which loads core classes)
 *   3. Run the Router
 */

// ── 1. CONSTANTS ─────────────────────────────────────────────
/**
 * ROOT_PATH points to the project root (one level above /public)
 * Used everywhere to build absolute file paths.
 */
define('ROOT_PATH', dirname(__DIR__));

/**
 * DS — shorthand for DIRECTORY_SEPARATOR
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * BASE_URL dynamically determines the base path of the application.
 * E.g., if running at http://localhost/academia/public/index.php
 * BASE_URL will be "/academia/public"
 */
$scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
define('BASE_URL', $scriptDir === '/' ? '' : $scriptDir);

/**
 * Global URL helper
 */
function url(string $path = ''): string {
    return BASE_URL . '/' . ltrim($path, '/');
}

// ── 2. COMPOSER AUTOLOAD (future use) ────────────────────────
// Uncomment when you add Composer packages:
// if (file_exists(ROOT_PATH . '/vendor/autoload.php')) {
//     require_once ROOT_PATH . '/vendor/autoload.php';
// }

// ── 3. BOOTSTRAP THE APPLICATION ─────────────────────────────
require_once ROOT_PATH . '/app/core/App.php';

// ── 4. RUN ────────────────────────────────────────────────────
$app = new App();
$app->run();
