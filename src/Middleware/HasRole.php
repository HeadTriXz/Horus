<?php

namespace Horus\Middleware;

use Horus\Auth;
use Horus\Core\Http\Message\Response;
use Horus\Core\Http\Message\ResponseInterface;
use Horus\Core\Http\Message\ServerRequestInterface;
use Horus\Core\Http\Server\MiddlewareInterface;
use Horus\Core\Http\Server\RequestHandlerInterface;

/**
 * Middleware that authorizes access to routes based on the user's role.
 */
class HasRole implements MiddlewareInterface
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
     * @param string ...$roles The roles that are authorized to access the route.
     *
     * @return ResponseInterface The response produced by the middleware.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler, string ...$roles): ResponseInterface
    {
        $user = Auth::user();
        $authorized = false;

        foreach ($roles as $role) {
            if ($role === "admin" && $user->isAdmin()) {
                $authorized = true;
                break;
            }

            if ($role === "teacher" && $user->isTeacher()) {
                $authorized = true;
                break;
            }

            if ($role === "student" && $user->isStudent()) {
                $authorized = true;
                break;
            }
        }

        if (!$authorized) {
            return new Response(404, "Not Found");
        }

        return $handler->handle($request);
    }
}
