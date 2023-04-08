<?php

namespace Horus\Models;

use Horus\Core\Database\Model;

class User extends Model
{
    protected static string $table = "users";

    public int $id;
    public string $first_name;
    public string $last_name;
    public string $email;
    public string $password;
    public int $role;

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }
}
