<?php

use Horus\Controllers\Auth\LoginController;
use Horus\Controllers\CourseController;
use Horus\Controllers\EnrollController;
use Horus\Controllers\GradeController;
use Horus\Controllers\HomeController;
use Horus\Controllers\UserController;
use Horus\Core\Application;
use Horus\Middleware\Authenticate;
use Horus\Middleware\RedirectIfAuthenticated;

$router = Application::getInstance()
    ->getRouter();

$router->middleware([RedirectIfAuthenticated::class], function ($router) {
    $router->get("/login", [LoginController::class, "show"])->name("login.show");
    $router->post("/login", [LoginController::class, "login"])->name("login.login");
});

$router->middleware([Authenticate::class], function ($router){
    $router->get("/", [HomeController::class, "index"])->name("home");
    $router->get("/grades", [GradeController::class, "index"])->name("grades");
    $router->get("/courses", [CourseController::class, "index"])->name("courses");
    $router->get("/enroll", [EnrollController::class, "index"])->name("enroll");
    $router->get("/profile", [UserController::class, "profile"])->name("profile");

    $router->get("/logout", [LoginController::class, "logout"])->name("logout");
});
