<?php

use Horus\Core\Http\Message\Request;
use Horus\Core\Http\Message\RequestInterface;
use Horus\Core\Http\Message\Stream;
use Horus\Core\Http\Message\Uri;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function getRequest(
        string $method = "GET",
        string $uri = "https://example.com:443/foo/bar?abc=123",
        array $headers = [],
        string $body = ""
    ): RequestInterface {
        $uri = new Uri($uri);
        $body = new Stream($body);

        return new Request($method, $uri, $headers, $body);
    }

    public function testAddsHostHeaderFromUri()
    {
        $request = $this->getRequest();
        $this->assertEquals("example.com", $request->getHeaderLine("Host"));
    }

    public function testGetMethod()
    {
        $this->assertEquals("GET", $this->getRequest()->getMethod());
    }

    public function testWithMethod()
    {
        $request = $this->getRequest()->withMethod("PUT");
        $this->assertEquals("PUT", $request->getMethod());
    }

    public function testWithMethodCaseSensitive()
    {
        $request = $this->getRequest()->withMethod("pOsT");
        $this->assertEquals("pOsT", $request->getMethod());
    }

    public function testWithMethodInvalid()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getRequest()->withMethod("B@R");
    }

    public function testCreateRequestWithInvalidMethodString()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getRequest("B@R");
    }

    public function testGetRequestTarget()
    {
        $this->assertEquals("/foo/bar?abc=123", $this->getRequest()->getRequestTarget());
    }

    public function testWithRequestTarget()
    {
        $clone = $this->getRequest()->withRequestTarget("/test?user=1");
        $this->assertEquals("/test?user=1", $clone->getRequestTarget());
    }

    public function testWithRequestTargetThatHasSpaces()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getRequest()->withRequestTarget("/test/m ore/stuff?user=1");
    }

    public function testWithUri()
    {
        $uri1 = new Uri("https://example.com:443/foo/bar?abc=123");
        $uri2 = new Uri("https://example2.com:443/test?xyz=123");

        $headers = [];
        $body = new Stream("");
        $request = new Request("GET", $uri1, $headers, $body);
        $clone = $request->withUri($uri2);

        $this->assertSame($uri2, $clone->getUri());
    }

    public function testWithUriPreservesHost()
    {
        $uri1 = new Uri("");
        $uri2 = new Uri("http://example2.com/test");

        $headers = [];
        $body = new Stream("");
        $request = new Request("GET", $uri1, $headers, $body);

        $clone = $request->withUri($uri2, true);
        $this->assertSame("example2.com", $clone->getHeaderLine("Host"));

        $uri3 = new Uri("");

        $clone = $request->withUri($uri3, true);
        $this->assertSame("", $clone->getHeaderLine("Host"));

        $request = $request->withHeader("Host", "example.com");
        $clone = $request->withUri($uri2, true);
        $this->assertSame("example.com", $clone->getHeaderLine("Host"));
    }
}
