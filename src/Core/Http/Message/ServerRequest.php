<?php

namespace Horus\Core\Http\Message;

/**
 * Represents an incoming, server-side HTTP request.
 */
class ServerRequest extends Request implements ServerRequestInterface
{
    /**
     * Represents an incoming, server-side HTTP request.
     *
     * @param string $method The request method.
     * @param UriInterface $uri The request URI.
     * @param array $headers An array of headers.
     * @param ?StreamInterface $body The body of the request.
     * @param string $protocol The protocol version of the request.
     * @param array $serverParams An array of server parameters.
     * @param array $cookieParams An array of cookie parameters.
     * @param array $queryParams An array of query parameters.
     * @param array $attributes Attribute values derived from the request.
     * @param array | object | null $parsedBody Parsed body parameters.
     */
    public function __construct(
        string $method,
        UriInterface $uri,
        array $headers = [],
        StreamInterface $body = null,
        string $protocol = "1.1",
        protected array $serverParams = [],
        protected array $cookieParams = [],
        protected array $queryParams = [],
        protected array $attributes = [],
        protected array | object | null $parsedBody = null
    ) {
        parent::__construct($method, $uri, $headers, $body, $protocol);
    }

    /**
     * Returns a new instance using super globals.
     *
     * @return static
     */
    public static function fromGlobals(): static
    {
        return new static(
            method: $_SERVER["REQUEST_METHOD"] ?? "GET",
            uri: Uri::fromGlobals(),
            headers: getallheaders() ?? [],
            body: new Stream(fopen("php://input", "r+")),
            protocol: $_SERVER["SERVER_PROTOCOL"] ?? "1.1",
            serverParams: $_SERVER,
            cookieParams: $_COOKIE ?? [],
            queryParams: $_GET ?? [],
            parsedBody: $_POST ?? null
        );
    }

    /**
     * Retrieve server parameters.
     *
     * @return array The server parameters.
     */
    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    /**
     * Retrieve cookies sent by the client to the server.
     *
     * @return array The cookies.
     */
    public function getCookieParams(): array
    {
        return $this->cookieParams;
    }

    /**
     * Return an instance with the specified cookies.
     *
     * @param array $cookies Array of key/value pairs representing cookies.
     * @return $this
     */
    public function withCookieParams(array $cookies): static
    {
        $clone = clone $this;
        $clone->cookieParams = $cookies;

        return $clone;
    }

    /**
     * Retrieve the deserialized query string arguments, if any.
     *
     * @return array The query string arguments.
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * Return an instance with the specified query string arguments.
     *
     * @param array $query Array of query string arguments.
     * @return $this
     */
    public function withQueryParams(array $query): static
    {
        $clone = clone $this;
        $clone->queryParams = $query;

        return $clone;
    }

    /**
     * Retrieve any parameters provided in the request body.
     *
     * @return object|array|null The deserialized body parameters, if any.
     */
    public function getParsedBody(): object | array | null
    {
        return $this->parsedBody;
    }

    /**
     * Return an instance with the specified body parameters.
     *
     * @param object|array|null $data The deserialized body data.
     * @return $this
     */
    public function withParsedBody(object | array | null $data): static
    {
        $clone = clone $this;
        $clone->parsedBody = $data;

        return $clone;
    }

    /**
     * Retrieve attributes derived from the request.
     *
     * @return array Attributes derived from the request.
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Retrieve a single derived request attribute.
     *
     * @param string $name The attribute name.
     * @param ?mixed $default Default value to return if the attribute does not exist.
     * @return mixed
     */
    public function getAttribute(string $name, mixed $default = null): mixed
    {
        if (!array_key_exists($name, $this->attributes)) {
            return $default;
        }

        return $this->attributes[$name];
    }

    /**
     * Return an instance with the specified derived request attribute.
     *
     * @param string $name The attribute name.
     * @param mixed $value The value of the attribute.
     * @return $this
     */
    public function withAttribute(string $name, mixed $value): static
    {
        $clone = clone $this;
        $clone->attributes[$name] = $value;

        return $clone;
    }

    /**
     * Return an instance that removes the specified derived request attribute.
     *
     * @param string $name The attribute name.
     * @return $this
     */
    public function withoutAttribute(string $name): static
    {
        if (!array_key_exists($name, $this->attributes)) {
            return $this;
        }

        $clone = clone $this;
        unset($clone->attributes[$name]);

        return $clone;
    }
}
