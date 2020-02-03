<?php


namespace Demoshop\HTTP;


class Request
{
    private $method;
    private $postData;
    private $getData;
    private $queryParameters;
    private $headers;
    private $requestURI;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->postData = $_POST;
        $this->getData = $_GET;
        $this->queryParameters = $_SERVER['QUERY_STRING'];
        $this->requestURI = $_SERVER['REQUEST_URI'];
        $this->headers = get_headers($_SERVER['HTTP_HOST' . $this->requestURI]);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPostData()
    {
        return $this->postData;
    }

    public function getGetData()
    {
        return $this->getData;
    }

    public function getQueryParameters()
    {
        return $this->queryParameters;
    }

    public function getHeaders(): string
    {
        return $this->headers;
    }

    public function getRequestURI(): string
    {
        return $this->requestURI;
    }
}