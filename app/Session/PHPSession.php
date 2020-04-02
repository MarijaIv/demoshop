<?php


namespace Demoshop\Session;


/**
 * Class PHPSession
 * @package Demoshop\Session
 */
class PHPSession implements Session
{
    /**
     * @inheritDoc
     */
    public function start(): void
    {
        session_start();
    }

    /**
     * @inheritDoc
     */
    public function add(string $key, string $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Remove session data.
     *
     * @param string $key
     */
    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }
}