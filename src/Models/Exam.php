<?php

namespace Horus\Models;

use DateInterval;
use DateTime;
use Horus\Auth;
use Horus\Core\Database\Model;

class Exam extends Model
{
    protected static string $table = "exams";

    public int $id;
    public int $course_id;
    public ?int $duration;
    public string $name;
    public string $exam_date;

    protected Course $course;
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
     * Get the grade of the exam for the logged-in user.
     *
     * @return ?Grade The grade of the logged-in user.
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
