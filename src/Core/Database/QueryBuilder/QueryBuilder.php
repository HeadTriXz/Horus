<?php

namespace Horus\Core\Database\QueryBuilder;

use Horus\Core\Database\Database;
use Horus\Core\Database\Model;

/**
 * A class for building SQL queries.
 */
class QueryBuilder
{
    /**
     * A class for building SQL queries.
     *
     * @param ?Database $database An optional instance of a Database object.
     * @param ?string $model An optional name of a model class to be used for automatic table name selection.
     */
    public function __construct(
        protected ?Database $database = null,
        protected ?string $model = null
    ) {}

    /**
     * Creates a DELETE query.
     *
     * @return DeleteQueryBuilder
     */
    public function delete(): DeleteQueryBuilder
    {
        $qb = new DeleteQueryBuilder($this->database);
        if (!empty($this->model) && class_exists($this->model) && is_subclass_of($this->model, Model::class)) {
            $qb->from($this->model::getTableName());
        }

        return $qb;
    }

    /**
     * Creates an INSERT query.
     *
     * @return InsertQueryBuilder
     */
    public function insert(): InsertQueryBuilder
    {
        $qb = new InsertQueryBuilder($this->database);
        if (!empty($this->model) && class_exists($this->model) && is_subclass_of($this->model, Model::class)) {
            $qb->into($this->model::getTableName());
        }

        return $qb;
    }

    /**
     * Creates an UPDATE query.
     *
     * @param ?string $table The name of the table to update.
     * @return UpdateQueryBuilder
     */
    public function update(string $table = null): UpdateQueryBuilder
    {
        $qb = new UpdateQueryBuilder($this->database);
        if ($table) {
            $qb->update($table);
        } elseif (!empty($this->model) && class_exists($this->model) && is_subclass_of($this->model, Model::class)) {
            $qb->update($this->model::getTableName());
        }

        return $qb;
    }

    /**
     * Creates a SELECT query and selects given data.
     *
     * @param string | array $column The column(s) to select. If an array is provided, each element should be
     *      either a string representing the column name, or an associative array containing the "column" key
     *      for the column name and optionally the "alias" key for an alias.
     * @param ?string $alias An optional alias for the single column provided as a string.
     * @return SelectQueryBuilder
     */
    public function select(string | array $column = "*", string $alias = null): SelectQueryBuilder
    {
        return (new SelectQueryBuilder($this->database, $this->model))
            ->select($column, $alias);
    }
}
