<?php


namespace Demoshop\Controllers\AdminControllers;


use Demoshop\Controllers\AdminController;
use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\JSONResponse;
use Demoshop\HTTP\Request;
use Demoshop\Services\ProductService;

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

    /**
     * Function for getting all products.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function getAllProducts(Request $request): JSONResponse
    {
        $products = ProductService::getAllProductsFormatted();

        return new JSONResponse($products);
    }
}