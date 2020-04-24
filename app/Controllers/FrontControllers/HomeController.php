<?php


namespace Demoshop\Controllers\FrontControllers;


use Demoshop\Controllers\FrontController;
use Demoshop\Formatters\CategoryFormatter;
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
        $this->getStatisticsService()->increaseHomeViewCount();
        $categoryFormatter = new CategoryFormatter();
        $allCategories = $this->getCategoryService()->getCategories();
        $categories = $categoryFormatter->formatCategoriesForTreeView($allCategories);
        $products = $this->getFrontProductService()->getFeaturedProducts();

        $homePageArguments = [
            'categories' => $categories,
            'products' => $products,
        ];

        return new HTMLResponse('/views/visitor/landingPage.php', $homePageArguments);
    }
}