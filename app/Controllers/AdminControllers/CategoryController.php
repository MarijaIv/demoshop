<?php


namespace Demoshop\Controllers\AdminControllers;


use Demoshop\Controllers\AdminController;
use Demoshop\HTTP\HTMLResponse;

/**
 * Class CategoryController
 * @package Demoshop\Controllers\AdminControllers
 */
class CategoryController extends AdminController
{
    /**
     * Function for rendering category.php page.
     *
     * @return void
     */
    public function index(): void
    {
        $htmlResponse = new HTMLResponse(__DIR__ . '/../../../resources/views/admin/category.php');
        $htmlResponse->render();
    }
}