<?php

namespace Horus\Core\Database;

use Horus\Core\Database\QueryBuilder\QueryBuilder;
use LogicException;

/**
 * The base model class for interacting with database tables.
 */
abstract class Model
{
    protected static Database $database;
    protected static string $primaryKey = "id";
    protected static string $table;

    /**
     * The base model class for interacting with database tables.
     *
     * @throws LogicException if $table is not set in the child class.
     */
    protected function __construct()
    {
        if (empty(static::$table)) {
            throw new LogicException(get_class($this) . " must have \$table set.");
        }
    }

    /**
     * Returns a new QueryBuilder instance for the current model.
     *
     * @return QueryBuilder
     */
    public static function createQueryBuilder(): QueryBuilder
    {
        return new QueryBuilder(self::$database, static::class);
    }

    /**
     * Finds and returns all rows from the model's table that match the specified conditions.
     *
     * @param array $where The conditions to match against.
     * @return array An array of rows matching the specified conditions.
     */
    public static function find(array $where): array
    {
        $qb = static::createQueryBuilder()
            ->select();

        foreach ($where as $key => $value) {
            $qb->andWhere($key . " = ?", $value);
        }

        return $qb->getAll();
    }

    /**
     * Finds and returns a single row from the model's table with the specified ID.
     *
     * @param string $id The ID of the row to find.
     * @return static The row matching the specified ID.
     */
    public static function findById(string $id): static
    {
        return static::createQueryBuilder()
            ->select()
            ->where(static::$primaryKey . " = ?", $id)
            ->getOne();
    }

    /**
     * Finds and returns a single row from the model's table that matches the specified conditions.
     *
     * @param array $where The conditions to match against.
     * @return static The model instance matching the specified conditions.
     */
    public static function findOne(array $where): static
    {
        $qb = static::createQueryBuilder()
            ->select();

        foreach ($where as $key => $value) {
            $qb->andWhere($key . " = ?", $value);
        }

        return $qb->getOne();
    }

    /**
     * Returns the name of the model's associated database table.
     *
     * @return string The name of the model's associated database table.
     */
    public static function getTableName(): string
    {
        return static::$table;
    }

    /**
     * Saves the current model instance to the database.
     *
     * @return int The number of rows affected by the save operation.
     */
    public function save(): int
    {
        $class = new \ReflectionClass($this);

        $values = [];
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $name = $property->getName();
            $values[$name] = $this->{$name};
        }

        return static::createQueryBuilder()
            ->insert()
            ->values($values)
            ->orUpdate($values)
            ->execute();
    }
}
