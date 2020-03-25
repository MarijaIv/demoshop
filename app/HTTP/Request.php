<?php


namespace Demoshop\HTTP;


/**
 * Class Request
 * @package Demoshop\HTTP
 */
class Request
{
    /**
     * @var string
     */
    private $method;
    /**
     * @var array
     */
    private $postData;
    /**
     * @var array
     */
    private $getData;
    /**
     * @var string
     */
    private $headers;
    /**
     * @var string
     */
    private $requestURI;
    /**
     * @var array
     */
    private $file;

    /**
     * Get request file.
     *
     * @param string $name
     * @return array
     */
    public function getFile(string $name): array
    {
        return $_FILES[$name];
    }

    /**
     * Set request file.
     *
     * @param array $file
     */
    public function setFile(array $file): void
    {
        $this->file = $file;
    }

    /**
     * Get request method.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Set request method.
     *
     * @param string $method
     */
    public function setMethod($method): void
    {
        $this->method = $method;
    }

    /**
     * Get request post data.
     *
     * @return array
     */
    public function getPostData(): array
    {
        return $this->postData;
    }

    /**
     * Set request post data.
     *
     * @param array $postData
     */
    public function setPostData($postData): void
    {
        $this->postData = $postData;
    }

    /**
     * Get request get data.
     *
     * @return array
     */
    public function getGetData(): array
    {
        return $this->getData;
    }

    /**
     * Set request get data.
     *
     * @param array $getData
     */
    public function setGetData($getData): void
    {
        $this->getData = $getData;
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
     * Set request headers.
     *
     * @param string $headers
     */
    public function setHeaders($headers): void
    {
        $this->headers = $headers;
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

    /**
     * Set request URI.
     *
     * @param string $requestURI
     */
    public function setRequestURI($requestURI): void
    {
        $this->requestURI = $requestURI;
    }
}