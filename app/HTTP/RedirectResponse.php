<?php


namespace Demoshop\HTTP;


/**
 * Class RedirectResponse
 * @package Demoshop\HTTP
 */
class RedirectResponse extends Response
{
    /**
     * @var
     */
    private $redirectionURL;

    /**
     * RedirectResponse constructor.
     * @param string $redirectionURL
     */
    public function __construct($redirectionURL)
    {
        $this->redirectionURL = $redirectionURL;
    }

    /**
     * Get redirect response redirection URL.
     *
     * @return string
     */
    public function getRedirectionURL(): string
    {
        return $this->redirectionURL;
    }

    /**
     * Function for redirecting to redirectionURL.
     *
     * @return void
     */
    public function render(): void
    {
        header('Location: ' . $this->redirectionURL);
        exit();
    }
}