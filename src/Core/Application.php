<?php

namespace Horus\Core;

use Closure;
use Horus\Core\Container\ContainerException;
use Horus\Core\Container\ContainerInterface;
use Horus\Core\Database\Database;
use Horus\Core\Database\DatabaseInterface;
use Horus\Core\Http\Message\ResponseInterface;
use Horus\Core\Http\Message\Stream;
use Horus\Core\Http\Router\RouterInterface;

/**
 * The main application class that handles incoming HTTP requests.
 */
class Application
{
    /**
     * A singleton instance of the Application class.
     *
     * @var ?Application
     */
    protected static ?self $instance = null;

    /**
     * A dependency injection container.
     *
     * @var ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * An HTTP request router.
     *
     * @var RouterInterface
     */
    protected RouterInterface $router;

    /**
     * A callback function to handle exceptions.
     *
     * @var Closure
     */
    protected Closure $exceptionCallback;

    /**
     * A callback function to handle not found errors.
     *
     * @var Closure
     */
    protected Closure $notFoundCallback;

    /**
     * Application constructor.
     * Prevent instantiation from outside the class.
     */
    private function __construct()
    {
    }

    /**
     * Get the dependency injection container.
     *
     * @return ContainerInterface The dependency injection container.
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * Get the database instance.
     *
     * @throws ContainerException Error while retrieving the database instance.
     * @return DatabaseInterface The database instance.
     */
    public function getDatabase(): DatabaseInterface
    {
        return $this->container->get(Database::class);
    }

    /**
     * Get a singleton instance of the Application class.
     *
     * @return static A singleton instance of the Application class.
     */
    public static function getInstance(): static
    {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Get the HTTP request router.
     *
     * @return RouterInterface The HTTP request router.
     */
    public function getRouter(): RouterInterface
    {
        return $this->router;
    }

    /**
     * Set a callback function to handle exceptions.
     *
     * @param callable $callback The callback function to handle exceptions.
     */
    public function handleException(callable $callback): void
    {
        $this->exceptionCallback = $callback;
    }

    /**
     * Set a callback function to handle not found errors.
     *
     * @param callable $callback The callback function to handle not found errors.
     */
    public function handleNotFound(callable $callback): void
    {
        $this->notFoundCallback = $callback;
    }

    /**
     * Handles an incoming HTTP request by routing it and producing a response.
     */
    public function run(): void
    {
        $response = $this->router->handle(request());
        if (!headers_sent()) {
            header(
                "HTTP/{$response->getProtocolVersion()} {$response->getStatusCode()} {$response->getReasonPhrase()}",
                true,
                $response->getStatusCode()
            );

            foreach ($response->getHeaders() as $key => $values) {
                foreach ($values as $value) {
                    header("$key: $value", false, $response->getStatusCode());
                }
            }
        }

        // Handle Internal Server Errors.
        if ($response->getStatusCode() === 500 && isset($this->exceptionCallback)) {
            $content = call_user_func($this->exceptionCallback, $response);
            if (is_string($content)) {
                $response = $response->withBody(new Stream($content));
            }

            if ($content instanceof ResponseInterface) {
                $response = $content;
            }
        }

        // Handle Not Found responses.
        if ($response->getStatusCode() === 404 && isset($this->notFoundCallback)) {
            $content = call_user_func($this->notFoundCallback, $response);
            if (is_string($content)) {
                $response = $response->withBody(new Stream($content));
            }

            if ($content instanceof ResponseInterface) {
                $response = $content;
            }
        }

        echo $response->getBody()->__toString();
    }

    /**
     * Set the dependency injection container.
     *
     * @param ContainerInterface $container The dependency injection container.
     */
    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    /**
     * Set the HTTP request router.
     *
     * @param RouterInterface $router The HTTP request router.
     */
    public function setRouter(RouterInterface $router): void
    {
        $this->router = $router;
    }
}
