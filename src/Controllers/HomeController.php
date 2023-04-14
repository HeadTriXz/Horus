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
        $user = Auth::user();
        if ($user->isAdmin()) {
            return $this->admin();
        } elseif ($user->isTeacher()) {
            return $this->teacher();
        }

        return $this->student();
    }

    protected function admin(): string
    {
        return View::render("Admin/home.php");
    }

    protected function teacher(): string
    {
        return $this->admin();
    }

    protected function student(): string
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

        return View::render("Student/home.php", [
            "grades" => $grades,
            "exams" => $exams
        ]);
    }
}
