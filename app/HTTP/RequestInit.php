<?php


namespace Demoshop\HTTP;


/**
 * Class RequestInit
 * @package Demoshop\HTTP
 */
class RequestInit
{
    /**
     * Init request.
     *
     * @return Request
     */
    public static function init(): Request
    {
        $request = new Request();
        $request->setMethod($_SERVER['REQUEST_METHOD']);
        $request->setPostData($_POST);
        $request->setGetData($_GET);
        $request->setRequestURI($_SERVER['REQUEST_URI']);
        $request->setHeaders(get_headers($_SERVER['HTTP_HOST'] . $request->getRequestURI()));
        return $request;
    }
}