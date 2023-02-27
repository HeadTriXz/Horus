<?php

use Horus\Core\Database\QueryBuilder\QueryBuilder;
use PHPUnit\Framework\TestCase;

class UpdateQueryBuilderTest extends TestCase
{
    public function testMultiOrder(): void
    {
        $qb = new QueryBuilder();
        $query = $qb
            ->update("users")
            ->set([
                "score" => 0,
                "tag" => "4021"
            ])
            ->orderBy([
                "score" => "DESC",
                "wins" => "ASC"
            ])
            ->limit(10)
            ->getQuery();

        $expectedQuery = "UPDATE users SET score = ?, tag = ? ORDER BY score DESC, wins ASC LIMIT 10";
        $this->assertEquals($expectedQuery, $query);
    }

    public function testOrder(): void
    {
        $qb = new QueryBuilder();
        $query = $qb
            ->update("users")
            ->set([
                "score" => 0,
                "tag" => "4021"
            ])
            ->orderBy("score", "DESC")
            ->limit(10)
            ->getQuery();

        $expectedQuery = "UPDATE users SET score = ?, tag = ? ORDER BY score DESC LIMIT 10";
        $this->assertEquals($expectedQuery, $query);
    }

    public function testSet(): void
    {
        $qb = new QueryBuilder();
        $query = $qb
            ->update("users")
            ->set([
                "score" => 0,
                "tag" => "4021"
            ])
            ->getQuery();

        $expectedQuery = "UPDATE users SET score = ?, tag = ?";
        $this->assertEquals($expectedQuery, $query);
    }

    public function testWhere(): void
    {
        $qb = new QueryBuilder();
        $query = $qb
            ->update("users")
            ->set([
                "score" => 0,
                "tag" => "4021"
            ])
            ->where("name = ?", "Peter")
            ->getQuery();

        $expectedQuery = "UPDATE users SET score = ?, tag = ? WHERE name = ?";
        $this->assertEquals($expectedQuery, $query);
    }
}
