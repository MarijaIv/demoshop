<?php


namespace Demoshop\HTTP;


class HTMLResponse extends Response
{
    private $path;
    private $viewArguments;

    /* HTMLResponse constructor
     * $path -> path to the view file
     * $viewArguments -> optional list of view arguments
     */
    public function __construct($path, $viewArguments = null)
    {
        $this->path = $path;
        $this->viewArguments = $viewArguments;
    }

    // get html response path
    public function getPath()
    {
        return $this->path;
    }

    // get html response view arguments
    public function getViewArguments()
    {
        return $this->viewArguments;
    }

    // function for rendering view file
    public function render(): void
    {
        if($this->viewArguments) {
            extract($this->viewArguments, null);
        }
        include $this->path;
    }
}