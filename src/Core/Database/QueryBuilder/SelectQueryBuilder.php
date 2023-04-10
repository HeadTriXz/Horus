<?php

namespace Horus\Core\Database\QueryBuilder;

use Horus\Core\Database\Database;
use Horus\Core\Database\Model;
use Horus\Core\Database\Traits\LimitClause;
use Horus\Core\Database\Traits\OrderByClause;
use Horus\Core\Database\Traits\WhereClause;
use InvalidArgumentException;
use stdClass;

/**
 * Builds a SELECT query to retrieve rows from the database.
 */
class SelectQueryBuilder
{
    use LimitClause;
    use OrderByClause;
    use WhereClause;

    protected string $columns = "*";
    protected ?string $groupBy;
    protected ?string $having;
    protected string $joins = "";
    protected ?string $offset;
    protected string $tableName;

    /**
     * Builds SELECT query to retrieve rows from the database.
     *
     * @param ?Database $database The database instance to use for queries.
     * @param ?string $model The model class to use for fetching query results into objects.
     */
    public function __construct(
        protected ?Database $database = null,
        protected ?string $model = null
    ) {
        if (!empty($model) && class_exists($model) && is_subclass_of($model, Model::class)) {
            $this->tableName = $model::getTableName();
        }
    }

    /**
     * Adds `AND HAVING` to the query. If you haven't previously defined `HAVING`,
     * the function will use that instead.
     *
     * @param string $condition The condition to add.
     * @param string ...$params Optional parameters for the condition.
     * @return $this
     */
    public function andHaving(string $condition, string ...$params): self
    {
        if (!$this->having) {
            return $this->having($condition, ...$params);
        }

        $this->params = array_merge($this->params, $params);
        $this->having .= " AND $condition";
        return $this;
    }

    /**
     * Sets the name of the table to select from.
     *
     * @param string $table The name of the table or the model.
     * @param ?string $alias An optional alias for the table.
     * @return $this
     */
    public function from(string $table, string $alias = null): self
    {
        if (class_exists($table) && is_subclass_of($table, Model::class)) {
            $this->model = $table;
            $table = $table::getTableName();
        }

        $this->tableName = $table;

        if (!empty($alias)) {
            $this->tableName .= " $alias";
        }

        return $this;
    }

    /**
     * Executes the query and returns all rows as an array of objects.
     *
     * @return array The rows as an array of objects.
     */
    public function getAll(): array
    {
        if (!isset($this->database)) {
            throw new InvalidArgumentException("Database is required, but not provided.");
        }

        return $this->database->query($this->getQuery(), $this->params, $this->model);
    }

    /**
     * Executes the query and returns the first row as an object.
     *
     * @return stdClass | Model | null An object representing the first row returned by the query, or null if there are no rows.
     */
    public function getOne(): stdClass | Model | null
    {
        if (!isset($this->database)) {
            throw new InvalidArgumentException("Database is required, but not provided.");
        }

        return $this->database->queryOne($this->getQuery(), $this->params, $this->model);
    }

    /**
     * Builds the complete query by concatenating all the parts and returns it as a string.
     *
     * @return string The complete query as a string.
     */
    public function getQuery(): string
    {
        return "SELECT $this->columns FROM $this->tableName"
            . (!empty($this->joins) ? " $this->joins" : "")
            . (!empty($this->where) ? " $this->where" : "")
            . (!empty($this->groupBy) ? " $this->groupBy" : "")
            . (!empty($this->having) ? " $this->having" : "")
            . (!empty($this->orderBy) ? " $this->orderBy" : "")
            . (isset($this->limit) ? " $this->limit" : "")
            . (isset($this->offset) ? " $this->offset" : "");
    }

    /**
     * Executes the query and returns the amount of rows found.
     *
     * @return int The amount of rows.
     */
    public function getRowCount(): int
    {
        if (!isset($this->database)) {
            throw new InvalidArgumentException("Database is required, but not provided.");
        }

        return $this->database->execute($this->getQuery(), $this->params);
    }

    /**
     * Sets the `GROUP BY` clause of the query.
     *
     * @param string | array $group The column name or array of column names to group the query result by.
     * @return $this
     */
    public function groupBy(string | array $group): self
    {
        $this->groupBy = "GROUP BY " . (is_string($group)
            ? $group : implode(", ", $group));
        return $this;
    }

    /**
     * Sets the `HAVING` clause of the query. If you previously defined `HAVING`,
     * the function will overwrite it.
     *
     * @param string $condition The condition to add.
     * @param string ...$params Optional parameters for the condition.
     * @return $this
     */
    public function having(string $condition, string ...$params): self
    {
        $this->params = array_merge($this->params, $params);
        $this->having = "HAVING $condition";
        return $this;
    }

    /**
     * Adds an `INNER JOIN` to the query.
     *
     * @param string $table The name of the table to join.
     * @param string $alias The alias of the table.
     * @param ?string $condition An optional condition for the join.
     * @param string ...$params Optional parameters for the condition.
     * @return $this
     */
    public function innerJoin(string $table, string $alias, string $condition = null, string ...$params): self
    {
        return $this->join("INNER JOIN", $table, $alias, $condition, ...$params);
    }

    /**
     * Adds a join to the query.
     *
     * @param string $type The type of join. (INNER JOIN, LEFT JOIN, or RIGHT JOIN)
     * @param string $table The name of the table to join.
     * @param string $alias The alias of the table.
     * @param ?string $condition An optional condition for the join.
     * @param string ...$params Optional parameters for the condition.
     * @return $this
     */
    protected function join(
        string $type,
        string $table,
        string $alias,
        string $condition = null,
        string ...$params
    ): self {
        $this->params = array_merge($this->params, $params);
        $this->joins .= "$type $table ";
        if (!empty($alias)) {
            $this->joins .= "$alias ";
        }

        $this->joins .= "ON $condition ";
        return $this;
    }

    /**
     * Adds an `LEFT JOIN` to the query.
     *
     * @param string $table The name of the table to join.
     * @param string $alias The alias of the table.
     * @param ?string $condition An optional condition for the join.
     * @param string ...$params Optional parameters for the condition.
     * @return $this
     */
    public function leftJoin(string $table, string $alias, string $condition = null, string ...$params): self
    {
        return $this->join("LEFT JOIN", $table, $alias, $condition, ...$params);
    }

    /**
     * Sets the `OFFSET` clause of the query.
     *
     * @param int $offset The offset for the query.
     * @return $this
     */
    public function offset(int $offset): self
    {
        $this->offset = "OFFSET $offset";
        return $this;
    }

    /**
     * Adds `OR HAVING` to the query. If you haven't previously defined `HAVING`,
     * the function will use that instead.
     *
     * @param string $condition The condition to add.
     * @param string ...$params Optional parameters for the condition.
     * @return $this
     */
    public function orHaving(string $condition, string ...$params): self
    {
        if (empty($this->having)) {
            return $this->having($condition, ...$params);
        }

        $this->params = array_merge($this->params, $params);
        $this->having .= " OR $condition";
        return $this;
    }

    /**
     * Adds an `RIGHT JOIN` to the query.
     *
     * @param string $table The name of the table to join.
     * @param string $alias The alias of the table.
     * @param ?string $condition An optional condition for the join.
     * @param string ...$params Optional parameters for the condition.
     * @return $this
     */
    public function rightJoin(string $table, string $alias, string $condition = null, string ...$params): self
    {
        return $this->join("RIGHT JOIN", $table, $alias, $condition, ...$params);
    }

    /**
     * Selects the columns to retrieve from the database.
     *
     * @param string | array $column The column(s) to select. If an array is provided, each element should be
     * either a string representing the column name, or an associative array containing the "column" key
     * for the column name and optionally the "alias" key for an alias.
     * @param ?string $alias An optional alias for the single column provided as a string.
     * @return $this
     */
    public function select(string | array $column, string $alias = null): self
    {
        if (is_string($column)) {
            $this->columns = $column;
            if (!empty($alias)) {
                $this->columns .= " $alias";
            }
            return $this;
        }

        if (empty($column)) {
            throw new InvalidArgumentException("Column is required, but not provided.");
        }

        if (!empty($alias)) {
            throw new InvalidArgumentException("Column is an array, but received an alias.");
        }

        $result = [];
        if (array_is_list($column)) {
            $result = $column;
        } else {
            foreach ($column as $key => $value) {
                $result[] = "$key $value";
            }
        }

        $this->columns = implode(", ", $result);
        return $this;
    }
}
