<?php


namespace Demoshop\Controllers\FrontControllers;


use Demoshop\Controllers\FrontController;
use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\Request;

/**
 * Class HomeController
 * @package Demoshop\Controllers\FrontControllers
 */
class HomeController extends FrontController
{
    /**
     * Render landingPage.php
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function index(Request $request): HTMLResponse
    {
        $categoryService = $this->getCategoryService();
        $productService = $this->getProductService();
        $statisticsService = $this->getStatisticsService();

        $statisticsService->increaseHomeViewCount();
        $categories = $categoryService->getRootCategories();
        $categories = $categoryService->formatCategoriesForTreeView($categories);
        $products = $productService->getFeaturedProducts();

        $homePageArguments = [
            'categories' => $categories,
            'products' => $products,
        ];

        return new HTMLResponse('/views/visitor/landingPage.php', $homePageArguments);
    }
}