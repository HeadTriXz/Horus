<?php

namespace Horus\Core;

use Closure;
use Horus\Core\Container\ContainerInterface;
use Horus\Core\Database\Database;
use Horus\Core\Database\DatabaseInterface;
use Horus\Core\Http\Message\ResponseInterface;
use Horus\Core\Http\Message\ServerRequest;
use Horus\Core\Http\Message\ServerRequestInterface;
use Horus\Core\Http\Message\Stream;
use Horus\Core\Http\Router\RouterInterface;

class Application
{
    protected static ?self $instance = null;

    protected ContainerInterface $container;
    protected RouterInterface $router;
    protected Closure $exceptionCallback;
    protected Closure $notFoundCallback;

    private function __construct()
    {
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function getDatabase(): DatabaseInterface
    {
        return $this->container->get(Database::class);
    }

    public static function getInstance(): static
    {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function getRouter(): RouterInterface
    {
        return $this->router;
    }

    public function handleException(callable $callback): void
    {
        $this->exceptionCallback = $callback;
    }

    public function handleNotFound(callable $callback): void
    {
        $this->notFoundCallback = $callback;
    }

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

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function setRouter(RouterInterface $router): void
    {
        $this->router = $router;
    }
}
