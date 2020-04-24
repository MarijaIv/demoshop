<?php


namespace Demoshop\Controllers\FrontControllers;


use Demoshop\Controllers\FrontController;
use Demoshop\Formatters\CategoryFormatter;
use Demoshop\Formatters\ProductFormatter;
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
     * @param string $sku
     * @return HTMLResponse
     */
    public function index(Request $request, string $sku): HTMLResponse
    {
        $productService = $this->getProductService();
        $formatter = new ProductFormatter();
        $product = $productService->getProductBySku($sku);

        $categories = $this->categories();

        if (!$product) {
            $landingPageViewArguments = [
                'categories' => $categories,
                'products' => $this->getFrontProductService()->getFeaturedProducts(),
            ];
            return new HTMLResponse('/views/visitor/landingPage.php', $landingPageViewArguments);
        }

        $product = $formatter->formatProduct($product);
        $productService->increaseProductViewCount($sku);

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
        $categoryService = $this->getCategoryService();
        $formatter = new ProductFormatter();
        $categoryFormatter = new CategoryFormatter();

        $categories = $categoryService->getAllSubcategories($code);
        $categoryCodes = $categoryFormatter->getCategoriesCodes($categories);
        $dataForCategoryDisplay = $this->getFrontProductService()->getDataForCategoryDisplay($categoryCodes);
        $dataForCategoryDisplay = $formatter->formatProductsForVisitor($dataForCategoryDisplay, $request->getGetData());

        $allCategories = $this->categories();

        $categoryDisplayArguments = [
            'products' => $dataForCategoryDisplay['products'],
            'currentPage' => $dataForCategoryDisplay['currentPage'],
            'numberOfPages' => $dataForCategoryDisplay['numberOfPages'],
            'sorting' => $dataForCategoryDisplay['sorting'],
            'productsPerPage' => $dataForCategoryDisplay['productsPerPage'],
            'categories' => $allCategories,
            'searchOrCategory' => 0,
            'selectedCategory' => $categoryService->getCategoryByCode($code),
        ];
        return new HTMLResponse('/views/visitor/categoryDisplay.php', $categoryDisplayArguments);
    }

    /**
     * Get categories for treeview.
     *
     * @return array
     */
    private function categories(): array
    {
        $categoryFormatter = new CategoryFormatter();
        $allCategories = $this->getCategoryService()->getCategories();
        return $categoryFormatter->formatCategoriesForTreeView($allCategories);
    }
}