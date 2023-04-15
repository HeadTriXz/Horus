<?php

namespace Horus\Models;

use Horus\Auth;
use Horus\Core\Database\Model;

/**
 * Represents a course.
 */
class Course extends Model
{
    /**
     * The table name for the model.
     *
     * @var string
     */
    protected static string $table = "courses";

    /**
     * The ID of the course.
     *
     * @var int
     */
    public int $id;

    /**
     * The ID of the teacher associated with this course.
     *
     * @var int
     */
    public int $teacher_id;

    /**
     * The code of this course.
     *
     * @var string
     */
    public string $code;

    /**
     * The name of this course.
     *
     * @var string
     */
    public string $name;

    /**
     * The average grade of the authenticated user for this course.
     *
     * @var ?float
     */
    protected ?float $avgGrade;

    /**
     * A list of exams of the course.
     *
     * @var Exam[]
     */
    protected array $exams;

    /**
     * A list of exams of the course for the authenticated user.
     *
     * @var Exam[]
     */
    protected array $studentExams;

    /**
     * The teacher associated with this course.
     *
     * @var User
     */
    protected User $teacher;

    /**
     * Get the average grade of the authenticated user for this course.
     *
     * @return ?float The average grade of the authenticated user.
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
     * Get a list of exams of the course for the authenticated user.
     *
     * @return Exam[] The exams of the course.
     */
    public function studentExams(): array
    {
        if (!isset($this->studentExams)) {
            $this->studentExams = Exam::createQueryBuilder()
                ->select("e.*")
                ->from("exams", "e")
                ->innerJoin("user_exams", "ue", "e.id = ue.exam_id")
                ->where("ue.user_id = ?", Auth::id())
                ->andWhere("e.course_id = ?", $this->id)
                ->orderBy("exam_date", "DESC")
                ->getAll();
        }

        return $this->studentExams;
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
