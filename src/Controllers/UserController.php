<?php

namespace Horus\Controllers;

use Horus\Core\Controller\BaseController;
use Horus\Core\View\View;

class UserController extends BaseController
{
    public function index(): string
    {
        return View::render("home.php", [
            "user" => "Peter"
        ]); // Random page.
    }
}
