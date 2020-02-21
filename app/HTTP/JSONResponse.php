<?php


namespace Demoshop\HTTP;


/**
 * Class JSONResponse
 * @package Demoshop\HTTP
 */
class JSONResponse extends Response
{
    /**
     * @var array
     */
    private $json;

    /**
     * JSONResponse constructor.
     * @param array $json
     */
    public function __construct($json)
    {
        $this->json = $json;
    }

    /**
     * Get json.
     *
     * @return array
     */
    public function getJson(): array
    {
        return $this->json;
    }

    /**
     * Set json.
     *
     * @param array $json
     */
    public function setJson($json): void
    {
        $this->json = $json;
    }

    /**
     * @inheritDoc
     */
    public function render(): void
    {
        parent::render();

        $this->json = json_encode($this->json, JSON_THROW_ON_ERROR, 512);
        header('Content-Type: application/json');
        echo $this->json;
    }
}