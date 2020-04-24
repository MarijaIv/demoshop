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
        $mostViewedProduct = $this->getProductService()->getMostViewedProduct();

        $myObj = [
            'amountOfProducts' => $this->getProductService()->getNumberOfProducts(),
            'amountOfCategories' => $this->getCategoryService()->getCountOfCategories(),
            'homeViewCount' => $this->getStatisticsService()->getTotalHomeViewCount(),
            'mostViewedProductId' => $mostViewedProduct ? $mostViewedProduct->id : 0,
            'mostViewedProduct' => $mostViewedProduct ? $mostViewedProduct->title : '',
            'numberOfMostViews' => $mostViewedProduct ? $mostViewedProduct->view_count : '',
        ];

        return new HTMLResponse('/views/admin/dashboard.php', $myObj);
    }

}