<?php

use Horus\Core\Application;
use Horus\Core\Database\MigrationInterface;

/**
 * Represents the migrations for the user courses table.
 */
return new class implements MigrationInterface {
    /**
     * Rollback the migration to the previous version.
     */
    public function down(): void
    {
        $database = Application::getInstance()->getDatabase();
        $database->execute("DROP TABLE IF EXISTS user_courses;");
    }

    /**
     * Perform the migration up to the current version.
     */
    public function up(): void
    {
        $database = Application::getInstance()->getDatabase();
        $database->execute("
             CREATE TABLE user_courses (
                user_id INT NOT NULL,
                course_id INT NOT NULL,
                PRIMARY KEY (user_id, course_id),
                FOREIGN KEY (user_id) REFERENCES users(id),
                FOREIGN KEY (course_id) REFERENCES courses(id)
            );
        ");
    }
};
