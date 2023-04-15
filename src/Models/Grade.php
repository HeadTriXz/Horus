<?php

namespace Horus\Models;

use Horus\Core\Database\Model;

/**
 * Represents a student's grade.
 */
class Grade extends Model
{
    /**
     * The table name for the model.
     *
     * @var string
     */
    protected static string $table = "grades";

    /**
     * The ID of the grade.
     *
     * @var int
     */
    public int $id;

    /**
     * The ID of the exam the grade is for.
     *
     * @var int
     */
    public int $exam_id;

    /**
     * The ID of the student the grade is for.
     *
     * @var int
     */
    public int $student_id;

    /**
     * The actual grade.
     *
     * @var float
     */
    public float $grade;

    /**
     * The date the grade got created.
     *
     * @var string
     */
    public string $created_at;

    /**
     * The date the grade got updated last.
     *
     * @var string
     */
    public string $updated_at;

    /**
     * The exam the grade is for.
     *
     * @var Exam
     */
    protected Exam $exam;

    /**
     * The student the grade is for.
     *
     * @var User
     */
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
