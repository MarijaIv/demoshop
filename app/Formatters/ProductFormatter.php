<?php


namespace Demoshop\Formatters;


use Demoshop\DTO\ProductDTO;
use Demoshop\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class ProductFormatter
 * @package Demoshop\Formatters
 */
class ProductFormatter
{
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
     * Format products for admin products table.
     *
     * @param Collection $products
     * @return array
     */
    public function formatProductsForTable(Collection $products): array
    {
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
     * Format products collection into array.
     *
     * @param Collection $products
     * @return array
     */
    public function formatProducts(Collection $products): array
    {
        $formattedProducts = [];

        foreach($products as $product) {
            $formattedProducts[] = (new ProductDTO($product))->toArray();
        }

        return $formattedProducts;
    }

    /**
     * Format products for category or search display.
     *
     * @param Collection $productCollection
     * @param array $data
     * @return array
     */
    public function formatProductsForVisitor(Collection $productCollection, array $data): array
    {
        $products = $this->formatProducts($productCollection);

        if (!$data['sorting']) {
            $data['sorting'] = 'priceDesc';
        }

        if ($data['sorting'] !== 'relevance') {
            $products = $this->sortProducts($products, $data['sorting']);
        }

        if (!$data['productsPerPage']) {
            $data['productsPerPage'] = 10;
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

}