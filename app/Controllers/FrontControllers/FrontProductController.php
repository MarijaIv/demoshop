<?php


namespace Demoshop\Controllers\FrontControllers;


use Demoshop\Controllers\FrontController;
use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\Request;

/**
 * Class FrontProductController
 * @package Demoshop\Controllers\FrontControllers
 */
class FrontProductController extends FrontController
{
    /**
     * Render productDetails.php page.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function index(Request $request): HTMLResponse
    {
        return new HTMLResponse('/views/visitor/productDetails.php');
    }

    /**
     * Render categoryDisplay.php page.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function listProducts(Request $request): HTMLResponse
    {
        $productService = $this->getProductService();
        $dataForCategoryDisplay = $productService->getDataForCategoryDisplay($request->getGetData());

        $categoryService = $this->getCategoryService();
        $categories = $categoryService->getRootCategories();
        $categories = $categoryService->formatCategoriesForTreeView($categories);

        $categoryDisplayArguments = [
            'products' => $dataForCategoryDisplay['products'],
            'currentPage' => $dataForCategoryDisplay['currentPage'],
            'numberOfPages' => $dataForCategoryDisplay['numberOfPages'],
            'sorting' => $dataForCategoryDisplay['sorting'],
            'productsPerPage' => $dataForCategoryDisplay['productsPerPage'],
            'categories' => $categories,
        ];
        return new HTMLResponse('/views/visitor/categoryDisplay.php', $categoryDisplayArguments);
    }
}