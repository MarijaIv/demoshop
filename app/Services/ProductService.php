<?php

namespace Demoshop\Services;

use Demoshop\Model\Product;
use Demoshop\Repositories\ProductsRepository;
use Demoshop\ServiceRegistry\ServiceRegistry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class ProductService
 * @package Demoshop\Services
 */
class ProductService
{
    public const MAX_HEIGHT_WIDTH_RATIO = 1.78;
    public const MIN_HEIGHT_WIDTH_RATIO = 1.33;

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
     * @return Product|null
     */
    public function getMostViewedProduct(): ?Product
    {
        return $this->productsRepository->getMostViewedProduct();
    }

    /**
     * Increase product view count.
     *
     * @param string $sku
     * @return bool
     */
    public function increaseProductViewCount(string $sku): bool
    {
        return $this->productsRepository->increaseViewCount($sku);
    }

    /**
     * Get all products for current page.
     *
     * @param int $currentPage
     * @param int $pageSize
     * @return Collection
     */
    public function getProductsForCurrentPage(int $currentPage, int $pageSize): Collection
    {
        $offset = ($currentPage - 1) * $pageSize;
        return $this->productsRepository->getProductsForCurrentPage($offset, $pageSize);
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
        if (!$this->isProductValid($data)) {
            return false;
        }

        $data['price'] = (float)$data['price'];
        $this->setEnabledAndFeaturedValue($data);

        if (!$this->checkImageHeightWidthRatio($file)) {
            return false;
        }

        $content = fopen($file['tmp_name'], 'rb');
        $content = fread($content, filesize($file['tmp_name']));

        return $this->productsRepository->createNewProduct($data, base64_encode($content));
    }

    /**
     * Check if request data is valid.
     *
     * @param array $data
     * @return bool
     */
    public function isProductValid(array $data): bool
    {
        return !((empty($data['sku']) || empty($data['title']) || empty($data['brand']) || empty($data['price'])
                || empty($data['shortDesc']) || empty($data['description']))
            || $this->productsRepository->productSkuExists($data['sku']));
    }

    /**
     * Set product enable and featured value.
     *
     * @param array $data
     */
    private function setEnabledAndFeaturedValue(array &$data): void
    {
        $data['enabled'] = $data['enabled'] ? 1 : 0;
        $data['featured'] = $data['featured'] ? 1 : 0;
    }

    /**
     * Check image characteristics.
     *
     * @param array $file
     * @return bool
     */
    private function checkImageHeightWidthRatio(array $file): bool
    {
        $imageSize = getimagesize($file['tmp_name']);

        if ($imageSize[0] < 600) {
            return false;
        }

        $heightWidthRatio = $imageSize[0] / $imageSize[1];

        return !($heightWidthRatio < self::MIN_HEIGHT_WIDTH_RATIO || $heightWidthRatio > self::MAX_HEIGHT_WIDTH_RATIO);
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

        if ($file['tmp_name'] !== '' && !$this->checkImageHeightWidthRatio($file)) {
            return false;
        }

        $this->setEnabledAndFeaturedValue($data);
        $data['price'] = (float)$data['price'];
        $data['category'] = (int)$data['category'];

        if ($file['tmp_name'] === '' && $product = $this->getProductBySku($oldSku)) {
            $content = $product['image'];
        } else {
            $this->saveFile($file);
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
    public function getProductBySku(string $sku): ?Model
    {
        return $this->productsRepository->getProductBySku($sku);
    }

    /**
     * Save image to /img directory.
     *
     * @param array $file
     */
    private function saveFile(array $file): void
    {
        $targetFile = __DIR__ . '/../../public/img/' . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $targetFile);
    }

    /**
     * Delete product by sku.
     *
     * @param string $sku
     * @return bool
     */
    public function deleteProduct(string $sku): bool
    {
        return $this->productsRepository->deleteProduct($sku);
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
            $this->productsRepository->deleteProduct($sku);
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
            $this->productsRepository->enableProduct($sku);
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
            $this->productsRepository->disableProduct($sku);
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

        if (!$product) {
            return false;
        }

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
     * @param int $pageSize
     * @return int
     */
    public function getNumberOfPages(int $pageSize): int
    {
        return ceil($this->productsRepository->getNumberOfProducts() / $pageSize) ?: 1;
    }

    /**
     * Get all featured products.
     *
     * @return Collection | null
     */
    public function getFeaturedProducts(): ?Collection
    {
        return $this->productsRepository->getFeaturedProducts();
    }

    /**
     * Get products for category display.
     *
     * @param string $code
     * @return Collection
     */
    public function getDataForCategoryDisplay(string $code): Collection
    {
        $categoryService = ServiceRegistry::get('CategoryService');

        if (!$code) {
            return null;
        }

        $category = $categoryService->getCategoryByCode($code);

        if (!$category) {
            return null;
        }

        return $this->getProductsForCategory($category->id);
    }

    /**
     * Get products for category and it's subcategories.
     *
     * @param int $id
     * @return Collection
     */
    public function getProductsForCategory(int $id): Collection
    {
        /** @var CategoryService $categoryService */
        $categoryService = ServiceRegistry::get('CategoryService');

        $products = $this->getProductsByCategoryId($id);
        $children = $categoryService->getCategoriesForParent($id);
        foreach ($children as $child) {
            $products = $products->merge($this->getProductsForCategory($child['id']));
        }

        return $products;
    }

    /**
     * Get products by category id.
     *
     * @param int $id
     * @return Collection
     */
    public function getProductsByCategoryId(int $id): Collection
    {
        return $this->productsRepository->getEnabledProductsByCategoryId($id);
    }

    /**
     * Search products.
     *
     * @param array $data
     * @return Collection
     */
    public function searchProducts(array &$data): Collection
    {
        $products = null;

        if (!empty($data['search']) && (empty($data['keyword']) && empty($data['category'])
                && empty($data['maxPrice']) && empty($data['minPrice']))) {
            $products = $this->getProductsByKeyword($data['search']);
            if (empty($data['sorting'])) {
                $data['sorting'] = 'relevance';
            }
        } else {
            $data['search'] = '';

            if (!empty($data['keyword'])) {
                $products = $this->getProductsByKeyword($data['keyword']);
            }

            if (!empty($data['category'])) {
                if ($products === null) {
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
                if ($products === null) {
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
                if ($products === null) {
                    $products = $this->productsRepository->getProductsMinPrice($data['minPrice']);
                } else {
                    foreach ($products as $productKey => $product) {
                        if ($product['price'] < $data['minPrice']) {
                            unset($products[$productKey]);
                        }
                    }
                }
            }

            if ($products === null) {
                $products = $this->productsRepository->getEnabledProducts();
                if (empty($data['sorting'])) {
                    $data['sorting'] = 'relevance';
                }
            }
        }

        return $products;
    }

    /**
     * Get products by keyword.
     *
     * @param string $keyword
     * @return Collection
     */
    public function getProductsByKeyword(string $keyword): Collection
    {
        $products = $this->productsRepository->getEnabledProducts();
        $searchedProducts = new \Illuminate\Database\Eloquent\Collection();

        foreach ($products as $product) {
            $title = strtolower($product->title);
            if (stripos($title, $keyword) !== false) {
                $searchedProducts = $searchedProducts->merge([$product]);
            }
        }

        foreach ($products as $product) {
            $brand = strtolower($product->brand);
            if (strpos($brand, $keyword) !== false && !$searchedProducts->contains($product)) {
                $searchedProducts = $searchedProducts->concat([$product]);
            }
        }

        /** @var CategoryService $categoryService */
        $categoryService = ServiceRegistry::get('CategoryService');
        $categories = $categoryService->getCategoriesByTitle($keyword);

        foreach ($categories as $category) {
            $searchedProducts = $searchedProducts->merge($this->productsRepository->getEnabledProductsByCategoryId($category['id']));
        }

        foreach ($products as $product) {
            $shortDesc = strtolower($product->short_description);
            if (strpos($shortDesc, $keyword) !== false && !$searchedProducts->contains($product)) {
                $searchedProducts = $searchedProducts->concat([$product]);
            }
        }

        foreach ($products as $product) {
            $desc = strtolower($product->description);
            if (strpos($desc, $keyword) !== false && !$searchedProducts->contains($product)) {
                $searchedProducts = $searchedProducts->concat([$product]);
            }
        }

        return $searchedProducts;
    }
}