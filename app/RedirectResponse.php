<?php


namespace Demoshop\HTTP;


class RedirectResponse extends Response
{
    private $redirectionURL;

    public function __construct($redirectionURL)
    {
        $this->redirectionURL = $redirectionURL;
    }

    public function getRedirectionURL()
    {
        return $this->redirectionURL;
    }

    public function render(): void
    {
        header('Location: ' . $this->redirectionURL);
        exit();
    }
}