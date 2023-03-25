<?php

namespace Horus\Core\Http\Message;

use Horus\Core\Http\Message\Interfaces\MessageInterface;
use Horus\Core\Http\Message\Interfaces\StreamInterface;
use InvalidArgumentException;

/**
 * Represents a HTTP message.
 */
abstract class Message implements MessageInterface
{
    protected ?StreamInterface $body;
    protected array $headers = [];
    protected array $headerNameMap = [];
    protected string $protocol = "1.1";

    /**
     * Retrieves the HTTP protocol version as a string.
     *
     * @return string HTTP protocol version.
     */
    public function getProtocolVersion(): string
    {
        return $this->protocol;
    }

    /**
     * Return an instance with the specified HTTP protocol version.
     *
     * @param string $version HTTP protocol version.
     * @return $this
     */
    public function withProtocolVersion(string $version): static
    {
        if ($version === $this->protocol) {
            return $this;
        }

        $clone = clone $this;
        $clone->protocol = $version;

        return $clone;
    }

    /**
     * Retrieves all message header values.
     *
     * @return string[][] Returns an associative array of the message's headers.
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Checks if a header exists by the given case-insensitive name.
     *
     * @param string $name Case-insensitive header field name.
     * @return bool Returns true if any header names match the given header
     *     name using a case-insensitive string comparison. Returns false if
     *     no matching header name is found in the message.
     */
    public function hasHeader(string $name): bool
    {
        return isset($this->headerNameMap[strtolower($name)]);
    }

    /**
     * Retrieves a message header value by the given case-insensitive name.
     *
     * @param string $name Case-insensitive header field name.
     * @return string[] An array of string values as provided for the given header.
     */
    public function getHeader(string $name): array
    {
        if (!$this->hasHeader($name)) {
            return [];
        }

        return $this->headers[$this->headerNameMap[strtolower($name)]];
    }

    /**
     * Retrieves a comma-separated string of the values for a single header.
     *
     * @param string $name Case-insensitive header field name.
     * @return string A string of values as provided for the given header
     *    concatenated together using a comma.
     */
    public function getHeaderLine(string $name): string
    {
        return implode(",", $this->getHeader($name));
    }

    /**
     * Return an instance with the provided value replacing the specified header.
     *
     * @param string $name Case-insensitive header field name.
     * @param array|string $value Header value(s).
     *
     * @throws InvalidArgumentException for invalid header names or values.
     * @return $this
     */
    public function withHeader(string $name, array | string $value): static
    {
        $clone = clone $this;
        $clone->setHeaders([$name => $value]);

        return $clone;
    }

    /**
     * Return an instance with the specified header appended with the given value.
     *
     * @param string $name Case-insensitive header field name to add.
     * @param array|string $value Header value(s).
     *
     * @throws InvalidArgumentException for invalid header names or values.
     * @return $this
     */
    public function withAddedHeader(string $name, array | string $value): static
    {
        $this->validateHeader($name, $value);
        $values = $this->trimHeaderValue($value);

        $clone = clone $this;
        if ($clone->hasHeader($name)) {
            $header = $clone->headerNameMap[strtolower($name)];
            $clone->headers[$header] = array_merge($clone->headers[$header], $values);
        } else {
            $clone->headers[$name] = $values;
            $clone->headerNameMap[strtolower($name)] = $name;
        }

        return $clone;
    }

    /**
     * Return an instance without the specified header.
     *
     * @param string $name Case-insensitive header field name to remove.
     * @return $this
     */
    public function withoutHeader(string $name): static
    {
        if (!$this->hasHeader($name)) {
            return $this;
        }

        $clone = clone $this;
        $headerName = $clone->headerNameMap[strtolower($name)];
        unset($clone->headers[$headerName], $clone->headerNameMap[strtolower($name)]);

        return $clone;
    }

    /**
     * Gets the body of the message.
     *
     * @return StreamInterface Returns the body as a stream.
     */
    public function getBody(): StreamInterface
    {
        if ($this->body === null) {
            return new Stream("");
        }

        return $this->body;
    }

    /**
     * Return an instance with the specified message body.
     *
     * @param StreamInterface $body Body.
     *
     * @throws InvalidArgumentException When the body is not valid.
     * @return $this
     */
    public function withBody(StreamInterface $body): static
    {
        if ($body === $this->body) {
            return $this;
        }

        $clone = clone $this;
        $clone->body = $body;

        return $clone;
    }

    /**
     * Initialize the headers. WARNING: Only use in constructors.
     *
     * @param array $headers The initial headers.
     *
     * @throws InvalidArgumentException for invalid header names or values.
     * @return $this
     */
    protected function setHeaders(array $headers): static
    {
        foreach ($headers as $name => $value) {
            $this->validateHeader($name, $value);
            $values = $this->trimHeaderValue($value);

            if ($this->hasHeader($name)) {
                unset($this->headers[$this->headerNameMap[strtolower($name)]]);
            }

            $this->headers[$name] = $values;
            $this->headerNameMap[strtolower($name)] = $name;
        }

        return $this;
    }

    /**
     * Trims whitespace from the beginning and end of each value in the given array or string.
     *
     * @param array|string $value The array or string to trim.
     * @return array The trimmed values in an array.
     */
    protected function trimHeaderValue(array | string $value): array
    {
        $values = is_array($value) ? $value : [$value];
        return array_map(fn($v) => trim($v, " \t"), $values);
    }

    /**
     * Validates the given header name and value according to RFC 7230.
     *
     * @param string $name The header name to validate.
     * @param array|string $value The header value or values to validate.
     *
     * @throws InvalidArgumentException if the header name or values are not RFC 7230 compatible strings.
     * @return void
     */
    protected function validateHeader(string $name, array | string $value): void
    {
        if (!preg_match("@^[!#$%&'*+.^_`|~0-9A-Za-z-]+$@", $name)) {
            throw new InvalidArgumentException("Header name must be an RFC 7230 compatible string.");
        }

        $values = is_array($value) ? $value : [$value];
        foreach ($values as $val) {
            if (!preg_match("@^[ \t\x21-\x7E\x80-\xFF]*$@", $val)) {
                throw new InvalidArgumentException("Header values must be RFC 7230 compatible strings.");
            }
        }
    }
}
