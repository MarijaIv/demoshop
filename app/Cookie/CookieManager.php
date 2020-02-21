<?php

namespace Demoshop\Cookie;

/**
 * Class CookieManager
 * @package Demoshop\Cookie
 */
class CookieManager implements CookieInterface
{
    /**
     * Add new cookie.
     *
     * @param string $key
     * @param string $value
     * @param int $time
     */
    public function add(string $key, string $value, int $time): void
    {
        setcookie($key, $value, $time);
    }

    /**
     * Get existing cookie.
     *
     * @param string $key
     * @return string
     */
    public function get(string $key): string
    {
        return $_COOKIE[$key] ?: '';
    }
}