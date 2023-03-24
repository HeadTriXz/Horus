<?php

use Horus\Core\Http\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testGetStatusCode()
    {
        $response = new Response(404);

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testWithStatus()
    {
        $response = new Response();
        $clone = $response->withStatus(302);

        $this->assertEquals(302, $clone->getStatusCode());
    }

    public function testWithStatusInvalidStatusCode()
    {
        $this->expectException(InvalidArgumentException::class);

        $response = new Response();
        $response->withStatus(800);
    }

    public function testWithStatusEmptyReasonPhrase()
    {
        $responseWithNoMessage = new Response(310);

        $this->assertEquals("", $responseWithNoMessage->getReasonPhrase());
    }

    public function testGetReasonPhrase()
    {
        $response = new Response(404, "Not Found");

        $this->assertEquals("Not Found", $response->getReasonPhrase());
    }

    public function testEmptyReasonPhrase()
    {
        $response = new Response();
        $response = $response->withStatus(418);

        $this->assertSame("", $response->getReasonPhrase());
    }

    public function testWithReasonPhrase()
    {
        $response = new Response();
        $clone = $response->withStatus(418, "I'm a teapot");

        $this->assertEquals("I'm a teapot", $clone->getReasonPhrase());
    }
}
