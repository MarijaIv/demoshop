<?php

namespace Demoshop\Services;

use Demoshop\Repositories\CategoryRepository;
use Demoshop\Repositories\ProductsRepository;
use Illuminate\Support\Collection;

/**
 * Class ProductService
 */
class ProductService
{
    public const MAX_HEIGHT_WIDTH_RATIO = 1.78;
    public const MIN_HEIGHT_WIDTH_RATIO = 1.33;

    /**
     * Get total amount of products.
     *
     * @return int
     */
    public static function getAmountOfProducts(): int
    {
        $products = new ProductsRepository();
        return $products->getAmountOfProducts();
    }

    /**
     * Get product which details page is displayed most often.
     *
     * @return string
     */
    public static function getMostViewedProduct(): string
    {
        $products = new ProductsRepository();
        return $products->getMostViewedProduct();
    }

    /**
     * Get number of most viewed product views.
     *
     * @return int
     */
    public static function getNumberOfMostViews(): int
    {
        $products = new ProductsRepository();
        return $products->getNumberOfMostViews();
    }

    /**
     * Get id of most viewed product.
     *
     * @return int
     */
    public static function getMostViewedProductId(): int
    {
        $products = new ProductsRepository();
        return $products->getMostViewedProductId();
    }


    /**
     * Get products by category id.
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getProductsByCategoryId(int $id): Collection
    {
        $products = new ProductsRepository();
        return $products->getProductsByCategoryId($id);
    }

    /**
     * Get all products.
     *
     * @return array
     */
    public static function getAllProductsFormatted(): array
    {
        $products = new ProductsRepository();
        $products = $products->getAllProducts();
        $category = new CategoryRepository();

        $formattedProducts = [];

        for ($i = 0; $i < $products->count(); $i++) {
            $formattedProducts[$i]['id'] = $products[$i]->id;

            $parentCategory = $category->getCategoryById($products[$i]->category_id);

            if ($parentCategory->parent_id === null) {
                $formattedProducts[$i]['category'] = $parentCategory->title;
            } else {
                $formattedProducts[$i]['category'] = ($category->getCategoryById($parentCategory->parent_id))->title
                    . ' > ' . $parentCategory->title;
            }

            $formattedProducts[$i]['sku'] = $products[$i]->sku;
            $formattedProducts[$i]['title'] = $products[$i]->title;
            $formattedProducts[$i]['brand'] = $products[$i]->brand;
            $formattedProducts[$i]['price'] = $products[$i]->price;
            $formattedProducts[$i]['shortDesc'] = $products[$i]->short_description;
            $formattedProducts[$i]['description'] = $products[$i]->description;
            $formattedProducts[$i]['enabled'] = $products[$i]->enabled;
            $formattedProducts[$i]['featured'] = $products[$i]->featured;
            $formattedProducts[$i]['viewCount'] = $products[$i]->view_count;
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
    public static function createNewProduct(array $data, array $file): bool
    {
        if ($data['sku'] === '' || $data['title'] === '' || $data['brand'] === '' || $data['price'] === ''
            || $data['shortDesc'] === '' || $data['description'] === '') {
            return false;
        }

        $products = new ProductsRepository();
        if (!$products->getProductBySku($data['sku'])) {
            return false;
        }

        $data['price'] = (float)$data['price'];

        if (!$data['enabled']) {
            $data['enabled'] = 0;
        }

        if (!$data['featured']) {
            $data['featured'] = 0;
        }

        $imageSize = getimagesize($file['tmp_name']);

        if ($imageSize[0] < 600) {
            return false;
        }

        $heightWidthRatio = $imageSize[0] / $imageSize[1];

        if ($heightWidthRatio < self::MIN_HEIGHT_WIDTH_RATIO || $heightWidthRatio > self::MAX_HEIGHT_WIDTH_RATIO) {
            return false;
        }

        self::saveFile($file);

        $fileName = __DIR__ . '/../../public/img/' . $file['name'];
        $content = fopen($fileName, 'rb');
        $content = fread($content, filesize($fileName));

        if (!$products->createNewProduct($data, $content)) {
            unlink($fileName);
            return false;
        }

        unlink($fileName);
        return true;
    }

    /**
     * Save image to /img directory.
     *
     * @param array $file
     */
    public static function saveFile(array $file): void
    {
        $targetFile = __DIR__ . '/../../public/img/' . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $targetFile);
    }
}