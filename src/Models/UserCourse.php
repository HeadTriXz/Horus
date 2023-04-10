<?php

namespace Horus\Models;

use Horus\Core\Database\Model;

class UserCourse extends Model
{
    protected static string $table = "user_courses";

    public int $course_id;
    public int $user_id;

    protected Course $course;
    protected User $user;

    public function course(): Course
    {
        if (!isset($this->course)) {
            $this->course = Course::findById($this->course_id);
        }

        return $this->course;
    }

    public function user(): User
    {
        if (!isset($this->user)) {
            $this->user = User::findById($this->user_id);
        }

        return $this->user;
    }
}
