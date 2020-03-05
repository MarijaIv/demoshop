<?php


namespace Demoshop\Controllers\FrontControllers;


use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\Request;
use Demoshop\ServiceRegistry\ServiceRegistry;

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
        $productService = ServiceRegistry::get('ProductsService');
        $products = $productService->getProductsForCategoryDisplay($request->getGetData()['id']);

        $categoryService = ServiceRegistry::get('CategoryService');
        $categories = $categoryService->getFormattedArray();

        $myObj = [
            'products' => $products,
            'categories' => $categories,
        ];
        return new HTMLResponse('/views/visitor/categoryDisplay.php', $myObj);
    }
}