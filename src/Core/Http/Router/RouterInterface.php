<?php

namespace Horus\Core\Http\Router;

use Horus\Core\Http\Message\ResponseInterface;
use Horus\Core\Http\Message\ServerRequestInterface;

/**
 * Provides HTTP route handling.
 */
interface RouterInterface
{
    /**
     * Add a new DELETE route to the router.
     *
     * @param string $path The path for the route.
     * @param array $controller An array containing the controller information for the route.
     *     The first element should be the controller class name,
     *     and the second element should be the name of the method to call.
     * @return RouteInterface The newly created Route object.
     */
    public function delete(string $path, array $controller): RouteInterface;

    /**
     * Add a new GET route to the router.
     *
     * @param string $path The path for the route.
     * @param array $controller An array containing the controller information for the route.
     *     The first element should be the controller class name,
     *     and the second element should be the name of the method to call.
     * @return RouteInterface The newly created Route object.
     */
    public function get(string $path, array $controller): RouteInterface;

    /**
     * Get a route by its name.
     *
     * @param string $name The name of the route.
     * @return ?RouteInterface The found route.
     */
    public function getRoute(string $name): ?RouteInterface;

    /**
     * Get all registered routes.
     *
     * @return RouteInterface[] An array of routes.
     */
    public function getRoutes(): array;

    /**
     * Handles an incoming request.
     *
     * @param ServerRequestInterface $request The request to handle.
     * @return ResponseInterface The response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface;

    /**
     * Add a new PATCH route to the router.
     *
     * @param string $path The path for the route.
     * @param array $controller An array containing the controller information for the route.
     *     The first element should be the controller class name,
     *     and the second element should be the name of the method to call.
     * @return RouteInterface The newly created Route object.
     */
    public function patch(string $path, array $controller): RouteInterface;

    /**
     * Add a new POST route to the router.
     *
     * @param string $path The path for the route.
     * @param array $controller An array containing the controller information for the route.
     *     The first element should be the controller class name,
     *     and the second element should be the name of the method to call.
     * @return RouteInterface The newly created Route object.
     */
    public function post(string $path, array $controller): RouteInterface;

    /**
     * Prefixes all routes inside the callback with the provided path.
     * <code>
     * $router->prefix('/api', function($router) {
     *     $router->get('/users', [UserController::class, 'index']);
     *     $router->post('/users', [UserController::class, 'create']);
     * });
     * </code>
     *
     * @param string $path The path to prefix the routes with.
     * @param callable $callback Callback in which you can define the routes.
     * @return $this
     */
    public function prefix(string $path, callable $callback): static;

    /**
     * Add a new PUT route to the router.
     *
     * @param string $path The path for the route.
     * @param array $controller An array containing the controller information for the route.
     *     The first element should be the controller class name,
     *     and the second element should be the name of the method to call.
     * @return RouteInterface The newly created Route object.
     */
    public function put(string $path, array $controller): RouteInterface;
}
