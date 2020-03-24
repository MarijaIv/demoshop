<?php


namespace Demoshop\Controllers\FrontControllers;


use Demoshop\Controllers\FrontController;
use Demoshop\Formatters\CategoryFormatter;
use Demoshop\Formatters\ProductFormatter;
use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\Request;

/**
 * Class ProductSearchController
 * @package Demoshop\Controllers\FrontControllers
 */
class ProductSearchController extends FrontController
{
    /**
     * Render search results.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function index(Request $request): HTMLResponse
    {
        $productService = $this->getProductService();
        $categoryService = $this->getCategoryService();
        $formatter = new ProductFormatter();
        $categoryFormatter = new CategoryFormatter();

        $getData = $request->getGetData();

        $categories = $categoryService->getRootCategories();
        $categories = $categoryService->formatCategoriesForTreeView($categories);

        $optionCategories = $categoryService->getCategories();
        $optionCategories = $categoryFormatter->getFormattedCategories($optionCategories);

        $searchProducts = $productService->searchProducts($getData);
        $searchProducts = $formatter->formatProductsForVisitor($searchProducts, $getData);

        $searchArguments = [
            'optionCategories' => $optionCategories,
            'categories' => $categories,
            'searchOrCategory' => 1,
            'products' => $searchProducts['products'],
            'numberOfPages' => $searchProducts['numberOfPages'],
            'currentPage' => $searchProducts['currentPage'],
            'sorting' => $searchProducts['sorting'],
            'productsPerPage' => $searchProducts['productsPerPage'],
            'search' => $getData['search'],
            'keyword' => $getData['keyword'],
            'maxPrice' => $getData['maxPrice'],
            'minPrice' => $getData['minPrice'],
            'selectedCategory' => $getData['category'],
        ];

        return new HTMLResponse('/views/visitor/categoryDisplay.php', $searchArguments);
    }
}