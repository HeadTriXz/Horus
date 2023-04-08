<?php

namespace Horus\Core\Controller;

use Horus\Core\Http\Message\Response;
use Horus\Core\Http\Message\ResponseInterface;

abstract class BaseController
{
    public function redirect(string $path): ResponseInterface
    {
        return new Response(302, "Found", [
            "Location" => $path
        ]);
    }
}
