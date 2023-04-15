<?php

namespace Horus;

use Horus\Core\Database\Model;
use Horus\Core\Database\QueryBuilder\SelectQueryBuilder;
use Horus\Core\Http\Message\ServerRequestInterface;

class Utils
{
    /**
     * Gets the selected row using the query parameters.
     *
     * @param string $key The key of the query parameter.
     * @param array $rows The rows in the database.
     * @param ServerRequestInterface $request The received request.
     * @return ?Model
     */
    public static function getSelected(string $key, array $rows, ServerRequestInterface $request): ?Model
    {
        if (empty($rows)) {
            return null;
        }

        $params = $request->getQueryParams();
        if (array_key_exists($key, $params)) {
            foreach ($rows as $row) {
                if ($row->id == $params[$key]) {
                    return $row;
                }
            }
        }

        return $rows[0];
    }

    public static function searchRows(ServerRequestInterface $request, SelectQueryBuilder $qb, array $filters): ?string
    {
        if (empty($filters)) {
            return null;
        }

        $search = null;
        $params = $request->getQueryParams();
        if (array_key_exists("q", $params)) {
            $search = $params["q"];

            if (!empty($search)) {
                if (count($filters) > 1) {
                    $initial = array_shift($filters);
                    $qb->andWhere("(LOWER($initial) LIKE LOWER(?)", "%{$search}%");

                    foreach ($filters as $index => $filter) {
                        $query = "LOWER($filter) LIKE LOWER(?)";
                        if ($index + 1 === count($filters)) {
                            $query .= ")";
                        }

                        $qb->orWhere($query, "%{$search}%");
                    }
                } else {
                    $qb->andWhere("LOWER($filters[0]) LIKE LOWER(?)", "%{$search}%");
                }
            }
        }

        return $search;
    }
}
