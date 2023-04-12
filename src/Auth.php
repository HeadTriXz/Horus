<?php

namespace Horus;

use Horus\Core\Container\ContainerException;
use Horus\Models\Session;
use Horus\Models\User;

class Auth
{
    public static function check(): bool
    {
        return static::session() !== null;
    }

    public static function id(): ?string
    {
        return static::session()?->get("user_id");
    }

    public static function session(): ?Session
    {
        $cookies = request()->getCookieParams();
        if (!array_key_exists("session_id", $cookies)) {
            return null;
        }

        $session = Session::findById($cookies["session_id"]);
        if (!$session || $session->isExpired()) {
            return null;
        }

        return $session;
    }

    public static function user(): ?User
    {
        $id = static::id();
        if (!isset($id)) {
            return null;
        }

        return User::findById($id);
    }
}
