<?php

use Horus\Core\Container\Container;
use Horus\Core\Http\Router\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function testNestedPrefix(): void
    {
        $container = new Container();
        $router = new Router($container);

        $router->prefix("/foo", function (Router $router) {
            $route1 = $router->get("/bar", ["", ""]);
            $this->assertEquals("/foo/bar", $route1->getPath());

            $router->prefix("/baz", function (Router $router) {
                $route2 = $router->get("/bar", ["", ""]);
                $this->assertEquals("/foo/baz/bar", $route2->getPath());
            });

            $route3 = $router->get("/bar", ["", ""]);
            $this->assertEquals("/foo/bar", $route3->getPath());
        });
    }

    public function testPrefix(): void
    {
        $container = new Container();
        $router = new Router($container);

        $route1 = $router->get("/bar", ["", ""]);
        $this->assertEquals("/bar", $route1->getPath());

        $router->prefix("/foo", function (Router $router) {
            $route2 = $router->get("/bar", ["", ""]);
            $this->assertEquals("/foo/bar", $route2->getPath());
        });

        $route3 = $router->get("/bar", ["", ""]);
        $this->assertEquals("/bar", $route3->getPath());
    }

    public function testPrefixFormatted(): void
    {
        $container = new Container();
        $router = new Router($container);

        $route1 = $router->get("///bar/", ["", ""]);
        $this->assertEquals("/bar", $route1->getPath());

        $router->prefix("/foo/", function (Router $router) {
            $route2 = $router->get("/bar", ["", ""]);
            $this->assertEquals("/foo/bar", $route2->getPath());
        });

        $route3 = $router->get("bar//", ["", ""]);
        $this->assertEquals("/bar", $route3->getPath());
    }
}
