<?php


namespace Demoshop\HTTP;


/**
 * Class HTMLResponse
 * @package Demoshop\HTTP
 */
class HTMLResponse extends Response
{
    /**
     * @var
     */
    private $path;
    /**
     * @var null
     */
    private $viewArguments;

    /**
     * HTMLResponse constructor.
     * @param string $path
     * @param null $viewArguments
     */
    public function __construct($path, $viewArguments = null)
    {
        $this->path = $path;
        $this->viewArguments = $viewArguments;
    }

    /**
     * Get html response path.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get html response view arguments.
     *
     * @return |null
     */
    public function getViewArguments()
    {
        return $this->viewArguments;
    }

    /**
     * Function for rendering view file.
     *
     * @return void
     */
    public function render(): void
    {
        if($this->viewArguments) {
            extract($this->viewArguments);
        }
        include $this->path;
    }
}