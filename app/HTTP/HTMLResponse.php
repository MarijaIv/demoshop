<?php


namespace Demoshop\HTTP;


/**
 * Class HTMLResponse
 * @package Demoshop\HTTP
 */
class HTMLResponse extends Response
{
    /**
     * @var string
     */
    private $path;
    /**
     * @var array
     */
    private $viewArguments;

    /**
     * HTMLResponse constructor.
     * @param string $path
     * @param array $viewArguments
     */
    public function __construct($path, $viewArguments = null)
    {
        $this->path = __DIR__ . '/../../resources' . $path;
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
     * @return array |null
     */
    public function getViewArguments(): array
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
        if ($this->viewArguments) {
            extract($this->viewArguments);
        }
        include $this->path;
    }
}