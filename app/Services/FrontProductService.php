<?php


namespace Demoshop\Services;


use Demoshop\Repositories\ProductsRepository;
use Illuminate\Support\Collection;

/**
 * Class FrontProductService
 * @package Demoshop\Services
 */
class FrontProductService
{
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
     * @param array $codes
     * @return Collection
     */
    public function getDataForCategoryDisplay(array $codes): Collection
    {
        return $this->productsRepository->getProductsByCategoryCode($codes);
    }

    /**
     * Search products.
     *
     * @param array $data
     * @return Collection
     */
    public function searchProducts(array &$data): Collection
    {
        if (!empty($data['search'])) {
            $products = $this->productsRepository->search($data['search']);
            $data['search'] = '';
        } else {
            if (!$data['maxPrice']) {
                $data['maxPrice'] = null;
            }

            if (!$data['minPrice']) {
                $data['minPrice'] = null;
            }

            if (!$data['category']) {
                $data['category'] = null;
            }

            $products = $this->productsRepository->search(
                $data['keyword'],
                $data['maxPrice'],
                $data['minPrice'],
                $data['category']
            );
        }

        if (empty($data['sorting'])) {
            $data['sorting'] = 'relevance';
        }

        return $products;
    }
}