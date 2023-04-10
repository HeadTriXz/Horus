<?php

namespace Horus\Controllers;

use Horus\Auth;
use Horus\Core\Controller\BaseController;
use Horus\Core\View\View;
use Horus\Models\Exam;
use Horus\Models\Grade;

class HomeController extends BaseController
{
    public function index(): string
    {
        $grades = Grade::createQueryBuilder()
            ->select()
            ->where("student_id = ?", Auth::id())
            ->orderBy("created_at", "DESC")
            ->limit(3)
            ->getAll();

        $exams = Exam::createQueryBuilder()
            ->select()
            ->from("exams", "e")
            ->innerJoin("courses", "c", "e.course_id = c.id")
            ->innerJoin("user_courses", "uc", "c.id = uc.course_id")
            ->where("e.exam_date > NOW()")
            ->andWhere("uc.user_id = ?", Auth::id())
            ->getAll();

        return View::render("home.php", [
            "grades" => $grades,
            "exams" => $exams
        ]);
    }
}
