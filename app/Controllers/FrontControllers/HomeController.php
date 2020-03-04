<?php


namespace Demoshop\Controllers\FrontControllers;


use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\Request;
use Demoshop\ServiceRegistry\ServiceRegistry;

/**
 * Class HomeController
 * @package Demoshop\Controllers\FrontControllers
 */
class HomeController
{
    /**
     * Render landingPage.php
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function index(Request $request): HTMLResponse
    {
        $categoryService = ServiceRegistry::get('CategoryService');
        $productService = ServiceRegistry::get('ProductsService');

        $categories = $categoryService->getFormattedArray();
        $products = $productService->getFeaturedProducts();

        $data = [
            'categories' => $categories,
            'products' => $products,
        ];

        return new HTMLResponse('/views/visitor/landingPage.php', $data);
    }
}