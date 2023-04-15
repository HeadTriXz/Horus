<?php

namespace Horus\Models;

use Horus\Core\Database\Model;

/**
 * Represents a mapping between a user and a course.
 */
class UserCourse extends Model
{
    /**
     * The table name for the model.
     *
     * @var string
     */
    protected static string $table = "user_courses";

    /**
     * The ID of the course associated with this user-course mapping.
     *
     * @var int
     */
    public int $course_id;

    /**
     * The ID of the user associated with this user-course mapping.
     *
     * @var int
     */
    public int $user_id;

    /**
     * The course associated with this user-course mapping.
     *
     * @var Course
     */
    protected Course $course;

    /**
     * The user associated with this user-course mapping.
     *
     * @var User
     */
    protected User $user;

    /**
     * Returns the course associated with this user-course mapping.
     *
     * @return Course The associated course.
     */
    public function course(): Course
    {
        if (!isset($this->course)) {
            $this->course = Course::findById($this->course_id);
        }

        return $this->course;
    }

    /**
     * Returns the user associated with this user-course mapping.
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
