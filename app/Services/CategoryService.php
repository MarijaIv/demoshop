<?php

namespace Demoshop\Services;


use Demoshop\DTO\CategoryDTO;
use Demoshop\Model\Category;
use Demoshop\Repositories\CategoryRepository;
use Demoshop\Repositories\ProductsRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CategoryService
 * @package Demoshop\Services
 */
class CategoryService
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * CategoryService constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get total amount of categories.
     *
     * @return int
     */
    public function getCountOfCategories(): int
    {
        return $this->categoryRepository->getCountOfCategories();
    }

    /**
     * Get category by id.
     *
     * @param int $id
     * @return Category
     */
    public function getCategoryById(int $id): Category
    {
        return $this->categoryRepository->getCategoryById($id);
    }

    /**
     * Format array for JSON encoding (for treeview).
     *
     * @param Collection $rootCategories
     * @return array
     */
    public function formatCategoriesForTreeView(Collection $rootCategories): array
    {
        $unvisitedCategories = $rootCategories;
        $tree = [];
        $visitedCategories = [];

        while ($category = $unvisitedCategories->shift()) {
            $visitedCategories[$category['id']] = (new CategoryDTO($category))->toArray();
            $unvisitedCategories = $unvisitedCategories->merge($this->getCategoriesForParent($category['id']));

            if ($category['parent_id'] === NULL) {
                $tree[] = &$visitedCategories[$category['id']];
            } else {
                $visitedCategories[$category['parent_id']]['children'][] = &$visitedCategories[$category['id']];
            }
        }

        return $tree;
    }

    /**
     * Get subcategories for parent.
     *
     * @param int $id
     * @return Collection
     */
    public function getCategoriesForParent(int $id): Collection
    {
        return $this->categoryRepository->getCategoriesForParent($id);
    }

    /**
     * Get all categories.
     *
     * @return Collection
     */
    public function getCategories(): Collection
    {
        return $this->categoryRepository->getCategories();
    }

    /**
     * Get root categories.
     *
     * @return Collection
     */
    public function getRootCategories(): Collection
    {
        return $this->categoryRepository->getRootCategories();
    }

    /**
     * Delete category by id.
     *
     * @param int $id
     * @return bool
     */
    public function deleteCategoryById(int $id): bool
    {
        if (!$this->canBeDeleted($id)) {
            return false;
        }

        $this->categoryRepository->deleteCategoryById($id);
        return true;
    }

    /**
     * Check if category has subcategories or products.
     * CategoryService having subcategories or products can't be deleted.
     *
     * @param int $id
     * @return bool
     */
    public function canBeDeleted(int $id): bool
    {
        $category = $this->categoryRepository->getCategoryById($id);
        if ($category) {
            $children = $this->categoryRepository->categoryHasSubcategories($category->id);

            $productRepository = new ProductsRepository();
            $products = $productRepository->categoryHasProducts($category->id);

            return !($children || $products);
        }

        return false;
    }

    /**
     * Add new category.
     *
     * @param array $data
     * @return bool
     */
    public function addNewCategory($data): bool
    {
        if ($this->isRequestDataValid($data)) {
            return false;
        }

        if ($this->categoryRepository->categoryExistsCode($data['code'])) {
            return false;
        }

        return $this->categoryRepository->addNewCategory($data);
    }

    /**
     * Check if request data is empty.
     *
     * @param array $data
     * @return bool
     */
    public function isRequestDataValid(array $data): bool
    {
        return empty($data['title']) || empty($data['code']) || empty($data['description']);
    }

    /**
     * Update existing category.
     *
     * @param array $data
     * @return bool
     */
    public function updateCategory($data): bool
    {
        if ($this->isRequestDataValid($data)) {
            return false;
        }

        if (empty($data['id']) && !$this->categoryRepository->categoryExists($data['id'])) {
            return false;
        }

        $this->categoryRepository->updateCategory($data);

        return true;
    }

    /**
     * Get categories where title contains keyword.
     *
     * @param string $keyword
     * @return Collection
     */
    public function getCategoriesByTitle(string $keyword): Collection
    {
        return $this->categoryRepository->getCategoriesByTitle($keyword);
    }

    /**
     * Get category by code.
     *
     * @param string $code
     * @return Model
     */
    public function getCategoryByCode(string $code):? Model
    {
        return $this->categoryRepository->getCategoryByCode($code);
    }
}