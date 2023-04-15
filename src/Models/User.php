<?php

namespace Horus\Models;

use Horus\Core\Database\Model;
use Horus\Enums\UserRole;

/**
 * Represents a user.
 */
class User extends Model
{
    /**
     * The table name for the model.
     *
     * @var string
     */
    protected static string $table = "users";

    /**
     * The ID of the user.
     *
     * @var int
     */
    public int $id;

    /**
     * The first name of the user.
     *
     * @var string
     */
    public string $first_name;

    /**
     * The last name of the user.
     *
     * @var string
     */
    public string $last_name;

    /**
     * The email of the user.
     *
     * @var string
     */
    public string $email;

    /**
     * The hashed password of the user.
     *
     * @var string
     */
    public string $password;

    /**
     * The role of the user.
     *
     * @var int
     */
    public int $role;

    /**
     * Check whether the user is an admin.
     *
     * @return bool Whether the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN->value;
    }

    /**
     * Check whether the user is a student.
     *
     * @return bool Whether the user is a student.
     */
    public function isStudent(): bool
    {
        return $this->role === UserRole::STUDENT->value;
    }

    /**
     * Check whether the user is a teacher.
     *
     * @return bool Whether the user is a teacher.
     */
    public function isTeacher(): bool
    {
        return $this->role === UserRole::TEACHER->value;
    }

    /**
     * Get a human-readable version of the user's role.
     *
     * @return string A human-readable version of the user's role.
     */
    public function prettyRole(): string
    {
        return match (UserRole::tryFrom($this->role)) {
            UserRole::STUDENT => "Student",
            UserRole::TEACHER => "Teacher",
            UserRole::ADMIN => "Admin",
            default => "Unknown"
        };
    }

    /**
     * Check whether a given password is the user's password.
     *
     * @param string $password The password to check.
     * @return bool Whether the given password is the user's password.
     */
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }
}
