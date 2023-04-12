<?php

namespace Horus\Models;

use Horus\Core\Database\Model;

class SessionProperty extends Model
{
    protected static string $table = "session_properties";

    public string $session_id;
    public string $p_key;
    public string $p_value;
}
