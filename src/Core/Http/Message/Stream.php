<?php

namespace Horus\Core\Http\Message;

use InvalidArgumentException;
use RuntimeException;

/**
 * Represents a data stream.
 */
class Stream implements StreamInterface
{
    /**
     * The underlying stream resource or object.
     *
     * @var string | resource
     */
    protected $stream;

    /**
     * Whether the stream is readable.
     *
     * @var bool
     */
    protected bool $readable;

    /**
     * Whether the stream is seekable.
     *
     * @var bool
     */
    protected bool $seekable;

    /**
     * Whether the stream is writable.
     *
     * @var bool
     */
    protected bool $writable;

    /**
     * The size of the stream.
     *
     * @var ?int
     */
    protected ?int $size;

    /**
     * Represents a data stream.
     *
     * @param string | resource $body The body of the stream.
     */
    public function __construct(mixed $body)
    {
        if (is_string($body)) {
            $resource = fopen("php://temp", "rw+");
            fwrite($resource, $body);
            $body = $resource;
        }

        if (is_resource($body)) {
            $this->stream = $body;

            $meta = $this->getMetadata();
            $this->seekable = $meta["seekable"] && fseek($this->stream, 0, SEEK_CUR) === 0;
            $this->readable = strpbrk($meta["mode"], "r+") !== false;
            $this->writable = strpbrk($meta["mode"], "w+") !== false;
            return;
        }

        throw new InvalidArgumentException("\$body must be of type string or resource.");
    }

    /**
     * Closes the stream and any underlying resources.
     *
     * @return void
     */
    public function close(): void
    {
        if (isset($this->stream) && is_resource($this->stream)) {
            fclose($this->stream);
        }

        $this->detach();
    }

    /**
     * Separates any underlying resources from the stream.
     *
     * After the stream has been detached, the stream is in an unusable state.
     *
     * @return ?resource Underlying PHP stream, if any
     */
    public function detach()
    {
        $stream = $this->stream;
        if (!isset($stream)) {
            return null;
        }

        $this->stream = null;
        $this->readable = false;
        $this->seekable = false;
        $this->writable = false;
        $this->size = null;

        return $stream;
    }

    /**
     * Get the size of the stream if known.
     *
     * @return ?int Returns the size in bytes if known, or null if unknown.
     */
    public function getSize(): ?int
    {
        if (isset($this->stream) && !isset($this->size)) {
            $stats = fstat($this->stream);
            if ($stats) {
                $this->size = $stats["size"];
            }
        }

        return $this->size;
    }

    /**
     * Returns the current position of the file read/write pointer
     *
     * @throws RuntimeException on error.
     * @return int Position of the file pointer
     */
    public function tell(): int
    {
        $position = false;
        if (isset($this->stream)) {
            $position = ftell($this->stream);
        }

        if ($position === false) {
            throw new RuntimeException("Could not get the position of the file pointer.");
        }

        return $position;
    }

    /**
     * Returns true if the stream is at the end of the stream.
     *
     * @return bool
     */
    public function eof(): bool
    {
        return !isset($this->stream) || feof($this->stream);
    }

    /**
     * Returns whether the stream is seekable.
     *
     * @return bool
     */
    public function isSeekable(): bool
    {
        return $this->seekable;
    }

    /**
     * Seek to a position in the stream.
     *
     * @param int $offset Stream offset.
     * @param int $whence Specifies how the cursor position will be calculated
     *     based on the seek offset.
     * @throws RuntimeException on failure.
     */
    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        if (!$this->isSeekable()) {
            throw new RuntimeException("Stream is not seekable.");
        }

        if (!isset($this->stream) || fseek($this->stream, $offset, $whence) === -1) {
            throw new RuntimeException("Could not seek to the position in the stream.");
        }
    }

    /**
     * Seek to the beginning of the stream.
     *
     * @throws RuntimeException on failure.
     */
    public function rewind(): void
    {
        $this->seek(0);
    }

    /**
     * Returns whether the stream is writable.
     *
     * @return bool
     */
    public function isWritable(): bool
    {
        return $this->writable;
    }

    /**
     * Write data to the stream.
     *
     * @param string $string The string that is to be written.
     *
     * @throws RuntimeException on failure.
     * @return int Returns the number of bytes written to the stream.
     */
    public function write(string $string): int
    {
        if (!$this->isWritable()) {
            throw new RuntimeException("Stream is not writable.");
        }

        $bytes = false;
        if (isset($this->stream)) {
            $bytes = fwrite($this->stream, $string);
        }

        if ($bytes === false) {
            throw new RuntimeException("Could not write to the stream.");
        }

        return $bytes;
    }

    /**
     * Returns whether the stream is readable.
     *
     * @return bool
     */
    public function isReadable(): bool
    {
        return $this->readable;
    }

    /**
     * Read data from the stream.
     *
     * @param int $length Read up to $length bytes from the object and return
     *     them. Fewer than $length bytes may be returned if underlying stream
     *     call returns fewer bytes.
     *
     * @throws RuntimeException if an error occurs.
     * @return string Returns the data read from the stream, or an empty string
     *     if no bytes are available.
     */
    public function read(int $length): string
    {
        if (!$this->isReadable()) {
            throw new RuntimeException("Stream is not readable.");
        }

        if ($length <= 0) {
            return "";
        }

        $string = false;
        if (isset($this->stream)) {
            $string = fread($this->stream, $length);
        }

        if ($string === false) {
            throw new RuntimeException("Could not read from the stream.");
        }

        return $string;
    }

    /**
     * Returns the remaining contents in a string
     *
     * @throws RuntimeException if unable to read.
     * @throws RuntimeException if error occurs while reading.
     * @return string
     */
    public function getContents(): string
    {
        $string = false;
        if (isset($this->stream)) {
            $string = stream_get_contents($this->stream);
        }

        if ($string === false) {
            throw new RuntimeException("Could not get the contents of the stream.");
        }

        return $string;
    }

    /**
     * Get stream metadata as an associative array or retrieve a specific key.
     *
     * @param ?string $key Specific metadata to retrieve.
     * @return array|mixed|null Returns an associative array if no key is
     *     provided. Returns a specific key value if a key is provided and the
     *     value is found, or null if the key is not found.
     */
    public function getMetadata(string $key = null): mixed
    {
        if (!isset($this->stream)) {
            return null;
        }

        $meta = stream_get_meta_data($this->stream);
        if (is_null($key)) {
            return $meta;
        }

        return $meta[$key];
    }

    /**
     * Reads all data from the stream into a string, from the beginning to end.
     *
     * @return string All data from the stream.
     */
    public function __toString()
    {
        if ($this->isSeekable()) {
            $this->seek(0);
        }

        return $this->getContents();
    }
}
