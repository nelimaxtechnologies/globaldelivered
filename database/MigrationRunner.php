<?php
/**
 * Global Delivered Logistics - Database Migration Runner
 *
 * Command-line migration and seeding system.
 * Usage: php database/migrate.php <command> [options]
 *
 * Commands:
 *   install   - Create all tables and run all seeders
 *   fresh     - Drop all tables and re-install
 *   refresh   - Fresh + seed
 *   seed      - Run seeders only
 *   status    - Show migration status
 *   rollback  - Roll back the last batch
 *   migrate   - Run pending migrations
 */

namespace Database;

class MigrationRunner
{
    private \PDO $pdo;
    private string $dbName;
    private array $output = [];
    private string $migrationTable = 'migrations';

    public function __construct()
    {
        $this->connect();
    }

    /**
     * Connect to MySQL (creates the database if it doesn't exist)
     */
    private function connect(): void
    {
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $port = getenv('DB_PORT') ?: '3306';
        $dbName = getenv('DB_DATABASE') ?: 'globaldelivered';
        $user = getenv('DB_USERNAME') ?: 'root';
        $pass = getenv('DB_PASSWORD') ?: '';

        $this->dbName = $dbName;

        try {
            // Connect without database to create it if needed
            $dsn = sprintf('mysql:host=%s;port=%s;charset=utf8mb4', $host, $port);
            $this->pdo = new \PDO($dsn, $user, $pass, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
            ]);

            $this->pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` 
                              CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->pdo->exec("USE `{$dbName}`");

            $this->line("✓ Connected to MySQL, using database: {$dbName}");
        } catch (\PDOException $e) {
            $this->error("Database connection failed: " . $e->getMessage());
            exit(1);
        }
    }

    /**
     * Run the full install (create tables + seed)
     */
    public function install(): void
    {
        $this->heading('INSTALL: Creating tables and seeding data');
        $this->runSchema();
        $this->runSeeders();
        $this->success('Install complete!');
    }

    /**
     * Drop all tables and re-install
     */
    public function fresh(): void
    {
        $this->heading('FRESH: Dropping all tables');
        $this->dropAllTables();
        $this->line('All tables dropped.');
        $this->install();
    }

    /**
     * Fresh + seed (convenience)
     */
    public function refresh(): void
    {
        $this->fresh();
        $this->success('Refresh complete!');
    }

    /**
     * Run seeders only
     */
    public function seed(string $seeder = ''): void
    {
        $this->heading('SEED: Running database seeders');
        $this->runSeeders($seeder);
        $this->success('Seeding complete!');
    }

    /**
     * Show current migration status
     */
    public function status(): void
    {
        $this->heading('MIGRATION STATUS');

        $this->ensureMigrationTable();

        $applied = $this->pdo->query("SELECT * FROM {$this->migrationTable} ORDER BY batch, id")->fetchAll();

        if (empty($applied)) {
            $this->line('  No migrations have been applied yet.');
        } else {
            $this->line(sprintf("  %-40s %-12s %-10s", 'Migration', 'Applied At', 'Batch'));
            $this->line(str_repeat('-', 65));
            foreach ($applied as $m) {
                $this->line(sprintf("  %-40s %-12s %-10s", $m->migration, $m->applied_at ?? '', $m->batch));
            }
        }

        // Check for pending migrations
        $files = glob(__DIR__ . '/migrations/*.sql');
        $fileNames = array_map(fn($f) => basename($f), $files);
        $appliedNames = array_map(fn($m) => $m->migration, $applied);
        $pending = array_diff($fileNames, $appliedNames);

        if (!empty($pending)) {
            $this->line('');
            $this->line('  Pending migrations:');
            foreach ($pending as $p) {
                $this->line("    - {$p}");
            }
        } else {
            $this->line('');
            $this->line('  ✓ All migrations are applied.');
        }
    }

    /**
     * Run pending migrations
     */
    public function migrate(): void
    {
        $this->heading('MIGRATE: Running pending migrations');
        $this->ensureMigrationTable();

        $files = glob(__DIR__ . '/migrations/*.sql');
        natsort($files);

        $applied = $this->pdo->query("SELECT migration FROM {$this->migrationTable}")->fetchAll(\PDO::FETCH_COLUMN);

        // Get the next batch number
        $maxBatch = (int) $this->pdo->query("SELECT COALESCE(MAX(batch), 0) FROM {$this->migrationTable}")->fetchColumn();
        $batch = $maxBatch + 1;

        $count = 0;
        foreach ($files as $file) {
            $filename = basename($file);

            if (in_array($filename, $applied)) {
                $this->line("  ~ {$filename} (already applied)");
                continue;
            }

            $this->line("  → {$filename} ... ", false);
            try {
                $sql = file_get_contents($file);
                // Split into individual statements for safety
                $statements = explode(';', $sql);
                foreach ($statements as $statement) {
                    $statement = trim($statement);
                    if (!empty($statement)) {
                        $this->pdo->exec($statement);
                    }
                }

                $stmt = $this->pdo->prepare(
                    "INSERT INTO {$this->migrationTable} (migration, batch, applied_at) VALUES (?, ?, NOW())"
                );
                $stmt->execute([$filename, $batch]);

                $this->line('✓', true);
                $count++;
            } catch (\PDOException $e) {
                $this->line('✗', true);
                $this->error("  Migration failed: " . $e->getMessage());
                exit(1);
            }
        }

        if ($count === 0) {
            $this->line('  Nothing to migrate.');
        } else {
            $this->success("Applied {$count} migration(s) in batch #{$batch}");
        }
    }

    /**
     * Rollback the last batch of migrations
     */
    public function rollback(): void
    {
        $this->heading('ROLLBACK: Reverting last batch');

        $maxBatch = (int) $this->pdo->query("SELECT COALESCE(MAX(batch), 0) FROM {$this->migrationTable}")->fetchColumn();

        if ($maxBatch === 0) {
            $this->line('  Nothing to roll back.');
            return;
        }

        $migrations = $this->pdo->query(
            "SELECT * FROM {$this->migrationTable} WHERE batch = {$maxBatch} ORDER BY id DESC"
        )->fetchAll();

        // For each migration, try to reverse it (simple approach: drop reversed tables)
        // In a more advanced system we'd have down() methods
        $this->line("  Reverting batch #{$maxBatch} with " . count($migrations) . " migration(s)");

        foreach ($migrations as $m) {
            $this->line("  ← {$m->migration}");

            $stmt = $this->pdo->prepare("DELETE FROM {$this->migrationTable} WHERE id = ?");
            $stmt->execute([$m->id]);
        }

        $this->line("  Removed " . count($migrations) . " migration record(s).");
        $this->success("Rollback complete. Run 'fresh' to fully reset the database.");
    }

    // ---------------------------------------------------------------
    // Internal helpers
    // ---------------------------------------------------------------

    /**
     * Run the full schema (initial migration)
     */
    private function runSchema(): void
    {
        $schemaFile = __DIR__ . '/schema.sql';

        if (!file_exists($schemaFile)) {
            $this->error("Schema file not found: {$schemaFile}");
            exit(1);
        }

        $this->line('');
        $this->line('  Creating tables...');

        try {
            $sql = file_get_contents($schemaFile);

            // Split into individual statements and execute each one
            // (safer than PDO::exec() with multi-statement strings)
            $statements = explode(';', $sql);
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement)) {
                    $this->pdo->exec($statement);
                }
            }
            $this->line('  ✓ All tables created successfully.');

            // Record the migration
            $this->ensureMigrationTable();
            $exists = $this->pdo->query(
                "SELECT COUNT(*) FROM {$this->migrationTable} WHERE migration = '001_create_tables.sql'"
            )->fetchColumn();
            if (!$exists) {
                $stmt = $this->pdo->prepare(
                    "INSERT INTO {$this->migrationTable} (migration, batch, applied_at) VALUES (?, 1, NOW())"
                );
                $stmt->execute(['001_create_tables.sql']);
            }

        } catch (\PDOException $e) {
            $this->error("Schema creation failed: " . $e->getMessage());
            exit(1);
        }
    }

    /**
     * Run all seeders
     */
    private function runSeeders(string $specific = ''): void
    {
        $seederDir = __DIR__ . '/seeders';
        
        // Define seeder ordering to respect dependency chains
        $order = [
            '001_RoleSeeder',
            '002_StatusSeeder',
            '003_SettingsSeeder',
            '004_UserSeeder',
            '005_BranchSeeder',
            '006_ShippingSeeder',
            '007_SampleShipmentSeeder',
        ];
        
        // If a specific seeder was requested, only run that one
        if (!empty($specific)) {
            $order = array_filter($order, fn($name) => str_contains($name, $specific));
            if (empty($order)) {
                // Also try glob for partial match
                $files = glob($seederDir . '/*' . $specific . '*Seeder.php');
                foreach ($files as $file) {
                    $order[] = basename($file, '.php');
                }
            }
        }

        foreach ($order as $className) {
            $file = $seederDir . '/' . $className . '.php';
            
            if (!file_exists($file)) {
                $this->line("  ~ {$className}: file not found, skipping.");
                continue;
            }

            require_once $file;

            // Strip numeric prefix to get actual class name (e.g. 001_RoleSeeder -> RoleSeeder)
            $actualClassName = preg_replace('/^\d+_/', '', $className);
            $fqcn = "Database\\Seeders\\{$actualClassName}";

            if (!class_exists($fqcn)) {
                $this->line("  ~ {$actualClassName}: class not found, skipping.");
                continue;
            }

            $this->line("  → {$className} ... ", false);
            try {
                $seeder = new $fqcn($this->pdo);
                $seeder->run();
                $this->line('✓', true);
            } catch (\PDOException $e) {
                if ($e->getCode() == 23000) {
                    // Duplicate entry - skip gracefully (already seeded)
                    $this->line('✓ (already exists)', true);
                } else {
                    $this->line('✗', true);
                    $this->line("    Error: " . $e->getMessage());
                }
            } catch (\Exception $e) {
                $this->line('✗', true);
                $this->line("    Error: " . $e->getMessage());
            }
        }
    }

    /**
     * Ensure the migrations tracking table exists
     */
    private function ensureMigrationTable(): void
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS `{$this->migrationTable}` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `migration` VARCHAR(255) NOT NULL,
                `batch` INT UNSIGNED NOT NULL DEFAULT 1,
                `applied_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY `idx_migration_name` (`migration`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    /**
     * Drop all tables in the database
     */
    private function dropAllTables(): void
    {
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

        $tables = $this->pdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            if ($table === $this->migrationTable) {
                continue; // Keep the migration table for tracking
            }
            $this->pdo->exec("DROP TABLE IF EXISTS `{$table}`");
        }

        // Also drop migration table to start fresh
        $this->pdo->exec("DROP TABLE IF EXISTS `{$this->migrationTable}`");

        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    }

    // ---------------------------------------------------------------
    // Output helpers
    // ---------------------------------------------------------------

    private function line(string $text, bool $inline = false): void
    {
        if ($inline) {
            echo $text;
        } else {
            echo $text . PHP_EOL;
        }
        $this->output[] = $text;
    }

    private function heading(string $text): void
    {
        echo PHP_EOL . str_repeat('=', 70) . PHP_EOL;
        echo "  {$text}" . PHP_EOL;
        echo str_repeat('=', 70) . PHP_EOL;
    }

    private function success(string $text): void
    {
        echo PHP_EOL . " ✅  {$text}" . PHP_EOL . PHP_EOL;
    }

    private function error(string $text): void
    {
        echo PHP_EOL . " ❌  {$text}" . PHP_EOL . PHP_EOL;
    }
}
