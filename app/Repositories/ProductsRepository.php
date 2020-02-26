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
     * Get total amount of products.
     *
     * @return int
     */
    public function getAmountOfProducts(): int
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
     * Get all products.
     *
     * @return Builder[]|Collection
     */
    public function getAllProducts(): Collection
    {
        return Product::query()->get();
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
                'image' => $data['img'],
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
     * Get product by sku.
     *
     * @param string $sku
     * @return bool
     */
    public function getProductBySku(string $sku): bool
    {
        if (Product::query()->where('sku', '=', $sku)->first()) {
            return false;
        }
        return true;
    }
}