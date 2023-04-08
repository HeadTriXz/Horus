<?php

require_once dirname(__DIR__) . "/src/autoload.php";

use Horus\Core\Application;
use Horus\Core\Container\Container;
use Horus\Core\DotEnv;
use Horus\Core\Http\Router\Router;
use Horus\Core\View\View;

DotEnv::load(dirname(__DIR__) . "/.env");

$container = new Container();
$router = new Router($container);

// Initialize application
$app = Application::getInstance();
$app->setContainer($container);
$app->setRouter($router);

// Custom 404 page
$app->handleNotFound(function () {
    return View::render("404.php");
});

// Custom 500 page
$app->handleException(function () {
    return View::render("500.php");
});

// Initialize routes
$routeFolder = dirname(__DIR__) . "/src/Routes/";
foreach (scandir($routeFolder) as $routeFile) {
    if (pathinfo($routeFile, PATHINFO_EXTENSION) === "php") {
        if (pathinfo($routeFile, PATHINFO_FILENAME) === "api") {
            $router->prefix("/api", function () use ($routeFolder, $routeFile) {
                require_once $routeFolder . $routeFile;
            });

            continue;
        }

        require_once $routeFolder . $routeFile;
    }
}

// Define global route function
function route(string $name): ?string
{
    $route = Application::getInstance()
        ->getRouter()
        ->getRoute($name);

    return $route?->getPath();
}

// Run the application
$app->run();
