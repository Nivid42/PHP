<?php

/**
 * Class Database
 *
 * Lightweight standalone PDO connection manager.
 * 
 * Features:
 * - Singleton connection pattern
 * - Optional PDO factory (for testing)
 * - Configurable PDO attributes
 * - Exception chaining for better debugging
 */
class Database
{
    /** @var \PDO|null Singleton PDO instance */
    private static ?\PDO $pdo = null;

    /** @var callable|null Custom PDO factory (e.g. for testing) */
    private static $pdoFactory = null;

    /** @var array Default PDO attributes */
    private static array $pdoOptions = [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_EMULATE_PREPARES => false,
    ];

    /** @var callable|null Optional callback executed after successful connection */
    private static $onConnect = null;

    /**
     * Sets a custom PDO factory for testing or dependency injection.
     *
     * @param callable|null $factory
     */
    public static function setPdoFactory(?callable $factory): void
    {
        self::$pdoFactory = $factory;
        self::$pdo = null; // force rebuild on next getConnection()
    }

    /**
     * Sets custom PDO attributes (e.g., fetch mode, error mode).
     *
     * @param array $options
     */
    public static function setPdoOptions(array $options): void
    {
        self::$pdoOptions = $options;
        self::$pdo = null; // force rebuild
    }

    /**
     * Registers a callback that runs after a successful connection.
     *
     * @param callable|null $callback
     */
    public static function onConnect(?callable $callback): void
    {
        self::$onConnect = $callback;
    }

    /**
     * Returns a singleton PDO connection.
     *
     * Reads configuration from environment variables:
     * DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS
     *
     * @return \PDO
     * @throws \RuntimeException If connection fails
     */
    public static function getConnection(): \PDO
    {
        if (self::$pdo === null) {
            $host = getenv('DB_HOST') ?: 'localhost';
            $port = getenv('DB_PORT') ?: 3306;
            $dbname = getenv('DB_NAME');
            $user = getenv('DB_USER');
            $pass = getenv('DB_PASS');

            if (!$dbname || !$user) {
                throw new \RuntimeException('Database configuration incomplete (check environment variables).');
            }

            $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

            try {
                if (self::$pdoFactory !== null) {
                    self::$pdo = call_user_func(self::$pdoFactory, $dsn, $user, $pass, self::$pdoOptions);
                } else {
                    self::$pdo = new \PDO($dsn, $user, $pass, self::$pdoOptions);
                }

                if (self::$onConnect) {
                    call_user_func(self::$onConnect, self::$pdo);
                }
            } catch (\PDOException $e) {
                throw new \RuntimeException(
                    "Database connection failed ({$e->getCode()}): " . $e->getMessage(),
                    0,
                    $e
                );
            }
        }

        return self::$pdo;
    }

    /**
     * Forces the current PDO instance to be closed.
     * (useful for testing or resetting connections)
     */
    public static function reset(): void
    {
        self::$pdo = null;
    }
}
