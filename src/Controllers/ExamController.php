<?php

namespace Horus\Controllers;

use DateTime;
use Horus\Auth;
use Horus\Core\Application;
use Horus\Core\Controller\BaseController;
use Horus\Core\Http\Message\Response;
use Horus\Core\Http\Message\ResponseInterface;
use Horus\Core\Http\Message\ServerRequestInterface;
use Horus\Core\View\View;
use Horus\Models\Course;
use Horus\Models\Exam;
use Horus\Utils;

/**
 * Controller for managing exam-related views and actions.
 */
class ExamController extends BaseController
{
    /**
     * Display the admin view for exams.
     *
     * @param ServerRequestInterface $request The server request instance.
     * @return string The rendered view.
     */
    public function admin(ServerRequestInterface $request): string
    {
        $error = Auth::session()->get("eu_error");
        if (isset($error)) {
            Auth::session()->delete("eu_error");
        }

        $qb = Exam::where([])->orderBy("exam_date");
        $search = Utils::searchRows($request, $qb, ["name"]);

        $exams = $qb->getAll();
        $selected = Utils::getSelected("e", $exams, $request);

        return View::render("Admin/Exams/index.php", [
            "courses" => Course::find([]),
            "exams" => $exams,
            "error" => $error,
            "selected" => $selected,
            "search" => $search
        ]);
    }

    /**
     * Display the create exam view.
     *
     * @return string The rendered view.
     */
    public function create(): string
    {
        $error = Auth::session()->get("ec_error");
        if (isset($error)) {
            Auth::session()->delete("ec_error");
        }

        $exams = Exam::where([])
            ->orderBy("exam_date")
            ->getAll();

        return View::render("Admin/Exams/create.php", [
            "courses" => Course::find([]),
            "exams" => $exams,
            "search" => null
        ]);
    }

    /**
     * Display the exams view for admins or teachers.
     *
     * @param ServerRequestInterface $request The server request instance.
     * @return string The rendered view.
     */
    public function index(ServerRequestInterface $request): string
    {
        return Auth::user()->isAdmin()
            ? $this->admin($request)
            : $this->teacher($request);
    }

    /**
     * Store a new exam in the database.
     *
     * @param ServerRequestInterface $request The server request instance.
     * @return ResponseInterface The response instance.
     */
    public function store(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
        $duration = $body["duration"];
        if (empty($duration) || $duration <= 0) {
            $duration = null;
        }

        $datetime = date_create_from_format('Y-m-d\TH:i', $body["datetime"]);
        if (!$datetime) {
            Auth::session()->set("ec_error", "The specified date is not valid.");
        } elseif ($datetime < new DateTime()) {
            Auth::session()->set("ec_error", "You cannot schedule an exam in the past.");
        } else {
            Exam::create([
                "course_id" => $body["course"],
                "name" => $body["name"],
                "duration" => $duration,
                "exam_date" => $datetime->format('Y-m-d H:i:s')
            ]);
        }

        $id = Application::getInstance()
            ->getDatabase()
            ->getLastInsertId();

        return $this->redirect(route("exams", [ "e" => $id ]));
    }

    /**
     * Display the teacher view for exams.
     *
     * @param ServerRequestInterface $request The server request instance.
     * @return string The rendered view.
     */
    public function teacher(ServerRequestInterface $request): string
    {
        $qb = Exam::createQueryBuilder()
            ->select("e.*")
            ->from("exams", "e")
            ->innerJoin("courses", "c", "e.course_id = c.id")
            ->where("c.teacher_id = ?", Auth::id());

        $search = Utils::searchRows($request, $qb, ["e.name"]);

        $exams = $qb->getAll();
        $selected = Utils::getSelected("e", $exams, $request);

        return View::render("Teacher/Exams/index.php", [
            "exams" => $exams,
            "selected" => $selected,
            "search" => $search
        ]);
    }

    /**
     * Update an existing exam.
     *
     * @param ServerRequestInterface $request The server request instance.
     * @return ResponseInterface The response instance.
     */
    public function update(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute("id");
        if (!isset($id)) {
            return new Response(404, "Not Found");
        }

        $body = $request->getParsedBody();
        $duration = $body["duration"];
        if (empty($duration) || $duration <= 0) {
            $duration = null;
        }

        $datetime = date_create_from_format('Y-m-d\TH:i', $body["datetime"]);
        if (!$datetime) {
            Auth::session()->set("eu_error", "The specified date is not valid.");
        } elseif ($datetime < new DateTime()) {
            Auth::session()->set("eu_error", "You cannot schedule an exam in the past.");
        } else {
            Exam::update($id, [
                "course_id" => $body["course"],
                "name" => $body["name"],
                "duration" => $duration,
                "exam_date" => $datetime->format('Y-m-d H:i:s')
            ]);
        }

        return $this->redirect(route("exams", [ "e" => $id ]));
    }
}
