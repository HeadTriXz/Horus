<?php

namespace Horus\Database\Seeders;

use Horus\Core\Application;
use Horus\Core\Database\SeederInterface;

/**
 * The initial database seeder.
 */
class InitialSeeder implements SeederInterface
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $baseDir = dirname(__DIR__) . "/Scripts/";
        $files = [
            "users.sql",
            "courses.sql",
            "user_courses.sql",
            "exams.sql",
            "user_exams.sql",
            "grades.sql"
        ];

        $database = Application::getInstance()->getDatabase();
        $database->beginTransaction();

        foreach ($files as $file) {
            echo "Running script: " . $file . PHP_EOL;

            $sql = file_get_contents($baseDir . $file);
            $database->execute($sql);
        }

        $database->commit();
    }
}
