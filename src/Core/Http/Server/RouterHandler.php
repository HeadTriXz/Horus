<?php

namespace Horus\Core\Http\Server;

use Horus\Core\Container\ContainerException;
use Horus\Core\Container\ContainerInterface;
use Horus\Core\Http\Message\Response;
use Horus\Core\Http\Message\ResponseInterface;
use Horus\Core\Http\Message\ServerRequestInterface;
use Horus\Core\Http\Message\Stream;
use Horus\Core\Http\Router\Route;

class RouterHandler implements RequestHandlerInterface
{
    public function __construct(
        protected Route $route,
        protected ContainerInterface $container
    ) {}

    /**
     * @throws ContainerException Error while retrieving the entry.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $controller = $this->container->get($this->route->getController());
        $content = call_user_func([$controller, $this->route->getControllerMethod()], $request);

        if (is_string($content)) {
            return new Response(200, body: new Stream($content));
        }

        if ($content instanceof ResponseInterface) {
            return $content;
        }

        throw new \InvalidArgumentException("Controller method should return string or ResponseInterface");
    }
}
