<?php

namespace Horus\Core\Controller;

use Horus\Core\Http\Message\Response;
use Horus\Core\Http\Message\ResponseInterface;

/**
 * The base controller class.
 */
abstract class BaseController
{
    /**
     * Redirects the user to the specified path.
     *
     * @param string $path The path to redirect the user to.
     * @return ResponseInterface The HTTP response object.
     */
    public function redirect(string $path): ResponseInterface
    {
        return new Response(302, "Found", [
            "Location" => $path
        ]);
    }
}
