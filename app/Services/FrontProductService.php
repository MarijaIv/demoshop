<?php


namespace Demoshop\Services;


use Demoshop\Entity\SearchParameters;
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
     * @param SearchParameters $data
     * @return Collection
     */
    public function searchProducts(SearchParameters $data): Collection
    {
        if (!empty($data->getSearch())) {
            $products = $this->productsRepository->search($data->getSearch());
        } else {
            $products = $this->productsRepository->search(
                $data->getKeyword(),
                $data->getMaxPrice(),
                $data->getMinPrice(),
                $data->getCategoryId()
            );
        }

        return $products;
    }
}