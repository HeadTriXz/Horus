<?php

use Horus\Core\Database\QueryBuilder\QueryBuilder;
use PHPUnit\Framework\TestCase;

class DeleteQueryBuilderTest extends TestCase
{
    public function testTop10(): void
    {
        $qb = new QueryBuilder();
        $query = $qb
            ->delete()
            ->from("users", "u")
            ->orderBy("u.score", "DESC")
            ->limit(10)
            ->getQuery();

        $expectedQuery = "DELETE FROM users u ORDER BY u.score DESC LIMIT 10";
        $this->assertEquals($expectedQuery, $query);
    }

    public function testWhere(): void
    {
        $qb = new QueryBuilder();
        $query = $qb
            ->delete()
            ->from("users", "u")
            ->where("u.name = ?", "Peter")
            ->getQuery();

        $expectedQuery = "DELETE FROM users u WHERE u.name = ?";
        $this->assertEquals($expectedQuery, $query);
    }
}
