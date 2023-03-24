<?php

use Horus\Core\Http\ServerRequest;
use Horus\Core\Http\Uri;
use PHPUnit\Framework\TestCase;

class ServerRequestTest extends TestCase
{
    public function testServerParams()
    {
        $params = ["name" => "value"];
        $uri = new Uri("/");

        $request = new ServerRequest("GET", $uri, [], null, "1.1", $params);
        $this->assertSame($params, $request->getServerParams());
    }

    public function testCookieParams()
    {
        $uri = new Uri("/");
        $request1 = new ServerRequest("GET", $uri);

        $params = ["name" => "value"];
        $request2 = $request1->withCookieParams($params);

        $this->assertNotSame($request2, $request1);
        $this->assertEmpty($request1->getCookieParams());
        $this->assertSame($params, $request2->getCookieParams());
    }

    public function testQueryParams()
    {
        $uri = new Uri("/");
        $request1 = new ServerRequest("GET", $uri);

        $params = ["name" => "value"];
        $request2 = $request1->withQueryParams($params);

        $this->assertNotSame($request2, $request1);
        $this->assertEmpty($request1->getQueryParams());
        $this->assertSame($params, $request2->getQueryParams());
    }

    public function testParsedBody()
    {
        $uri = new Uri("/");
        $request1 = new ServerRequest("GET", $uri);

        $params = ["name" => "value"];
        $request2 = $request1->withParsedBody($params);

        $this->assertNotSame($request2, $request1);
        $this->assertEmpty($request1->getParsedBody());
        $this->assertSame($params, $request2->getParsedBody());
    }

    public function testAttributes()
    {
        $uri = new Uri("/");
        $request1 = new ServerRequest("GET", $uri);

        $request2 = $request1->withAttribute("name", "value");
        $request3 = $request2->withAttribute("other", "otherValue");
        $request4 = $request3->withoutAttribute("other");
        $request5 = $request3->withoutAttribute("unknown");

        $this->assertNotSame($request2, $request1);
        $this->assertNotSame($request3, $request2);
        $this->assertNotSame($request4, $request3);
        $this->assertNotSame($request5, $request4);

        $this->assertEmpty($request1->getAttributes());
        $this->assertEmpty($request1->getAttribute("name"));
        $this->assertEquals("something", $request1->getAttribute("name", "something"));

        $this->assertEquals("value", $request2->getAttribute("name"));
        $this->assertEquals(["name" => "value"], $request2->getAttributes());
        $this->assertEquals(["name" => "value", "other" => "otherValue"], $request3->getAttributes());
        $this->assertEquals(["name" => "value"], $request4->getAttributes());
    }

    public function testNullAttribute()
    {
        $uri = new Uri("/");
        $request = (new ServerRequest("GET", $uri))
            ->withAttribute("name", null);

        $this->assertSame(["name" => null], $request->getAttributes());
        $this->assertNull($request->getAttribute("name", "different-default"));

        $requestWithoutAttribute = $request->withoutAttribute("name");

        $this->assertSame([], $requestWithoutAttribute->getAttributes());
        $this->assertSame("different-default", $requestWithoutAttribute->getAttribute("name", "different-default"));
    }
}
