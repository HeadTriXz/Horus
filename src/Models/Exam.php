<?php

namespace Horus\Models;

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
}
