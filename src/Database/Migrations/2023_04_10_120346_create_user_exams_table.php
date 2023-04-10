<?php

use Horus\Core\Application;
use Horus\Core\Database\MigrationInterface;

/**
 * Represents the migrations for the user exams table.
 */
return new class implements MigrationInterface {
    /**
     * Rollback the migration to the previous version.
     */
    public function down(): void
    {
        $database = Application::getInstance()->getDatabase();
        $database->execute("DROP TABLE IF EXISTS user_exams;");
    }

    /**
     * Perform the migration up to the current version.
     */
    public function up(): void
    {
        $database = Application::getInstance()->getDatabase();
        $database->execute("
             CREATE TABLE user_exams (
                user_id INT NOT NULL,
                exam_id INT NOT NULL,
                PRIMARY KEY (user_id, exam_id),
                FOREIGN KEY (user_id) REFERENCES users(id),
                FOREIGN KEY (exam_id) REFERENCES exams(id)
            );
        ");
    }
};
