<?php

namespace Horus\Models;

use Horus\Core\Database\Model;

class User extends Model
{
    public int $id;
    public string $first_name;
    public string $last_name;
    public string $email;
    public string $password;
}
