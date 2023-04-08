<?php

//require_once __DIR__ . "/src/autoload.php";
//
//use Horus\Core\Application;
//use Horus\Core\Container\Container;
//use Horus\Core\DotEnv;
//
//DotEnv::load(__DIR__ . "/.env");
//
//$container = new Container();
//
//// Initialize application
//$app = Application::getInstance();
//$app->setContainer($container);
//
//// Run the migrations
//$database = $app->getDatabase();
//
//$migrationsDir = __DIR__ . "/src/Database/Migrations/";
//foreach (scandir($migrationsDir) as $file) {
//    if (!str_ends_with($file, ".php")) {
//        continue;
//    }
//
//    $migration = require_once $migrationsDir . $file;
//    $migration->up();
//}

echo password_hash("password", PASSWORD_BCRYPT) . "'";
