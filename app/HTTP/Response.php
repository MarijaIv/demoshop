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
     * @var int
     *
     * default value 200 - OK
     */
    protected $status = 200;

    /**
     * Function for rendering/redirecting pages.
     *
     * @return void
     */
    public function render(): void
    {
        http_response_code($this->getStatus());
    }

    /**
     * Get response status.
     *
     * @return int
     */
    public function getStatus(): int
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

}