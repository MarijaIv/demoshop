<?php


namespace Demoshop\Controllers\FrontControllers;


use Demoshop\Controllers\FrontController;
use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\Request;

/**
 * Class ProductSearchController
 * @package Demoshop\Controllers\FrontControllers
 */
class ProductSearchController extends FrontController
{
    /**
     * Render searchResult.php page.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function index(Request $request) : HTMLResponse
    {
        return new HTMLResponse('/views/visitor/categoryDisplay.php');
    }
}