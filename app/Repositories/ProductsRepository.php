<?php


namespace Demoshop\Repositories;


use Demoshop\Model\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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
     * @return string
     */
    public function getMostViewedProduct(): string
    {
        $result = Product::query()->orderBy('view_count', 'desc')->first();
        return $result ? $result->title : '';
    }

    /**
     * Get id of most viewed product.
     *
     * @return int
     */
    public function getMostViewedProductId(): int
    {
        $result = Product::query()->orderBy('view_count', 'desc')->first();
        return $result ? $result->id : '';
    }

    /**
     * Get number of most viewed product views.
     *
     * @return int
     */
    public function getNumberOfMostViews(): int
    {
        return Product::query()->max('view_count');
    }


    /**
     * Get products by category id.
     *
     * @param int $id
     * @return Collection
     */
    public function getProductsByCategoryId(int $id): Collection
    {
        return Product::query()->where('category_id', '=', $id)->get();
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
     * @param $file
     * @return bool
     */
    public function createNewProduct($data, $file): bool
    {
        Product::query()->insert(
            [
                'category_id' => $data['category'],
                'sku' => $data['sku'],
                'title' => $data['title'],
                'brand' => $data['brand'],
                'price' => $data['price'],
                'short_description' => $data['shortDesc'],
                'description' => $data['description'],
                'image' => 'img',
                'enabled' => $data['enabled'],
                'featured' => $data['featured'],
            ]
        );

        if (!$product = Product::query()->where('sku', '=', $data['sku'])->first()) {
            return false;
        }

        $product->image = $file;
        $product->save();

        return true;
    }

    /**
     * Check if product with given sku exists.
     *
     * @param string $sku
     * @return bool
     */
    public function productSkuExists(string $sku): bool
    {
        if (!Product::query()->where('sku', '=', $sku)->first()) {
            return false;
        }
        return true;
    }

    /**
     * Get product by sku.
     *
     * @param string $sku
     * @return Builder|Model
     */
    public function getProductBySku(string $sku): Model
    {
        return Product::query()->where('sku', '=', $sku)->first();
    }

    /**
     * Update product.
     *
     * @param array $data
     * @param string $image
     * @return int
     */
    public function updateProduct(array $data, string $image): int
    {
        return Product::query()
            ->where('sku', '=', $data['oldSku'])
            ->update(
                [
                    'category_id' => $data['category'],
                    'sku' => $data['sku'],
                    'title' => $data['title'],
                    'brand' => $data['brand'],
                    'price' => $data['price'],
                    'short_description' => $data['shortDesc'],
                    'description' => $data['description'],
                    'enabled' => $data['enabled'],
                    'featured' => $data['featured'],
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
     * Enable product.
     *
     * @param string $sku
     * @return int
     */
    public function enableProduct(string $sku): int
    {
        return Product::query()
            ->where('sku', '=', $sku)
            ->update(
                [
                    'enabled' => 1,
                ]
            );
    }

    /**
     * Disable product.
     *
     * @param string $sku
     * @return int
     */
    public function disableProduct(string $sku): int
    {
        return Product::query()
            ->where('sku', '=', $sku)
            ->update(
                [
                    'enabled' => 0,
                ]
            );
    }

    /**
     * Get all featured products.
     *
     * @return Collection
     */
    public function getFeaturedProducts(): Collection
    {
        return Product::query()->where('featured', '=', 1)->get();
    }

    /**
     * Get products where title is like keyword.
     *
     * @param string $keyword
     * @return Collection
     */
    public function getProductsByTitle(string $keyword): Collection
    {
        return Product::query()->where('title', 'like', '%' . $keyword . '%')->get();
    }

    /**
     * Get products where brand contains keyword.
     *
     * @param string $keyword
     * @return Collection
     */
    public function getProductsByBrand(string $keyword): Collection
    {
        return Product::query()->where('brand', 'like', '%' . $keyword . '%')->get();
    }

    /**
     * Get products where short description contains keyword.
     *
     * @param string $keyword
     * @return Collection
     */
    public function getProductsByShortDesc(string $keyword): Collection
    {
        return Product::query()->where('short_description', 'like', '%' . $keyword . '%')->get();
    }

    /**
     * Get products where description contains keyword.
     *
     * @param string $keyword
     * @return Collection
     */
    public function getProductsByDescription(string $keyword): Collection
    {
        return Product::query()->where('description', 'like', '%' . $keyword . '%')->get();
    }

    /**
     * Get products where price is less then maxPrice.
     *
     * @param float $maxPrice
     * @return Collection
     */
    public function getProductsMaxPrice(float $maxPrice): Collection
    {
        return Product::query()->where('price', '<', $maxPrice)->get();
    }

    /**
     * Get products where price is greater then minPrice.
     *
     * @param float $minPrice
     * @return Collection
     */
    public function getProductsMinPrice(float $minPrice): Collection
    {
        return Product::query()->where('price', '>', $minPrice)->get();
    }
}