<?php

namespace Horus\Core\Database;

/**
 * Represents a database seeder.
 */
interface SeederInterface
{
    /**
     * Run the database seeder.
     */
    public function run(): void;
}
