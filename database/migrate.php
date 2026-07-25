#!/usr/bin/env php
<?php
/**
 * Global Delivered Logistics - Database Migration CLI
 *
 * Command-line tool for installing, migrating, and seeding the database.
 *
 * Usage:
 *   php database/migrate.php install    - Create all tables + seed data
 *   php database/migrate.php fresh      - Drop all tables + re-install
 *   php database/migrate.php refresh    - fresh + seed
 *   php database/migrate.php seed       - Run seeders only
 *   php database/migrate.php seed:User  - Run a specific seeder (partial match)
 *   php database/migrate.php migrate    - Run pending migrations
 *   php database/migrate.php rollback   - Roll back the last migration batch
 *   php database/migrate.php status     - Show migration status
 *   php database/migrate.php --help     - Show this help
 */

// ---------------------------------------------------------------
// 1. Environment detection
// ---------------------------------------------------------------
$projectRoot = dirname(__DIR__);
chdir($projectRoot);

// Try to load .env if it exists
$envFile = $projectRoot . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $_ENV[trim($parts[0])] = trim($parts[1]);
            putenv(trim($parts[0]) . '=' . trim($parts[1]));
        }
    }
}

// ---------------------------------------------------------------
// 2. Load env() helper (needed by config files)
// ---------------------------------------------------------------
$helperFile = $projectRoot . '/app/Helpers/helpers.php';
if (file_exists($helperFile)) {
    require_once $helperFile;
}

// ---------------------------------------------------------------
// 3. Load database config
// ---------------------------------------------------------------
$configFile = $projectRoot . '/config/database.php';
if (file_exists($configFile)) {
    $dbConfig = require $configFile;
    $mysql = $dbConfig['connections']['mysql'] ?? [];

    foreach ($mysql as $key => $value) {
        if (is_string($value)) {
            $envKey = strtoupper('DB_' . $key);
            if (!isset($_ENV[$envKey])) {
                $_ENV[$envKey] = $value;
                putenv("{$envKey}={$value}");
            }
        }
    }
}

// ---------------------------------------------------------------
// 3. Parse CLI arguments
// ---------------------------------------------------------------
$command = '--help';
$seederName = '';

if ($argc > 1) {
    $command = $argv[1];

    // Support "seed:Role" syntax for specific seeder
    if (str_starts_with($command, 'seed:')) {
        $seederName = substr($command, 5);
        $command = 'seed';
    }
}

if ($command === '--help' || $command === '-h') {
    echo PHP_EOL;
    echo "  GLOBAL DELIVERED LOGISTICS - Migration CLI" . PHP_EOL;
    echo "  " . str_repeat('=', 48) . PHP_EOL;
    echo PHP_EOL;
    echo "  Usage: php database/migrate.php <command>" . PHP_EOL;
    echo PHP_EOL;
    echo "  Commands:" . PHP_EOL;
    echo "    install        Create all tables and seed data" . PHP_EOL;
    echo "    fresh          Drop all tables and re-install" . PHP_EOL;
    echo "    refresh        Alias for fresh (drop + install)" . PHP_EOL;
    echo "    seed           Run all seeders" . PHP_EOL;
    echo "    seed:<Name>    Run a specific seeder (e.g. seed:Role)" . PHP_EOL;
    echo "    migrate        Run pending migrations" . PHP_EOL;
    echo "    rollback       Roll back the last migration batch" . PHP_EOL;
    echo "    status         Show migration status" . PHP_EOL;
    echo PHP_EOL;
    echo "  Examples:" . PHP_EOL;
    echo "    php database/migrate.php install" . PHP_EOL;
    echo "    php database/migrate.php fresh" . PHP_EOL;
    echo "    php database/migrate.php seed:User" . PHP_EOL;
    echo PHP_EOL;
    exit(0);
}

// ---------------------------------------------------------------
// 4. Boot the migration runner
// ---------------------------------------------------------------
require_once __DIR__ . '/MigrationRunner.php';

echo PHP_EOL;
echo "  ╔══════════════════════════════════════════════════╗" . PHP_EOL;
echo "  ║   Global Delivered Logistics - Migration Tool   ║" . PHP_EOL;
echo "  ╚══════════════════════════════════════════════════╝" . PHP_EOL;

try {
    $runner = new \Database\MigrationRunner();

    switch ($command) {
        case 'install':
            $runner->install();
            break;

        case 'fresh':
        case 'refresh':
            $runner->fresh();
            break;

        case 'seed':
            $runner->seed($seederName);
            break;

        case 'migrate':
            $runner->migrate();
            break;

        case 'rollback':
            $runner->rollback();
            break;

        case 'status':
            $runner->status();
            break;

        default:
            echo PHP_EOL . "  ❌  Unknown command: {$command}" . PHP_EOL;
            echo "  💡  Run php database/migrate.php --help for usage." . PHP_EOL . PHP_EOL;
            exit(1);
    }
} catch (\Exception $e) {
    echo PHP_EOL . "  ❌  Fatal error: " . $e->getMessage() . PHP_EOL;
    echo "  📍  " . $e->getFile() . ":" . $e->getLine() . PHP_EOL . PHP_EOL;
    exit(1);
}
