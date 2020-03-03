<?php


namespace Demoshop\Controllers\AdminControllers;


use Demoshop\Controllers\AdminController;
use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\Request;
use Demoshop\ServiceRegistry\ServiceRegistry;

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
        $productService = ServiceRegistry::get('ProductsService');
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
        $productService = ServiceRegistry::get('ProductsService');
        $response = new HTMLResponse('/views/admin/addEditProduct.php');
        $categoryService = ServiceRegistry::get('CategoryService');
        $categories = $categoryService->getAllCategories();

        if (isset($request->getGetData()['sku'])) {
            $product = $productService->getProductBySku($request->getGetData()['sku']);

            if (!$product) {
                $myObj = [
                    'message' => 'Error! Product with given sku not found.',
                    'products' => $productService->getAllProductsFormatted(1),
                    'currentPage' => 1,
                    'numberOfPages' => $productService->getNumberOfPages(),
                ];
                $response = new HTMLResponse('/views/admin/product.php');
            } else {
                $myObj = [
                    'id' => $product['id'],
                    'categories' => $categories,
                    'sku' => $product['sku'],
                    'title' => $product['title'],
                    'brand' => $product['brand'],
                    'category' => $product['category_id'],
                    'price' => $product['price'],
                    'shortDesc' => $product['short_description'],
                    'description' => $product['description'],
                    'enabled' => $product['enabled'],
                    'featured' => $product['featured'],
                    'image' => base64_encode($product['image']),
                ];
            }
        } else {
            $myObj = [
                'categories' => $categories,
            ];
        }

        $response->setViewArguments($myObj);
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
        $productService = ServiceRegistry::get('ProductsService');
        $categoryService = ServiceRegistry::get('CategoryService');
        $response = new HTMLResponse('/views/admin/product.php');

        $file = $request->getFile('img');

        if ($productService->createNewProduct($request->getPostData(), $file)) {
            $myObj = [
                'message' => 'Product insert successful.',
                'products' => $productService->getAllProductsFormatted(1),
                'currentPage' => 1,
                'numberOfPages' => $productService->getNumberOfPages(),
            ];
        } else {
            $categories = $categoryService->getAllCategories();
            $myObj = [
                'message' => 'Product insert failed',
                'categories' => $categories,
            ];

            $response = new HTMLResponse('/views/admin/addEditProduct.php');
        }

        $response->setViewArguments($myObj);
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
        $productService = ServiceRegistry::get('ProductsService');
        $categoryService = ServiceRegistry::get('CategoryService');
        $response = new HTMLResponse('/views/admin/product.php');

        $file = $request->getFile('img');

        if (!$productService->updateProduct($request->getPostData(), $file)) {
            $categories = $categoryService->getAllCategories();
            $product = $productService->getProductBySku($request->getPostData()['oldSku']);

            if (!$product) {
                $myObj = [
                    'message' => 'Product update failed.',
                    'categories' => $categories,
                ];
            } else {
                $myObj = [
                    'message' => 'Product update failed.',
                    'categories' => $categories,
                    'id' => $product['id'],
                    'sku' => $product['sku'],
                    'title' => $product['title'],
                    'brand' => $product['brand'],
                    'category' => $product['category_id'],
                    'price' => $product['price'],
                    'shortDesc' => $product['short_description'],
                    'description' => $product['description'],
                    'enabled' => $product['enabled'],
                    'featured' => $product['featured'],
                    'image' => base64_encode($product['image']),
                ];
            }

            $response = new HTMLResponse('/views/admin/addEditProduct.php');
        } else {
            $myObj = [
                'message' => 'Product update successful.',
                'products' => $productService->getAllProductsFormatted(1),
                'currentPage' => 1,
                'numberOfPages' => $productService->getNumberOfPages(),
            ];
        }

        $response->setViewArguments($myObj);
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
        $productService = ServiceRegistry::get('ProductsService');
        $response = new HTMLResponse('/views/admin/product.php');
        $myObj = [
            'message' => 'Product deleted.',
        ];
        $currentPage = $request->getGetData()['currentPage'];

        if (!$productService->deleteProduct($request->getGetData()['sku'])) {
            $myObj['failMessage'] = 'Failed to delete product.';
            unset($myObj['message']);
        }

        if ($currentPage > $productService->getNumberOfPages()) {
            $currentPage = $productService->getNumberOfPages();
        }

        $myObj['products'] = $productService->getAllProductsFormatted($currentPage);
        $myObj['currentPage'] = $currentPage;
        $myObj['numberOfPages'] = $productService->getNumberOfPages();
        $response->setViewArguments($myObj);

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
        $productService = ServiceRegistry::get('ProductsService');
        $response = new HTMLResponse('/views/admin/product.php');
        $currentPage = $request->getGetData()['currentPage'];

        if(!empty($request->getPostData())) {
            if (!$productService->deleteMultipleProducts($request->getPostData())) {
                $myObj = [
                    'failMessage' => 'Failed to delete products.',
                ];
            } else {
                $myObj = [
                    'message' => 'Products deleted.',
                ];
            }
        }

        if ($currentPage > $productService->getNumberOfPages()) {
            $currentPage = $productService->getNumberOfPages();
        }

        $myObj['products'] = $productService->getAllProductsFormatted($currentPage);
        $myObj['currentPage'] = $currentPage;
        $myObj['numberOfPages'] = $productService->getNumberOfPages();
        $response->setViewArguments($myObj);

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
        $productService = ServiceRegistry::get('ProductsService');
        $response = new HTMLResponse('/views/admin/product.php');

        if (!$productService->enableProducts($request->getPostData())) {
            $myObj = [
                'failMessage' => 'Failed to enable products.',
            ];
        }

        $myObj['products'] = $productService->getAllProductsFormatted($request->getGetData()['currentPage']);
        $myObj['currentPage'] = $request->getGetData()['currentPage'];
        $myObj['numberOfPages'] = $productService->getNumberOfPages();
        $response->setViewArguments($myObj);

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
        $productService = ServiceRegistry::get('ProductsService');
        $response = new HTMLResponse('/views/admin/product.php');

        if (!$productService->disableProducts($request->getPostData())) {
            $myObj = [
                'failMessage' => 'Failed to disable products.',
            ];
        }

        $myObj['products'] = $productService->getAllProductsFormatted($request->getGetData()['currentPage']);
        $myObj['currentPage'] = $request->getGetData()['currentPage'];
        $myObj['numberOfPages'] = $productService->getNumberOfPages();
        $response->setViewArguments($myObj);

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
        $productService = ServiceRegistry::get('ProductsService');
        $response = new HTMLResponse('/views/admin/product.php');

        if (!$productService->enableOrDisableProduct($request->getGetData()['sku'])) {
            $myObj = [
                'failMessage' => 'Failed to change enable.',
            ];
        }

        $myObj['products'] = $productService->getAllProductsFormatted($request->getGetData()['currentPage']);
        $myObj['currentPage'] = $request->getGetData()['currentPage'];
        $myObj['numberOfPages'] = $productService->getNumberOfPages();
        $response->setViewArguments($myObj);

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
        $productService = ServiceRegistry::get('ProductsService');
        $myObj = [
            'products' => $productService->getAllProductsFormatted(1),
            'currentPage' => 1,
            'numberOfPages' => $productService->getNumberOfPages(),
        ];

        return new HTMLResponse('/views/admin/product.php', $myObj);
    }

    /**
     * Render next page.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function nextPage(Request $request): HTMLResponse
    {
        $productService = ServiceRegistry::get('ProductsService');

        if ($request->getGetData()['currentPage'] >= $productService->getNumberOfPages()) {
            $currentPage = $productService->getNumberOfPages();
        } else {
            $currentPage = $request->getGetData()['currentPage'] + 1;
        }

        $myObj = [
            'products' => $productService->getAllProductsFormatted($currentPage),
            'currentPage' => $currentPage,
            'numberOfPages' => $productService->getNumberOfPages(),
        ];

        return new HTMLResponse('/views/admin/product.php', $myObj);
    }

    /**
     * Render last page.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function lastPage(Request $request): HTMLResponse
    {
        $productService = ServiceRegistry::get('ProductsService');
        $myObj = [
            'products' => $productService->getAllProductsFormatted($productService->getNumberOfPages()),
            'currentPage' => $productService->getNumberOfPages(),
            'numberOfPages' => $productService->getNumberOfPages(),
        ];

        return new HTMLResponse('/views/admin/product.php', $myObj);
    }

    /**
     * Render previous page.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function prevPage(Request $request): HTMLResponse
    {
        $productService = ServiceRegistry::get('ProductsService');

        if ($request->getGetData()['currentPage'] === '1') {
            $currentPage = 1;
        } else {
            $currentPage = $request->getGetData()['currentPage'] - 1;
        }

        $myObj = [
            'products' => $productService->getAllProductsFormatted($currentPage),
            'currentPage' => $currentPage,
            'numberOfPages' => $productService->getNumberOfPages(),
        ];

        return new HTMLResponse('/views/admin/product.php', $myObj);
    }
}