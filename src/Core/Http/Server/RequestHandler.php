<?php

namespace Horus\Core\Http\Server;

use Horus\Core\Http\Message\Interfaces\ResponseInterface;
use Horus\Core\Http\Message\Interfaces\ServerRequestInterface;
use Horus\Core\Http\Server\Interfaces\RequestHandlerInterface;

/**
 * Handles a server request and produces a response.
 */
class RequestHandler implements RequestHandlerInterface
{
    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // TODO: Implement handle() method.
    }
}
