<?php

namespace Horus\Models;

use Horus\Core\Database\Model;

/**
 * Represents a session.
 */
class Session extends Model
{
    /**
     * The table name for the model.
     *
     * @var string
     */
    protected static string $table = "sessions";

    /**
     * The ID of the session.
     *
     * @var string
     */
    public string $id;

    /**
     * The date the session expires at.
     *
     * @var string
     */
    public string $expires_at;

    /**
     * Delete a property of the session.
     *
     * @param string $key The key of the property.
     */
    public function delete(string $key): void
    {
        SessionProperty::createQueryBuilder()
            ->delete()
            ->where("session_id = ?", $this->id)
            ->andWhere("p_key = ?", $key)
            ->execute();
    }

    /**
     * Destroy the session.
     *
     * @return bool Whether the session got destroyed successfully.
     */
    public function destroy(): bool
    {
        return static::createQueryBuilder()
            ->delete()
            ->where("id = ?", $this->id)
            ->execute() > 0;
    }

    /**
     * Generate a new session ID based on the user's IP and User Agent.
     *
     * @return string The new session ID.
     */
    protected static function generateId(): string
    {
        return md5($_SERVER["REMOTE_ADDR"] . $_SERVER["HTTP_USER_AGENT"] . rand());
    }

    /**
     * Get the value of a property of the session.
     *
     * @param string $key The key of the property.
     * @return ?string The value of the property.
     */
    public function get(string $key): ?string
    {
        return SessionProperty::findOne([
            "session_id" => $this->id,
            "p_key" => $key
        ])?->p_value;
    }

    /**
     * Check whether the session is expired.
     *
     * @return bool Whether the session is expired.
     */
    public function isExpired(): bool
    {
        return strtotime($this->expires_at) <= time();
    }

    /**
     * Create a new session.
     *
     * @return static The instance of the new session.
     */
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

    /**
     * Set the value of a property of the session.
     *
     * @param string $key The key of the property.
     * @param string $value The value of the property.
     */
    public function set(string $key, string $value): void
    {
        SessionProperty::createQueryBuilder()
            ->insert()
            ->values([
                "session_id" => $this->id,
                "p_key" => $key,
                "p_value" => $value
            ])
            ->orUpdate([ "p_value" ])
            ->execute();
    }
}
