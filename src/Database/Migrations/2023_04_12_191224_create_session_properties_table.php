<?php

use Horus\Core\Application;
use Horus\Core\Database\MigrationInterface;

/**
 * Represents the migrations for the session properties table.
 */
return new class implements MigrationInterface {
    /**
     * Rollback the migration to the previous version.
     */
    public function down(): void
    {
        $database = Application::getInstance()->getDatabase();
        $database->execute("DROP TABLE IF EXISTS session_properties;");
    }

    /**
     * Perform the migration up to the current version.
     */
    public function up(): void
    {
        $database = Application::getInstance()->getDatabase();
        $database->execute("
            CREATE TABLE session_properties (
                session_id VARCHAR(32) NOT NULL,
                p_key VARCHAR(255) NOT NULL,
                p_value VARCHAR(255) NOT NULL,
                PRIMARY KEY (session_id, p_key),
                FOREIGN KEY (session_id)
                    REFERENCES sessions(id)
                    ON DELETE CASCADE
            )
        ");
    }
};
