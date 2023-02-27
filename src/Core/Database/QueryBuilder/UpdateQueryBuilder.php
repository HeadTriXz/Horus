<?php

namespace Horus\Core\Database\QueryBuilder;

use Horus\Core\Database\Database;
use Horus\Core\Database\Traits\LimitClause;
use Horus\Core\Database\Traits\OrderByClause;
use Horus\Core\Database\Traits\WhereClause;
use InvalidArgumentException;
use LogicException;

/**
 * Builds an UPDATE query to update rows of the database.
 */
class UpdateQueryBuilder
{
    use LimitClause;
    use OrderByClause;
    use WhereClause;

    protected array $columns = [];
    protected string $tableName;

    /**
     * Builds an UPDATE query to update rows of the database.
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

        return $this->database->execute($this->getQuery(), array_merge(...$this->params));
    }

    /**
     * Builds the complete query by concatenating all the parts and returns it as a string.
     *
     * @return string The complete query as a string.
     */
    public function getQuery(): string
    {
        if (empty($this->columns)) {
            throw new LogicException("No values provided.");
        }

        $placeholders = implode(", ", array_map(fn($col) => "$col = ?", $this->columns));

        return "UPDATE $this->tableName SET $placeholders"
            . (!empty($this->where) ? " $this->where" : "")
            . (!empty($this->orderBy) ? " $this->orderBy" : "")
            . (isset($this->limit) ? " $this->limit" : "");
    }

    /**
     * Specifies the values to update.
     *
     * @param array $values The values to update.
     *
     * @throws InvalidArgumentException if the values array is not valid.
     * @return $this
     */
    public function set(array $values): self
    {
        if (array_is_list($values)) {
            throw new InvalidArgumentException("Argument must be an associative array.");
        }

        $this->columns = array_merge($this->columns, array_keys($values));
        $this->params = array_merge($this->params, array_values($values));
        return $this;
    }

    /**
     * Specifies the table to update.
     *
     * @param string $table The name of the table to update.
     * @return $this
     */
    public function update(string $table): self
    {
        $this->tableName = $table;
        return $this;
    }
}