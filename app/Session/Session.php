<?php


namespace Demoshop\Session;


/**
 * Interface Session
 * @package Demoshop\Session
 */
interface Session
{
    /**
     * Start session
     */
    public function start(): void;

    /**
     * Add data to session.
     * @param string $key
     * @param string $value
     */
    public function add(string $key, string $value): void;

    /**
     * Get data from session.
     * @param string $key
     * @param mixed $default
     */
    public function get(string $key, $default = null);
}