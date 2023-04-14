<?php

namespace Horus\Controllers;

use Horus\Auth;
use Horus\Core\Controller\BaseController;
use Horus\Core\Http\Message\Response;
use Horus\Core\Http\Message\ResponseInterface;
use Horus\Core\Http\Message\ServerRequestInterface;
use Horus\Core\View\View;
use Horus\Models\Exam;
use Horus\Models\Grade;
use Horus\Models\User;

class GradeController extends BaseController
{
    public function index(ServerRequestInterface $request): string
    {
        $grades = Grade::createQueryBuilder()
            ->select()
            ->where("student_id = ?", Auth::id())
            ->orderBy("created_at", "DESC")
            ->getAll();

        $params = $request->getQueryParams();
        $selected = $grades[0];
        if (array_key_exists("g", $params) && count($grades) > 0) {
            for ($i = 0; $i < count($grades); $i++) {
                if ($grades[$i]->id == $params["g"]) {
                    $selected = $grades[$i];
                    break;
                }
            }
        }

        return View::render("Student/Grades/index.php", [
            "grades" => $grades,
            "selected" => $selected
        ]);
    }

    public function manage(ServerRequestInterface $request): string
    {
        $exams = Exam::where([])
            ->orderBy("exam_date")
            ->getAll();

        $id = $request->getAttribute("id");

        $selected = null;
        foreach ($exams as $exam) {
            if ($exam->id == $id) {
                $selected = $exam;
                break;
            }
        }

        $students = User::where([ "uc.course_id" => $selected->course_id ])
            ->innerJoin("user_courses", "uc", "id = uc.user_id")
            ->getAll();

        $grades = Grade::where([ "e.course_id" => $selected->course_id ])
            ->innerJoin("exams", "e", "exam_id = e.id")
            ->andWhere("student_id IN (" . implode(", ", array_column($students, "id")) . ")")
            ->getAll();

        foreach ($grades as $grade) {
            foreach ($students as $student) {
                if ($student->id === $grade->student_id) {
                    $student->grade = $grade->grade;
                    break;
                }
            }
        }

        return View::render("Admin/Exams/grades.php", [
            "exams" => $exams,
            "selected" => $selected,
            "students" => $students
        ]);
    }

    public function update(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute("id");
        if (!isset($id)) {
            return new Response(404, "Not Found");
        }

        $grades = [];
        $body = $request->getParsedBody();
        foreach ($body as $key => $value) {
            if (str_starts_with($key, "g-")) {
                $userId = +substr($key, 2);
                if ($userId === 0) {
                    continue;
                }

                $grades[] = [
                    "exam_id" => $id,
                    "student_id" => $userId,
                    "grade" => $value
                ];
            }
        }

        Grade::createQueryBuilder()
            ->insert()
            ->values($grades)
            ->orUpdate([ "grade" ])
            ->execute();

        return $this->redirect(route("exams", [ "e" => $id ]));
    }
}
