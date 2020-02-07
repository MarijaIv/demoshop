<?php

use Demoshop\Controllers\AdminControllers\{CategoryController, DashboardController, ProductController};
use Demoshop\HTTP\RedirectResponse;

require_once __DIR__ . '/../bootstrap.php';

if(isset($_SESSION['username']) || isset($_COOKIE['username'])) {
    if(!$request->getGetData()['controller'] || $request->getGetData()['controller'] === 'dashboard'){
        $dashboardController = new DashboardController();
        $dashboardController->index();

    } else if($request->getGetData()['controller'] === 'product') {
        $productController = new ProductController();
        $productController->index();
    } else if($request->getGetData()['controller'] === 'category') {
        $categoryController = new CategoryController();

        if($request->getGetData()['action'] === 'delete') {
            $categoryController->delete();
        } else if($request->getGetData()['action'] === 'select') {
            $categoryController->select();
        } else {
            $categoryController->index();
        }
    }
} else {
    $redirect = new RedirectResponse('/login.php');
    $redirect->render();
}


