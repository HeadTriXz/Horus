<?php

namespace Horus;

use Horus\Models\Session;
use Horus\Models\User;

/**
 * Utility class for checking user authentication.
 */
class Auth
{
    /**
     * Check if the user is authenticated.
     *
     * @return bool Whether the user is authenticated.
     */
    public static function check(): bool
    {
        return static::session() !== null;
    }

    /**
     * Get the ID of the authenticated user.
     *
     * @return ?string The ID of the authenticated user, if any.
     */
    public static function id(): ?string
    {
        return static::session()?->get("user_id");
    }

    /**
     * Get the session of the authenticated user.
     *
     * @return ?Session The authenticated user's session, if any.
     */
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

    /**
     * Get the authenticated user.
     *
     * @return ?User The authenticated user, if any.
     */
    public static function user(): ?User
    {
        $id = static::id();
        if (!isset($id)) {
            return null;
        }

        return User::findById($id);
    }
}
