<?php

namespace Horus\Core\Database;

use stdClass;

interface DatabaseInterface
{
    /**
     * Begins a new transaction.
     *
     * @return bool Returns true on success or false on failure.
     */
    public function beginTransaction(): bool;

    /**
     * Commits the current transaction.
     *
     * @return bool Returns true on success or false on failure.
     */
    public function commit(): bool;

    /**
     * Executes an SQL statement and returns the number of affected rows.
     *
     * @param string $query The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     *
     * @return int Returns the number of affected rows.
     */
    public function execute(string $query, array $params = []): int;

    /**
     * Get the ID of the last inserted row.
     *
     * @return ?string The ID of the last inserted row.
     */
    public function getLastInsertId(): ?string;

    /**
     * Executes an SQL statement and returns the result set as an array of objects.
     *
     * @param string $query The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @param ?string $model The name of the class to use for the returned objects.
     *
     * @return array Returns an array of objects of the specified class or an empty array on failure.
     */
    public function query(string $query, array $params = [], string $model = null): array;

    /**
     * Executes an SQL statement and returns the first row as an object of the specified class.
     *
     * @param string $query The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @param ?string $model The name of the class to use for the returned object.
     *
     * @return stdClass | Model | null Returns an object of the specified class or null if no rows are returned.
     */
    public function queryOne(string $query, array $params = [], string $model = null): stdClass | Model | null;

    /**
     * Rolls back the current transaction.
     *
     * @return bool Returns true on success or false on failure.
     */
    public function rollback(): bool;
}
