<?php

namespace Horus\Models;

use Horus\Core\Database\Model;

class Session extends Model
{
    protected static string $table = "sessions";

    public string $id;
    public string $expires_at;

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

    public static function open(): static
    {
        $expiresAt = strtotime("+1 day");
        $session = new static();
        $session->id = static::generateId();
        $session->expires_at = date("Y-m-d H:i:s", $expiresAt);

        setcookie("session_id", $session->id, $expiresAt);

        $session->save();
        return $session;
    }

    public function get(string $key): ?string
    {
        return SessionProperty::findOne([
            "session_id" => $this->id,
            "p_key" => $key
        ])?->p_value;
    }

    public function set(string $key, string $value): void
    {
        SessionProperty::createQueryBuilder()
            ->insert()
            ->values([
                "session_id" => $this->id,
                "p_key" => $key,
                "p_value" => $value
            ])
            ->orUpdate([
                "p_value" => $value
            ])
            ->execute();
    }

    protected static function generateId(): string
    {
        return md5($_SERVER["REMOTE_ADDR"] . $_SERVER["HTTP_USER_AGENT"] . rand());
    }
}
