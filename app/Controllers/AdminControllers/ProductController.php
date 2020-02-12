<?php


namespace Demoshop\Controllers\AdminControllers;


use Demoshop\Controllers\AdminController;
use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\Request;

/**
 * Class ProductController
 * @package Demoshop\Controllers\AdminControllers
 */
class ProductController extends AdminController
{
    /**
     * Function for rendering product.php page.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function index(Request $request): HTMLResponse
    {
        return new HTMLResponse('/views/admin/product.php');
    }
}