<?php

use Demoshop\AuthorizationMiddleware\Authorization;
use Demoshop\AuthorizationMiddleware\Exceptions\RouteAlreadyRegisteredException;
use Demoshop\Routing\Route;
use Demoshop\Routing\Routes;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';

try {
    Routes::add(new Route('GET', '/admin/login', 'login', 'index', []));
    Routes::add(new Route('POST', '/admin', 'login', 'login', []));

    Routes::add(new Route('GET', '/admin',
        'dashboard', 'index', [Authorization::class]));

    Routes::add(new Route('GET', '/admin/categories',
        'category', 'index', [Authorization::class]));
    Routes::add(new Route('GET', '/admin/categories/listCategories',
        'category', 'listCategories', [Authorization::class]));
    Routes::add(new Route('GET', '/admin/categories/listAllCategories',
        'category', 'listAllCategories', [Authorization::class]));
    Routes::add(new Route('GET', '/admin/categories/displayCategory',
        'category', 'displayCategory', [Authorization::class]));
    Routes::add(new Route('POST', '/admin/categories',
        'category', 'addNewCategory', [Authorization::class]));
    Routes::add(new Route('PUT', '/admin/categories',
        'category', 'updateCategory', [Authorization::class]));
    Routes::add(new Route('DELETE', '/admin/categories',
        'category', 'delete', [Authorization::class]));

    Routes::add(new Route('GET', '/admin/products',
        'product', 'index', [Authorization::class]));
    Routes::add(new Route('POST', '/admin/products/delete',
        'product', 'deleteProduct', [Authorization::class]));
    Routes::add(new Route('POST', '/admin/products/deleteMultiple',
        'product', 'deleteMultiple', [Authorization::class]));
    Routes::add(new Route('POST', '/admin/products/enableSelected',
        'product', 'enableSelected', [Authorization::class]));
    Routes::add(new Route('POST', '/admin/products/disableSelected',
        'product', 'disableSelected', [Authorization::class]));
    Routes::add(new Route('GET', '/admin/products/enableDisable',
        'product', 'enableOrDisableProduct', [Authorization::class]));
    Routes::add(new Route('GET', '/admin/products/addEdit',
        'product', 'addEditProduct', [Authorization::class]));
    Routes::add(new Route('GET', '/admin/products/{sku}',
        'product', 'updateProduct', [Authorization::class]));
    Routes::add(new Route('GET', '/admin/products/create',
        'product', 'createNewProduct', [Authorization::class]));
    Routes::add(new Route('GET', '/admin/products/firstPage',
        'product', 'firstPage', [Authorization::class]));
    Routes::add(new Route('GET', '/admin/products/nextPage',
        'product', 'nextPage', [Authorization::class]));
    Routes::add(new Route('GET', '/admin/products/lastPage',
        'product', 'lastPage', [Authorization::class]));
    Routes::add(new Route('GET', '/admin/products/prevPage',
        'product', 'prevPage', [Authorization::class]));

    Routes::add(new Route('GET', '', 'home', 'index', []));
    Routes::add(new Route('GET', '/{category_code}', 'frontProduct', 'listProducts', []));
    Routes::add(new Route('GET', '/search', 'productSearch', 'index', []));
    Routes::add(new Route('GET', '/product/{sku}', 'frontProduct', 'index', []));

} catch (RouteAlreadyRegisteredException $e) {
}

$response->render();
