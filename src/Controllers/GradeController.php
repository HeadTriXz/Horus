<?php

namespace Horus\Controllers;

use Horus\Core\Controller\BaseController;
use Horus\Core\View\View;

class GradeController extends BaseController
{
    public function index(): string
    {
        return View::render("Enroll/index.php");
    }
}
