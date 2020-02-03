<?php


namespace Demoshop\HTTP;


class RedirectResponse extends Response
{
    private $redirectionURL;

    /* RedirectResponse constructor
     *  $redirectionURL -> redirection URL
     */
    public function __construct($redirectionURL)
    {
        $this->redirectionURL = $redirectionURL;
    }

    // get redirect response redirection URL
    public function getRedirectionURL()
    {
        return $this->redirectionURL;
    }

    // function for redirecting to redirectionURL
    public function render(): void
    {
        header('Location: ' . $this->redirectionURL);
        exit();
    }
}