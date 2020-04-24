<?php

namespace Demoshop\Services;


use Demoshop\AuthorizationMiddleware\Exceptions\CategoryCantBeDeletedException;
use Demoshop\AuthorizationMiddleware\Exceptions\CategoryDataEmptyException;
use Demoshop\AuthorizationMiddleware\Exceptions\CategoryDoesntExistException;
use Demoshop\Entity\Category as CategoryEntity;
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
     * @throws CategoryCantBeDeletedException
     * @throws CategoryDoesntExistException
     */
    public function deleteCategoryById(int $id): void
    {
        $this->canBeDeleted($id);
        $this->categoryRepository->deleteCategoryById($id);
    }

    /**
     * Check if category has subcategories or products.
     * CategoryService having subcategories or products can't be deleted.
     *
     * @param int $id
     * @return void
     * @throws CategoryCantBeDeletedException
     * @throws CategoryDoesntExistException
     */
    public function canBeDeleted(int $id): void
    {
        $category = $this->categoryRepository->getCategoryById($id);
        if ($category) {
            $productRepository = new ProductsRepository();

            if ($this->categoryRepository->categoryHasSubcategories($category->id)) {
                throw new CategoryCantBeDeletedException('Category has subcategories.');
            }

            if ($productRepository->categoryHasProducts($category->id)) {
                throw new CategoryCantBeDeletedException('Category contains products.');
            }
        } else {
            throw new CategoryDoesntExistException('Category doesn\'t exist.');
        }
    }

    /**
     * Add new category.
     *
     * @param CategoryEntity $category
     * @return void
     * @throws CategoryDataEmptyException
     * @throws CategoryDoesntExistException
     */
    public function addNewCategory(CategoryEntity $category): void
    {
        $this->categoryValidInsert($category);

        if ($this->categoryRepository->categoryExistsCode($category->getCode())) {
            throw new CategoryDoesntExistException('Category code exists.');
        }

        $this->categoryRepository->addNewCategory($category);
    }

    /**
     * Check if category is valid for insert.
     *
     * @param CategoryEntity $category
     * @throws CategoryDataEmptyException
     */
    private function categoryValidInsert(CategoryEntity $category): void
    {
        if (empty($category->getTitle()) || empty($category->getCode()) ||
            empty($category->getDescription())) {
            throw new CategoryDataEmptyException('Category data is not valid');
        }
    }

    /**
     * Update existing category.
     *
     * @param CategoryEntity $category
     * @return void
     * @throws CategoryDataEmptyException
     * @throws CategoryDoesntExistException
     */
    public function updateCategory(CategoryEntity $category): void
    {
        $this->categoryValidUpdate($category);

        if (!$this->categoryRepository->categoryExists($category->getId())) {
            throw new CategoryDoesntExistException('Category doesn\'t exist.');
        }

        $this->categoryRepository->updateCategory($category);
    }

    /**
     * Check if category is valid for update.
     *
     * @param CategoryEntity $category
     * @throws CategoryDataEmptyException
     */
    private function categoryValidUpdate(CategoryEntity $category): void
    {
        if (empty($category->getTitle()) || empty($category->getCode()) ||
            empty($category->getDescription()) || $category->getId() === null) {
            throw new CategoryDataEmptyException('Category data is not valid.');
        }
    }

    /**
     * Get category and all it's subcategories.
     *
     * @param string $code
     * @return Collection
     */
    public function getAllSubcategories(string $code): Collection
    {
        $category = $this->getCategoryByCode($code);
        $children = $this->getSubcategoriesByCode($code);
        foreach ($children as $child) {
            $children = $children->merge($this->getAllSubcategories($child['code']));
        }

        $children->add($category);

        return $children;
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