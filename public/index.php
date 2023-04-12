<?php

require_once dirname(__DIR__) . "/src/autoload.php";

use Horus\Core\Application;
use Horus\Core\Container\Container;
use Horus\Core\DotEnv;
use Horus\Core\Http\Message\ServerRequest;
use Horus\Core\Http\Message\ServerRequestInterface;
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
function route(string $name, array $params = []): ?string
{
    $route = Application::getInstance()
        ->getRouter()
        ->getRoute($name);

    if (!$route) {
        return null;
    }

    $path = $route->getPath();
    foreach ($params as $key => $value) {
        if (str_contains($path, ":$key")) {
            $path = str_replace(":$key", urlencode($value), $path);
            unset($params[$key]);
        }
    }

    if (!empty($params)) {
        $path .= "?" . http_build_query($params);
    }

    return $path;
}

function request(): ServerRequestInterface
{
    static $request;

    if ($request === null) {
        $request = ServerRequest::fromGlobals();
    }

    return $request;
}

// Run the application
$app->run();
