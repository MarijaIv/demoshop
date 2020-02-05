<?php

use Demoshop\Controllers\AdminControllers\CategoryController;
use Demoshop\Controllers\AdminControllers\DashboardController;
use Demoshop\Controllers\AdminControllers\ProductController;

require_once __DIR__ . '/../bootstrap.php';

if(!$request->getGetData()['controller'] || $request->getGetData()['controller'] === 'dashboard'){
    $dashboardController = new DashboardController();
    $dashboardController->index();
} else if($request->getGetData()['controller'] === 'product') {
    $productController = new ProductController();
    $productController->index();
} else if($request->getGetData()['controller'] === 'category') {
    $categoryController = new CategoryController();
    $categoryController->index();
}
