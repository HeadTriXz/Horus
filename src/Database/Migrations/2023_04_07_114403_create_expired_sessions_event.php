<?php

use Horus\Core\Application;
use Horus\Core\Database\MigrationInterface;

/**
 * Represents the migrations for the delete_expired_sessions event.
 */
return new class implements MigrationInterface {
    /**
     * Rollback the migration to the previous version.
     */
    public function down(): void
    {
        $database = Application::getInstance()->getDatabase();
        $database->execute("DROP EVENT IF EXISTS delete_expired_sessions;");
    }

    /**
     * Perform the migration up to the current version.
     */
    public function up(): void
    {
        $database = Application::getInstance()->getDatabase();
        $database->execute("
            CREATE EVENT delete_expired_sessions
            ON SCHEDULE
                EVERY 2 MINUTE
                STARTS NOW()
            DO
                DELETE FROM sessions WHERE expires_at < NOW();
        ");
    }
};
