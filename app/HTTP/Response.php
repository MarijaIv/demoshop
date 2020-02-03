<?php


namespace Demoshop\HTTP;


abstract class Response
{
    protected $headers;
    protected $status;

    // function for rendering/redirecting pages
    abstract public function render();

    // get response headers
    public function getHeaders()
    {
        return $this->headers;
    }

    // set response headers
    public function setHeaders($headers): void
    {
        $this->headers = $headers;
    }

    // get response status
    public function getStatus()
    {
        return $this->status;
    }

    // set response status
    public function setStatus($status): void
    {
        $this->status = $status;
    }

}