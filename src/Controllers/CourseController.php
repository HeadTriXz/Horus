<?php

namespace Horus\Controllers;

use Horus\Auth;
use Horus\Core\Application;
use Horus\Core\Controller\BaseController;
use Horus\Core\Database\QueryBuilder\QueryBuilder;
use Horus\Core\Http\Message\Response;
use Horus\Core\Http\Message\ResponseInterface;
use Horus\Core\Http\Message\ServerRequestInterface;
use Horus\Core\View\View;
use Horus\Enums\UserRole;
use Horus\Models\Course;
use Horus\Models\User;
use Horus\Utils;

class CourseController extends BaseController
{
    public function index(ServerRequestInterface $request): string
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return $this->admin($request);
        } elseif ($user->isTeacher()) {
            return $this->teacher($request);
        } else {
            return $this->student($request);
        }
    }

    public function create(): string
    {
        $error = Auth::session()->get("cc_error");
        if (isset($error)) {
            Auth::session()->delete("cc_error");
        }

        $courses = Course::find([]);
        $teachers = User::where([ "role" => UserRole::TEACHER->value ])
            ->orderBy("first_name")
            ->getAll();

        return View::render("Admin/Courses/create.php", [
            "courses" => $courses,
            "error" => $error,
            "teachers" => $teachers,
            "search" => null
        ]);
    }

    public function store(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
        $existing = Course::findOne([
            "code" => $body["code"]
        ]);

        if (isset($existing)) {
            Auth::session()->set("cc_error", "The specified code is already in use.");
            return $this->redirect(route("courses.create"));
        } else {
            Course::create([
                "code" => $body["code"],
                "name" => $body["name"],
                "teacher_id" => $body["teacher"]
            ]);
        }

        $id = Application::getInstance()
            ->getDatabase()
            ->getLastInsertId();

        return $this->redirect(route("courses", [ "c" => $id ]));
    }

    public function update(ServerRequestInterface $request): string | ResponseInterface
    {
        $id = $request->getAttribute("id");
        if (!isset($id)) {
            return new Response(404, "Not Found");
        }

        $body = $request->getParsedBody();
        $existing = Course::findOne([
            "code" => $body["code"]
        ]);

        if (isset($existing) && $existing->id != $id) {
            Auth::session()->set("cu_error", "The specified code is already in use.");
        } else {
            Course::update($id, [
                "code" => $body["code"],
                "name" => $body["name"],
                "teacher_id" => $body["teacher"]
            ]);
        }

        return $this->redirect(route("courses", [ "c" => $id ]));
    }

    protected function admin(ServerRequestInterface $request): string
    {
        $error = Auth::session()->get("cu_error");
        if (isset($error)) {
            Auth::session()->delete("cu_error");
        }

        $qb = Course::where([])->orderBy("name");
        $search = Utils::searchRows($request, $qb, ["name", "code"]);

        $courses = $qb->getAll();
        $selected = Utils::getSelected("c", $courses, $request);

        $teachers = User::where([ "role" => UserRole::TEACHER->value ])
            ->orderBy("first_name")
            ->getAll();

        return View::render("Admin/Courses/index.php", [
            "courses" => $courses,
            "error" => $error,
            "search" => $search,
            "selected" => $selected,
            "teachers" => $teachers
        ]);
    }

    protected function teacher(ServerRequestInterface $request): string
    {
        $qb = Course::where([ "teacher_id" => Auth::id() ])
            ->orderBy("name");

        $search = Utils::searchRows($request, $qb, ["name", "code"]);

        $courses = $qb->getAll();
        $selected = Utils::getSelected("c", $courses, $request);

        return View::render("Teacher/Courses/index.php", [
            "courses" => $courses,
            "selected" => $selected,
            "search" => $search
        ]);
    }

    protected function student(ServerRequestInterface $request): string
    {
        $coursesBuilder = Course::createQueryBuilder()
            ->select("c.*")
            ->from("courses", "c")
            ->innerJoin("user_courses", "uc", "c.id = uc.course_id")
            ->leftJoin("exams", "e", "c.id = e.course_id")
            ->leftJoin("grades", "g", "e.id = g.exam_id")
            ->where("uc.user_id = ?", Auth::id())
            ->andWhere("g.grade IS NULL")
            ->groupBy("c.id");

        $search = Utils::searchRows($request, $coursesBuilder, ["c.name", "c.code"]);
        $courses = $coursesBuilder->getAll();

        $completedBuilder = Course::createQueryBuilder()
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
                ->getQuery() . ")");

        Utils::searchRows($request, $completedBuilder, ["c.name", "c.code"]);
        $completedCourses = $completedBuilder->getAll();

        $courses = array_merge($courses, $completedCourses);
        $selected = Utils::getSelected("c", $courses, $request);

        return View::render("Student/Courses/index.php", [
            "courses" => $courses,
            "selected" => $selected,
            "search" => $search
        ]);
    }
}
