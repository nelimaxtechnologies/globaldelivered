<?php
/**
 * Global Delivered Logistics - Email Queue Processor
 * 
 * This script processes the email queue and should be run via cron.
 * 
 * CRON COMMAND (run every 5 minutes):
 * * /5 * * * * php /path/to/globaldelivered/cron/process-emails.php >> /path/to/globaldelivered/storage/logs/cron.log 2>&1
 * 
 * Or run once:
 * php cron/process-emails.php
 */

// ------------------------------------------------------------
// Bootstrap
// ------------------------------------------------------------
define('START_TIME', microtime(true));
define('APP_DEBUG', false);

// Load autoloader
$autoloadPaths = [
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../vendor/autoload.php',
];
$autoloader = null;
foreach ($autoloadPaths as $path) {
    if (file_exists($path)) {
        $autoloader = require $path;
        break;
    }
}

// Custom autoloader fallback
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';
    if (str_starts_with($class, $prefix)) {
        $file = $baseDir . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
        if (file_exists($file)) { require $file; return true; }
    }
    return false;
});

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../app/Helpers/helpers.php';

// ------------------------------------------------------------
// Process Email Queue
// ------------------------------------------------------------
echo "[" . date('Y-m-d H:i:s') . "] Starting email queue processing...\n";

try {
    $emailService = \App\Services\EmailService::getInstance();
    $results = $emailService->processQueue(20);
    
    echo "[" . date('Y-m-d H:i:s') . "] Sent: {$results['sent']}, Failed: {$results['failed']}\n";
    
    if (!empty($results['errors'])) {
        foreach ($results['errors'] as $error) {
            echo "[" . date('Y-m-d H:i:s') . "] ERROR: {$error}\n";
        }
    }
} catch (\Exception $e) {
    echo "[" . date('Y-m-d H:i:s') . "] CRITICAL: " . $e->getMessage() . "\n";
    error_log("Email queue processor error: " . $e->getMessage());
}

$duration = round((microtime(true) - START_TIME) * 1000, 2);
echo "[" . date('Y-m-d H:i:s') . "] Completed in {$duration}ms\n";
