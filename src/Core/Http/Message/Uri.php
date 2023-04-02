<?php

namespace Horus\Core\Http\Message;

use InvalidArgumentException;

/**
 * Value object representing a URI.
 */
class Uri implements UriInterface
{
    protected const STANDARD_PORTS = [
        "http" => 80,
        "https" => 443,
        "ftp" => 21
    ];

    protected string $scheme = "";
    protected string $userInfo = "";
    protected string $host = "";
    protected ?int $port = null;
    protected string $path = "";
    protected string $query = "";
    protected string $fragment = "";

    /**
     * Value object representing a URI.
     *
     * @param string $uri A string representation of a URI.
     */
    public function __construct(string $uri = "")
    {
        if ($uri === "") {
            return;
        }

        $parsedUri = parse_url($uri);
        if ($parsedUri === false) {
            throw new InvalidArgumentException("Invalid URI: $uri");
        }

        if (isset($parsedUri["scheme"])) {
            $this->scheme = strtolower($parsedUri["scheme"]);
        }

        if (isset($parsedUri["user"])) {
            $this->userInfo = $this->encode($parsedUri["user"]);
            if (isset($parsedUri["pass"])) {
                $this->userInfo .= ":" . $this->encode($parsedUri["pass"]);
            }
        }

        if (isset($parsedUri["host"])) {
            $this->host = strtolower($parsedUri["host"]);
        }

        if (isset($parsedUri["port"])) {
            $this->port = $parsedUri["port"];
        }

        if (isset($parsedUri["path"])) {
            $this->path = $this->encodePath($parsedUri["path"]);
        }

        if (isset($parsedUri["query"])) {
            $this->query = $this->encodeQuery($parsedUri["query"]);
        }

        if (isset($parsedUri["fragment"])) {
            $this->fragment = $this->encodeFragment($parsedUri["fragment"]);
        }

        if ($this->port === static::STANDARD_PORTS[$this->scheme]) {
            $this->port = null;
        }
    }

    /**
     * Returns a new instance using super globals.
     *
     * @return static
     */
    public static function fromGlobals(): static
    {
        $protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off")
            || $_SERVER["SERVER_PORT"] === 443 ? "https" : "http";
        $host = $_SERVER["HTTP_HOST"] ?? $_SERVER["SERVER_NAME"];
        $port = $_SERVER["SERVER_PORT"];
        $path = $_SERVER["REQUEST_URI"];
        $query = $_SERVER["QUERY_STRING"] ?? "";

        return new Uri(sprintf('%s://%s:%d%s%s', $protocol, $host, $port, $path, $query));
    }

    /**
     * Retrieve the scheme component of the URI.
     *
     * @return string The URI scheme.
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * Retrieve the authority component of the URI.
     *
     * The authority syntax of the URI is:
     *
     * <pre>
     * [user-info@]host[:port]
     * </pre>
     *
     * @return string The URI authority, in "[user-info@]host[:port]" format.
     */
    public function getAuthority(): string
    {
        $authority = $this->host;
        if ($this->userInfo !== "") {
            $authority = $this->userInfo . "@" . $authority;
        }

        if ($this->port !== null) {
            $authority .= ":" . $this->port;
        }

        return $authority;
    }

    /**
     * Retrieve the user information component of the URI.
     *
     * @return string The URI user information, in "username[:password]" format.
     */
    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    /**
     * Retrieve the host component of the URI.
     *
     * @return string The URI host.
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Retrieve the port component of the URI.
     *
     * @return ?int The URI port.
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * Retrieve the path component of the URI.
     *
     * @return string The URI path.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Retrieve the query string of the URI.
     *
     * @return string The URI query string.
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * Retrieve the fragment component of the URI.
     *
     * @return string The URI fragment.
     */
    public function getFragment(): string
    {
        return $this->fragment;
    }

    /**
     * Return an instance with the specified scheme.
     *
     * @param string $scheme The scheme to use with the new instance.
     *
     * @throws InvalidArgumentException for invalid or unsupported schemes.
     * @return $this A new instance with the specified scheme.
     */
    public function withScheme(string $scheme): static
    {
        $scheme = strtok(strtolower($scheme), ":");
        if ($scheme === $this->scheme) {
            return $this;
        }

        $clone = clone $this;
        $clone->scheme = $scheme;
        if ($clone->port === static::STANDARD_PORTS[$scheme]) {
            $clone->port = null;
        }

        return $clone;
    }

    /**
     * Return an instance with the specified user information.
     *
     * @param string $user The username to use for authority.
     * @param ?string $password The password associated with $user.
     * @return $this A new instance with the specified user information.
     */
    public function withUserInfo(string $user, string $password = null): static
    {
        $userInfo = $this->encode($user);
        if ($password !== null && $user !== "") {
            $userInfo .= ":" . $this->encode($password);
        }

        if ($userInfo === $this->userInfo) {
            return $this;
        }

        $clone = clone $this;
        $clone->userInfo = $userInfo;

        return $clone;
    }

    /**
     * Return an instance with the specified host.
     *
     * @param string $host The hostname to use with the new instance.
     *
     * @throws InvalidArgumentException for invalid hostnames.
     * @return $this A new instance with the specified host.
     */
    public function withHost(string $host): static
    {
        $host = strtolower($host);
        if ($host === $this->host) {
            return $this;
        }

        $clone = clone $this;
        $clone->host = $host;

        return $clone;
    }

    /**
     * Return an instance with the specified port.
     *
     * @param ?int $port The port to use with the new instance; a null value
     *     removes the port information.
     *
     * @throws InvalidArgumentException for invalid ports.
     * @return $this A new instance with the specified port.
     */
    public function withPort(?int $port): static
    {
        if (isset($this->port) && $port === $this->port) {
            return $this;
        }

        $clone = clone $this;
        $clone->port = $port;
        if ($port === null) {
            return $clone;
        }

        if ($port < 1 || $port > 65535) {
            throw new InvalidArgumentException("Provided port is invalid. Must be between 0 and 65535.");
        }

        if ($clone->port === static::STANDARD_PORTS[$clone->getScheme()]) {
            $clone->port = null;
        }

        return $clone;
    }

    /**
     * Return an instance with the specified path.
     *
     * @param string $path The path to use with the new instance.
     *
     * @throws InvalidArgumentException for invalid paths.
     * @return $this A new instance with the specified path.
     */
    public function withPath(string $path): static
    {
        $path = $this->encodePath($path);
        if ($path === $this->path) {
            return $this;
        }

        $clone = clone $this;
        $clone->path = $path;

        return $clone;
    }

    /**
     * Return an instance with the specified query string.
     *
     * @param string $query The query string to use with the new instance.
     *
     * @throws InvalidArgumentException for invalid query strings.
     * @return $this A new instance with the specified query string.
     */
    public function withQuery(string $query): static
    {
        $query = $this->encodeQuery($query);
        if ($query === $this->query) {
            return $this;
        }

        $clone = clone $this;
        $clone->query = $query;

        return $clone;
    }

    /**
     * Return an instance with the specified URI fragment.
     *
     * @param string $fragment The fragment to use with the new instance.
     * @return $this A new instance with the specified fragment.
     */
    public function withFragment(string $fragment): static
    {
        $fragment = $this->encodeFragment($fragment);
        if ($fragment === $this->fragment) {
            return $this;
        }

        $clone = clone $this;
        $clone->fragment = $fragment;

        return $clone;
    }

    /**
     * Encodes the raw URI fragment.
     *
     * @param string $fragment The fragment to encode.
     * @return string The encoded fragment.
     */
    protected function encodeFragment(string $fragment): string
    {
        $fragment = ltrim($fragment, '#');

        if ($fragment === "") {
            return "";
        }

        return $this->encode($fragment);
    }

    /**
     * Encodes the raw URI path.
     *
     * @param string $path The path to encode.
     * @return string The encoded path.
     */
    protected function encodePath(string $path): string
    {
        if ($path === "") {
            return "";
        }

        return implode("/", array_map(function ($str) {
            return $this->encode($str);
        }, explode("/", $path)));
    }

    /**
     * Encodes the raw URI query.
     *
     * @param string $query The query to encode.
     * @return string The encoded query.
     */
    protected function encodeQuery(string $query): string
    {
        $query = ltrim($query, "?");
        if ($query === "") {
            return "";
        }

        if (!str_contains($query, "=")) {
            return $this->encode($query);
        }

        return implode("&", array_map(function ($str) {
            list($name, $value) = explode("=", $str, 2);
            return $this->encode($name) . "=" . $this->encode($value);
        }, explode("&", $query)));
    }

    /**
     * Utility function to encode a URI component. But will not double-encode it.
     *
     * @param string $value The component to encode.
     * @return string The encoded component.
     */
    protected function encode(string $value): string
    {
        return rawurlencode(rawurldecode($value));
    }

    /**
     * Return the string representation as a URI reference.
     *
     * @return string
     */
    public function __toString()
    {
        $uri = "";
        if ($this->getScheme() !== "") {
            $uri .= $this->getScheme() . ":";
        }

        $path = $this->getPath();
        if ($this->getAuthority() !== "") {
            $uri .= "//" . $this->getAuthority();

            if ($path !== "" && $path[0] !== "/") {
                $path = "/" . $path;
            }
        } elseif ($path !== "" && str_starts_with($path, "//")) {
            $path = "/" . ltrim($path, "/");
        }

        $uri .= $path;
        if ($this->getQuery() !== "") {
            $uri .= "?" . $this->getQuery();
        }

        if ($this->getFragment() !== "") {
            $uri .= '#' . $this->getFragment();
        }

        return $uri;
    }
}
