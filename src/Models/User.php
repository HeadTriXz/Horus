<?php

namespace Horus\Models;

use Horus\Core\Database\Model;
use Horus\Enums\UserRole;

class User extends Model
{
    protected static string $table = "users";

    public int $id;
    public string $first_name;
    public string $last_name;
    public string $email;
    public string $password;
    public int $role;

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN->value;
    }

    public function isStudent(): bool
    {
        return $this->role === UserRole::STUDENT->value;
    }

    public function isTeacher(): bool
    {
        return $this->role === UserRole::TEACHER->value;
    }

    public function prettyRole(): string
    {
        return match (UserRole::tryFrom($this->role)) {
            UserRole::STUDENT => "Student",
            UserRole::TEACHER => "Teacher",
            UserRole::ADMIN => "Admin",
            default => "Unknown"
        };
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }
}
