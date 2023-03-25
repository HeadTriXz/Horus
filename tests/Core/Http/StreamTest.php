<?php

use Horus\Core\Http\Message\Stream;
use PHPUnit\Framework\TestCase;

class StreamTest extends TestCase
{
    public function testClose()
    {
        $resource = fopen("php://temp", "r+");
        $stream = new Stream($resource);
        $stream->close();

        $this->assertFalse($stream->isSeekable());
        $this->assertFalse($stream->isReadable());
        $this->assertFalse($stream->isWritable());
        $this->assertNull($stream->getSize());
        $this->assertEmpty($stream->getMetadata());
    }

    public function testEof()
    {
        $resource = fopen("php://temp", "w+");
        $stream = new Stream($resource);
        $stream->write("Hello world!");

        $this->assertFalse($stream->eof());

        $stream->read(12);
        $this->assertTrue($stream->eof());
        $stream->close();
    }

    public function testIsWriteable()
    {
        $resource = fopen("php://temp", "w");
        $stream = new Stream($resource);

        $this->assertEquals(12, $stream->write("Hello world!"));

        $this->assertTrue($stream->isWritable());
    }

    public function testIsReadable()
    {
        $resource = fopen("php://temp", "r");
        $stream = new Stream($resource);

        $this->assertTrue($stream->isReadable());
        $this->assertFalse($stream->isWritable());
    }

    public function testIsWritableAndReadable()
    {
        $resource = fopen("php://temp", "w+");
        $stream = new Stream($resource);

        $stream->write("Hello world!");

        $this->assertEquals("Hello world!", $stream);

        $this->assertTrue($stream->isWritable());
        $this->assertTrue($stream->isReadable());
    }

    public function testPosition()
    {
        $resource = fopen("php://temp", "w+");
        $stream = new Stream($resource);

        $this->assertEquals(0, $stream->tell());

        $stream->write("foo");
        $this->assertEquals(3, $stream->tell());

        $stream->seek(1);
        $this->assertEquals(1, $stream->tell());
        $this->assertSame(ftell($resource), $stream->tell());
        $stream->close();
    }

    public function testString()
    {
        $stream = new Stream("Hello world!");
        $this->assertEquals("", $stream->getContents());
        $this->assertEquals("Hello world!", $stream->__toString());
        $stream->close();
    }
}
