<?php


namespace Demoshop\Repositories;


use Demoshop\Model\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class CategoryRepository
 * @package Demoshop\Repositories
 */
class CategoryRepository
{
    /**
     * Get total amount of categories.
     *
     * @return int
     */
    public function getAmountOfCategories(): int
    {
        return Category::query()->count();
    }

    /**
     * Get all root categories.
     *
     * @return Builder[]|Collection
     */
    public function getRootCategories(): Collection
    {
        return Category::query()->where('parent_id', '=', null)->get();
    }

    /**
     * Get subcategories for category.
     *
     * @param int $id
     * @return Builder[]|Collection
     */
    public function getCategoriesForParent(int $id): Collection
    {
        return Category::query()->where('parent_id', '=', $id)->get();
    }


    /**
     * Get category by id.
     *
     * @param int $id
     * @return Builder|Model|object|null
     */
    public function getCategoryById(int $id): Model
    {
        return Category::query()->where('id', '=', $id)->first();
    }

    /**
     * Get category by code
     *
     * @param string $code
     *
     * @return Model
     */
    public function getCategoryByCode(string $code): Model
    {
        return Category::query()->where('code', '=', $code)->first();
    }

    /**
     * Delete category by id.
     *
     * @param int $id
     * @return int
     */
    public function deleteCategoryById(int $id): int
    {
        return Category::query()->where('id', '=', $id)->delete();
    }

    /**
     * Get all categories.
     *
     * @return Collection
     */
    public function getAllCategories(): Collection
    {
        return Category::query()->select('id', 'parent_id', 'code', 'title', 'description')->get();
    }

    /**
     * Insert new category.
     *
     * @param array $data
     * @return bool
     */
    public function addNewCategory($data): bool
    {
        if ($data['parentCategory'] === '') {
            $parentCategory = null;
        } else {
            $parentCategory = $data['parentCategory'];
        }

        return Category::query()->insert(
            [
                'parent_id' => $parentCategory,
                'code' => $data['code'],
                'title' => $data['title'],
                'description' => $data['description'],
            ]
        );
    }

    /**
     * Update existing category.
     *
     * @param array $data
     * @return int
     */
    public function updateCategory($data): int
    {
        if ($data['parentCategory'] === '') {
            $parentCategory = null;
        } else {
            $parentCategory = $data['parentCategory'];
        }

        return Category::query()
            ->where('id', '=', $data['id'])
            ->update(
                [
                    'parent_id' => $parentCategory,
                    'code' => $data['code'],
                    'title' => $data['title'],
                    'description' => $data['description'],
                ]
            );
    }

    /**
     * Check if category with given id exists.
     *
     * @param int $id
     * @return bool
     */
    public function categoryExists(int $id): bool
    {
        $category = Category::query()->where('id', '=', $id)->first();
        if (!$category) {
            return false;
        }

        return true;
    }

    /**
     * Check if category with given code exists.
     *
     * @param string $code
     * @return bool
     */
    public function categoryExistsCode(string $code): bool
    {
        $category = Category::query()->where('code', '=', $code)->first();
        if (!$category) {
            return false;
        }

        return true;
    }
}