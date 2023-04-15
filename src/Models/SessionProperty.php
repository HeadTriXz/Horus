<?php

namespace Horus\Models;

use Horus\Core\Database\Model;

/**
 * Represents a property of a session.
 */
class SessionProperty extends Model
{
    /**
     * The table name for the model.
     *
     * @var string
     */
    protected static string $table = "session_properties";

    /**
     * The ID of the session.
     *
     * @var string
     */
    public string $session_id;

    /**
     * The key of the session property.
     *
     * @var string
     */
    public string $p_key;

    /**
     * The name of the session property.
     *
     * @var string
     */
    public string $p_value;
}
