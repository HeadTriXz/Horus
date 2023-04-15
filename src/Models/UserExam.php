<?php

namespace Horus\Models;

use Horus\Core\Database\Model;

/**
 * Represents a mapping between a user and an exam.
 */
class UserExam extends Model
{
    /**
     * The table name for the model.
     *
     * @var string
     */
    protected static string $table = "user_exams";

    /**
     * The ID of the exam associated with this user-exam mapping.
     *
     * @var int
     */
    public int $exam_id;

    /**
     * The ID of the user associated with this user-exam mapping.
     *
     * @var int
     */
    public int $user_id;

    /**
     * The exam associated with this user-exam mapping.
     *
     * @var Exam
     */
    protected Exam $exam;

    /**
     * The user associated with this user-exam mapping.
     *
     * @var User
     */
    protected User $user;

    /**
     * Returns the exam associated with this user-exam mapping.
     *
     * @return Exam The associated exam.
     */
    public function exam(): Exam
    {
        if (!isset($this->exam)) {
            $this->exam = Exam::findById($this->exam_id);
        }

        return $this->exam;
    }

    /**
     * Returns the user associated with this user-exam mapping.
     *
     * @return User The associated user.
     */
    public function user(): User
    {
        if (!isset($this->user)) {
            $this->user = User::findById($this->user_id);
        }

        return $this->user;
    }
}
