<?php
/**
 * Global Delivered Logistics - Database Singleton
 * 
 * Enterprise database connection using PDO with prepared statements,
 * connection pooling support, and error handling.
 */

namespace App\Core;

class Database
{
    private static ?Database $instance = null;
    private \PDO $pdo;
    private int $queryCount = 0;
    private array $log = [];

    private function __construct()
    {
        $config = $this->loadConfig();
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );

        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
            \PDO::ATTR_EMULATE_PREPARES   => false,
            \PDO::ATTR_PERSISTENT         => false,
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$config['charset']} COLLATE {$config['collation']}",
        ];

        try {
            $this->pdo = new \PDO($dsn, $config['username'], $config['password'], $options);
        } catch (\PDOException $e) {
            if (defined('APP_DEBUG') && APP_DEBUG) {
                throw new \RuntimeException("Database connection failed: " . $e->getMessage());
            }
            throw new \RuntimeException("Database connection failed. Please try again later.");
        }
    }

    /**
     * Prevent cloning
     */
    private function __clone() {}

    /**
     * Get singleton instance
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Load database configuration
     */
    private function loadConfig(): array
    {
        $configFile = dirname(__DIR__, 2) . '/config/database.php';
        if (!file_exists($configFile)) {
            return [
                'host'      => getenv('DB_HOST') ?: '127.0.0.1',
                'port'      => getenv('DB_PORT') ?: '3306',
                'database'  => getenv('DB_DATABASE') ?: 'globaldelivered',
                'username'  => getenv('DB_USERNAME') ?: 'root',
                'password'  => getenv('DB_PASSWORD') ?: '',
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ];
        }

        $config = require $configFile;
        return $config['connections']['mysql'] ?? $config['connections'][$config['default'] ?? 'mysql'];
    }

    /**
     * Get PDO connection
     */
    public function getConnection(): \PDO
    {
        return $this->pdo;
    }

    /**
     * Prepare a SQL statement
     */
    public function prepare(string $sql): \PDOStatement
    {
        $this->queryCount++;
        return $this->pdo->prepare($sql);
    }

    /**
     * Execute a query with parameters
     */
    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->prepare($sql);
        $stmt->execute($params);
        
        if (defined('APP_DEBUG') && APP_DEBUG) {
            $this->log[] = [
                'sql' => $sql,
                'params' => $params,
                'time' => microtime(true),
            ];
        }
        
        return $stmt;
    }

    /**
     * Fetch all rows as objects
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * Fetch a single row as object
     */
    public function fetch(string $sql, array $params = [])
    {
        return $this->query($sql, $params)->fetch();
    }

    /**
     * Fetch a single column value
     */
    public function fetchColumn(string $sql, array $params = []): mixed
    {
        return $this->query($sql, $params)->fetchColumn();
    }

    /**
     * Get last inserted ID
     */
    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Begin transaction
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Commit transaction
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * Rollback transaction
     */
    public function rollback(): bool
    {
        return $this->pdo->rollBack();
    }

    /**
     * Get query count
     */
    public function getQueryCount(): int
    {
        return $this->queryCount;
    }

    /**
     * Get query log
     */
    public function getQueryLog(): array
    {
        return $this->log;
    }

    /**
     * Paginate results
     */
    public function paginate(string $countSql, string $dataSql, array $params = [], int $page = 1, int $perPage = 25): object
    {
        $total = (int) $this->fetchColumn($countSql, $params);
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = max(1, min($page, $totalPages));
        $offset = ($page - 1) * $perPage;
        
        $dataSql .= " LIMIT " . (int)$perPage . " OFFSET " . (int)$offset;
        $data = $this->fetchAll($dataSql, $params);
        
        return (object) [
            'data'        => $data,
            'total'       => $total,
            'page'        => $page,
            'perPage'     => $perPage,
            'totalPages'  => $totalPages,
            'from'        => $offset + 1,
            'to'          => min($offset + $perPage, $total),
            'hasMore'     => $page < $totalPages,
            'hasPrevious' => $page > 1,
        ];
    }

    /**
     * Reset singleton (useful for testing)
     */
    public static function reset(): void
    {
        self::$instance = null;
    }
}
