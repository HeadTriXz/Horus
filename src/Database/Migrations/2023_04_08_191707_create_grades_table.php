<?php

use Horus\Core\Application;
use Horus\Core\Database\MigrationInterface;

/**
 * Represents the migrations for the grades table.
 */
return new class implements MigrationInterface {
    /**
     * Rollback the migration to the previous version.
     */
    public function down(): void
    {
        $database = Application::getInstance()->getDatabase();
        $database->execute("DROP TABLE IF EXISTS grades;");
    }

    /**
     * Perform the migration up to the current version.
     */
    public function up(): void
    {
        $database = Application::getInstance()->getDatabase();
        $database->execute("
            CREATE TABLE grades (
                id INT AUTO_INCREMENT PRIMARY KEY,
                exam_id INT NOT NULL,
                student_id INT NOT NULL,
                grade FLOAT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (student_id) REFERENCES users(id),
                FOREIGN KEY (exam_id) REFERENCES exams(id)
            );
        ");
    }
};
