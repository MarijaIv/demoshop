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
        return $this->page(1);
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
                return $this->redirection('errorMessage', 'Product with given sku does not exist.');
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
        $file = $request->getFile('img');
        $product = $this->createProduct($request->getPostData());

        try {
            $productService->createNewProduct($product, $file);
            return $this->insertOrUpdate('Product insert successful.');
        } catch (ProductDataInvalidException $e) {
            return $this->redirection('errorMessage', $e->getMessage());
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
        $file = $request->getFile('img');
        $product = $this->createProduct($request->getPostData());

        try {
            $productService->updateProduct($product, $oldSku, $file);

            return $this->insertOrUpdate('Product update successful.');
        } catch (ProductDataInvalidException $e) {
            return $this->redirection('errorMessage', $e->getMessage());
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
        $currentPage = $request->getGetData()['currentPage'];

        if (!$productService->deleteProduct($request->getGetData()['sku'])) {
            return $this->redirection('errorMessage', 'Failed to delete product.');
        }

        return $this->delete($currentPage);
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
        $currentPage = $request->getGetData()['currentPage'];

        if (!empty($request->getPostData())) {
            if (!$productService->deleteMultipleProducts($request->getPostData())) {
                return $this->redirection('errorMessage', 'Failed to delete products.');
            }
        }

        return $this->delete($currentPage);
    }

    /**
     * Enable selected products.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function enableSelected(Request $request): HTMLResponse
    {
        $this->getProductService()->enableProducts($request->getPostData());

        return $this->page($request->getGetData()['currentPage']);
    }

    /**
     * Disable selected products.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function disableSelected(Request $request): HTMLResponse
    {
        $this->getProductService()->disableProducts($request->getPostData());

        return $this->page($request->getGetData()['currentPage']);
    }

    /**
     * Render first page.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function firstPage(Request $request): HTMLResponse
    {
        return $this->page(1);
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

        return $this->page($currentPage);
    }

    /**
     * Render last page.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function lastPage(Request $request): HTMLResponse
    {
        $lastPage = $this->getProductService()->getNumberOfPages(10);
        return $this->page($lastPage);
    }

    /**
     * Render previous page.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function prevPage(Request $request): HTMLResponse
    {
        if ($request->getGetData()['currentPage'] === '1') {
            $currentPage = 1;
        } else {
            $currentPage = $request->getGetData()['currentPage'] - 1;
        }
        return $this->page($currentPage);
    }

    /**
     * Get products for page and return response.
     *
     * @param int $page
     * @return HTMLResponse
     */
    private function page(int $page): HTMLResponse
    {
        $products = $this->getProductService()->getProducts($page, 10);
        $viewArguments = $this->setViewArguments($products, $page);

        return new HTMLResponse('/views/admin/product.php', $viewArguments);
    }

    /**
     * Set view arguments for delete or delete multiple and return response.
     *
     * @param $currentPage
     * @return HTMLResponse
     */
    public function delete($currentPage): HTMLResponse
    {
        $numberOfPages = $this->getProductService()->getNumberOfPages(10);

        if ($currentPage > $numberOfPages) {
            $currentPage = $numberOfPages;
        }

        $this->setSessionMessage('message', 'Product deleted.');

        return $this->page($currentPage);
    }

    /**
     * Set view arguments and return response for create new product or update.
     *
     * @param string $message
     * @return HTMLResponse
     */
    private function insertOrUpdate(string $message): HTMLResponse
    {
        $this->setSessionMessage('message', $message);

        return $this->page(1);
    }

    /**
     * Create new Product entity.
     *
     * @param array $data
     * @return Product
     */
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

    /**
     * Set view arguments.
     *
     * @param Collection $products
     * @param int $currentPage
     * @return array
     */
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
     * Set session message.
     *
     * @param string $messageName
     * @param string $message
     */
    private function setSessionMessage(string $messageName, string $message): void
    {
        /** @var PHPSession $session */
        $session = ServiceRegistry::get('Session');
        $session->add($messageName, $message);
    }

    /**
     * Set session message and return redirect response.
     *
     * @param string $messageName
     * @param string $message
     * @return RedirectResponse
     */
    private function redirection(string $messageName, string $message): RedirectResponse
    {
        $this->setSessionMessage($messageName, $message);

        return new RedirectResponse('/admin/products');
    }
}