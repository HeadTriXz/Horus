<?php

namespace Horus\Controllers;

use Horus\Auth;
use Horus\Core\Controller\BaseController;
use Horus\Core\Database\QueryBuilder\QueryBuilder;
use Horus\Core\Database\QueryBuilder\SelectQueryBuilder;
use Horus\Core\Http\Message\ResponseInterface;
use Horus\Core\Http\Message\ServerRequestInterface;
use Horus\Core\View\View;
use Horus\Models\Course;
use Horus\Models\Exam;
use Horus\Models\UserCourse;
use Horus\Models\UserExam;
use Horus\Utils;

/**
 * Controller for enrolling in courses and exams
 */
class EnrollController extends BaseController
{
    /**
     * Display the available courses for enrollment.
     *
     * @param ServerRequestInterface $request The server request instance.
     * @return string The rendered view.
     */
    public function courses(ServerRequestInterface $request): string
    {
        $qb = Course::createQueryBuilder()
            ->select()
            ->from("courses", "c")
            ->where("c.id NOT IN (" . (new QueryBuilder())
                ->select("uc.course_id")
                ->from("user_courses", "uc")
                ->where("uc.user_id = ?")
                ->getQuery() . ")",
                Auth::id());

        $search = Utils::searchRows($request, $qb, ["c.name", "c.code"]);

        $courses = $qb->getAll();
        $selected = Utils::getSelected("c", $courses, $request);

        return View::render("Student/Enroll/courses.php", [
            "courses" => $courses,
            "selected" => $selected,
            "search" => $search
        ]);
    }

    /**
     * Display the available exams for enrollment.
     *
     * @param ServerRequestInterface $request The server request instance.
     * @return string The rendered view.
     */
    public function exams(ServerRequestInterface $request): string
    {
        $qb = Exam::createQueryBuilder()
            ->select()
            ->from("exams", "e")
            ->where("e.exam_date > NOW()")
            ->andWhere("e.id NOT IN (" . (new QueryBuilder())
                    ->select("ue.exam_id")
                    ->from("user_exams", "ue")
                    ->where("ue.user_id = ?")
                    ->getQuery() . ")",
                    Auth::id());

        $search = Utils::searchRows($request, $qb, ["e.name"]);

        $exams = $qb->getAll();
        $selected = Utils::getSelected("e", $exams, $request);

        return View::render("Student/Enroll/exams.php", [
            "exams" => $exams,
            "selected" => $selected,
            "search" => $search
        ]);
    }

    /**
     * Display the index page.
     *
     * @return string The rendered view.
     */
    public function index(): string
    {
        return View::render("Student/Enroll/index.php");
    }

    /**
     * Store a new user course.
     *
     * @param ServerRequestInterface $request The server request instance.
     * @return ResponseInterface A redirect response to the courses page.
     */
    public function storeCourse(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
        $courseId = $body["course_id"];

        UserCourse::create([
            "course_id" => $courseId,
            "user_id" => Auth::id()
        ]);

        UserExam::createQueryBuilder()
            ->insert()
            ->ignore()
            ->into("user_exams", ["user_id", "exam_id"])
            ->select(fn (SelectQueryBuilder $qb) => $qb
                ->select(["?", "e.id"])
                ->from("exams", "e")
                ->where("e.course_id = ?"),
                Auth::id(), $courseId)
            ->execute();

        return $this->redirect(route("enroll.courses"));
    }

    /**
     * Store a new user exam.
     *
     * @param ServerRequestInterface $request The server request instance.
     * @return ResponseInterface A redirect response to the exams page.
     */
    public function storeExam(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
        $examId = $body["exam_id"];

        UserExam::create([
            "exam_id" => $examId,
            "user_id" => Auth::id()
        ]);

        UserCourse::createQueryBuilder()
            ->insert()
            ->ignore()
            ->into("user_courses", ["user_id", "course_id"])
            ->select(fn (SelectQueryBuilder $qb) => $qb
                ->select(["?", "e.course_id"])
                ->from("exams", "e")
                ->where("e.id = ?"),
                Auth::id(), $examId)
            ->execute();

        return $this->redirect(route("enroll.exams"));
    }
}
