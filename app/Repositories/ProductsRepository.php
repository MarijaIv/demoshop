<?php


namespace Demoshop\Repositories;


use Demoshop\Model\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ProductsRepository
 * @package Demoshop\Repositories
 */
class ProductsRepository
{
    /**
     * Get number of products.
     *
     * @return int
     */
    public function getNumberOfProducts(): int
    {
        return Product::query()->count();
    }

    /**
     * Get product which details page is displayed most often.
     *
     * @return Product|null
     */
    public function getMostViewedProduct(): ?Product
    {
        return Product::query()->orderBy('view_count', 'desc')->first();
    }

    /**
     * Increase product view count.
     *
     * @param string $sku
     * @return bool
     */
    public function increaseViewCount(string $sku): bool
    {
        return Product::query()->where('sku', '=', $sku)->increment('view_count');
    }

    /**
     * Get enabled products by category id.
     *
     * @param int $id
     * @return Collection
     */
    public function getEnabledProductsByCategoryId(int $id): Collection
    {
        return Product::query()
            ->where('category_id', '=', $id)
            ->where('enabled', '=', 1)
            ->get();
    }

    /**
     * Check if category has products.
     *
     * @param int $id
     * @return bool
     */
    public function categoryHasProducts(int $id): bool
    {
        return Product::query()->where('category_id', '=', $id)->exists();
    }

    /**
     * Get products for current page.
     *
     * @param int $offset
     * @param int $recordsPerPage
     * @return Collection
     */
    public function getProductsForCurrentPage(int $offset, int $recordsPerPage): Collection
    {
        return Product::query()->offset($offset)->limit($recordsPerPage)->get();
    }

    /**
     * Create new product.
     *
     * @param array $data
     * @param string $fileContent
     * @return bool
     */
    public function createNewProduct(\Demoshop\Entity\Product $data): bool
    {
        return Product::query()->insert(
            [
                'category_id' => $data->getCategoryId(),
                'sku' => $data->getSku(),
                'title' => $data->getTitle(),
                'brand' => $data->getBrand(),
                'price' => $data->getPrice(),
                'short_description' => $data->getShortDescription(),
                'description' => $data->getDescription(),
                'image' => $data->getImage(),
                'enabled' => $data->isEnabled(),
                'featured' => $data->isFeatured(),
            ]
        );
    }

    /**
     * Check if product with given sku exists.
     *
     * @param string $sku
     * @return bool
     */
    public function productSkuExists(string $sku): bool
    {
        return Product::query()->where('sku', '=', $sku)->exists();
    }

    /**
     * Get product by sku.
     *
     * @param string $sku
     * @return Product|null
     */
    public function getProductBySku(string $sku): ?Product
    {
        return Product::query()->where('sku', '=', $sku)->first();
    }

    /**
     * Get products by category code.
     *
     * @param array $codes
     * @return Collection
     */
    public function getProductsByCategoryCode(array $codes): Collection
    {
        return Product::query()
            ->join('category', 'product.category_id', '=', 'category.id')
            ->whereIn('category.code', $codes)
            ->get(['product.id', 'product.category_id', 'product.sku', 'product.title',
                'product.brand', 'product.price', 'product.short_description', 'product.description',
                'product.image', 'product.enabled', 'product.featured', 'product.view_count']);
    }

    /**
     * Update product.
     *
     * @param array $data
     * @param string $oldSku
     * @param string $image
     * @return int
     */
    public function updateProduct(\Demoshop\Entity\Product $data, string $oldSku, string $image): int
    {
        return Product::query()
            ->where('sku', '=', $oldSku)
            ->update(
                [
                    'category_id' => $data->getCategoryId(),
                    'sku' => $data->getSku(),
                    'title' => $data->getTitle(),
                    'brand' => $data->getBrand(),
                    'price' => $data->getPrice(),
                    'short_description' => $data->getShortDescription(),
                    'description' => $data->getDescription(),
                    'enabled' => $data->isEnabled(),
                    'featured' => $data->isFeatured(),
                    'image' => $image,
                ]
            );
    }

    /**
     * Delete product by sku.
     *
     * @param string $sku
     * @return int
     */
    public function deleteProduct(string $sku): int
    {
        return Product::query()->where('sku', '=', $sku)->delete();
    }

    /**
     * Delete multiple products by sku.
     *
     * @param array $skuArray
     * @return int
     */
    public function deleteMultipleProducts(array $skuArray): int
    {
        return Product::query()->whereIn('sku', $skuArray)->delete();
    }

    /**
     * Enable multiple products.
     *
     * @param array $skuArray
     * @return int
     */
    public function enableMultiple(array $skuArray): int
    {
        return Product::query()
            ->whereIn('sku', $skuArray)
            ->update(
                [
                    'enabled' => 1,
                ]
            );
    }

    /**
     * Disable multiple products.
     *
     * @param array $skuArray
     * @return int
     */
    public function disableMultipleProducts(array $skuArray): int
    {
        return Product::query()
            ->whereIn('sku', $skuArray)
            ->update(
                [
                    'enabled' => 0,
                ]
            );
    }

    /**
     * Get all featured products.
     *
     * @return Collection | null
     */
    public function getFeaturedProducts(): ?Collection
    {
        return Product::query()->where('featured', '=', 1)->get();
    }

    /**
     * Search products.
     *
     * @param string $keyword
     * @param float|null $maxPrice
     * @param float|null $minPrice
     * @param int $categoryId
     * @return Builder[]|Collection
     */
    public function search(
        string $keyword = null,
        float $maxPrice = null,
        float $minPrice = null,
        int $categoryId = null
    )
    {
        $query = Product::query()
            ->join('category', 'product.category_id', '=', 'category.id')
            ->where('product.enabled', '=', 1);

        if ($keyword) {
            $query = $query->where(static function (Builder $q) use ($keyword) {
                $q->orWhere('product.title', 'like', '%' . $keyword . '%')
                    ->orWhere('product.brand', 'like', '%' . $keyword . '%')
                    ->orWhere('category.title', 'like', '%' . $keyword . '%')
                    ->orWhere('product.short_description', 'like', '%' . $keyword . '%')
                    ->orWhere('product.description', 'like', '%' . $keyword . '%');
            });
        }

        if ($maxPrice) {
            $query = $query->where('product.price', '<', $maxPrice);
        }

        if ($minPrice) {
            $query = $query->where('product.price', '>', $minPrice);
        }

        if ($categoryId) {
            $query = $query->where('product.category_id', '=', $categoryId);
        }

        return $query->get(['product.id', 'product.category_id', 'product.sku', 'product.title',
            'product.brand', 'product.price', 'product.short_description', 'product.description',
            'product.image', 'product.enabled', 'product.featured', 'product.view_count']);
    }

    /**
     * Get all enabled products.
     *
     * @return Collection
     */
    public function getEnabledProducts(): Collection
    {
        return Product::query()->where('enabled', '=', 1)->get();
    }
}