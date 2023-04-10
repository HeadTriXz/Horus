<?php

namespace Horus\Models;

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
