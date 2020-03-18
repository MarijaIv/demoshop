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
    public function index(Request $request, string $sku): HTMLResponse
    {
        $productService = $this->getProductService();
        $product = $productService->getProductBySku($sku);
        if ($product) {
            $product = $productService->formatProduct($product);
        }
        $productService->increaseProductViewCount($sku);

        $categoryService = $this->getCategoryService();
        $categories = $categoryService->getRootCategories();
        $categories = $categoryService->formatCategoriesForTreeView($categories);

        $productDetails = [
            'product' => $product,
            'categories' => $categories,
        ];

        return new HTMLResponse('/views/visitor/productDetails.php', $productDetails);
    }

    /**
     * Render categoryDisplay.php page.
     *
     * @param Request $request
     * @param string $code
     * @return HTMLResponse
     */
    public function listProducts(Request $request, string $code): HTMLResponse
    {
        $productService = $this->getProductService();
        $dataForCategoryDisplay = $productService->getDataForCategoryDisplay($code, $request->getGetData());

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
            'searchOrCategory' => 0,
            'selectedCategory' => $categoryService->getCategoryByCode($code),
            'message' => 'This category is empty.',
        ];
        return new HTMLResponse('/views/visitor/categoryDisplay.php', $categoryDisplayArguments);
    }
}