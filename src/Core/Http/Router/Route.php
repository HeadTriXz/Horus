<?php

namespace Horus\Core\Http\Router;

use Horus\Core\Http\Message\ServerRequestInterface;
use Horus\Core\Http\Server\MiddlewareInterface;

/**
 * Represents an HTTP route.
 */
class Route implements RouteInterface
{
    protected string $controller;
    protected string $controllerMethod;
    protected array $middleware = [];

    /**
     * Represents an HTTP route.
     *
     * @param string $method The HTTP method of the route.
     * @param string $path The path of the route.
     * @param array $controller An array with the controller class and method. E.g. [UserController::class, 'update']
     */
    public function __construct(
        protected string $method,
        protected string $path,
        array $controller
    ) {
        [$this->controller, $this->controllerMethod] = $controller;
    }

    /**
     * Get the name of the controller class.
     *
     * @return string The name of the controller.
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * Get the name of the method to be called on the controller class.
     *
     * @return string The name of the method.
     */
    public function getControllerMethod(): string
    {
        return $this->controllerMethod;
    }

    /**
     * Get the HTTP method for this route.
     *
     * @return string The HTTP method.
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Get an array of middleware for this route.
     *
     * @return string[] An array of middleware.
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    /**
     * Get the path for this route.
     *
     * @return string The path of the route.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Check if the route matches the given ServerRequest.
     *
     * @param ServerRequestInterface $request The request to check.
     * @return bool Whether the request matches the route.
     */
    public function match(ServerRequestInterface $request): bool
    {
        if ($request->getMethod() !== $this->getMethod()) {
            return false;
        }

        $requestPath = $request->getUri()->getPath();
        $routePath = $this->getPath();

        return $requestPath === $routePath;
    }

    /**
     * Add middleware to this route.
     *
     * @param string ...$middleware The middleware to add.
     * @return $this
     */
    public function middleware(string ...$middleware): static
    {
        $this->middleware = array_merge($this->middleware, $middleware);
        return $this;
    }
}
