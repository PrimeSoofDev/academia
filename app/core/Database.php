<?php
/**
 * Database.php — PDO Singleton Connection
 *
 * Provides a single shared PDO connection across the application.
 * Uses prepared statements for all queries.
 */

class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    /**
     * Private constructor — loads config and establishes PDO connection.
     */
    private function __construct()
    {
        $config = require ROOT_PATH . '/config/database.php';

        $dsn = sprintf(
            '%s:host=%s;port=%s;dbname=%s;charset=%s',
            $config['driver'],
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );

        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
        } catch (PDOException $e) {
            // In production, log this and show a friendly error page
            error_log('DB Connection Failed: ' . $e->getMessage());
            die(json_encode(['error' => 'Database connection failed. Please try again later.']));
        }
    }

    /**
     * Singleton getter — returns the single Database instance.
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Returns the raw PDO object.
     */
    public function pdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * Prepare and execute a query with optional bindings.
     *
     * @param string $sql
     * @param array  $bindings
     * @return PDOStatement
     */
    public function query(string $sql, array $bindings = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);
        return $stmt;
    }

    /**
     * Fetch a single row.
     */
    public function fetchOne(string $sql, array $bindings = []): array|false
    {
        return $this->query($sql, $bindings)->fetch();
    }

    /**
     * Fetch all rows.
     */
    public function fetchAll(string $sql, array $bindings = []): array
    {
        return $this->query($sql, $bindings)->fetchAll();
    }

    /**
     * Get the ID of the last inserted row.
     */
    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Begin a transaction.
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Commit a transaction.
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * Roll back a transaction.
     */
    public function rollBack(): bool
    {
        return $this->pdo->rollBack();
    }

    // Prevent cloning and unserialization of the singleton
    private function __clone() {}
    public function __wakeup() { throw new \Exception('Cannot unserialize singleton'); }
}
