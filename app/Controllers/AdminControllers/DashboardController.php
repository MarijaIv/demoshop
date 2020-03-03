<?php


namespace Demoshop\Controllers\AdminControllers;


use Demoshop\Controllers\AdminController;
use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\Request;
use Demoshop\ServiceRegistry\ServiceRegistry;

/**
 * Class DashboardController
 * @package Demoshop\Controllers\AdminControllers
 */
class DashboardController extends AdminController
{
    /**
     * Function for rendering dashboard.php page.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function index(Request $request): HTMLResponse
    {
        $productService = ServiceRegistry::get('ProductsService');
        $categoryService = ServiceRegistry::get('CategoryService');
        $statisticsService = ServiceRegistry::get('StatisticsService');

        $myObj = [
            'amountOfProducts' => $productService->getNumberOfProducts(),
            'amountOfCategories' => $categoryService->getCountOfCategories(),
            'homeViewCount' => $statisticsService->getTotalHomeViewCount(),
            'mostViewedProductId' => $productService->getMostViewedProductId(),
            'mostViewedProduct' => $productService->getMostViewedProduct(),
            'numberOfMostViews' => $productService->getNumberOfMostViews()
        ];

        return new HTMLResponse('/views/admin/dashboard.php', $myObj);
    }

}