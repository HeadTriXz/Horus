<?php

namespace Horus\Core\Http\Router;

use Horus\Core\Http\Message\ServerRequestInterface;
use Horus\Core\Http\Server\MiddlewareInterface;

/**
 * Represents an HTTP route.
 */
interface RouteInterface
{
    /**
     * Get the name of the controller class.
     *
     * @return string The name of the controller.
     */
    public function getController(): string;

    /**
     * Get the name of the method to be called on the controller class.
     *
     * @return string The name of the method.
     */
    public function getControllerMethod(): string;

    /**
     * Get the HTTP method for this route.
     *
     * @return string The HTTP method.
     */
    public function getMethod(): string;

    /**
     * Get an array of middleware for this route.
     *
     * @return string[] An array of middleware.
     */
    public function getMiddleware(): array;

    /**
     * Get the path for this route.
     *
     * @return string The path of the route.
     */
    public function getPath(): string;

    /**
     * Check if the route matches the given ServerRequest.
     *
     * @param ServerRequestInterface $request The request to check.
     * @return bool Whether the request matches the route.
     */
    public function match(ServerRequestInterface $request): bool;

    /**
     * Add middleware to this route.
     *
     * @param string ...$middleware The middleware to add.
     * @return $this
     */
    public function middleware(string ...$middleware): static;
}
