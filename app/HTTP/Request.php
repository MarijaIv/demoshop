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

    // set request method
    public function setMethod($method): void
    {
        $this->method = $method;
    }

    // set request post data
    public function setPostData($postData): void
    {
        $this->postData = $postData;
    }

    // set request get data
    public function setGetData($getData): void
    {
        $this->getData = $getData;
    }

    // set request query parameters
    public function setQueryParameters($queryParameters): void
    {
        $this->queryParameters = $queryParameters;
    }

    // set request headers
    public function setHeaders($headers): void
    {
        $this->headers = $headers;
    }

    // set request URI
    public function setRequestURI($requestURI): void
    {
        $this->requestURI = $requestURI;
    }

    // get request method
    public function getMethod()
    {
        return $this->method;
    }

    // get request post data
    public function getPostData()
    {
        return $this->postData;
    }

    // get request get data
    public function getGetData()
    {
        return $this->getData;
    }

    // get request query parameters
    public function getQueryParameters()
    {
        return $this->queryParameters;
    }

    // get request headers
    public function getHeaders(): string
    {
        return $this->headers;
    }

    // get request URI
    public function getRequestURI(): string
    {
        return $this->requestURI;
    }
}