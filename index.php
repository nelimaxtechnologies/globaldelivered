<?php
/**
 * Global Delivered Logistics (GDL) - Entry Point
 * 
 * Enterprise-grade courier & logistics management system.
 * All requests are routed through this file.
 */

// ------------------------------------------------------------
// 1. System Constants & Environment
// ------------------------------------------------------------
define('START_TIME', microtime(true));
define('APP_DEBUG', true);

// ------------------------------------------------------------
// 2. Composer Autoloader (with fallback)
// ------------------------------------------------------------
$autoloadPaths = [
    __DIR__ . '/vendor/autoload.php',
    __DIR__ . '/../vendor/autoload.php',
];

$autoloader = null;
foreach ($autoloadPaths as $path) {
    if (file_exists($path)) {
        $autoloader = require $path;
        break;
    }
}

// ------------------------------------------------------------
// 3. Custom Autoloader (fallback)
// ------------------------------------------------------------
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/app/';
    
    if (str_starts_with($class, $prefix)) {
        $relativeClass = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
        
        if (file_exists($file)) {
            require $file;
            return true;
        }
    }
    return false;
});

// ------------------------------------------------------------
// 4. Load Configuration (helpers FIRST because constants.php uses env())
// ------------------------------------------------------------
require_once __DIR__ . '/app/Helpers/helpers.php';
require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/routes/web.php';
require_once __DIR__ . '/routes/api.php';

// ------------------------------------------------------------
// 5. Start Session
// ------------------------------------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ------------------------------------------------------------
// 6. Set Error Handler
// ------------------------------------------------------------
if (!APP_DEBUG) {
    set_error_handler(function ($severity, $message, $file, $line) {
        throw new \ErrorException($message, 0, $severity, $file, $line);
    });
    
    set_exception_handler(function ($exception) {
        error_log("Uncaught Exception: " . $exception->getMessage());
        http_response_code(500);
        
        $viewPath = __DIR__ . '/app/Views/errors/500.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "<h1>500 - Server Error</h1><p>Something went wrong. Please try again later.</p>";
        }
        exit;
    });
}

// ------------------------------------------------------------
// 7. Set Headers
// ------------------------------------------------------------
header('Content-Type: text/html; charset=utf-8');
header('X-Powered-By: Global Delivered Logistics');

// ------------------------------------------------------------
// 8. Dispatch Request
// ------------------------------------------------------------
try {
    \App\Core\Router::dispatch();
} catch (\Exception $e) {
    error_log("Routing Error: " . $e->getMessage());
    
    if (APP_DEBUG) {
        echo "<h1>Error</h1>";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . ":" . $e->getLine() . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        http_response_code(500);
        $viewPath = __DIR__ . '/app/Views/errors/500.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "<h1>500 - Server Error</h1>";
        }
    }
}

// ------------------------------------------------------------
// 9. Performance Logging
// ------------------------------------------------------------
if (APP_DEBUG) {
    $executionTime = (microtime(true) - START_TIME) * 1000;
    $memoryUsage = memory_get_peak_usage(true) / 1024 / 1024;
    error_log(sprintf(
        "[GDL] %s %s - %.2fms, %.2fMB",
        $_SERVER['REQUEST_METHOD'],
        $_SERVER['REQUEST_URI'],
        $executionTime,
        $memoryUsage
    ));
}
