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

    public function isProductValidUpdate(array $data, string $oldSku): bool
    {
        return (isset($data['sku'], $data['title'], $data['brand'],
            $data['price'], $data['shortDesc'], $data['description']) &&
            $this->productsRepository->productSkuExists($oldSku));
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
        if (!$this->isProductValidUpdate($data, $oldSku)) {
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
            $content = fopen($file['tmp_name'], 'rb');
            $content = fread($content, filesize($file['tmp_name']));
        }

        $this->productsRepository->updateProduct($data, $oldSku, base64_encode($content));
        return true;
    }

    /**
     * Get product by sku.
     *
     * @param string $sku
     * @return Product|null
     */
    public function getProductBySku(string $sku): ?Product
    {
        return $this->productsRepository->getProductBySku($sku);
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
        return $this->productsRepository->deleteMultipleProducts($skuArray);
    }

    /**
     * Enable products.
     *
     * @param array $skuArray
     * @return bool
     */
    public function enableProducts(array $skuArray): bool
    {
        $this->productsRepository->enableMultiple($skuArray);

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
        $this->productsRepository->disableMultipleProducts($skuArray);

        return true;
    }

    /**
     * Enable or disable product.
     *
     * @param array $data
     * @return bool
     */
    public function enableOrDisableProduct(array $data): bool
    {
        if($data['enabled'] !== 'false') {
            return $this->productsRepository->disableProduct($data['sku']);
        }

        return $this->productsRepository->enableProduct($data['sku']);
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
     * Get products for category and it's subcategories.
     *
     * @param string $code
     * @return Collection
     */
    public function getDataForCategoryDisplay(string $code): Collection
    {
        /** @var CategoryService $categoryService */
        $categoryService = ServiceRegistry::get('CategoryService');

        $products = $this->productsRepository->getProductsByCategoryCode($code);
        $children = $categoryService->getSubcategoriesByCode($code);
        foreach ($children as $child) {
            $products = $products->merge($this->getDataForCategoryDisplay($child['code']));
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
        $products = new Collection();

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
                $products = $this->getProductsByCategory($products, $data['category']);
            }

            if (!empty($data['maxPrice'])) {
                $products = $this->getProductsByMaxPrice($products, $data['maxPrice']);
            }

            if (!empty($data['minPrice'])) {
                $products = $this->getProductsByMinPrice($products, $data['minPrice']);
            }

            if (!$products->first()) {
                $products = $this->productsRepository->getEnabledProducts();
                if (empty($data['sorting'])) {
                    $data['sorting'] = 'relevance';
                }
            }
        }

        return $products;
    }

    /**
     * Get products for searched category.
     *
     * @param Collection $products
     * @param int $id
     * @return Collection
     */
    public function getProductsByCategory(Collection $products, int $id): Collection
    {
        if (!$products->first()) {
            $products = $this->productsRepository->getEnabledProductsByCategoryId($id);
        } else {
            foreach ($products as $productKey => $product) {
                if ((string)$product['category_id'] !== $id) {
                    unset($products[$productKey]);
                }
            }
        }

        return $products;
    }

    /**
     * Get products where price is lower than maxPrice.
     *
     * @param Collection $products
     * @param float $maxPrice
     * @return Collection
     */
    public function getProductsByMaxPrice(Collection $products, float $maxPrice): Collection
    {
        if (!$products->first()) {
            $products = $this->productsRepository->getProductsMaxPrice($maxPrice);
        } else {
            foreach ($products as $productKey => $product) {
                if ($product['price'] > $maxPrice) {
                    unset($products[$productKey]);
                }
            }
        }

        return $products;
    }

    /**
     * Get products where price is greater than minPrice.
     *
     * @param Collection $products
     * @param float $minPrice
     * @return Collection
     */
    public function getProductsByMinPrice(Collection $products, float $minPrice): Collection
    {
        if (!$products->first()) {
            $products = $this->productsRepository->getProductsMinPrice($minPrice);
        } else {
            foreach ($products as $productKey => $product) {
                if ($product['price'] < $minPrice) {
                    unset($products[$productKey]);
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
        $products = $this->productsRepository->getProductsByKeyword($keyword);

        /** @var CategoryService $categoryService */
        $categoryService = ServiceRegistry::get('CategoryService');
        $categories = $categoryService->getCategoriesByTitle($keyword);

        foreach ($categories as $category) {
            $products =
                $products->merge($this->productsRepository->getEnabledProductsByCategoryId($category['id']));
        }

        foreach($products as $product) {
            if(strpos($product->title, $keyword) !== false) {
                $product->weight = 1;
            } else if(strpos($product->brand, $keyword) !== false) {
                $product->weight = 2;
            } else if(strpos($product->short_description, $keyword) !== false) {
                $product->weight = 4;
            } else if(strpos($product->description, $keyword) !== false) {
                $product->weight = 5;
            } else {
                $product->weight = 3;
            }
        }

        $products->sortBy('weight');

        return $products;
    }
}