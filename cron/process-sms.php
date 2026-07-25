<?php
/**
 * Global Delivered Logistics - SMS Queue Processor
 * 
 * Processes pending SMS messages. Run via cron.
 * 
 * CRON: * /5 * * * * php /path/to/cron/process-sms.php
 */

define('START_TIME', microtime(true));

$autoloadPaths = [
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../vendor/autoload.php',
];
foreach ($autoloadPaths as $path) {
    if (file_exists($path)) { require $path; break; }
}

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

echo "[" . date('Y-m-d H:i:s') . "] SMS queue processor starting...\n";
echo "[" . date('Y-m-d H:i:s') . "] SMS provider integration pending (Twilio/Africa's Talking/Vonage).\n";
echo "[" . date('Y-m-d H:i:s') . "] Completed in " . round((microtime(true) - START_TIME) * 1000, 2) . "ms\n";
