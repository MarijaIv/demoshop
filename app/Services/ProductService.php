<?php

namespace Demoshop\Services;

use Demoshop\AuthorizationMiddleware\Exceptions\ProductDataInvalidException;
use Demoshop\Entity\Product as ProductEntity;
use Demoshop\Model\Product;
use Demoshop\Repositories\ProductsRepository;
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
    public function getProducts(int $currentPage, int $pageSize): Collection
    {
        $offset = ($currentPage - 1) * $pageSize;
        return $this->productsRepository->getProductsForCurrentPage($offset, $pageSize);
    }

    /**
     * Create new product.
     *
     * @param ProductEntity $data
     * @param array $file
     * @return bool
     * @throws ProductDataInvalidException
     */
    public function createNewProduct(ProductEntity $data, array $file): bool
    {
        $this->checkImageHeightWidthRatio($file);

        $content = fopen($file['tmp_name'], 'rb');
        $fileContent = fread($content, filesize($file['tmp_name']));

        $product = $this->productsRepository->createNewProduct($data, base64_encode($fileContent));
        fclose($content);
        return $product;
    }

    /**
     * Check image characteristics.
     *
     * @param array $file
     * @throws ProductDataInvalidException
     */
    private function checkImageHeightWidthRatio(array $file): void
    {
        $imageSize = getimagesize($file['tmp_name']);

        if ($imageSize[0] < 600) {
            throw new ProductDataInvalidException('Image size is not valid.');
        }

        $heightWidthRatio = $imageSize[0] / $imageSize[1];

        if ($heightWidthRatio < self::MIN_HEIGHT_WIDTH_RATIO || $heightWidthRatio > self::MAX_HEIGHT_WIDTH_RATIO) {
            throw new ProductDataInvalidException('Image height-width ratio is not valid.');
        }
    }

    /**
     * Check if request data is valid.
     *
     * @param ProductEntity $data
     * @throws ProductDataInvalidException
     */
    public function validateProduct(ProductEntity $data): void
    {
        if ((empty($data->getSku()) || empty($data->getTitle()) || empty($data->getBrand()) || empty($data->getPrice())
            || empty($data->getShortDescription()) || empty($data->getDescription()))) {
            throw new ProductDataInvalidException('Product data is not valid.');
        }

        if ($this->productsRepository->productSkuExists($data->getSku())) {
            throw new ProductDataInvalidException('Product with given sku already exists.');
        }
    }

    /**
     * Update product.
     *
     * @param ProductEntity $data
     * @param string $oldSku
     * @param array $file
     * @return void
     * @throws ProductDataInvalidException
     */
    public function updateProduct(ProductEntity $data, string $oldSku, array $file): void
    {
        if ($oldSku !== $data->getSku() && $this->productsRepository->productSkuExists($data->getSku())) {
            throw new ProductDataInvalidException('Product sku already exists.');
        }

        if ($file['tmp_name'] !== '') {
            $this->checkImageHeightWidthRatio($file);
        }

        if ($file['tmp_name'] === '' && $product = $this->getProductBySku($oldSku)) {
            $fileContent = $product['image'];
            $this->productsRepository->updateProduct($data, $oldSku, $fileContent);
        } else {
            $handle = fopen($file['tmp_name'], 'rb');
            $fileContent = fread($handle, filesize($file['tmp_name']));
            $this->productsRepository->updateProduct($data, $oldSku, base64_encode($fileContent));
            fclose($handle);
        }
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
     * Check if product data is valid (for update).
     *
     * @param ProductEntity $data
     * @param string $oldSku
     * @throws ProductDataInvalidException
     */
    public function validateProductForUpdate(ProductEntity $data, string $oldSku): void
    {
        if (!$this->productsRepository->productSkuExists($oldSku)
            && (empty($data->getSku()) || empty($data->getTitle()) ||
                empty($data->getBrand()) || empty($data->getPrice()) ||
                empty($data->getShortDescription()) || empty($data->getShortDescription()))) {
            throw new ProductDataInvalidException('Product data is not valid');
        }
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
     * @return void
     */
    public function enableProducts(array $skuArray): void
    {
        $this->productsRepository->enableMultiple($skuArray);
    }

    /**
     * Disable products.
     *
     * @param array $skuArray
     * @return void
     */
    public function disableProducts(array $skuArray): void
    {
        $this->productsRepository->disableMultipleProducts($skuArray);
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
}