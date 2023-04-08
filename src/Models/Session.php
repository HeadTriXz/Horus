<?php

namespace Horus\Models;

use Horus\Core\Database\Model;

class Session extends Model
{
    protected static string $table = "sessions";

    public string $id;
    public string $expires_at;
    public int $user_id;

    public function destroy(): bool
    {
        return static::createQueryBuilder()
            ->delete()
            ->where("id = ?", $this->id)
            ->execute() > 0;
    }

    public function isExpired(): bool
    {
        return strtotime($this->expires_at) <= time();
    }

    public static function open(int $userId): static
    {
        $expiresAt = strtotime("+1 day");
        $session = new static();
        $session->id = static::generateId();
        $session->user_id = $userId;
        $session->expires_at = date("Y-m-d H:i:s", $expiresAt);

        setcookie("session_id", $session->id, $expiresAt);

        $session->save();
        return $session;
    }

    protected static function generateId(): string
    {
        return md5($_SERVER["REMOTE_ADDR"] . $_SERVER["HTTP_USER_AGENT"] . rand());
    }
}
