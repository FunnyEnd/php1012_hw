<?php

namespace Framework;

class Session
{
    private const SESSION_HTTP_USER_AGENT_KEY = 'ua';
    private const SESSION_REMOTE_ADDR_KEY = 'ra';
    private const SERVER_HTTP_USER_AGENT_KEY = 'HTTP_USER_AGENT';
    private const SERVER_REMOTE_ADDR_KEY = 'REMOTE_ADDR';
    private const COOKIE_PHPSESSID_KEY = 'PHPSESSID';

    private static $instance;

    public static function getInstance(): Session
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }


    private function __construct()
    {
        if ($this->cookieExist()) {
            $this->start();
        }
    }

    private function __clone()
    {
    }

    public function start(): void
    {
        if ($this->sessionExist()) {
            return;
        }

        session_start();

        if (!isset($_SESSION[self::SESSION_HTTP_USER_AGENT_KEY])) {
            $_SESSION[self::SESSION_HTTP_USER_AGENT_KEY] = $_SERVER[self::SERVER_HTTP_USER_AGENT_KEY];
        } elseif ($_SESSION[self::SESSION_HTTP_USER_AGENT_KEY] != $_SERVER[self::SERVER_HTTP_USER_AGENT_KEY]) {
            $this->destroy();
        }

        if (!isset($_SESSION[self::SESSION_REMOTE_ADDR_KEY])) {
            $_SESSION[self::SESSION_REMOTE_ADDR_KEY] = $_SERVER[self::SERVER_REMOTE_ADDR_KEY];
        } elseif ($_SESSION[self::SESSION_REMOTE_ADDR_KEY] != $_SERVER[self::SERVER_REMOTE_ADDR_KEY]) {
            $this->destroy();
        }
    }

    public function set(string $key, $val): void
    {
        if (!$this->sessionExist()) {
            return;
        }

        $_SESSION[$key] = $val;
    }

    public function get(string $key)
    {
        if ($this->sessionExist()) {
            return $_SESSION[$key];
        } else {
            $this->start();
            return $_SESSION[$key];
        }
    }

    public function getName(): string
    {
        if ($this->sessionExist()) {
            return session_name();
        } else {
            $this->start();
            return session_name();
        }
    }

    public function setName(string $name): void
    {
        if ($this->sessionExist()) {
            return;
        }

        session_name($name);
    }

    public function cookieExist(): bool
    {
        if (isset($_COOKIE[self::COOKIE_PHPSESSID_KEY])) {
            return true;
        }

        return false;
    }

    public function sessionExist(): bool
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            return true;
        }

        return false;
    }

    public function keyExist(string $key): bool
    {
        if ($this->sessionExist()) {
            return isset($_SESSION[$key]);
        } else {
            return false;
        }
    }

    public function destroy(): void
    {
        if (!$this->cookieExist() || !$this->sessionExist()) {
            return;
        }

        session_destroy();
        setcookie(self::COOKIE_PHPSESSID_KEY, "", time() - 3600, '/');
    }
}
