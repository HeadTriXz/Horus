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
use Horus\Models\UserCourse;
use Horus\Models\UserExam;

class EnrollController extends BaseController
{
    public function index(): string
    {
        return View::render("Enroll/index.php");
    }

    public function courses(ServerRequestInterface $request): string
    {
        $courses = Course::createQueryBuilder()
            ->select()
            ->from("courses", "c")
            ->where("c.id NOT IN (" . (new QueryBuilder())
                ->select("uc.course_id")
                ->from("user_courses", "uc")
                ->where("uc.user_id = ?")
                ->getQuery() . ")", Auth::id())
            ->getAll();

        $params = $request->getQueryParams();
        $selectedCourse = null;
        if (array_key_exists("c", $params) && count($courses) > 0) {
            for ($i = 0; $i < count($courses); $i++) {
                if ($courses[$i]->id == $params["c"]) {
                    $selectedCourse = $courses[$i];
                    break;
                }
            }
        }

        return View::render("Enroll/courses.php", [
            "courses" => $courses,
            "selectedCourse" => $selectedCourse
        ]);
    }

    public function exams(): string
    {
        return View::render("Enroll/exams.php");
    }

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
            ->into("user_exams", ["user_id", "exam_id"])
            ->select(fn (SelectQueryBuilder $qb) => $qb
                ->select(["?", "e.id"])
                ->from("exams", "e")
                ->where("e.course_id = ?"),
                Auth::id(), $courseId)
            ->execute();

        return $this->redirect(route("enroll.courses"));
    }

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
