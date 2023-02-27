<?php

namespace Horus\Core\Database\Traits;

use InvalidArgumentException;

trait OrderByClause
{
    protected ?string $orderBy;

    /**
     * Sets the `ORDER BY` clause of the query.
     *
     * @param string | array $column The column(s) to order by.
     * @param ?string $order The direction to sort the column(s) in. Accepted values are "ASC" and "DESC".
     * If not provided, the default sorting direction will be used.
     *
     * @throws InvalidArgumentException If the column is not provided.
     * @return $this
     */
    public function orderBy(string | array $column, string $order = null): self
    {
        $this->orderBy = "ORDER BY ";
        if (is_string($column)) {
            $this->orderBy .= $column;
            if (!empty($order)) {
                $this->orderBy .= " $order";
            }
            return $this;
        }

        if (empty($column)) {
            throw new InvalidArgumentException("Column is required, but not provided.");
        }

        $result = [];
        if (array_is_list($column)) {
            if (!empty($order)) {
                foreach ($column as $value) {
                    $result[] = "$value $order";
                }
            } else {
                $result = $column;
            }
        } else {
            foreach ($column as $key => $value) {
                $result[] = "$key $value";
            }
        }

        $this->orderBy .= implode(", ", $result);
        return $this;
    }
}