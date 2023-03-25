<?php

use Horus\Core\Http\Message\Message;
use PHPUnit\Framework\TestCase;

class BasicMessage extends Message
{
    public function __construct(array $headers = [])
    {
        $this->setHeaders($headers);
    }
}

class MessageTest extends TestCase
{
    public function testWithProtocolVersion(): void
    {
        $message = new BasicMessage();
        $clone = $message->withProtocolVersion("1.1");

        $this->assertEquals("1.1", $clone->getProtocolVersion());
    }

    public function testWithHeaders(): void
    {
        $message = new BasicMessage();
        $clone = $message->withHeader("Content-Type", "application/json");

        $expected = [
            "Content-Type" => ["application/json"]
        ];

        $this->assertEquals($expected, $clone->getHeaders());
        $this->assertEquals(["application/json"], $clone->getHeader("content-type"));
    }

    public function testGetHeaders(): void
    {
        $token = "Bearer MTExIHlvdSAgdHJpZWQgMTEx.O5rKAA.dQw4w9WgXcQ_wpV-gGA4PSk_bm8";
        $message = new BasicMessage([
            "Accept" => "application/json",
            "Authorization" => $token
        ]);
        $clone = $message->withAddedHeader("accept", "text/html");

        $expected = [
            "Accept" => ["application/json", "text/html"],
            "Authorization" => [$token]
        ];

        $this->assertEquals($expected, $clone->getHeaders());
    }

    public function testInvalidHeaders(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new BasicMessage([
            "<Accept" => "application/json",
        ]);
    }
}
