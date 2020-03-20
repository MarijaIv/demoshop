var Demoshop = Demoshop || {};
Demoshop.Service = Demoshop.Service || {};

Demoshop.Service.CategoryService = class {

    ajaxService = new AjaxService();

    /**
     *  Delete category.
     *
     * @param {int} id
     *
     * @return Promise
     */
    deleteCategory(id) {
        return this.ajaxService.delete("/admin/categories?id=" + id);
    }

    /**
     * List categories.
     *
     * @return Promise
     */
    listCategory() {
        return this.ajaxService.get("/admin/categories/listCategories");
    }

    /**
     * Display category details.
     *
     * @param {int} id
     *
     * @return Promise
     */
    displayCategory(id) {
        return this.ajaxService.get("/admin/categories/displayCategory?id=" + id);
    }

    /**
     * Get all categories.
     *
     * @return Promise
     */
    listAllCategories() {
        return this.ajaxService.get("/admin/categories/listAllCategories");
    }

    /**
     * Get categories for category edit.
     *
     * @param {int} id
     * @return Promise
     */
    listCategoriesForEdit(id) {
        return this.ajaxService.get("/admin/categories/getCategoriesForEdit?id=" + id);
    }

    /**
     * Add new category.
     *
     * @param {string} title
     * @param {string} parentCategory
     * @param {string} code
     * @param {string} description
     *
     * @return Promise
     */
    addNewCategory(title, parentCategory, code, description) {
        let data = {title: title, code: code, description: description};
        data.parentCategory = parentCategory === "root" ? null : parentCategory;

        return this.ajaxService.post("/admin/categories", data);
    }

    /**
     * Update existing category.
     *
     * @param {int} id
     * @param {string} title
     * @param {string} parentCategory
     * @param {string} code
     * @param {string} description
     *
     * @return Promise
     */
    updateCategory(id, title, parentCategory, code, description) {
        let data = {id: id, title: title, code: code, description: description};
        data.parentCategory = parentCategory === "root" ? null : parentCategory;

        return this.ajaxService.put("/admin/categories", data);
    }
};

window.Demoshop.Service.categoryService = new Demoshop.Service.CategoryService();