<?php

namespace Horus\Controllers;

use Horus\Auth;
use Horus\Core\Controller\BaseController;
use Horus\Core\Database\QueryBuilder\QueryBuilder;
use Horus\Core\Http\Message\ServerRequestInterface;
use Horus\Core\View\View;
use Horus\Models\Course;

class CourseController extends BaseController
{
    public function index(ServerRequestInterface $request): string
    {
        $courses = Course::createQueryBuilder()
            ->select("c.*")
            ->from("courses", "c")
            ->innerJoin("user_courses", "uc", "c.id = uc.course_id")
            ->leftJoin("exams", "e", "c.id = e.course_id")
            ->leftJoin("grades", "g", "e.id = g.exam_id")
            ->where("uc.user_id = ?", Auth::id())
            ->andWhere("g.grade IS NULL")
            ->groupBy("c.id")
            ->getAll();

        $completedCourses = Course::createQueryBuilder()
            ->select("c.*")
            ->from("courses", "c")
            ->innerJoin("user_courses", "uc", "c.id = uc.course_id")
            ->where("uc.user_id = ?", Auth::id())
            ->andWhere("NOT EXISTS (" . (new QueryBuilder())
                ->select("1")
                ->from("exams", "e")
                ->leftJoin("grades", "g", "e.id = g.exam_id")
                ->where("e.course_id = c.id")
                ->andWhere("g.grade IS NULL")
                ->getQuery() . ")")
            ->andWhere("EXISTS (" . (new QueryBuilder())
                ->select("1")
                ->from("exams", "e")
                ->where("e.course_id = c.id")
                ->getQuery() . ")")
            ->getAll();

        $courses = array_merge($courses, $completedCourses);

        $params = $request->getQueryParams();
        $selectedCourse = $courses[0];
        if (array_key_exists("c", $params) && count($courses) > 0) {
            for ($i = 0; $i < count($courses); $i++) {
                if ($courses[$i]->id == $params["c"]) {
                    $selectedCourse = $courses[$i];
                    break;
                }
            }
        }

        return View::render("Courses/index.php", [
            "courses" => $courses,
            "selectedCourse" => $selectedCourse
        ]);
    }
}
