<?php

namespace Horus\Middleware;

use Horus\Auth;
use Horus\Core\Http\Message\Response;
use Horus\Core\Http\Message\ResponseInterface;
use Horus\Core\Http\Message\ServerRequestInterface;
use Horus\Core\Http\Server\MiddlewareInterface;
use Horus\Core\Http\Server\RequestHandlerInterface;

class HasRole implements MiddlewareInterface
{
    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler, string $role = null): ResponseInterface
    {
        $user = Auth::user();
        if ($role === "admin" && !$user->isAdmin()) {
            return new Response(404, "Not Found");
        }

        if ($role === "teacher" && !$user->isTeacher()) {
            return new Response(404, "Not Found");
        }

        if ($role === "student" && !$user->isStudent()) {
            return new Response(404, "Not Found");
        }

        return $handler->handle($request);
    }
}
