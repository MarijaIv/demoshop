<?php


namespace Demoshop\Cookie;


/**
 * Interface CookieInterface
 * @package Demoshop\Cookie
 */
interface CookieInterface
{
    /**
     * Add new cookie.
     *
     * @param string $key
     * @param string $value
     * @param int $time
     */
    public function add(string $key, string $value, int $time): void;

    /**
     * Get existing cookie.
     *
     * @param string $key
     * @return string
     */
    public function get(string $key): string;
}