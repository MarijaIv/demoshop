<?php


namespace Demoshop\HTTP;


class RequestInit
{
    // function for initializing request
    public static function init(): Request
    {
        $request = new Request();
        $request->setMethod($_SERVER['REQUEST_METHOD']);
        $request->setPostData($_POST);
        $request->setGetData($_GET);
        $request->setQueryParameters($_SERVER['QUERY_STRING']);
        $request->setRequestURI($_SERVER['REQUEST_URI']);
        $request->setHeaders(get_headers($_SERVER['HTTP_HOST'] . $request->getRequestURI()));
        return $request;
    }
}