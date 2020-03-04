<?php


namespace Demoshop\Controllers\FrontControllers;


use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\Request;

/**
 * Class FrontProductController
 * @package Demoshop\Controllers\FrontControllers
 */
class FrontProductController
{
    /**
     * Render productDetails.php page.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function index(Request $request): HTMLResponse
    {
        return new HTMLResponse('/views/visitor/productDetails.php');
    }

    /**
     * Render categoryDisplay.php page.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function listProducts(Request $request): HTMLResponse
    {
        return new HTMLResponse('/views/visitor/categoryDisplay.php');
    }
}