<?php


namespace Demoshop\Controllers\AdminControllers;


use Demoshop\Controllers\AdminController;
use Demoshop\HTTP\HTMLResponse;

/**
 * Class ProductController
 * @package Demoshop\Controllers\AdminControllers
 */
class ProductController extends AdminController
{
    /**
     * Function for rendering product.php page.
     *
     * @return void
     */
    public function index(): void
    {
        $htmlResponse = new HTMLResponse(__DIR__ . '/../../../resources/views/admin/product.php');
        $htmlResponse->render();
    }
}