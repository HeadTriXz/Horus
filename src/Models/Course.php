<?php

namespace Horus\Models;

use Horus\Auth;
use Horus\Core\Database\Model;

class Course extends Model
{
    protected static string $table = "courses";

    public int $id;
    public int $teacher_id;
    public string $code;
    public string $name;

    protected array $exams;
    protected ?float $avgGrade;
    protected User $teacher;

    /**
     * Get a list of exams of the course.
     *
     * @return Exam[] The exams of the course.
     */
    public function exams(): array
    {
        if (!isset($this->exams)) {
            $this->exams = Exam::createQueryBuilder()
                ->select()
                ->where("course_id = ?", $this->id)
                ->orderBy("exam_date", "DESC")
                ->getAll();
        }

        return $this->exams;
    }

    /**
     * Get the average grade of the logged-in user for this course.
     *
     * @return ?float The average grade of the logged-in user.
     */
    public function avgGrade(): ?float
    {
        if (!isset($this->avgGrade)) {
            $result = Grade::createQueryBuilder()
                ->select("ROUND(AVG(g.grade), 1)", "avg")
                ->from("grades", "g")
                ->innerJoin("exams", "e", "g.exam_id = e.id")
                ->where("g.student_id = ?", Auth::id())
                ->andWhere("e.course_id = ?", $this->id)
                ->getOne();

            $this->avgGrade = $result->avg;
        }

        return $this->avgGrade;
    }

    /**
     * Get the teacher that manages this course.
     *
     * @return User The teacher that manages this course.
     */
    public function teacher(): User
    {
        if (!isset($this->teacher)) {
            $this->teacher = User::findById($this->teacher_id);
        }

        return $this->teacher;
    }
}
