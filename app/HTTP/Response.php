<?php


namespace Demoshop\HTTP;


/**
 * Class Response
 * @package Demoshop\HTTP
 */
abstract class Response
{
    /**
     * @var
     */
    protected $headers;
    /**
     * @var
     */
    protected $status;

    /**
     * Function for rendering/redirecting pages.
     *
     * @return mixed
     */
    abstract public function render();

    /**
     * Get response headers.
     *
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Set response headers.
     *
     * @param $headers
     */
    public function setHeaders($headers): void
    {
        $this->headers = $headers;
    }

    /**
     * Get response status.
     *
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set response status.
     *
     * @param $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

}