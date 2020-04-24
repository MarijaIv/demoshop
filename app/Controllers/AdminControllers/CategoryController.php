<?php


namespace Demoshop\Controllers\AdminControllers;


use Demoshop\AuthorizationMiddleware\Exceptions\CategoryCantBeDeletedException;
use Demoshop\AuthorizationMiddleware\Exceptions\CategoryDataEmptyException;
use Demoshop\AuthorizationMiddleware\Exceptions\CategoryDoesntExistException;
use Demoshop\Controllers\AdminController;
use Demoshop\Entity\Category;
use Demoshop\Formatters\CategoryFormatter;
use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\JSONResponse;
use Demoshop\HTTP\Request;

/**
 * Class CategoryController
 * @package Demoshop\Controllers\AdminControllers
 */
class CategoryController extends AdminController
{
    /**
     * Function for rendering category.php page.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function index(Request $request): HTMLResponse
    {
        return new HTMLResponse('/views/admin/category.php');
    }

    /**
     * Get formatted categories in json file.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function listCategories(Request $request): JSONResponse
    {
        return $this->categories();
    }

    /**
     * List all categories in json file.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function listAllCategories(Request $request): JSONResponse
    {
        $formatter = new CategoryFormatter();

        $categories = $this->getCategoryService()->getCategories();
        $categories = $formatter->getFormattedCategories($categories);

        return new JSONResponse($categories);
    }

    /**
     * @param Request $request
     * @return JSONResponse
     */
    public function getCategoriesForEdit(Request $request): JSONResponse
    {
        $formatter = new CategoryFormatter();

        $categories = $this->getCategoryService()->getCategoriesForEdit($request->getGetData()['id']);
        $categories = $formatter->getFormattedCategories($categories);

        return new JSONResponse($categories);
    }

    /**
     * Get category data for display.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function displayCategory(Request $request): JSONResponse
    {
        $categoryService = $this->getCategoryService();
        $formatter = new CategoryFormatter();

        $category = $categoryService->getCategoryById($request->getGetData()['id']);
        $parent = null;
        if ($category && $category->parent_id) {
            $parent = $categoryService->getCategoryById($category->parent_id);
        }

        $category = $formatter->formatCategory($category, $parent);

        return new JSONResponse($category);
    }

    /**
     * Delete category if it doesn't have subcategories or products.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function delete(Request $request): JSONResponse
    {
        try {
            $this->getCategoryService()->deleteCategoryById($request->getGetData()['id']);

            return $this->categories();
        } catch (CategoryCantBeDeletedException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (CategoryDoesntExistException $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Add new category.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function addNewCategory(Request $request): JSONResponse
    {
        $category = $this->jsonCategory();

        try {
            $this->getCategoryService()->addNewCategory($category);

            return $this->categories();
        } catch (CategoryDataEmptyException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (CategoryDoesntExistException $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Update existing category.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function updateCategory(Request $request): JSONResponse
    {
        $category = $this->jsonCategory();

        try {
            $this->getCategoryService()->updateCategory($category);

            return $this->categories();
        } catch (CategoryDataEmptyException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (CategoryDoesntExistException $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Get categories for treeview and return json response.
     *
     * @return JSONResponse
     */
    private function categories(): JSONResponse
    {
        $categoryFormatter = new CategoryFormatter();

        $categories = $this->getCategoryService()->getCategories();
        $categoriesForTreeView = $categoryFormatter->formatCategoriesForTreeView($categories);

        return new JSONResponse($categoriesForTreeView);
    }

    /**
     * Set error message and return json response.
     *
     * @param $message
     * @return JSONResponse
     */
    private function errorResponse($message): JSONResponse
    {
        $json = new JSONResponse(['message' => $message]);
        $json->setStatus(400);
        return $json;
    }

    /**
     * Create category entity.
     *
     * @param array $category
     * @return Category
     */
    private function createCategory(array $category): Category
    {
        return new Category(
            $category['code'],
            $category['title'],
            $category['description'],
            empty($category['parentCategory']) ? null : $category['parentCategory'],
            empty($category['id']) ? null : $category['id']
        );
    }

    /**
     * Get category from json file.
     *
     * @return Category
     */
    private function jsonCategory(): Category
    {
        $categoryJson = json_decode(file_get_contents('php://input'), true,
            512, JSON_THROW_ON_ERROR);

        return $this->createCategory($categoryJson);
    }
}