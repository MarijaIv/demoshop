<?php

namespace Demoshop\Services;


use Demoshop\AuthorizationMiddleware\Exceptions\CategoryDataEmptyException;
use Demoshop\Model\Category;
use Demoshop\Repositories\CategoryRepository;
use Demoshop\Repositories\ProductsRepository;
use Illuminate\Database\Eloquent\Collection;

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
     * @return Category|null
     */
    public function getCategoryById(int $id): ?Category
    {
        return $this->categoryRepository->getCategoryById($id);
    }

    /**
     * Get categories for category edit.
     *
     * @param int $id
     * @return Collection
     */
    public function getCategoriesForEdit(int $id): Collection
    {
        $categories = $this->getCategories();
        $childrenIds[] = $id;

        foreach ($categories as $key => $category) {
            if (in_array($category->id, $childrenIds, true)) {
                $categories->forget($key);
            }

            if (in_array($category->parent_id, $childrenIds, true)) {
                $childrenIds[] = $category->id;
                $categories->forget($key);
            }
        }

        return $categories;
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
    public function addNewCategory(array $data): bool
    {
        if ($this->categoryRepository->categoryExistsCode($data['code'])) {
            return false;
        }

        return $this->categoryRepository->addNewCategory($data);
    }

    /**
     * Check if request data is empty.
     *
     * @param array $data
     * @throws CategoryDataEmptyException
     */
    public function isRequestDataValid(array $data): void
    {
        if (empty($data['title']) || empty($data['code']) || empty($data['description']) || empty($data['id'])) {
            throw new CategoryDataEmptyException('Category data is not valid.');
        }
    }

    /**
     * Update existing category.
     *
     * @param array $data
     * @return bool
     */
    public function updateCategory($data): bool
    {
        if (!$this->categoryRepository->categoryExists($data['id'])) {
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
     * @return Category|null
     */
    public function getCategoryByCode(string $code): ?Category
    {
        return $this->categoryRepository->getCategoryByCode($code);
    }

    /**
     * Get subcategories by code.
     *
     * @param string $code
     * @return Collection
     */
    public function getSubcategoriesByCode(string $code): Collection
    {
        return $this->categoryRepository->getSubcategoriesByCode($code);
    }
}