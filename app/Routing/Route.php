<?php


namespace Demoshop\Routing;


/**
 * Class Route
 * @package Demoshop\Routing
 */
class Route
{
    /**
     * @var string
     */
    private $httpMethod;
    /**
     * @var string
     */
    private $path;
    /**
     * @var string
     */
    private $controller;
    /**
     * @var string
     */
    private $action;
    /**
     * @var array
     */
    private $middlewareList;

    /**
     * Route constructor.
     * @param string $httpMethod
     * @param string $path
     * @param string $controller
     * @param string $action
     * @param array $middlewareList
     */
    public function __construct(string $httpMethod, string $path, string $controller,
                                string $action, array $middlewareList)
    {
        $this->httpMethod = $httpMethod;
        $this->path = $path;
        $this->controller = $controller;
        $this->action = $action;
        $this->middlewareList = $middlewareList;
    }

    /**
     * Get http method.
     *
     * @return string
     */
    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    /**
     * Set http method.
     *
     * @param string $httpMethod
     */
    public function setHttpMethod(string $httpMethod): void
    {
        $this->httpMethod = $httpMethod;
    }

    /**
     * Get path.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Set path.
     *
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * Get controller.
     *
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * Set controller.
     *
     * @param string $controller
     */
    public function setController(string $controller): void
    {
        $this->controller = $controller;
    }

    /**
     * Get action.
     *
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * Set action.
     *
     * @param string $action
     */
    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    /**
     * Get middleware list.
     *
     * @return array
     */
    public function getMiddlewareList(): array
    {
        return $this->middlewareList;
    }

    /**
     * Set middleware list.
     *
     * @param array $middlewareList
     */
    public function setMiddlewareList(array $middlewareList): void
    {
        $this->middlewareList = $middlewareList;
    }
}