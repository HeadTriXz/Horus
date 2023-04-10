<?php

namespace Horus\Controllers;

use Horus\Auth;
use Horus\Core\Controller\BaseController;
use Horus\Core\Http\Message\ServerRequestInterface;
use Horus\Core\View\View;
use Horus\Models\Grade;

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
        $selectedGrade = $grades[0];
        if (array_key_exists("g", $params) && count($grades) > 0) {
            for ($i = 0; $i < count($grades); $i++) {
                if ($grades[$i]->id == $params["g"]) {
                    $selectedGrade = $grades[$i];
                    break;
                }
            }
        }

        return View::render("Grades/index.php", [
            "grades" => $grades,
            "selectedGrade" => $selectedGrade
        ]);
    }
}
