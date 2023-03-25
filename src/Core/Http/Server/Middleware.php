<?php

namespace Horus\Core\Http\Server;

use Horus\Core\Http\Message\Interfaces\ResponseInterface;
use Horus\Core\Http\Message\Interfaces\ServerRequestInterface;
use Horus\Core\Http\Server\Interfaces\MiddlewareInterface;
use Horus\Core\Http\Server\Interfaces\RequestHandlerInterface;

/**
 * Participant in processing a server request and response.
 */
class Middleware implements MiddlewareInterface
{
    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // TODO: Implement process() method.
    }
}
