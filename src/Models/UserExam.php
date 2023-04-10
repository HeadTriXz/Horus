<?php

namespace Horus\Models;

use Horus\Core\Database\Model;

class UserExam extends Model
{
    protected static string $table = "user_exams";

    public int $exam_id;
    public int $user_id;

    protected Exam $exam;
    protected User $user;

    public function exam(): Exam
    {
        if (!isset($this->exam)) {
            $this->exam = Exam::findById($this->exam_id);
        }

        return $this->exam;
    }

    public function user(): User
    {
        if (!isset($this->user)) {
            $this->user = User::findById($this->user_id);
        }

        return $this->user;
    }
}
