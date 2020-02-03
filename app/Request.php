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

    public function setMethod($method): void
    {
        $this->method = $method;
    }

    public function setPostData($postData): void
    {
        $this->postData = $postData;
    }

    public function setGetData($getData): void
    {
        $this->getData = $getData;
    }

    public function setQueryParameters($queryParameters): void
    {
        $this->queryParameters = $queryParameters;
    }

    public function setHeaders($headers): void
    {
        $this->headers = $headers;
    }

    public function setRequestURI($requestURI): void
    {
        $this->requestURI = $requestURI;
    }

    public function getMethod()
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