<?php


namespace Demoshop\HTTP;


abstract class Response
{
    private $headers;
    private $status;

    abstract public function render();

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setHeaders($headers): void
    {
        $this->headers = $headers;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status): void
    {
        $this->status = $status;
    }

}