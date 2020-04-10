<?php


namespace Demoshop\Controllers\AdminControllers;


use Demoshop\AuthorizationMiddleware\Exceptions\ProductDataInvalidException;
use Demoshop\Controllers\AdminController;
use Demoshop\Entity\Product;
use Demoshop\Formatters\CategoryFormatter;
use Demoshop\Formatters\ProductFormatter;
use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\RedirectResponse;
use Demoshop\HTTP\Request;
use Demoshop\HTTP\Response;
use Demoshop\ServiceRegistry\ServiceRegistry;
use Demoshop\Session\PHPSession;
use Illuminate\Support\Collection;

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
        $products = $productService->getProducts(1, 10);
        $productsViewArguments = $this->setViewArguments($products, 1);

        return new HTMLResponse('/views/admin/product.php', $productsViewArguments);
    }

    private function setViewArguments(Collection $products, int $currentPage): array
    {
        $formatter = new ProductFormatter();
        $productService = $this->getProductService();

        return [
            'products' => $formatter->formatProductsForTable($products),
            'currentPage' => $currentPage,
            'numberOfPages' => $productService->getNumberOfPages(10),
        ];
    }

    /**
     * Function for rendering addEditProduct.php page.
     *
     * @param Request $request
     * @return Response
     */
    public function addEditProduct(Request $request): Response
    {
        $productService = $this->getProductService();
        $productFormatter = new ProductFormatter();
        $categoryFormatter = new CategoryFormatter();

        $response = new HTMLResponse('/views/admin/addEditProduct.php');
        $categoryService = $this->getCategoryService();
        $categories = $categoryService->getCategories();
        $categories = $categoryFormatter->getFormattedCategories($categories);

        if (!preg_match('/create$/', $request->getRequestURI())) {
            $uriSegments = explode('/', $request->getRequestURI());
            $sku = end($uriSegments);
            $product = $productService->getProductBySku($sku);

            if (!$product) {
                $this->setSessionMessage('errorMessage', 'Product with given sku does not exist.');
                return new RedirectResponse('/admin/products');
            }

            $viewArguments = $productFormatter->formatProduct($product);
            $viewArguments['categories'] = $categories;
        } else {
            $viewArguments = [
                'categories' => $categories,
            ];
        }

        $response->setViewArguments($viewArguments);
        return $response;
    }

    /**
     * Function for inserting new product.
     *
     * @param Request $request
     * @return Response
     */
    public function createNewProduct(Request $request): Response
    {
        $productService = $this->getProductService();
        $response = new HTMLResponse('/views/admin/product.php');
        $file = $request->getFile('img');
        $product = $this->createProduct($request->getPostData());

        try {
            $productService->validateProduct($product);
            $productService->createNewProduct($product, $file);
            $products = $productService->getProducts(1, 10);

            $this->setSessionMessage('message', 'Product insert successful.');
            $viewArguments = $this->setViewArguments($products, 1);

            $response->setViewArguments($viewArguments);
            return $response;
        } catch (ProductDataInvalidException $e) {
            $this->setSessionMessage('errorMessage', $e->getMessage());
            return new RedirectResponse('/admin/products');
        }
    }

    /**
     * Update existing product.
     *
     * @param Request $request
     * @param string $oldSku
     * @return Response
     */
    public function updateProduct(Request $request, string $oldSku): Response
    {
        $productService = $this->getProductService();
        $response = new HTMLResponse('/views/admin/product.php');
        $file = $request->getFile('img');
        $product = $this->createProduct($request->getPostData());

        try {
            $productService->validateProductForUpdate($product, $oldSku);
            $productService->updateProduct($product, $oldSku, $file);
            $products = $productService->getProducts(1, 10);
            $this->setSessionMessage('message', 'Product update successful.');
            $viewArguments = $this->setViewArguments($products, 1);

            $response->setViewArguments($viewArguments);
            return $response;
        } catch (ProductDataInvalidException $e) {
            $this->setSessionMessage('errorMessage', $e->getMessage());
            return new RedirectResponse('/admin/products');
        }
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
        $currentPage = $request->getGetData()['currentPage'];

        if (!$productService->deleteProduct($request->getGetData()['sku'])) {
            $this->setSessionMessage('errorMessage', 'Failed to delete product.');
            return new RedirectResponse('/admin/products');
        }

        $numberOfPages = $productService->getNumberOfPages(10);

        if ($currentPage > $numberOfPages) {
            $currentPage = $numberOfPages;
        }

        $products = $productService->getProducts($currentPage, 10);
        $this->setSessionMessage('message', 'Product deleted.');
        $viewArguments = $this->setViewArguments($products, $currentPage);

        $response->setViewArguments($viewArguments);

        return $response;
    }

    /**
     * Delete multiple products.
     *
     * @param Request $request
     * @return Response
     */
    public function deleteMultiple(Request $request): Response
    {
        $productService = $this->getProductService();
        $response = new HTMLResponse('/views/admin/product.php');
        $currentPage = $request->getGetData()['currentPage'];

        if (!empty($request->getPostData())) {
            if (!$productService->deleteMultipleProducts($request->getPostData())) {
                $this->setSessionMessage('errorMessage', 'Failed to delete products.');
                return new RedirectResponse('/admin/products');
            }
        }

        $numberOfPages = $productService->getNumberOfPages(10);

        if ($currentPage > $numberOfPages) {
            $currentPage = $numberOfPages;
        }

        $products = $productService->getProducts($currentPage, 10);
        $this->setSessionMessage('message', 'Products deleted.');
        $viewArguments = $this->setViewArguments($products, $currentPage);
        $response->setViewArguments($viewArguments);

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
        $productService->enableProducts($request->getPostData());
        $products = $productService->getProducts($request->getGetData()['currentPage'], 10);
        $viewArguments = $this->setViewArguments($products, $request->getGetData()['currentPage']);
        $response->setViewArguments($viewArguments);

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
        $productService->disableProducts($request->getPostData());
        $products = $productService->getProducts($request->getGetData()['currentPage'], 10);
        $viewArguments = $this->setViewArguments($products, $request->getGetData()['currentPage']);
        $response->setViewArguments($viewArguments);

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

        if ($request->getGetData()['enabled'] === 'true') {
            $productService->disableProducts([$request->getGetData()['sku']]);
        } else {
            $productService->enableProducts([$request->getGetData()['sku']]);
        }

        $products = $productService->getProducts($request->getGetData()['currentPage'], 10);
        $viewArguments = $this->setViewArguments($products, $request->getGetData()['currentPage']);
        $response->setViewArguments($viewArguments);

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
        $products = $productService->getProducts(1, 10);
        $firstPageViewArguments = $this->setViewArguments($products, 1);

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
        $numberOfPages = $productService->getNumberOfPages(10);

        if ($request->getGetData()['currentPage'] >= $numberOfPages) {
            $currentPage = $numberOfPages;
        } else {
            $currentPage = $request->getGetData()['currentPage'] + 1;
        }

        $products = $productService->getProducts($currentPage, 10);
        $nextPageViewArguments = $this->setViewArguments($products, $currentPage);

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
        $lastPage = $productService->getNumberOfPages(10);
        $products = $productService->getProducts($lastPage, 10);
        $lastPageViewArguments = $this->setViewArguments($products, $lastPage);

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

        $products = $productService->getProducts($currentPage, 10);
        $prevPageViewArguments = $this->setViewArguments($products, $currentPage);

        return new HTMLResponse('/views/admin/product.php', $prevPageViewArguments);
    }

    private function createProduct(array $data): Product
    {
        return new Product(
            $data['category'],
            $data['sku'],
            $data['title'],
            $data['brand'],
            $data['price'],
            $data['shortDesc'],
            $data['description'],
            $data['enabled'] ? true : false,
            $data['featured'] ? true : false
        );
    }

    private function setSessionMessage(string $messageName, string $message): void
    {
        /** @var PHPSession $session */
        $session = ServiceRegistry::get('Session');
        $session->add($messageName, $message);
    }
}