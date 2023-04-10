<?php

require_once __DIR__ . "/src/autoload.php";

use Horus\Core\Application;
use Horus\Core\Container\Container;
use Horus\Core\Database\SeederInterface;
use Horus\Core\DotEnv;

DotEnv::load(__DIR__ . "/.env");

// Define functions
function migrate(string $dir, bool $down = false): void
{
    foreach (scandir($dir, (int) $down) as $file) {
        if (!str_ends_with($file, ".php")) {
            continue;
        }

        $migration = require $dir . $file;
        if ($down) {
            echo "Rolling back: $file" . PHP_EOL;
            $migration->down();
        } else {
            echo "Migrating: $file" . PHP_EOL;
            $migration->up();
        }
    }
}

function seed(string $dir): void
{
    foreach (scandir($dir) as $file) {
        if (!str_ends_with($file, ".php")) {
            continue;
        }

        $class = "Horus\\Database\\Seeders\\" . basename($file, ".php");
        if (class_exists($class) && in_array(SeederInterface::class, class_implements($class))) {
            echo "Seeding: $file" . PHP_EOL;

            $seeder = new $class();
            $seeder->run();
        }
    }
}

// Define folders.
$migrationsDir = __DIR__ . "/src/Database/Migrations/";
$seedersDir = __DIR__ . "/src/Database/Seeders/";

// Initialize application
$app = Application::getInstance();
$app->setContainer(new Container());

$database = $app->getDatabase();

// Run migrations
if (in_array("--fresh", $argv)) {
    migrate($migrationsDir, true);
    migrate($migrationsDir);

    if (in_array("--seed", $argv)) {
        seed($seedersDir);
    }
} elseif (in_array("--seed", $argv)) {
    seed($seedersDir);
} else {
    migrate($migrationsDir);
}

echo "Completed migrating successfully.";
