<?php

use Horus\Core\Database\QueryBuilder\QueryBuilder;
use PHPUnit\Framework\TestCase;

class SelectQueryBuilderTest extends TestCase
{
    public function testAlias(): void
    {
        $qb = new QueryBuilder();
        $query = $qb
            ->select([
                "u.name" => "username",
                "u.foo" => "bar"
            ])
            ->from("users", "u")
            ->getQuery();

        $expectedQuery = "SELECT u.name username, u.foo bar FROM users u";
        $this->assertEquals($expectedQuery, $query);
    }

    public function testJoin(): void
    {
        $qb = new QueryBuilder();
        $query = $qb
            ->select(["a.address", "c.city"])
            ->from("address", "a")
            ->innerJoin("city", "c", "a.city_id = c.city_id")
            ->where("a.district = ''")
            ->orderBy("c.city")
            ->getQuery();

        $expectedQuery = "SELECT a.address, c.city FROM address a"
            . " INNER JOIN city c ON a.city_id = c.city_id"
            . " WHERE a.district = ''"
            . " ORDER BY c.city";

        $this->assertEquals($expectedQuery, $query);
    }

    public function testWhere(): void
    {
        $qb = new QueryBuilder();
        $query = $qb
            ->select()
            ->from("users")
            ->where("name = ?", "Peter")
            ->getQuery();

        $expectedQuery = "SELECT * FROM users WHERE name = ?";
        $this->assertEquals($expectedQuery, $query);
    }
}