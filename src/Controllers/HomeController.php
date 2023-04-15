<?php

namespace Horus\Controllers;

use Horus\Auth;
use Horus\Core\Controller\BaseController;
use Horus\Core\View\View;
use Horus\Models\Exam;
use Horus\Models\Grade;

/**
 * Controller for rendering the home page.
 */
class HomeController extends BaseController
{
    /**
     * Renders the home page based on the user's role.
     *
     * @return string The rendered view.
     */
    public function index(): string
    {
        $user = Auth::user();
        if ($user->isTeacher() || $user->isAdmin()) {
            return $this->teacher();
        }

        return $this->student();
    }

    /**
     * Renders the teacher home page.
     *
     * @return string The rendered view.
     */
    protected function teacher(): string
    {
        return View::render("Teacher/home.php");
    }

    /**
     * Renders the student home page.
     *
     * @return string The rendered view.
     */
    protected function student(): string
    {
        $grades = Grade::createQueryBuilder()
            ->select()
            ->where("student_id = ?", Auth::id())
            ->orderBy("created_at", "DESC")
            ->limit(4)
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
