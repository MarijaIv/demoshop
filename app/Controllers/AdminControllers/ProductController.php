<?php


namespace Demoshop\Controllers\AdminControllers;


use Demoshop\Controllers\AdminController;
use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\JSONResponse;
use Demoshop\HTTP\Request;
use Demoshop\Services\CategoryService;
use Demoshop\Services\ProductService;

/**
 * Class ProductController
 * @package Demoshop\Controllers\AdminControllers
 */
class ProductController extends AdminController
{
    /**
     * Function for rendering product.php page.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function index(Request $request): HTMLResponse
    {
        return new HTMLResponse('/views/admin/product.php');
    }

    /**
     * Function for getting all products.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function getAllProducts(Request $request): JSONResponse
    {
        $products = ProductService::getAllProductsFormatted();

        return new JSONResponse($products);
    }

    /**
     * Function for rendering addEditProduct.php page.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function addEditProduct(Request $request): HTMLResponse
    {
        $categories = CategoryService::getAllCategories();
        $categories = CategoryService::getFormattedCategories($categories);
        $myObj = [
            'categories' => $categories,
        ];
        return new HTMLResponse('/views/admin/addEditProduct.php', $myObj);
    }

    /**
     * Function for inserting new product.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function createNewProduct(Request $request): HTMLResponse
    {
        $myObj = [
            'message' => 'Product insert successful.',
        ];

        $response = new HTMLResponse('/views/admin/product.php', $myObj);

        $file = $request->getFile('img');

        if (!ProductService::createNewProduct($request->getPostData(), $file)) {
            $categories = CategoryService::getAllCategories();
            $categories = CategoryService::getFormattedCategories($categories);
            $myObj = [
                'message' => 'Product insert failed.',
                'categories' => $categories,
            ];
            $response = new HTMLResponse('/views/admin/addEditProduct.php', $myObj);
        }

        return $response;
    }
}