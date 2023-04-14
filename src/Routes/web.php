<?php

use Horus\Controllers\Auth\LoginController;
use Horus\Controllers\Auth\PasswordController;
use Horus\Controllers\CourseController;
use Horus\Controllers\EnrollController;
use Horus\Controllers\ExamController;
use Horus\Controllers\GradeController;
use Horus\Controllers\HomeController;
use Horus\Controllers\UserController;
use Horus\Core\Application;
use Horus\Middleware\Authenticate;
use Horus\Middleware\HasRole;
use Horus\Middleware\RedirectIfAuthenticated;

$router = Application::getInstance()
    ->getRouter();

$router->middleware([RedirectIfAuthenticated::class], function ($router) {
    $router->get("/login", [LoginController::class, "show"])->name("login.show");
    $router->post("/login", [LoginController::class, "login"])->name("login.login");
});

$router->middleware([Authenticate::class], function ($router) {
    $router->get("", [HomeController::class, "index"])->name("home");
    $router->get("/courses", [CourseController::class, "index"])->name("courses");
    $router->get("/profile", [UserController::class, "profile"])->name("profile");
    $router->get("/logout", [LoginController::class, "logout"])->name("logout");

    $router->post("/password", [PasswordController::class, "update"])->name("password.update");

    $router->middleware([HasRole::class . ":teacher:admin"], function ($router) {
        $router->get("/exams", [ExamController::class, "index"])->name("exams");
        $router->get("/exams/:id/grades", [GradeController::class, "manage"])->name("grades.manage");
        $router->post("/exams/:id/grades", [GradeController::class, "update"])->name("grades.update");
    });

    $router->middleware([HasRole::class . ":admin"], function ($router) {
        $router->get("/users", [UserController::class, "index"])->name("users");
        $router->get("/users/new", [UserController::class, "create"])->name("users.create");
        $router->post("/users/new", [UserController::class, "store"])->name("users.store");
        $router->post("/users/:id", [UserController::class, "update"])->name("users.update");

        $router->get("/courses/new", [CourseController::class, "create"])->name("courses.create");
        $router->post("/courses/new", [CourseController::class, "store"])->name("courses.store");
        $router->post("/courses/:id", [CourseController::class, "update"])->name("courses.update");

        $router->get("/exams/new", [ExamController::class, "create"])->name("exams.create");
        $router->post("/exams/new", [ExamController::class, "store"])->name("exams.store");
        $router->post("/exams/:id", [ExamController::class, "update"])->name("exams.update");
    });

    $router->middleware([HasRole::class . ":student"], function ($router) {
        $router->get("/grades", [GradeController::class, "index"])->name("grades");
        $router->prefix("/enroll", function ($router) {
            $router->get("", [EnrollController::class, "index"])->name("enroll.index");
            $router->get("/courses", [EnrollController::class, "courses"])->name("enroll.courses");
            $router->post("/courses", [EnrollController::class, "storeCourse"])->name("enroll.courses.enroll");

            $router->get("/exams", [EnrollController::class, "exams"])->name("enroll.exams");
            $router->post("/exams", [EnrollController::class, "storeExam"])->name("enroll.exams.enroll");
        });
    });
});
