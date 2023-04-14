<?php

namespace Horus\Controllers\Admin;

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

class ExamController extends BaseController
{
    public function index(ServerRequestInterface $request): string
    {
        $error = Auth::session()->get("eu_error");
        if (isset($error)) {
            Auth::session()->delete("eu_error");
        }

        $exams = Exam::where([])
            ->orderBy("exam_date")
            ->getAll();

        $selected = $this->getSelected($exams, $request);

        return View::render("Admin/Exams/index.php", [
            "courses" => Course::find([]),
            "exams" => $exams,
            "error" => $error,
            "selected" => $selected
        ]);
    }

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
            "exams" => $exams
        ]);
    }

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

    /**
     * Gets the selected exam using the query parameters.
     *
     * @param array $exams The array of exams.
     * @param ServerRequestInterface $request The received request.
     * @return ?Exam
     */
    public function getSelected(array $exams, ServerRequestInterface $request): ?Exam
    {
        $selected = null;
        if (!empty($exams)) {
            $selected = $exams[0];
            $params = $request->getQueryParams();
            if (array_key_exists("e", $params)) {
                for ($i = 0; $i < count($exams); $i++) {
                    if ($exams[$i]->id == $params["e"]) {
                        $selected = $exams[$i];
                        break;
                    }
                }
            }
        }
        return $selected;
    }
}
