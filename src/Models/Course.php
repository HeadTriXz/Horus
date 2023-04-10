<?php

namespace Horus\Models;

use Horus\Core\Database\Model;

class Course extends Model
{
    protected static string $table = "courses";

    public int $id;
    public int $teacher_id;
    public string $code;
    public string $name;

    protected array $exams;
    protected User $teacher;

    /**
     * Get a list of exams of the course.
     *
     * @return Exam[] The exams of the course.
     */
    public function exams(): array
    {
        if (!isset($this->exams)) {
            $this->exams = Exam::find([
                "course_id" => $this->id
            ]);
        }

        return $this->exams;
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
