<?php
namespace App\Session;

class PHPSession
{
    private $messages;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    public function get(string $key, $default = null)
    {
        if ($this->has($key)) {
            return $_SESSION[$key];
        }

        return $default;
    }

    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function delete(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function flash(string $key): ?string
    {
        if (is_null($this->messages)) {
            $this->messages = $this->get($key);
            $this->delete($key);
        }

        return $this->messages;
    }
}
