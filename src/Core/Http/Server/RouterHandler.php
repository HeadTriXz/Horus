<?php

namespace Horus\Core\Http\Server;

use Horus\Core\Container\ContainerException;
use Horus\Core\Container\ContainerInterface;
use Horus\Core\Http\Message\Response;
use Horus\Core\Http\Message\ResponseInterface;
use Horus\Core\Http\Message\ServerRequestInterface;
use Horus\Core\Http\Router\Route;

class RouterHandler implements RequestHandlerInterface
{
    protected array $stack;

    public function __construct(
        protected Route $route,
        protected ContainerInterface $container
    ) {
        $this->stack = $this->route->getMiddleware();
    }

    /**
     * @throws ContainerException Error while retrieving the entry.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $routeSegments = explode("/", trim($this->route->getPath(), "/"));
        $requestSegments = explode("/", trim($request->getUri()->getPath(), "/"));

        for ($i = 0; $i < count($routeSegments); $i++) {
            if (str_starts_with($routeSegments[$i], ":")) {
                $key = substr($routeSegments[$i], 1);
                $value = $requestSegments[$i];

                $request = $request->withAttribute($key, $value);
            }
        }

        $middleware = array_shift($this->stack);
        if ($middleware !== null) {
            $chunks = explode(":", $middleware);
            return $this->container
                ->get(array_shift($chunks))
                ->process($request, $this, ...$chunks);
        }

        $controller = $this->container->get($this->route->getController());
        $content = call_user_func([$controller, $this->route->getControllerMethod()], $request);

        if (is_string($content)) {
            return new Response(200, body: $content);
        }

        if ($content instanceof ResponseInterface) {
            return $content;
        }

        throw new \InvalidArgumentException("Controller method should return string or ResponseInterface");
    }
}
