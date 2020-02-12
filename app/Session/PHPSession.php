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
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }
}