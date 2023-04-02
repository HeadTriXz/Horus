<?php

namespace Horus\Core\Http\Message;

use InvalidArgumentException;

/**
 * Represents an outgoing, server-side response.
 */
class Response extends Message implements ResponseInterface
{
    protected int $statusCode;
    protected string $statusText = "";

    /**
     * Represents an outgoing, server-side response.
     */
    public function __construct(
        int $statusCode = 200,
        string $statusText = "",
        array $headers = [],
        StreamInterface $body = null,
        string $protocol = "1.1"
    ) {
        if ($statusCode < 100 || $statusCode > 599) {
            throw new InvalidArgumentException("Invalid HTTP status code: " . $statusCode);
        }

        $this->setHeaders($headers);
        $this->statusCode = $statusCode;
        $this->statusText = $statusText;
        $this->body = $body;
        $this->protocol = $protocol;
    }

    /**
     * Gets the response status code.
     *
     * The status code is a 3-digit integer result code of the server's attempt
     * to understand and satisfy the request.
     *
     * @return int Status code.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Return an instance with the specified status code and, optionally, reason phrase.
     *
     * @param int $code The 3-digit integer result code to set.
     * @param string $reasonPhrase The reason phrase to use with the
     *     provided status code.
     *
     * @throws InvalidArgumentException For invalid status code arguments.
     * @return $this
     */
    public function withStatus(int $code, string $reasonPhrase = ""): static
    {
        if ($code < 100 || $code > 599) {
            throw new InvalidArgumentException("Invalid HTTP status code: " . $code);
        }

        $clone = clone $this;
        $clone->statusCode = $code;
        $clone->statusText = $reasonPhrase;

        return $clone;
    }

    /**
     * Gets the response reason phrase associated with the status code.
     *
     * @return string Reason phrase; returns an empty string if none present.
     */
    public function getReasonPhrase(): string
    {
        return $this->statusText;
    }
}
