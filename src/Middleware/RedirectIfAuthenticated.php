<?php

namespace Horus\Middleware;

use Horus\Auth;
use Horus\Core\Http\Message\Response;
use Horus\Core\Http\Message\ResponseInterface;
use Horus\Core\Http\Message\ServerRequestInterface;
use Horus\Core\Http\Server\MiddlewareInterface;
use Horus\Core\Http\Server\RequestHandlerInterface;

/**
 * Middleware that redirects authenticated users to the home page.
 */
class RedirectIfAuthenticated implements MiddlewareInterface
{
    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     *
     * @param ServerRequestInterface $request The incoming server request.
     * @param RequestHandlerInterface $handler The next handler in the middleware stack.
     *
     * @return ResponseInterface The response produced by the middleware.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (Auth::check()) {
            return new Response(302, "Found", [
                "Location" => route("home")
            ]);
        }

        return $handler->handle($request);
    }
}
