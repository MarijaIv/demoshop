<?php

namespace Demoshop\Services;

use Demoshop\DTO\ProductDTO;
use Demoshop\Repositories\CategoryRepository;
use Demoshop\Repositories\ProductsRepository;
use Demoshop\ServiceRegistry\ServiceRegistry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class ProductService
 */
class ProductService
{
    public const MAX_HEIGHT_WIDTH_RATIO = 1.78;
    public const MIN_HEIGHT_WIDTH_RATIO = 1.33;
    public const RECORDS_PER_PAGE = 10;

    /**
     * @var ProductsRepository
     */
    public $productsRepository;

    /**
     * ProductService constructor.
     * @param ProductsRepository $productsRepository
     */
    public function __construct(ProductsRepository $productsRepository)
    {
        $this->productsRepository = $productsRepository;
    }

    /**
     * Get number of products.
     *
     * @return int
     */
    public function getNumberOfProducts(): int
    {
        return $this->productsRepository->getNumberOfProducts();
    }

    /**
     * Get product which details page is displayed most often.
     *
     * @return string
     */
    public function getMostViewedProduct(): string
    {
        return $this->productsRepository->getMostViewedProduct();
    }

    /**
     * Get number of most viewed product views.
     *
     * @return int
     */
    public function getNumberOfMostViews(): int
    {
        return $this->productsRepository->getNumberOfMostViews();
    }

    /**
     * Get id of most viewed product.
     *
     * @return int
     */
    public function getMostViewedProductId(): int
    {
        return $this->productsRepository->getMostViewedProductId();
    }

    /**
     * Increase product view count.
     *
     * @param string $sku
     * @return bool
     */
    public function increaseProductViewCount(string $sku): bool
    {
        if (!$this->productsRepository->productSkuExists($sku)) {
            return false;
        }

        return $this->productsRepository->increaseViewCount($sku);
    }

    /**
     * Get all products for current page.
     *
     * @param int $currentPage
     * @return array
     */
    public function getAllProductsFormatted(int $currentPage): array
    {
        $offset = ($currentPage - 1) * self::RECORDS_PER_PAGE;
        $products = $this->productsRepository->getProductsForCurrentPage($offset, self::RECORDS_PER_PAGE);
        $categoryRepository = new CategoryRepository();
        $formattedProducts = [];
        $i = 0;

        foreach ($products as $product) {
            $formattedProducts[$i]['id'] = $product->id;

            $category = $categoryRepository->getCategoryById($product->category_id);

            $formattedProducts[$i]['category'] = $category->title;

            if ($category->parent_id !== null) {
                $parent = $categoryRepository->getCategoryById($category->parent_id);
                $formattedProducts[$i]['category'] = $parent->title . ' > ' . $formattedProducts[$i]['category'];
                while ($parent->parent_id) {
                    $parent = $categoryRepository->getCategoryById($parent->parent_id);
                    $formattedProducts[$i]['category'] = $parent->title
                        . ' > ' . $formattedProducts[$i]['category'];
                }
            }

            $formattedProducts[$i]['sku'] = $product->sku;
            $formattedProducts[$i]['title'] = $product->title;
            $formattedProducts[$i]['brand'] = $product->brand;
            $formattedProducts[$i]['price'] = $product->price;
            $formattedProducts[$i]['shortDesc'] = $product->short_description;
            $formattedProducts[$i]['description'] = $product->description;
            $formattedProducts[$i]['enabled'] = $product->enabled;
            $formattedProducts[$i]['featured'] = $product->featured;
            $formattedProducts[$i]['viewCount'] = $product->view_count;
            $i++;
        }

        return $formattedProducts;
    }

    /**
     * Create new product.
     *
     * @param array $data
     * @param array $file
     * @return bool
     */
    public function createNewProduct(array $data, array $file): bool
    {
        if (empty($data['sku']) || empty($data['title']) || empty($data['brand']) || empty($data['price'])
            || empty($data['shortDesc']) || empty($data['description'])) {
            return false;
        }

        if ($this->productsRepository->productSkuExists($data['sku'])) {
            return false;
        }

        $data['price'] = (float)$data['price'];
        self::setEnabledAndFeaturedValue($data);

        if (!self::checkImageCharacteristics($file)) {
            return false;
        }

        self::saveFile($file);

        $fileName = __DIR__ . '/../../public/img/' . $file['name'];
        $content = fopen($fileName, 'rb');
        $content = fread($content, filesize($fileName));

        if (!$this->productsRepository->createNewProduct($data, $content)) {
            unlink($fileName);
            return false;
        }

        unlink($fileName);
        return true;
    }

    /**
     * Set product enable and featured value.
     *
     * @param array $data
     */
    private static function setEnabledAndFeaturedValue(array &$data): void
    {
        $data['enabled'] = $data['enabled'] === 'on' ? 1 : 0;
        $data['featured'] = $data['featured'] === 'on' ? 1 : 0;
    }

    /**
     * Check image characteristics.
     *
     * @param array $file
     * @return bool
     */
    private static function checkImageCharacteristics(array $file): bool
    {
        $imageSize = getimagesize($file['tmp_name']);

        if ($imageSize[0] < 600) {
            return false;
        }

        $heightWidthRatio = $imageSize[0] / $imageSize[1];

        return !($heightWidthRatio < self::MIN_HEIGHT_WIDTH_RATIO || $heightWidthRatio > self::MAX_HEIGHT_WIDTH_RATIO);
    }

    /**
     * Save image to /img directory.
     *
     * @param array $file
     */
    private static function saveFile(array $file): void
    {
        $targetFile = __DIR__ . '/../../public/img/' . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $targetFile);
    }

    /**
     * Update product.
     *
     * @param array $data
     * @param string $oldSku
     * @param array $file
     * @return bool
     */
    public function updateProduct(array $data, string $oldSku, array $file): bool
    {
        if (!isset($data['sku'], $data['title'], $data['brand'],
            $data['price'], $data['shortDesc'], $data['description'])) {
            return false;
        }

        if (!$this->productsRepository->productSkuExists($oldSku)) {
            return false;
        }

        if ($oldSku !== $data['sku'] && $this->productsRepository->productSkuExists($data['sku'])) {
            return false;
        }

        if ($file['tmp_name'] !== '' && !self::checkImageCharacteristics($file)) {
            return false;
        }

        if (!$this->productsRepository->getProductBySku($oldSku)) {
            return false;
        }

        self::setEnabledAndFeaturedValue($data);
        $data['price'] = (float)$data['price'];
        $data['category'] = (int)$data['category'];

        if ($file['tmp_name'] === '' && $product = $this->getProductBySku($oldSku)) {
            $content = $product['image'];
        } else {
            self::saveFile($file);
            $fileName = __DIR__ . '/../../public/img/' . $file['name'];
            $content = fopen($fileName, 'rb');
            $content = fread($content, filesize($fileName));
            unlink($fileName);
        }

        $this->productsRepository->updateProduct($data, $oldSku, $content);
        return true;
    }

    /**
     * Get product by sku.
     *
     * @param string $sku
     * @return Builder|Model|null
     */
    public function getProductBySku(string $sku): Model
    {
        if (!$this->productsRepository->productSkuExists($sku)) {
            return null;
        }

        return $this->productsRepository->getProductBySku($sku);
    }

    /**
     * Format product.
     *
     * @param Model $product
     * @return array
     */
    public function formatProduct(Model $product): array
    {
        $productDto = new ProductDTO($product);
        return $productDto->toArray();
    }

    /**
     * Delete product by sku.
     *
     * @param string $sku
     * @return bool
     */
    public function deleteProduct(string $sku): bool
    {
        if (!$this->productsRepository->productSkuExists($sku)) {
            return false;
        }

        $this->productsRepository->deleteProduct($sku);
        return true;
    }

    /**
     * Delete multiple products.
     *
     * @param array $skuArray
     * @return bool
     */
    public function deleteMultipleProducts(array $skuArray): bool
    {
        foreach ($skuArray as $sku) {
            if (!$this->productsRepository->deleteProduct($sku)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Enable products.
     *
     * @param array $skuArray
     * @return bool
     */
    public function enableProducts(array $skuArray): bool
    {
        foreach ($skuArray as $sku) {
            $product = $this->productsRepository->getProductBySku($sku);
            if (!$product->enabled) {
                $this->productsRepository->enableProduct($sku);
            }
        }

        return true;
    }

    /**
     * Disable products.
     *
     * @param array $skuArray
     * @return bool
     */
    public function disableProducts(array $skuArray): bool
    {
        foreach ($skuArray as $sku) {
            $product = $this->productsRepository->getProductBySku($sku);
            if ($product->enabled) {
                $this->productsRepository->disableProduct($sku);
            }
        }

        return true;
    }

    /**
     * Enable or disable product.
     *
     * @param $sku
     * @return bool
     */
    public function enableOrDisableProduct($sku): bool
    {
        $product = $this->productsRepository->getProductBySku($sku);
        if ($product->enabled) {
            $this->productsRepository->disableProduct($sku);
            return true;
        }

        $this->productsRepository->enableProduct($sku);
        return true;
    }

    /**
     * Get number of pages.
     *
     * @return int
     */
    public function getNumberOfPages(): int
    {
        return ceil($this->productsRepository->getNumberOfProducts() / self::RECORDS_PER_PAGE) ?: 1;
    }

    /**
     * Get all featured products.
     *
     * @return Collection
     */
    public function getFeaturedProducts(): Collection
    {
        $products = $this->productsRepository->getFeaturedProducts();

        foreach ($products as $item) {
            $item['image'] = base64_encode($item['image']);
        }

        return $products;
    }

    /**
     * Get products for category display.
     *
     * @param string $code
     * @param array $data
     * @return array
     */
    public function getDataForCategoryDisplay(string $code, array $data): array
    {
        $categoryService = ServiceRegistry::get('CategoryService');

        if (!$code) {
            return [];
        }

        $category = $categoryService->getCategoryByCode($code);

        if (!$category) {
            return [];
        }

        $products = $this->getProductsForCategory($category->id);

        $formattedData = $this->formatProducts($products, $data);

        return [
            'products' => $formattedData['products'],
            'currentPage' => $formattedData['currentPage'],
            'numberOfPages' => $formattedData['numberOfPages'],
            'sorting' => $formattedData['sorting'],
            'productsPerPage' => $formattedData['productsPerPage'],
        ];
    }

    /**
     * Get products for category and it's subcategories.
     *
     * @param int $id
     * @return array
     */
    public function getProductsForCategory(int $id): array
    {
        $formattedProducts = [];

        $categoryService = ServiceRegistry::get('CategoryService');

        $products = $this->getProductsByCategoryId($id);
        foreach ($products as $item) {
            $formattedProducts[] = [
                'id' => $item['id'],
                'categoryId' => $item['category_id'],
                'sku' => $item['sku'],
                'title' => $item['title'],
                'brand' => $item['brand'],
                'price' => $item['price'],
                'shortDescription' => $item['short_description'],
                'description' => $item['description'],
                'image' => base64_encode($item['image']),
            ];
        }

        if ($categoryService->getCategoriesForParent($id)) {
            $children = $categoryService->getCategoriesForParent($id);
            foreach ($children as $child) {
                $products = $this->getProductsForCategory($child['id']);
                foreach ($products as $product) {
                    $formattedProducts[] = $product;
                }
            }
        }

        return $formattedProducts;
    }

    /**
     * Get products by category id.
     *
     * @param int $id
     * @return Collection
     */
    public
    function getProductsByCategoryId(int $id): Collection
    {
        return $this->productsRepository->getEnabledProductsByCategoryId($id);
    }

    /**
     * Format products for category or search display.
     *
     * @param array $products
     * @param array $data
     * @return array
     */
    public function formatProducts(array $products, array $data): array
    {
        if (!$data['sorting']) {
            $data['sorting'] = 'priceDesc';
        }

        if ($data['sorting'] !== 'relevance') {
            $products = $this->sortProducts($products, $data['sorting']);
        }

        if (!$data['productsPerPage']) {
            $data['productsPerPage'] = 5;
        }

        $numberOfPages = ceil(count($products) / $data['productsPerPage']) ?: 1;

        if (!$data['currentPage']) {
            $data['currentPage'] = 1;
        }

        $currentPage = $this->setPage($data, $numberOfPages);

        $offset = ($currentPage - 1) * $data['productsPerPage'];

        $products = array_slice($products, $offset, $data['productsPerPage']);

        return [
            'products' => $products,
            'numberOfPages' => $numberOfPages,
            'currentPage' => $currentPage,
            'sorting' => $data['sorting'],
            'productsPerPage' => $data['productsPerPage'],
        ];
    }

    /**
     * Sort products by sort order.
     *
     * @param array $products
     * @param string $sortOrder
     * @return array
     */
    public function sortProducts(array $products, string $sortOrder): array
    {
        if ($sortOrder === 'priceDesc') {
            usort($products, static function ($a, $b) {
                return $a['price'] < $b['price'];
            });
        } else if ($sortOrder === 'priceAsc') {
            usort($products, static function ($a, $b) {
                return $a['price'] > $b['price'];
            });
        } else if ($sortOrder === 'titleDesc') {
            usort($products, static function ($a, $b) {
                return strtolower($a['title']) < strtolower($b['title']);
            });
        } else if ($sortOrder === 'titleAsc') {
            usort($products, static function ($a, $b) {
                return strtolower($a['title']) > strtolower($b['title']);
            });
        } else if ($sortOrder === 'brandDesc') {
            usort($products, static function ($a, $b) {
                return strtolower($a['brand']) < strtolower($b['brand']);
            });
        } else if ($sortOrder === 'brandAsc') {
            usort($products, static function ($a, $b) {
                return strtolower($a['brand']) > strtolower($b['brand']);
            });
        }

        return $products;
    }

    /**
     * Set current page.
     *
     * @param array $data
     * @param int $numberOfPages
     * @return int
     */
    public function setPage(array $data, int $numberOfPages): int
    {
        if ($data['pagination'] === 'firstPage') {
            $data['currentPage'] = 1;
        }

        if (($data['pagination'] === 'prevPage') && $data['currentPage'] !== '1') {
            $data['currentPage']--;
        }

        if ($data['pagination'] === 'lastPage') {
            $data['currentPage'] = $numberOfPages;
        }

        if (($data['pagination'] === 'nextPage') && $data['currentPage'] !== (string)$numberOfPages) {
            $data['currentPage']++;
        }

        if ($data['currentPage'] > $numberOfPages) {
            $data['currentPage'] = 1;
        }

        return $data['currentPage'];
    }

    /**
     * Search products.
     *
     * @param array $data
     * @return array
     */
    public function searchProducts(array $data): array
    {
        $formattedProducts = [];
        $products = [];

        if (!empty($data['search']) && (empty($data['keyword']) && empty($data['category'])
                && empty($data['maxPrice']) && empty($data['minPrice']))) {
            $products = $this->getProductsByKeyword($data['search']);

            foreach ($products as $product) {
                $formattedProducts[] = (new ProductDTO($product))->toArray();
            }

            if (empty($data['sorting'])) {
                $data['sorting'] = 'relevance';
            }

            $formattedData = $this->formatProducts($formattedProducts, $data);
        } else {
            if (!empty($data['keyword'])) {
                $products = $this->getProductsByKeyword($data['keyword']);
            }

            if (!empty($data['category'])) {
                if (empty($products)) {
                    $products = $this->productsRepository->getEnabledProductsByCategoryId($data['category']);
                } else {
                    foreach ($products as $productKey => $product) {
                        if ((string)$product['category_id'] !== $data['category']) {
                            unset($products[$productKey]);
                        }
                    }
                }
            }

            if (!empty($data['maxPrice'])) {
                if (empty($products)) {
                    $products = $this->productsRepository->getProductsMaxPrice($data['maxPrice']);
                } else {
                    foreach ($products as $productKey => $product) {
                        if ($product['price'] > $data['maxPrice']) {
                            unset($products[$productKey]);
                        }
                    }
                }
            }

            if (!empty($data['minPrice'])) {
                if (empty($products)) {
                    $products = $this->productsRepository->getProductsMinPrice($data['minPrice']);
                } else {
                    foreach ($products as $productKey => $product) {
                        if ($product['price'] < $data['minPrice']) {
                            unset($products[$productKey]);
                        }
                    }
                }
            }

            foreach ($products as $product) {
                $formattedProducts[] = (new ProductDTO($product))->toArray();
            }

            $formattedData = $this->formatProducts($formattedProducts, $data);
        }

        return [
            'products' => $formattedData['products'],
            'numberOfPages' => $formattedData['numberOfPages'],
            'currentPage' => $formattedData['currentPage'],
            'sorting' => $formattedData['sorting'],
            'productsPerPage' => $formattedData['productsPerPage'],
        ];
    }

    /**
     * Get products by keyword.
     *
     * @param string $keyword
     * @return Collection
     */
    public function getProductsByKeyword(string $keyword): Collection
    {
        $products = $this->productsRepository->getProductsByTitle($keyword);
        $products = $products->merge($this->productsRepository->getProductsByBrand($keyword));
        /** @var CategoryService $categoryService */
        $categoryService = ServiceRegistry::get('CategoryService');
        $categories = $categoryService->getCategoriesByTitle($keyword);

        foreach ($categories as $category) {
            $products = $products->merge($this->productsRepository->getEnabledProductsByCategoryId($category['id']));
        }

        $products = $products->merge($this->productsRepository->getProductsByShortDesc($keyword));
        $products = $products->merge($this->productsRepository->getProductsByDescription($keyword));

        return $products;
    }

}