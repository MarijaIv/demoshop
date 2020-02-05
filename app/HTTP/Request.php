<?php


namespace Demoshop\HTTP;


/**
 * Class Request
 * @package Demoshop\HTTP
 */
class Request
{
    /**
     * @var
     */
    private $method;
    /**
     * @var
     */
    private $postData;
    /**
     * @var
     */
    private $getData;
    /**
     * @var
     */
    private $headers;
    /**
     * @var
     */
    private $requestURI;

    /**
     * Set request method.
     *
     * @param $method
     */
    public function setMethod($method): void
    {
        $this->method = $method;
    }

    /**
     * Set request post data.
     *
     * @param $postData
     */
    public function setPostData($postData): void
    {
        $this->postData = $postData;
    }

    /**
     * Set request get data.
     *
     * @param $getData
     */
    public function setGetData($getData): void
    {
        $this->getData = $getData;
    }

    /**
     * Set request headers.
     *
     * @param $headers
     */
    public function setHeaders($headers): void
    {
        $this->headers = $headers;
    }

    /**
     * Set request URI.
     *
     * @param $requestURI
     */
    public function setRequestURI($requestURI): void
    {
        $this->requestURI = $requestURI;
    }

    /**
     * Get request method.
     *
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get request post data.
     *
     * @return mixed
     */
    public function getPostData()
    {
        return $this->postData;
    }

    /**
     * Get request get data.
     *
     * @return mixed
     */
    public function getGetData()
    {
        return $this->getData;
    }

    /**
     * Get request headers.
     *
     * @return string
     */
    public function getHeaders(): string
    {
        return $this->headers;
    }

    /**
     * Get request URI.
     *
     * @return string
     */
    public function getRequestURI(): string
    {
        return $this->requestURI;
    }
}