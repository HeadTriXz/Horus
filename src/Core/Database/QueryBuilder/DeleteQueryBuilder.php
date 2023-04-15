<?php

namespace Horus\Core\Database\QueryBuilder;

use Horus\Core\Database\Database;
use Horus\Core\Database\Traits\LimitClause;
use Horus\Core\Database\Traits\OrderByClause;
use Horus\Core\Database\Traits\WhereClause;
use InvalidArgumentException;

/**
 * Builds a DELETE query to delete rows from the database.
 */
class DeleteQueryBuilder
{
    use LimitClause;
    use OrderByClause;
    use WhereClause;

    /**
     * The name of the table to delete from.
     *
     * @var string
     */
    protected string $tableName;

    /**
     * Builds a DELETE query to delete rows from the database.
     *
     * @param ?Database $database The database instance to use for queries.
     */
    public function __construct(
        protected ?Database $database = null
    ) {}

    /**
     * Executes the query and returns the number of affected rows.
     *
     * @return int The number of affected rows.
     */
    public function execute(): int
    {
        if (!isset($this->database)) {
            throw new InvalidArgumentException("Database is required, but not provided.");
        }

        return $this->database->execute($this->getQuery(), $this->params);
    }

    /**
     * Sets the name of the table to delete from.
     *
     * @param string $table The name of the table.
     * @param ?string $alias An optional alias for the table.
     * @return $this
     */
    public function from(string $table, string $alias = null): self
    {
        $this->tableName = $table;

        if (!empty($alias)) {
            $this->tableName .= " $alias";
        }

        return $this;
    }

    /**
     * Builds the complete query by concatenating all the parts and returns it as a string.
     *
     * @return string The complete query as a string.
     */
    public function getQuery(): string
    {
        return "DELETE FROM $this->tableName"
            . (!empty($this->where) ? " $this->where" : "")
            . (!empty($this->orderBy) ? " $this->orderBy" : "")
            . (isset($this->limit) ? " $this->limit" : "");
    }
}
