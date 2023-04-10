<?php

namespace Horus\Models;

use Horus\Core\Database\Model;

class Grade extends Model
{
    protected static string $table = "grades";

    public int $id;
    public int $exam_id;
    public int $student_id;
    public float $grade;
    public string $created_at;
    public string $updated_at;

    protected Exam $exam;
    protected User $student;

    /**
     * Get the exam the grade is for.
     *
     * @return Exam The exam the grade is for.
     */
    public function exam(): Exam
    {
        if (!isset($this->exam)) {
            $this->exam = Exam::findById($this->exam_id);
        }

        return $this->exam;
    }

    /**
     * Get the student who received the grade.
     *
     * @return User The student who received the grade.
     */
    public function student(): User
    {
        if (!isset($this->student)) {
            $this->student = User::findById($this->student_id);
        }

        return $this->student;
    }
}
