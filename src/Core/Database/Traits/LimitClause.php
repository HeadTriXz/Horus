<?php

namespace Horus\Core\Database\Traits;

trait LimitClause
{
    protected ?string $limit;

    /**
     * Sets the `LIMIT` clause of the query. This will limit the amount of rows to be selected.
     *
     * @param int $limit The maximum amount of rows to select.
     * @return $this
     */
    public function limit(int $limit): self
    {
        $this->limit = "LIMIT $limit";
        return $this;
    }
}