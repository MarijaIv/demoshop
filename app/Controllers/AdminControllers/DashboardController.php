<?php


namespace Demoshop\Controllers\AdminControllers;


use Demoshop\Controllers\AdminController;
use Demoshop\HTTP\HTMLResponse;

/**
 * Class DashboardController
 * @package Demoshop\Controllers\AdminControllers
 */
class DashboardController extends AdminController
{
    /**
     * Function for rendering dashboard.php page.
     *
     * @return void
     */
    public function index(): void
    {
        $htmlResponse = new HTMLResponse(__DIR__ . '/../../../resources/views/admin/dashboard.php');
        $htmlResponse->render();
    }

}