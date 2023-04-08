<?php

namespace Horus\Controllers;

use Horus\Core\Controller\BaseController;
use Horus\Core\View\View;

class CourseController extends BaseController
{
    public function index(): string
    {
        return View::render("Courses/index.php");
    }
}
