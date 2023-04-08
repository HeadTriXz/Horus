<?php

namespace Horus;

use Horus\Core\Application;
use Horus\Core\Container\ContainerException;
use Horus\Core\Http\Message\ServerRequestInterface;
use Horus\Models\Session;
use Horus\Models\User;

class Auth
{
    /**
     * @throws ContainerException
     */
    public static function check(): bool
    {
        return static::session() !== null;
    }

    /**
     * @throws ContainerException
     */
    public static function id(): ?string
    {
        return static::session()?->user_id;
    }

    /**
     * @throws ContainerException
     */
    public static function session(): ?Session
    {
        $request = Application::getInstance()
            ->getContainer()
            ->get(ServerRequestInterface::class);

        $cookies = $request->getCookieParams();
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
     * @throws ContainerException
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
