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
            ->select("e.*")
            ->from("exams", "e")
            ->innerJoin("user_exams", "ue", "e.id = ue.exam_id")
            ->where("e.exam_date > NOW()")
            ->andWhere("ue.user_id = ?", Auth::id())
            ->getAll();

        return View::render("home.php", [
            "grades" => $grades,
            "exams" => $exams
        ]);
    }
}
