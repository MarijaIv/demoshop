<?php


namespace Demoshop\HTTP;


/**
 * Class Response
 * @package Demoshop\HTTP
 */
abstract class Response
{
    /**
     * @var string
     */
    protected $headers;
    /**
     * @var string
     */
    protected $status;

    /**
     * Function for rendering/redirecting pages.
     *
     * @return void
     */
    abstract public function render(): void;

    /**
     * Get response headers.
     *
     * @return string
     */
    public function getHeaders(): string
    {
        return $this->headers;
    }

    /**
     * Set response headers.
     *
     * @param string $headers
     */
    public function setHeaders($headers): void
    {
        $this->headers = $headers;
    }

    /**
     * Get response status.
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set response status.
     *
     * @param string $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

}