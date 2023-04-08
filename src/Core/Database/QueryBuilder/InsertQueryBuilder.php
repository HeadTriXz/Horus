<?php

namespace Horus\Core\Database\QueryBuilder;

use Horus\Core\Database\Database;
use InvalidArgumentException;
use LogicException;

/**
 * Builds an INSERT query to insert rows into the database.
 */
class InsertQueryBuilder
{
    protected array $columns = [];
    protected array $params = [];
    protected array $update = [];
    protected string $tableName;
    protected int $rowCount = 0;

    /**
     * Builds INSERT query to insert rows into the database.
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

        $params = array_merge($this->params, array_values($this->update));
        return $this->database->execute($this->getQuery(), $params);
    }

    /**
     * Builds the complete query by concatenating all the parts and returns it as a string.
     *
     * @throws LogicException if no values are provided.
     * @return string The complete query as a string.
     */
    public function getQuery(): string
    {
        if (empty($this->columns)) {
            throw new LogicException("No values provided.");
        }

        $columns = implode(", ", $this->columns);
        $values = implode(", ", array_fill(0, $this->rowCount,
            "(" . implode(", ", array_fill(0, count($this->columns), "?")) . ")"
        ));

        $query = "INSERT INTO $this->tableName ($columns) VALUES $values";
        if (!empty($this->update)) {
            $query .= " ON DUPLICATE KEY UPDATE " . implode(" = ?, ", array_keys($this->update)) . " = ?";
        }

        return $query;
    }

    /**
     * Specifies the table to insert into.
     *
     * @param string $table The name of the table to insert into.
     * @return $this
     */
    public function into(string $table): self
    {
        $this->tableName = $table;
        return $this;
    }

    /**
     * Specifies the values to update on duplicate.
     *
     * @param array $values The values to update.
     *
     * @throws InvalidArgumentException if the values array is not valid.
     * @return $this
     */
    public function orUpdate(array $values): self
    {
        if (array_is_list($values)) {
            throw new InvalidArgumentException("Argument must be an associative array.");
        }

        $this->update = $values;
        return $this;
    }

    /**
     * Specifies the values to insert.
     *
     * @param array $values The values to insert.
     *
     * @throws InvalidArgumentException if the values array is not valid.
     * @return $this
     */
    public function values(array $values): self
    {
        if (array_is_list($values)) {
            foreach ($values as $value) {
                if (is_array($value)) {
                    $this->values($value);
                    continue;
                }

                throw new InvalidArgumentException("Must be an (array of) associative arrays.");
            }
        } else {
            if (!empty($this->columns)) {
                if (count($this->columns) !== count($values)) {
                    throw new InvalidArgumentException("Inconsistent usage of columns.");
                }

                foreach ($this->columns as $key) {
                    if (array_key_exists($key, $values)) {
                        continue;
                    }

                    throw new InvalidArgumentException("Inconsistent usage of columns.");
                }
            }

            $this->columns = array_keys($values);
            $this->params = array_merge($this->params, array_values($values));
            $this->rowCount++;
        }

        return $this;
    }
}
