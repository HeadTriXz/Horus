<?php

namespace Horus\Core\Http\Message;

use InvalidArgumentException;

/**
 * Represents an outgoing, client-side request.
 */
class Request extends Message implements RequestInterface
{
    /**
     * An array of valid HTTP methods.
     */
    public const HTTP_METHODS = ["GET", "POST", "PUT", "DELETE", "PATCH"];

    /**
     * The request method.
     *
     * @var string
     */
    protected string $method;

    /**
     * The request target.
     *
     * @var string
     */
    protected string $requestTarget;

    /**
     * The request URI.
     *
     * @var UriInterface
     */
    protected UriInterface $uri;

    /**
     * Represents an outgoing, client-side request.
     *
     * @param string $method The request method.
     * @param UriInterface $uri The request URI.
     * @param array $headers An array of headers.
     * @param ?StreamInterface $body The body of the request.
     * @param string $protocol The protocol version of the request.
     */
    public function __construct(
        string $method,
        UriInterface $uri,
        array $headers = [],
        StreamInterface $body = null,
        string $protocol = "1.1"
    ) {
        if (!in_array(strtoupper($method), self::HTTP_METHODS)) {
            throw new InvalidArgumentException("\$method must be one of \"GET\", \"POST\", \"PUT\", \"DELETE\", or \"PATCH\".");
        }

        $this->setHeaders($headers);
        $this->method = strtoupper($method);
        $this->uri = $uri;
        $this->body = $body;
        $this->protocol = $protocol;

        if ($uri->getHost() !== "" && !$this->hasHeader("Host") || $this->getHeaderLine("Host") === "") {
            $this->setHeaders(["Host" => $uri->getHost()]);
        }
    }

    /**
     * Retrieves the message's request target.
     *
     * @return string The message's request target.
     */
    public function getRequestTarget(): string
    {
        if (isset($this->requestTarget)) {
            return $this->requestTarget;
        }

        $target = $this->uri->getPath();
        if (empty($target)) {
            $target = "/";
        }

        if (!empty($this->uri->getQuery())) {
            $target .= "?" . $this->uri->getQuery();
        }

        return $target;
    }

    /**
     * Return an instance with the specific request-target.
     *
     * @param string $requestTarget The request target.
     * @return $this
     */
    public function withRequestTarget(string $requestTarget): static
    {
        if (str_contains($requestTarget, " ")) {
            throw new InvalidArgumentException("\$requestTarget may not contain any whitespaces.");
        }

        $clone = clone $this;
        $clone->requestTarget = $requestTarget;

        return $clone;
    }

    /**
     * Retrieves the HTTP method of the request.
     *
     * @return string Returns the request method.
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Return an instance with the provided HTTP method.
     *
     * @param string $method Case-sensitive method.
     *
     * @throws InvalidArgumentException for invalid HTTP methods.
     * @return $this
     */
    public function withMethod(string $method): static
    {
        if (!in_array(strtoupper($method), self::HTTP_METHODS)) {
            throw new InvalidArgumentException("\$method must be one of \"GET\", \"POST\", \"PUT\", \"DELETE\", or \"PATCH\".");
        }

        $clone = clone $this;
        $clone->method = $method;

        return $clone;
    }

    /**
     * Retrieves the URI instance.
     *
     * @return UriInterface Returns a UriInterface instance
     *     representing the URI of the request.
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * Returns an instance with the provided URI.
     *
     * @param UriInterface $uri New request URI to use.
     * @param bool $preserveHost Preserve the original state of the Host header.
     * @return $this
     */
    public function withUri(UriInterface $uri, bool $preserveHost = false): static
    {
        $clone = clone $this;
        $clone->uri = $uri;

        if ($uri->getHost() !== "" && (!$preserveHost || !$this->hasHeader("Host")) || $this->getHeaderLine("Host") === "") {
            return $clone->withHeader("Host", $uri->getHost());
        }

        return $clone;
    }
}
