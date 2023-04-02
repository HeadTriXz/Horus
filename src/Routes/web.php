<?php

use Horus\Controllers\UserController;
use Horus\Core\Application;

$router = Application::getInstance()
    ->getRouter();

$router->get("/", [UserController::class, "index"]);
