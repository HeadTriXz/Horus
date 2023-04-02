<?php

namespace Horus\Core\Http\Router;

use Horus\Core\Container\ContainerInterface;
use Horus\Core\Http\Message\Response;
use Horus\Core\Http\Message\ResponseInterface;
use Horus\Core\Http\Message\ServerRequestInterface;
use Horus\Core\Http\Server\RequestHandlerInterface;
use Horus\Core\Http\Server\RouterHandler;

/**
 * Provides HTTP route handling.
 */
class Router implements RouterInterface, RequestHandlerInterface
{
    protected string $prefix = "";
    protected array $routes = [];

    /**
     * Provides HTTP route handling.
     *
     * @param ContainerInterface $container The DI-Container to use.
     */
    public function __construct(
        protected ContainerInterface $container,
        protected array $middleware = []
    ) {}

    /**
     * Add a new route to the router.
     *
     * @param string $method The method of the route.
     * @param string $path The path for the route.
     * @param array $controller An array containing the controller information for the route.
     *     The first element should be the controller class name,
     *     and the second element should be the name of the method to call.
     * @return RouteInterface The newly created Route object.
     */
    protected function addRoute(string $method, string $path, array $controller): RouteInterface
    {
        $path = $this->prefix . $this->formatPath($path);
        $route = new Route($method, $path, $controller);

        if (!empty($this->middleware)) {
            $route->middleware(...$this->middleware);
        }

        return $this->routes[] = $route;
    }

    /**
     * Add a new DELETE route to the router.
     *
     * @param string $path The path for the route.
     * @param array $controller An array containing the controller information for the route.
     *     The first element should be the controller class name,
     *     and the second element should be the name of the method to call.
     * @return RouteInterface The newly created Route object.
     */
    public function delete(string $path, array $controller): RouteInterface
    {
        return $this->addRoute("DELETE", $path, $controller);
    }

    /**
     * Format the given path to ensure it has a leading slash and no trailing slashes.
     *
     * @param string $path The path to format.
     * @return string The formatted path.
     */
    protected function formatPath(string $path): string
    {
        return "/" . trim($path, "/");
    }

    /**
     * Add a new GET route to the router.
     *
     * @param string $path The path for the route.
     * @param array $controller An array containing the controller information for the route.
     *     The first element should be the controller class name,
     *     and the second element should be the name of the method to call.
     * @return RouteInterface The newly created Route object.
     */
    public function get(string $path, array $controller): RouteInterface
    {
        return $this->addRoute("GET", $path, $controller);
    }

    /**
     * Get all registered routes.
     *
     * @return RouteInterface[] An array of routes.
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Handles an incoming request.
     *
     * @param ServerRequestInterface $request The request to handle.
     * @return ResponseInterface The response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            foreach ($this->getRoutes() as $route) {
                if (!$route->match($request)) {
                    continue;
                }

                $handler = new RouterHandler($route, $this->container);
                foreach ($route->getMiddleware() as $middleware) {
                    $handler = $this->container
                        ->get($middleware)
                        ->process($request, $handler);
                }

                return $handler->handle($request);
            }
        } catch (\Throwable) {
            return new Response(500, "Internal Server Error");
        }

        return new Response(404, "Not Found");
    }

    /**
     * Adds middleware to all the routes inside the callback.
     * ```php
     * $router->middleware([First::class, Second::class], function() {
     *     $router->get('/users', [UserController::class, 'index']);
     *     $router->post('/users', [UserController::class, 'create']);
     * });
     * ```
     *
     * @param array $middleware An array of middleware to add.
     * @param callable $callback Callback in which you can define the routes.
     * @return $this
     */
    public function middleware(array $middleware, callable $callback): static
    {
        $oldMiddleware = $this->middleware;

        $this->middleware = array_merge($this->middleware, $middleware);
        call_user_func($callback);
        $this->middleware = $oldMiddleware;

        return $this;
    }

    /**
     * Add a new PATCH route to the router.
     *
     * @param string $path The path for the route.
     * @param array $controller An array containing the controller information for the route.
     *     The first element should be the controller class name,
     *     and the second element should be the name of the method to call.
     * @return RouteInterface The newly created Route object.
     */
    public function patch(string $path, array $controller): RouteInterface
    {
        return $this->addRoute("PATCH", $path, $controller);
    }

    /**
     * Add a new POST route to the router.
     *
     * @param string $path The path for the route.
     * @param array $controller An array containing the controller information for the route.
     *     The first element should be the controller class name,
     *     and the second element should be the name of the method to call.
     * @return RouteInterface The newly created Route object.
     */
    public function post(string $path, array $controller): RouteInterface
    {
        return $this->addRoute("POST", $path, $controller);
    }

    /**
     * Prefixes all routes inside the callback with the provided path.
     * ```php
     * $router->prefix('/api', function() {
     *     $router->get('/users', [UserController::class, 'index']);
     *     $router->post('/users', [UserController::class, 'create']);
     * });
     * ```
     *
     * @param string $path The path to prefix the routes with.
     * @param callable $callback Callback in which you can define the routes.
     * @return $this
     */
    public function prefix(string $path, callable $callback): static
    {
        $oldPrefix = $this->prefix;

        $this->prefix .= $this->formatPath($path);
        call_user_func($callback);
        $this->prefix = $oldPrefix;

        return $this;
    }

    /**
     * Add a new PUT route to the router.
     *
     * @param string $path The path for the route.
     * @param array $controller An array containing the controller information for the route.
     *     The first element should be the controller class name,
     *     and the second element should be the name of the method to call.
     * @return RouteInterface The newly created Route object.
     */
    public function put(string $path, array $controller): RouteInterface
    {
        return $this->addRoute("PUT", $path, $controller);
    }
}
