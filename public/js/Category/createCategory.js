var Demoshop = Demoshop || {};
Demoshop.Category = Demoshop.Category || {};

Demoshop.Category.CreateCategory = class {

    /**
     * Add new root category.
     *
     * add => addOrCancel = true;
     * cancel => addOrCancel = false;
     *
     * @param {boolean} addOrCancel
     */
    displayOrCancelAddCategory(addOrCancel) {
        Demoshop.Category.treeView.setEmpty();
        document.getElementById("parentCategoryInput").hidden = addOrCancel;
        document.getElementById("parentCategory").hidden = !addOrCancel;
        document.getElementById("deleteBtn").hidden = addOrCancel;
        document.getElementById("editBtn").hidden = addOrCancel;
        document.getElementById("cancelBtn").hidden = !addOrCancel;
        document.getElementById("okBtn").hidden = !addOrCancel;

        if(addOrCancel) {
            Demoshop.Category.treeView.treeViewDisable();
            document.getElementById("header").innerText = "Create category";
            document.getElementById("parentCategory").disabled = true;
            document.getElementById("parentCategory").options[0] = new Option("root");
            document.getElementById("parentCategory").options[0].selected = true;
            document.getElementById("title").disabled = false;
            document.getElementById("code").disabled = false;
            document.getElementById("description").disabled = false;
        } else {
            Demoshop.Category.treeView.disable();
            Demoshop.Category.treeView.treeViewEnable();
            document.getElementById("header").innerText = "Selected category";
            Demoshop.CategoryPopups.popup.removePopups();
            Demoshop.Category.treeView.displayCategory("");
        }
    }

    /**
     * Add new subcategory.
     */
    addSubcategory() {
        Demoshop.Category.createCategory.displayOrCancelAddCategory(true);
        document.getElementById("parentCategory").disabled = false;
        let p = Demoshop.Service.categoryService;
        p.listAllCategories().then(this.addOptionsToSelect, this.displayError);
    }

    /**
     * Call Messages.displayError.
     *
     * @param {object} data
     */
    displayError(data) {
        Demoshop.CategoryMessages.messages.displayError(data);
    }

    /**
     * Add options to select component.
     *
     * @param {object} data
     */
    addOptionsToSelect(data) {
        let i;
        let parent = document.getElementById("parentCategory");

        for (i = 0; i < data.length; i++) {
            parent.options[i] = new Option(data[i]['title']);
            parent.options[i].value = data[i]['id'];
        }

        document.getElementById("parentCategory").value = Demoshop.Category.treeView.id;
    }

    /**
     * Save new category or subcategory.
     */
    saveCategory() {
        let title = document.getElementById("title").value;
        let parentCategory = document.getElementById("parentCategory").value;
        let code = document.getElementById("code").value;
        let description = document.getElementById("description").value;
        if (title !== "" && code !== "" && description !== "") {
            let p = Demoshop.Service.categoryService;
            p.addNewCategory(title, parentCategory, code, description).then(this.categoryAdded, this.displayError);
        } else {
            Demoshop.CategoryPopups.popup.addPopups();
        }
    }

    /**
     * Refresh treeview and alert user that new category was added.
     *
     * @param {object} data
     */
    categoryAdded(data) {
        document.getElementById("tree").innerHTML = "";
        Demoshop.Category.treeView.drawTree(data, document.getElementById("tree"));
        Demoshop.Category.treeView.expand();
        Demoshop.Category.treeView.display();
        Demoshop.CategoryMessages.messages.displayInfo("CategoryService added.");
        Demoshop.Category.createCategory.displayOrCancelAddCategory(false);
    }
};

window.Demoshop.Category.createCategory = new Demoshop.Category.CreateCategory();