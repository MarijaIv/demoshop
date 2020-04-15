<?php


namespace Demoshop\Controllers\FrontControllers;


use Demoshop\Controllers\FrontController;
use Demoshop\Entity\SearchParameters;
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
        $productService = $this->getFrontProductService();
        $categoryService = $this->getCategoryService();
        $formatter = new ProductFormatter();
        $categoryFormatter = new CategoryFormatter();

        $getData = $request->getGetData();

        $allCategories = $categoryService->getCategories();
        $optionCategories = $categoryFormatter->getFormattedCategories($allCategories);
        $categories = $categoryFormatter->formatCategoriesForTreeView($allCategories);

        $getData['sorting'] = empty($getData['sorting'])? 'relevance' : $getData['sorting'];

        $searchParameters = new SearchParameters(
            empty($getData['keyword']) ? '' : $getData['keyword'],
            empty($getData['minPrice'])? null : $getData['minPrice'],
            empty($getData['maxPrice'])? null : $getData['maxPrice'],
            empty($getData['category'])? null : $getData['category'],
            empty($getData['search'])? '' : $getData['search']
        );

        $searchProducts = $productService->searchProducts($searchParameters);
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
            'search' => '',
            'keyword' => $getData['keyword'],
            'maxPrice' => $getData['maxPrice'],
            'minPrice' => $getData['minPrice'],
            'selectedCategory' => $getData['category'],
        ];

        return new HTMLResponse('/views/visitor/categoryDisplay.php', $searchArguments);
    }
}