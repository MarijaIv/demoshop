<?php

namespace Demoshop\Services;


use Demoshop\DTO\CategoryDTO;
use Demoshop\Repositories\CategoryRepository;
use Demoshop\Repositories\ProductsRepository;
use Demoshop\ServiceRegistry\ServiceRegistry;
use Illuminate\Database\Eloquent\Builder;
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
    public $categoryRepository;

    /**
     * CategoryService constructor.
     */
    public function __construct()
    {
        $this->categoryRepository = ServiceRegistry::get('CategoryRepository');
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
     * @return array
     */
    public function getCategoryById(int $id): array
    {
        $category = $this->categoryRepository->getCategoryById($id);
        return $this->getFormattedCategory($category);
    }

    /**
     * Format category for JSON encoding.
     *
     * @param Model $category
     * @return array
     */
    public function getFormattedCategory($category): array
    {
        $formattedCategory['id'] = $category->id;

        if ($category->parent_id) {
            $parent = $this->categoryRepository->getCategoryById($category->parent_id);
            $formattedCategory['parentId'] = ($parent ? $parent->code : '');
        } else {
            $formattedCategory['parentId'] = '';
        }

        $formattedCategory['title'] = $category->title;
        $formattedCategory['code'] = $category->code;
        $formattedCategory['description'] = $category->description;

        return $formattedCategory;
    }

    /**
     * Format array for JSON encoding (for treeview).
     *
     * @return array
     */
    public function getFormattedArray(): array
    {
        $myArray = $this->getAllCategories();
        $flat = [];
        $tree = [];

        foreach ($myArray as $item) {
            $flat[$item->id] = $item;
            if (NULL === $item->parentId) {
                $tree[] = &$flat[$item->id];
            } else {
                $flat[$item->parentId]->nodes[] = &$flat[$item->id];
            }
        }

        return $tree;
    }

    /**
     * Get all categories.
     *
     * @return array
     */
    public function getAllCategories(): array
    {
        $categories = $this->categoryRepository->getAllCategories();
        return $this->getFormattedCategories($categories);
    }

    /**
     * Format categories for JSON encoding.
     *
     * @param $myArray
     * @return array
     */
    public function getFormattedCategories($myArray): array
    {
        $formattedArray = [];

        foreach ($myArray as $item) {
            $formattedArray[] = new CategoryDTO($item);
        }

        return $formattedArray;
    }

    /**
     * Get root categories.
     *
     * @return Builder[]|Collection
     */
    public function getRootCategories(): Collection
    {
        return $this->categoryRepository->getRootCategories();
    }

    /**
     * Get subcategories for parent.
     *
     * @param int $id
     * @return Builder[]|Collection
     */
    public function getCategoriesForParent(int $id): Collection
    {
        return $this->categoryRepository->getCategoriesForParent($id);
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
            $children = $this->categoryRepository->getCategoriesForParent($category->id);

            $productRepository = new ProductsRepository();
            $products = $productRepository->getProductsByCategoryId($category->id);

            return !($children->count() || $products->count());
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
        if ($this->isRequestDataEmpty($data)) {
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
    public function isRequestDataEmpty(array $data): bool
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
        if ($this->isRequestDataEmpty($data)) {
            return false;
        }

        if (empty($data['id']) && !$this->categoryRepository->categoryExists($data['id'])) {
            return false;
        }

        $this->categoryRepository->updateCategory($data);
        return true;
    }
}