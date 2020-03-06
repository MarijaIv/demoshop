<?php


namespace Demoshop\Controllers\AdminControllers;


use Demoshop\Controllers\AdminController;
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
        $products = [
            'products' => $productService->getAllProductsFormatted(1),
            'currentPage' => 1,
            'numberOfPages' => $productService->getNumberOfPages(),
        ];

        return new HTMLResponse('/views/admin/product.php', $products);
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
        $response = new HTMLResponse('/views/admin/addEditProduct.php');
        $categoryService = $this->getCategoryService();
        $categories = $categoryService->getAllCategories();
        $categories = $categoryService->getFormattedCategories($categories);

        if (isset($request->getGetData()['sku'])) {
            $product = $productService->getProductBySku($request->getGetData()['sku']);

            if (!$product) {
                $addEditViewArguments = [
                    'message' => 'Error! Product with given sku not found.',
                    'products' => $productService->getAllProductsFormatted(1),
                    'currentPage' => 1,
                    'numberOfPages' => $productService->getNumberOfPages(),
                ];
                $response = new HTMLResponse('/views/admin/product.php');
            } else {
                $addEditViewArguments = $productService->formatProduct($product);
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
        $response = new HTMLResponse('/views/admin/product.php');

        $file = $request->getFile('img');

        if ($productService->createNewProduct($request->getPostData(), $file)) {
            $createProgramViewArguments = [
                'message' => 'Product insert successful.',
                'products' => $productService->getAllProductsFormatted(1),
                'currentPage' => 1,
                'numberOfPages' => $productService->getNumberOfPages(),
            ];
        } else {
            $categories = $categoryService->getAllCategories();
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
     * @return HTMLResponse
     */
    public function updateProduct(Request $request): HTMLResponse
    {
        $productService = $this->getProductService();
        $categoryService = $this->getCategoryService();
        $response = new HTMLResponse('/views/admin/product.php');

        $file = $request->getFile('img');

        if (!$productService->updateProduct($request->getPostData(), $file)) {
            $categories = $categoryService->getAllCategories();
            $categories = $categoryService->getFormattedCategories($categories);
            $product = $productService->getProductBySku($request->getPostData()['oldSku']);

            if (!$product) {
                $updateProductViewArguments = [
                    'message' => 'Product update failed.',
                    'categories' => $categories,
                ];
            } else {
                $updateProductViewArguments = $productService->formatProduct($product);
                $updateProductViewArguments['message'] = 'Product update failed.';
                $updateProductViewArguments['categories'] = $categories;
            }

            $response = new HTMLResponse('/views/admin/addEditProduct.php');
        } else {
            $updateProductViewArguments = [
                'message' => 'Product update successful.',
                'products' => $productService->getAllProductsFormatted(1),
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

        $deleteViewArguments['products'] = $productService->getAllProductsFormatted($currentPage);
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

        $deleteViewArguments['products'] = $productService->getAllProductsFormatted($currentPage);
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
        $response = new HTMLResponse('/views/admin/product.php');

        if (!$productService->enableProducts($request->getPostData())) {
            $enableViewArguments = [
                'failMessage' => 'Failed to enable products.',
            ];
        }

        $enableViewArguments['products'] =
            $productService->getAllProductsFormatted($request->getGetData()['currentPage']);
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
        $response = new HTMLResponse('/views/admin/product.php');

        if (!$productService->disableProducts($request->getPostData())) {
            $disableViewArguments = [
                'failMessage' => 'Failed to disable products.',
            ];
        }

        $disableViewArguments['products'] =
            $productService->getAllProductsFormatted($request->getGetData()['currentPage']);
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
        $response = new HTMLResponse('/views/admin/product.php');

        if (!$productService->enableOrDisableProduct($request->getGetData()['sku'])) {
            $enableDisableViewArguments = [
                'failMessage' => 'Failed to change enable.',
            ];
        }

        $enableDisableViewArguments['products'] =
            $productService->getAllProductsFormatted($request->getGetData()['currentPage']);
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
        $firstPageViewArguments = [
            'products' => $productService->getAllProductsFormatted(1),
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

        if ($request->getGetData()['currentPage'] >= $productService->getNumberOfPages()) {
            $currentPage = $productService->getNumberOfPages();
        } else {
            $currentPage = $request->getGetData()['currentPage'] + 1;
        }

        $nextPageViewArguments = [
            'products' => $productService->getAllProductsFormatted($currentPage),
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
        $lastPageViewArguments = [
            'products' => $productService->getAllProductsFormatted($productService->getNumberOfPages()),
            'currentPage' => $productService->getNumberOfPages(),
            'numberOfPages' => $productService->getNumberOfPages(),
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

        if ($request->getGetData()['currentPage'] === '1') {
            $currentPage = 1;
        } else {
            $currentPage = $request->getGetData()['currentPage'] - 1;
        }

        $prevPageViewArguments = [
            'products' => $productService->getAllProductsFormatted($currentPage),
            'currentPage' => $currentPage,
            'numberOfPages' => $productService->getNumberOfPages(),
        ];

        return new HTMLResponse('/views/admin/product.php', $prevPageViewArguments);
    }
}