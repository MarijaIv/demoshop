<?php


namespace Demoshop\Controllers\FrontControllers;


use Demoshop\Controllers\FrontController;
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

        $categories = $categoryService->getRootCategories();
        $categories = $categoryService->formatCategoriesForTreeView($categories);

        $optionCategories = $categoryService->getCategories();
        $optionCategories = $categoryService->getFormattedCategories($optionCategories);

        $searchProducts = $productService->searchProducts($request->getGetData());
        $searchProducts = $formatter->formatProductsForVisitor($searchProducts, $request->getGetData());

        $searchArguments = [
            'optionCategories' => $optionCategories,
            'categories' => $categories,
            'searchOrCategory' => 1,
            'products' => $searchProducts['products'],
            'numberOfPages' => $searchProducts['numberOfPages'],
            'currentPage' => $searchProducts['currentPage'],
            'sorting' => $searchProducts['sorting'],
            'productsPerPage' => $searchProducts['productsPerPage'],
            'search' => $request->getGetData()['search'],
            'keyword' => $request->getGetData()['keyword'],
            'maxPrice' => $request->getGetData()['maxPrice'],
            'minPrice' => $request->getGetData()['minPrice'],
            'message' => 'No search results.',
        ];

        return new HTMLResponse('/views/visitor/categoryDisplay.php', $searchArguments);
    }
}