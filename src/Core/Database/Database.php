<?php

namespace Horus\Core\Database;

use PDO;

/**
 * The Database class provides an interface to interact with the database using PDO.
 */
class Database
{
    private PDO $connection;

    /**
     * The Database class provides an interface to interact with the database using PDO.
     */
    public function __construct()
    {
        $dsn = "mysql:host=" . getenv("DB_HOST") . ";port=" . getenv("DB_PORT") . ";dbname=" . getenv("DB_NAME");
        $this->connection = new PDO($dsn, getenv("DB_USERNAME"), getenv("DB_PASSWORD"), [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    /**
     * Begins a new transaction.
     *
     * @return bool Returns true on success or false on failure.
     */
    public function beginTransaction(): bool
    {
        return $this->connection->beginTransaction();
    }

    /**
     * Commits the current transaction.
     *
     * @return bool Returns true on success or false on failure.
     */
    public function commit(): bool
    {
        return $this->connection->commit();
    }

    /**
     * Executes an SQL statement and returns the number of affected rows.
     *
     * @param string $query The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     *
     * @return int Returns the number of affected rows.
     */
    public function execute(string $query, array $params = []): int
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        return $stmt->rowCount();
    }

    /**
     * Executes an SQL statement and returns the result set as an array of objects.
     *
     * @param string $query The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @param ?string $model The name of the class to use for the returned objects.
     *
     * @return array Returns an array of objects of the specified class or an empty array on failure.
     */
    public function query(string $query, array $params = [], string $model = null): array
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_CLASS, $model);
    }

    /**
     * Executes an SQL statement and returns the first row as an object of the specified class.
     *
     * @param string $query The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @param ?string $model The name of the class to use for the returned object.
     *
     * @return ?Model Returns an object of the specified class or null if no rows are returned.
     */
    public function queryOne(string $query, array $params = [], string $model = null): ?Model
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchObject($model);
    }

    /**
     * Rolls back the current transaction.
     *
     * @return bool Returns true on success or false on failure.
     */
    public function rollback(): bool
    {
        return $this->connection->rollBack();
    }
}
