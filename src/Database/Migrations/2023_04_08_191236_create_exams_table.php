<?php

use Horus\Core\Application;
use Horus\Core\Database\MigrationInterface;

/**
 * Represents the migrations for the exams table.
 */
return new class implements MigrationInterface {
    /**
     * Rollback the migration to the previous version.
     */
    public function down(): void
    {
        $database = Application::getInstance()->getDatabase();
        $database->execute("DROP TABLE exams;");
    }

    /**
     * Perform the migration up to the current version.
     */
    public function up(): void
    {
        $database = Application::getInstance()->getDatabase();
        $database->execute("
            CREATE TABLE exams (
                id INT AUTO_INCREMENT PRIMARY KEY,
                course_id INT NOT NULL,
                duration INT,
                name VARCHAR(255) NOT NULL,
                exam_date TIMESTAMP NOT NULL,
                FOREIGN KEY (course_id) REFERENCES courses(id)
            );
        ");
    }
};
