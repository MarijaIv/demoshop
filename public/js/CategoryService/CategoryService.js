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
        return this.ajaxService.get("/admin.php?controller=category&action=delete&id=" + id);
    }

    /**
     * List categories.
     *
     * @return Promise
     */
    listCategory() {
        return this.ajaxService.get("/admin.php?controller=category&action=listCategories");
    }

    /**
     * Display category details.
     *
     * @param {int} id
     *
     * @return Promise
     */
    displayCategory(id) {
        return this.ajaxService.get("/admin.php?controller=category&action=displayCategory&id=" + id);
    }

    /**
     * Get all categories.
     *
     * @return Promise
     */
    listAllCategories() {
        return this.ajaxService.get("/admin.php?controller=category&action=listAllCategories");
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

        return this.ajaxService.post("/admin.php?controller=category&action=addNewCategory", data);
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

        return this.ajaxService.put("/admin.php?controller=category&action=updateCategory&jsonString=" + JSON.stringify(data), data);
    }
};

window.Demoshop.Service.categoryService = new Demoshop.Service.CategoryService();