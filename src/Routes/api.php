<?php

use Horus\Core\Application;

$router = Application::getInstance()
    ->getRouter();

// All routes in this file will be prefixed with "/api"

// $router->post("/grade", [GradeController::class, "create"]);
// This will result in: POST /api/grade
