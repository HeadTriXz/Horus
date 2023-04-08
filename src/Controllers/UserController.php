<?php

namespace Horus\Controllers;

use Horus\Auth;
use Horus\Core\Controller\BaseController;
use Horus\Core\View\View;

class UserController extends BaseController
{
    public function profile(): string
    {
        return View::render("Profiles/index.php", [
            "user" => Auth::user()->first_name
        ]);
    }
}
