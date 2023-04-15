<?php

namespace Horus\Models;

use DateInterval;
use DateTime;
use Horus\Auth;
use Horus\Core\Database\Model;

/**
 * Represents an exam.
 */
class Exam extends Model
{
    /**
     * The table name for the model.
     *
     * @var string
     */
    protected static string $table = "exams";

    /**
     * The ID of the exam.
     *
     * @var int
     */
    public int $id;

    /**
     * The ID of the course associated with this exam.
     *
     * @var int
     */
    public int $course_id;

    /**
     * The duration of this exam, if any.
     *
     * @var ?int
     */
    public ?int $duration;

    /**
     * The name of the exam.
     *
     * @var string
     */
    public string $name;

    /**
     * The date of the exam.
     *
     * @var string
     */
    public string $exam_date;

    /**
     * The course associated with this exam.
     *
     * @var Course
     */
    protected Course $course;

    /**
     * The grade of the exam for the authenticated user.
     *
     * @var ?Grade
     */
    protected ?Grade $grade;

    /**
     * Get the course this exam is for.
     *
     * @return Course The course this exam is for.
     */
    public function course(): Course
    {
        if (!isset($this->course)) {
            $this->course = Course::findById($this->course_id);
        }

        return $this->course;
    }

    /**
     * Get the date of when the exam ends.
     *
     * @throws \Exception in case of an error.
     * @return string The date of the end of the exam.
     */
    public function endsAt(): string
    {
        if ($this->duration === null) {
            return $this->exam_date;
        }

        $date = new DateTime($this->exam_date);
        $interval = new DateInterval("PT{$this->duration}M");

        return $date
            ->add($interval)
            ->format('Y-m-d H:i:s');
    }

    /**
     * Get the grade of the exam for the authenticated user.
     *
     * @return ?Grade The grade of the authenticated user.
     */
    public function grade(): ?Grade
    {
        if (!isset($this->grade)) {
            $this->grade = Grade::findOne([
                "exam_id" => $this->id,
                "student_id" => Auth::id()
            ]);
        }

        return $this->grade;
    }
}
