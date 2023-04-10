<?php

use Horus\Core\Application;
use Horus\Core\Database\MigrationInterface;

/**
 * Represents the migrations for the courses table.
 */
return new class implements MigrationInterface {
    /**
     * Rollback the migration to the previous version.
     */
    public function down(): void
    {
        $database = Application::getInstance()->getDatabase();
        $database->execute("DROP TABLE courses;");
    }

    /**
     * Perform the migration up to the current version.
     */
    public function up(): void
    {
        $database = Application::getInstance()->getDatabase();
        $database->execute("
            CREATE TABLE courses (
                id INT AUTO_INCREMENT PRIMARY KEY,
                teacher_id INT NOT NULL,
                name VARCHAR(255) NOT NULL,
                code VARCHAR(20) NOT NULL,
                FOREIGN KEY (teacher_id) REFERENCES users(id)
            );
        ");
    }
};
