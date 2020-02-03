<?php


namespace Demoshop\HTTP;


class HTMLResponse extends Response
{
    private $path;
    private $viewArguments;

    public function __construct($path, $viewArguments = null)
    {
        $this->path = $path;
        $this->viewArguments = $viewArguments;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getViewArguments()
    {
        return $this->viewArguments;
    }

    public function render(): void
    {
        if($this->viewArguments) {
            extract($this->viewArguments, null);
        }
        include $this->path;
    }
}