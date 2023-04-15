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
use Horus\Utils;

/**
 * Controller for managing grade-related views and actions.
 */
class GradeController extends BaseController
{
    /**
     * Display the list of grades for the authenticated student.
     *
     * @param ServerRequestInterface $request The server request instance.
     * @return string The rendered view.
     */
    public function index(ServerRequestInterface $request): string
    {
        $grades = Grade::createQueryBuilder()
            ->select()
            ->where("student_id = ?", Auth::id())
            ->orderBy("created_at", "DESC")
            ->getAll();

        $selected = Utils::getSelected("g", $grades, $request);

        return View::render("Student/Grades/index.php", [
            "grades" => $grades,
            "selected" => $selected
        ]);
    }

    /**
     * Display the form for managing students and their grades for the selected exam.
     *
     * @param ServerRequestInterface $request The server request instance.
     * @return string The rendered view.
     */
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

        if (!empty($students)) {
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
        }

        return View::render("Teacher/Exams/grades.php", [
            "exams" => $exams,
            "selected" => $selected,
            "students" => $students,
            "search" => null
        ]);
    }

    /**
     * Update the grades for the selected exam.
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
