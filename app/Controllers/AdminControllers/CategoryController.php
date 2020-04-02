<?php


namespace Demoshop\Controllers\AdminControllers;


use Demoshop\AuthorizationMiddleware\Exceptions\CategoryDataEmptyException;
use Demoshop\Controllers\AdminController;
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
        $categoryService = $this->getCategoryService();
        $categoryFormatter = new CategoryFormatter();
        $categories = $categoryService->getCategories();
        $categoriesForTreeView = $categoryFormatter->formatCategoriesForTreeView($categories);

        return new JSONResponse($categoriesForTreeView);
    }

    /**
     * List all categories in json file.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function listAllCategories(Request $request): JSONResponse
    {
        $categoryService = $this->getCategoryService();
        $formatter = new CategoryFormatter();

        $categories = $categoryService->getCategories();
        $categories = $formatter->getFormattedCategories($categories);

        return new JSONResponse($categories);
    }

    /**
     * @param Request $request
     * @return JSONResponse
     */
    public function getCategoriesForEdit(Request $request): JSONResponse
    {
        $categoryService = $this->getCategoryService();
        $formatter = new CategoryFormatter();

        $categories = $categoryService->getCategoriesForEdit($request->getGetData()['id']);
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
        $categoryService = $this->getCategoryService();
        $categoryFormatter = new CategoryFormatter();

        $category = $categoryService->deleteCategoryById($request->getGetData()['id']);
        if (!$category) {
            $jsonMessage['message'] = 'Category contains products.';
            $json = new JSONResponse($jsonMessage);
            $json->setStatus(400);
            return $json;
        }

        $categories = $categoryService->getCategories();
        $categoriesForTreeView = $categoryFormatter->formatCategoriesForTreeView($categories);

        return new JSONResponse($categoriesForTreeView);
    }

    /**
     * Add new category.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function addNewCategory(Request $request): JSONResponse
    {
        $categoryService = $this->getCategoryService();
        $categoryFormatter = new CategoryFormatter();

        $category = json_decode(file_get_contents('php://input'), true,
            512, JSON_THROW_ON_ERROR);

        try {
            $categoryService->isRequestDataValid($category);

            if (!$categoryService->addNewCategory($category)) {
                $json = new JSONResponse(['message' => 'Category id already exists.']);
                $json->setStatus(400);
                return $json;
            }

            $categories = $categoryService->getCategories();
            $categoriesForTreeView = $categoryFormatter->formatCategoriesForTreeView($categories);

            return new JSONResponse($categoriesForTreeView);
        } catch (CategoryDataEmptyException $e) {
            $json = new JSONResponse(['message' => $e->getMessage()]);
            $json->setStatus(400);
            return $json;
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
        $categoryService = $this->getCategoryService();
        $categoryFormatter = new CategoryFormatter();

        $category = json_decode(file_get_contents('php://input'), true,
            512, JSON_THROW_ON_ERROR);

        try {
            $categoryService->isRequestDataValid($category);

            if (!$categoryService->updateCategory($category)) {
                $json = new JSONResponse(['message' => 'Category id doesn\'t exists.']);
                $json->setStatus(400);
                return $json;
            }

            $categories = $categoryService->getCategories();
            $categoriesForTreeView = $categoryFormatter->formatCategoriesForTreeView($categories);

            return new JSONResponse($categoriesForTreeView);
        } catch (CategoryDataEmptyException $e) {
            $json = new JSONResponse(['message' => $e->getMessage()]);
            $json->setStatus(400);
            return $json;
        }
    }
}