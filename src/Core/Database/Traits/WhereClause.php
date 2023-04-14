<?php

namespace Horus\Core\Database\Traits;

trait WhereClause
{
    protected array $params = [];
    protected ?string $where;

    /**
     * Adds `AND WHERE` to the query. If you haven't previously defined `WHERE`,
     * the function will use that instead.
     *
     * @param string $condition The condition to add.
     * @param string ...$params Optional parameters for the condition.
     * @return $this
     */
    public function andWhere(string $condition, string ...$params): self
    {
        if (empty($this->where)) {
            return $this->where($condition, ...$params);
        }

        $this->params = array_merge($this->params, $params);
        $this->where .= " AND $condition";
        return $this;
    }

    /**
     * Adds `OR WHERE` to the query. If you haven't previously defined `WHERE`,
     * the function will use that instead.
     *
     * @param string $condition The condition to add.
     * @param string ...$params Optional parameters for the condition.
     * @return $this
     */
    public function orWhere(string $condition, string ...$params): self
    {
        if (empty($this->where)) {
            return $this->where($condition, ...$params);
        }

        $this->params = array_merge($this->params, $params);
        $this->where .= " OR $condition";
        return $this;
    }

    /**
     * Sets the `WHERE` clause of the query. If you previously defined `WHERE`,
     * the function will overwrite it.
     *
     * @param string $condition The condition to add.
     * @param string ...$params Optional parameters for the condition.
     * @return $this
     */
    public function where(string $condition, string ...$params): self
    {
        $this->params = array_merge($this->params, $params);
        $this->where = "WHERE $condition";
        return $this;
    }
}
