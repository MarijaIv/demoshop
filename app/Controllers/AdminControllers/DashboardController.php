<?php


namespace Demoshop\Controllers\AdminControllers;


use Demoshop\Controllers\AdminController;
use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\Request;

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
        $productService = $this->getProductService();
        $categoryService = $this->getCategoryService();
        $statisticsService = $this->getStatisticsService();

        $mostViewedProduct = $productService->getMostViewedProduct();

        $myObj = [
            'amountOfProducts' => $productService->getNumberOfProducts(),
            'amountOfCategories' => $categoryService->getCountOfCategories(),
            'homeViewCount' => $statisticsService->getTotalHomeViewCount(),
            'mostViewedProductId' => $mostViewedProduct->id,
            'mostViewedProduct' => $mostViewedProduct->title,
            'numberOfMostViews' => $mostViewedProduct->view_count,
        ];

        return new HTMLResponse('/views/admin/dashboard.php', $myObj);
    }

}