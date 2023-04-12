<?php

namespace Horus\Core\Http\Router;

use Horus\Core\Http\Message\ServerRequestInterface;

/**
 * Represents an HTTP route.
 */
class Route implements RouteInterface
{
    protected string $controller;
    protected string $controllerMethod;
    protected array $middleware = [];
    protected ?string $name = null;

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
     * Get the name of this route.
     *
     * @return ?string The name of the route
     */
    public function getName(): ?string
    {
        return $this->name;
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

        $routeSegments = explode("/", trim($routePath, "/"));
        $requestSegments = explode("/", trim($requestPath, "/"));

        if (count($routeSegments) !== count($requestSegments)) {
            return false;
        }

        for ($i = 0; $i < count($routeSegments); $i++) {
            if (str_starts_with($routeSegments[$i], ":")) {
                continue;
            }

            if ($routeSegments[$i] !== $requestSegments[$i]) {
                return false;
            }
        }

        return true;
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

    /**
     * Set the name of the route.
     *
     * @param string $name The name of the route, must be unique.
     * @return $this
     */
    public function name(string $name): static
    {
        $this->name = $name;
        return $this;
    }
}
