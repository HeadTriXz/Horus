<?php

namespace Horus\Core\Database;

/**
 * Defines the required behavior for a database migration.
 */
interface MigrationInterface
{
    /**
     * Rollback the migration to the previous version.
     */
    public function down(): void;

    /**
     * Perform the migration up to the current version.
     */
    public function up(): void;
}
