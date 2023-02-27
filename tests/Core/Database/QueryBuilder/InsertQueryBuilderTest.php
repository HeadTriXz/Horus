<?php

use Horus\Core\Database\QueryBuilder\QueryBuilder;
use PHPUnit\Framework\TestCase;

class InsertQueryBuilderTest extends TestCase
{
    public function testMissing(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $qb = new QueryBuilder();
        $qb
            ->insert()
            ->into("users")
            ->values([
                [
                    "name" => "Peter",
                    "age" => 21
                ],
                [
                    "name" => "Petra" // Missing age.
                ]
            ])
            ->getQuery();
    }

    public function testMultiple(): void
    {
        $qb = new QueryBuilder();
        $query = $qb
            ->insert()
            ->into("users")
            ->values([
                [
                    "name" => "Peter",
                    "age" => 21
                ],
                [
                    "name" => "Petra",
                    "age" => 48
                ]
            ])
            ->getQuery();

        $expectedQuery = "INSERT INTO users (name, age) VALUES (?, ?), (?, ?)";
        $this->assertEquals($expectedQuery, $query);
    }

    public function testSingle(): void
    {
        $qb = new QueryBuilder();
        $query = $qb
            ->insert()
            ->into("users")
            ->values([
                "name" => "Peter",
                "age" => 21
            ])
            ->getQuery();

        $expectedQuery = "INSERT INTO users (name, age) VALUES (?, ?)";
        $this->assertEquals($expectedQuery, $query);
    }

    public function testUpdate(): void
    {
        $qb = new QueryBuilder();
        $query = $qb
            ->insert()
            ->into("users")
            ->values([
                "name" => "Peter",
                "age" => 21
            ])
            ->orUpdate([
                "name" => "Peter",
                "age" => 21
            ])
            ->getQuery();

        $expectedQuery = "INSERT INTO users (name, age) VALUES (?, ?) ON DUPLICATE KEY UPDATE name = ?, age = ?";
        $this->assertEquals($expectedQuery, $query);
    }
}
