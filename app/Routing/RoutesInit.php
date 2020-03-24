<?php


namespace Demoshop\Routing;


use Demoshop\AuthorizationMiddleware\Authorization;
use Demoshop\AuthorizationMiddleware\Exceptions\RouteAlreadyRegisteredException;
use Demoshop\Controllers\AdminControllers\CategoryController;
use Demoshop\Controllers\AdminControllers\DashboardController;
use Demoshop\Controllers\AdminControllers\ProductController;
use Demoshop\Controllers\FrontControllers\FrontProductController;
use Demoshop\Controllers\FrontControllers\HomeController;
use Demoshop\Controllers\FrontControllers\LoginController;
use Demoshop\Controllers\FrontControllers\ProductSearchController;

/**
 * Class RoutesInit
 * @package Demoshop\Routing
 */
class RoutesInit
{
    /**
     * Add routes to listOfRoutes.
     *
     * @throws RouteAlreadyRegisteredException
     */
    public static function routesInit()
    {
        Routes::add(new Route('GET', '/admin/login',
            LoginController::class, 'index', []));
        Routes::add(new Route('POST', '/admin', LoginController::class, 'login', []));

        Routes::add(new Route('GET', '/admin',
            DashboardController::class, 'index', [Authorization::class]));

        Routes::add(new Route('GET', '/admin/categories',
            CategoryController::class, 'index', [Authorization::class]));
        Routes::add(new Route('GET', '/admin/categories/listCategories',
            CategoryController::class, 'listCategories', [Authorization::class]));
        Routes::add(new Route('GET', '/admin/categories/listAllCategories',
            CategoryController::class, 'listAllCategories', [Authorization::class]));
        Routes::add(new Route('GET', '/admin/categories/getCategoriesForEdit',
            CategoryController::class, 'getCategoriesForEdit', [Authorization::class]));
        Routes::add(new Route('GET', '/admin/categories/displayCategory',
            CategoryController::class, 'displayCategory', [Authorization::class]));
        Routes::add(new Route('POST', '/admin/categories',
            CategoryController::class, 'addNewCategory', [Authorization::class]));
        Routes::add(new Route('PUT', '/admin/categories',
            CategoryController::class, 'updateCategory', [Authorization::class]));
        Routes::add(new Route('DELETE', '/admin/categories',
            CategoryController::class, 'delete', [Authorization::class]));

        Routes::add(new Route('GET', '/admin/products',
            ProductController::class, 'index', [Authorization::class]));
        Routes::add(new Route('POST', '/admin/products/delete',
            ProductController::class, 'deleteProduct', [Authorization::class]));
        Routes::add(new Route('POST', '/admin/products/deleteMultiple',
            ProductController::class, 'deleteMultiple', [Authorization::class]));
        Routes::add(new Route('POST', '/admin/products/enableSelected',
            ProductController::class, 'enableSelected', [Authorization::class]));
        Routes::add(new Route('POST', '/admin/products/disableSelected',
            ProductController::class, 'disableSelected', [Authorization::class]));
        Routes::add(new Route('GET', '/admin/products/enableDisable',
            ProductController::class, 'enableOrDisableProduct', [Authorization::class]));
        Routes::add(new Route('GET', '/admin/products/create',
            ProductController::class, 'addEditProduct', [Authorization::class]));
        Routes::add(new Route('GET', '/admin/products/%',
            ProductController::class, 'addEditProduct', [Authorization::class]));
        Routes::add(new Route('POST', '/admin/products/create',
            ProductController::class, 'createNewProduct', [Authorization::class]));
        Routes::add(new Route('POST', '/admin/products/%',
            ProductController::class, 'updateProduct', [Authorization::class]));
        Routes::add(new Route('GET', '/admin/products/firstPage',
            ProductController::class, 'firstPage', [Authorization::class]));
        Routes::add(new Route('GET', '/admin/products/nextPage',
            ProductController::class, 'nextPage', [Authorization::class]));
        Routes::add(new Route('GET', '/admin/products/lastPage',
            ProductController::class, 'lastPage', [Authorization::class]));
        Routes::add(new Route('GET', '/admin/products/prevPage',
            ProductController::class, 'prevPage', [Authorization::class]));

        Routes::add(new Route('GET', '/', HomeController::class, 'index', []));
        Routes::add(new Route('GET', '/%',
            FrontProductController::class, 'listProducts', []));
        Routes::add(new Route('GET', '/search',
            ProductSearchController::class, 'index', []));
        Routes::add(new Route('GET', '/product/%',
            FrontProductController::class, 'index', []));
    }
}