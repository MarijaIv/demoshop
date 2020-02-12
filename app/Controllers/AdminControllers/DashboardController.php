<?php


namespace Demoshop\Controllers\AdminControllers;


use Demoshop\Controllers\AdminController;
use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\Request;
use Demoshop\Services\CategoryService;
use Demoshop\Services\ProductService;
use Demoshop\Services\StatisticsService;

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
        $myObj = [
            'amountOfProducts' => ProductService::getAmountOfProducts(),
            'amountOfCategories' => CategoryService::getAmountOfCategories(),
            'homeViewCount' => StatisticsService::getTotalHomeViewCount(),
            'mostViewedProductId' => ProductService::getMostViewedProductId(),
            'mostViewedProduct' => ProductService::getMostViewedProduct(),
            'numberOfMostViews' => ProductService::getNumberOfMostViews()
        ];

        return new HTMLResponse('/views/admin/dashboard.php', $myObj);
    }

}