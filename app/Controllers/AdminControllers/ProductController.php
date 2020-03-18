<?php


namespace Demoshop\Controllers\AdminControllers;


use Demoshop\Controllers\AdminController;
use Demoshop\Formatters\ProductFormatter;
use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\Request;

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
        $productService = $this->getProductService();
        $formatter = new ProductFormatter();

        $products = $productService->getProductsForCurrentPage(1);
        $products = $formatter->formatProductsForTable($products);

        $productsViewArguments = [
            'products' => $products,
            'currentPage' => 1,
            'numberOfPages' => $productService->getNumberOfPages(),
        ];

        return new HTMLResponse('/views/admin/product.php', $productsViewArguments);
    }

    /**
     * Function for rendering addEditProduct.php page.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function addEditProduct(Request $request): HTMLResponse
    {
        $productService = $this->getProductService();
        $formatter = new ProductFormatter();

        $response = new HTMLResponse('/views/admin/addEditProduct.php');
        $categoryService = $this->getCategoryService();
        $categories = $categoryService->getCategories();
        $categories = $categoryService->getFormattedCategories($categories);

        if (!preg_match('/create$/', $request->getRequestURI())) {
            $uriSegments = explode('/', $request->getRequestURI());
            $sku = end($uriSegments);
            $product = $productService->getProductBySku($sku);

            if (!$product) {
                $products = $productService->getProductsForCurrentPage(1);
                $products = $formatter->formatProductsForTable($products);

                $addEditViewArguments = [
                    'message' => 'Error! Product with given sku not found.',
                    'products' => $products,
                    'currentPage' => 1,
                    'numberOfPages' => $productService->getNumberOfPages(),
                ];
                $response = new HTMLResponse('/views/admin/product.php');
            } else {
                $addEditViewArguments = $formatter->formatProduct($product);
                $addEditViewArguments['categories'] = $categories;
            }
        } else {
            $addEditViewArguments = [
                'categories' => $categories,
            ];
        }

        $response->setViewArguments($addEditViewArguments);
        return $response;
    }

    /**
     * Function for inserting new product.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function createNewProduct(Request $request): HTMLResponse
    {
        $productService = $this->getProductService();
        $categoryService = $this->getCategoryService();
        $formatter = new ProductFormatter();

        $response = new HTMLResponse('/views/admin/product.php');

        $file = $request->getFile('img');

        if ($productService->createNewProduct($request->getPostData(), $file)) {
            $products = $productService->getProductsForCurrentPage(1);
            $products = $formatter->formatProductsForTable($products);

            $createProgramViewArguments = [
                'message' => 'Product insert successful.',
                'products' => $products,
                'currentPage' => 1,
                'numberOfPages' => $productService->getNumberOfPages(),
            ];
        } else {
            $categories = $categoryService->getCategories();
            $categories = $categoryService->getFormattedCategories($categories);
            $createProgramViewArguments = [
                'message' => 'Product insert failed',
                'categories' => $categories,
            ];

            $response = new HTMLResponse('/views/admin/addEditProduct.php');
        }

        $response->setViewArguments($createProgramViewArguments);
        return $response;
    }

    /**
     * Update existing product.
     *
     * @param Request $request
     * @param string $oldSku
     * @return HTMLResponse
     */
    public function updateProduct(Request $request, string $oldSku): HTMLResponse
    {
        $productService = $this->getProductService();
        $categoryService = $this->getCategoryService();
        $formatter = new ProductFormatter();

        $response = new HTMLResponse('/views/admin/product.php');

        $file = $request->getFile('img');

        if (!$productService->updateProduct($request->getPostData(), $oldSku, $file)) {
            $categories = $categoryService->getCategories();
            $categories = $categoryService->getFormattedCategories($categories);
            $product = $productService->getProductBySku($request->getPostData()['oldSku']);

            if (!$product) {
                $updateProductViewArguments = [
                    'message' => 'Product update failed.',
                    'categories' => $categories,
                ];
            } else {
                $updateProductViewArguments = $formatter->formatProduct($product);
                $updateProductViewArguments['message'] = 'Product update failed.';
                $updateProductViewArguments['categories'] = $categories;
            }

            $response = new HTMLResponse('/views/admin/addEditProduct.php');
        } else {
            $products = $productService->getProductsForCurrentPage(1);
            $products = $formatter->formatProductsForTable($products);

            $updateProductViewArguments = [
                'message' => 'Product update successful.',
                'products' => $products,
                'currentPage' => 1,
                'numberOfPages' => $productService->getNumberOfPages(),
            ];
        }

        $response->setViewArguments($updateProductViewArguments);
        return $response;
    }

    /**
     * Delete one product.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function deleteProduct(Request $request): HTMLResponse
    {
        $productService = $this->getProductService();
        $formatter = new ProductFormatter();

        $response = new HTMLResponse('/views/admin/product.php');
        $deleteViewArguments = [
            'message' => 'Product deleted.',
        ];
        $currentPage = $request->getGetData()['currentPage'];

        if (!$productService->deleteProduct($request->getGetData()['sku'])) {
            $deleteViewArguments['failMessage'] = 'Failed to delete product.';
            unset($deleteViewArguments['message']);
        }

        if ($currentPage > $productService->getNumberOfPages()) {
            $currentPage = $productService->getNumberOfPages();
        }

        $products = $productService->getProductsForCurrentPage($currentPage);
        $products = $formatter->formatProductsForTable($products);

        $deleteViewArguments['products'] = $products;
        $deleteViewArguments['currentPage'] = $currentPage;
        $deleteViewArguments['numberOfPages'] = $productService->getNumberOfPages();
        $response->setViewArguments($deleteViewArguments);

        return $response;
    }

    /**
     * Delete multiple products.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function deleteMultiple(Request $request): HTMLResponse
    {
        $productService = $this->getProductService();
        $formatter = new ProductFormatter();

        $response = new HTMLResponse('/views/admin/product.php');
        $currentPage = $request->getGetData()['currentPage'];

        if (!empty($request->getPostData())) {
            if (!$productService->deleteMultipleProducts($request->getPostData())) {
                $deleteViewArguments = [
                    'failMessage' => 'Failed to delete products.',
                ];
            } else {
                $deleteViewArguments = [
                    'message' => 'Products deleted.',
                ];
            }
        }

        if ($currentPage > $productService->getNumberOfPages()) {
            $currentPage = $productService->getNumberOfPages();
        }

        $products = $productService->getProductsForCurrentPage($currentPage);
        $products = $formatter->formatProductsForTable($products);

        $deleteViewArguments['products'] = $products;
        $deleteViewArguments['currentPage'] = $currentPage;
        $deleteViewArguments['numberOfPages'] = $productService->getNumberOfPages();
        $response->setViewArguments($deleteViewArguments);

        return $response;
    }

    /**
     * Enable selected products.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function enableSelected(Request $request): HTMLResponse
    {
        $productService = $this->getProductService();
        $formatter = new ProductFormatter();

        $response = new HTMLResponse('/views/admin/product.php');

        if (!$productService->enableProducts($request->getPostData())) {
            $enableViewArguments = [
                'failMessage' => 'Failed to enable products.',
            ];
        }

        $products = $productService->getProductsForCurrentPage($request->getGetData()['currentPage']);

        $enableViewArguments['products'] = $formatter->formatProductsForTable($products);
        $enableViewArguments['currentPage'] = $request->getGetData()['currentPage'];
        $enableViewArguments['numberOfPages'] = $productService->getNumberOfPages();
        $response->setViewArguments($enableViewArguments);

        return $response;
    }

    /**
     * Disable selected products.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function disableSelected(Request $request): HTMLResponse
    {
        $productService = $this->getProductService();
        $formatter = new ProductFormatter();
        $response = new HTMLResponse('/views/admin/product.php');

        if (!$productService->disableProducts($request->getPostData())) {
            $disableViewArguments = [
                'failMessage' => 'Failed to disable products.',
            ];
        }

        $products = $productService->getProductsForCurrentPage($request->getGetData()['currentPage']);

        $disableViewArguments['products'] = $formatter->formatProductsForTable($products);
        $disableViewArguments['currentPage'] = $request->getGetData()['currentPage'];
        $disableViewArguments['numberOfPages'] = $productService->getNumberOfPages();
        $response->setViewArguments($disableViewArguments);

        return $response;
    }

    /**
     * Enable or disable product.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function enableOrDisableProduct(Request $request): HTMLResponse
    {
        $productService = $this->getProductService();
        $formatter = new ProductFormatter();
        $response = new HTMLResponse('/views/admin/product.php');

        if (!$productService->enableOrDisableProduct($request->getGetData()['sku'])) {
            $enableDisableViewArguments = [
                'failMessage' => 'Failed to change enable.',
            ];
        }

        $products = $productService->getProductsForCurrentPage($request->getGetData()['currentPage']);

        $enableDisableViewArguments['products'] = $formatter->formatProductsForTable($products);
        $enableDisableViewArguments['currentPage'] = $request->getGetData()['currentPage'];
        $enableDisableViewArguments['numberOfPages'] = $productService->getNumberOfPages();
        $response->setViewArguments($enableDisableViewArguments);

        return $response;
    }

    /**
     * Render first page.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function firstPage(Request $request): HTMLResponse
    {
        $productService = $this->getProductService();
        $formatter = new ProductFormatter();

        $products = $productService->getProductsForCurrentPage(1);

        $firstPageViewArguments = [
            'products' => $formatter->formatProductsForTable($products),
            'currentPage' => 1,
            'numberOfPages' => $productService->getNumberOfPages(),
        ];

        return new HTMLResponse('/views/admin/product.php', $firstPageViewArguments);
    }

    /**
     * Render next page.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function nextPage(Request $request): HTMLResponse
    {
        $productService = $this->getProductService();
        $formatter = new ProductFormatter();

        if ($request->getGetData()['currentPage'] >= $productService->getNumberOfPages()) {
            $currentPage = $productService->getNumberOfPages();
        } else {
            $currentPage = $request->getGetData()['currentPage'] + 1;
        }

        $products = $productService->getProductsForCurrentPage($currentPage);

        $nextPageViewArguments = [
            'products' => $formatter->formatProductsForTable($products),
            'currentPage' => $currentPage,
            'numberOfPages' => $productService->getNumberOfPages(),
        ];

        return new HTMLResponse('/views/admin/product.php', $nextPageViewArguments);
    }

    /**
     * Render last page.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function lastPage(Request $request): HTMLResponse
    {
        $productService = $this->getProductService();
        $formatter = new ProductFormatter();

        $lastPage = $productService->getNumberOfPages();
        $products = $productService->getProductsForCurrentPage($lastPage);

        $lastPageViewArguments = [
            'products' => $formatter->formatProductsForTable($products),
            'currentPage' => $lastPage,
            'numberOfPages' => $lastPage,
        ];

        return new HTMLResponse('/views/admin/product.php', $lastPageViewArguments);
    }

    /**
     * Render previous page.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function prevPage(Request $request): HTMLResponse
    {
        $productService = $this->getProductService();
        $formatter = new ProductFormatter();

        if ($request->getGetData()['currentPage'] === '1') {
            $currentPage = 1;
        } else {
            $currentPage = $request->getGetData()['currentPage'] - 1;
        }

        $products = $productService->getProductsForCurrentPage($currentPage);

        $prevPageViewArguments = [
            'products' => $formatter->formatProductsForTable($products),
            'currentPage' => $currentPage,
            'numberOfPages' => $productService->getNumberOfPages(),
        ];

        return new HTMLResponse('/views/admin/product.php', $prevPageViewArguments);
    }
}