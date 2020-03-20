var Demoshop = Demoshop || {};
Demoshop.Category = Demoshop.Category || {};

Demoshop.Category.EditCategory = class {

    /**
     * Edit selected category.
     */
    edit() {
        document.getElementById("header").innerText = "Edit category";

        document.getElementById("cancelEdit").hidden = false;
        document.getElementById("okEdit").hidden = false;
        document.getElementById("editBtn").hidden = true;
        document.getElementById("cancelBtn").hidden = true;
        document.getElementById("okBtn").hidden = true;
        document.getElementById("deleteBtn").disabled = true;

        document.getElementById("title").disabled = false;
        document.getElementById("code").disabled = false;
        document.getElementById("description").disabled = false;
        document.getElementById("parentCategory").disabled = false;
        document.getElementById("parentCategoryInput").hidden = true;
        document.getElementById("parentCategory").hidden = false;

        Demoshop.Category.treeView.treeViewDisable();

        let selected = document.getElementsByClassName("selected");

        let p = Demoshop.Service.categoryService;
        p.listCategoriesForEdit(parseInt(selected[0].id)).then(this.addOptionsToSelect, this.displayError);
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
            if (data[i]['code'] === document.getElementById("parentCategoryInput").value) {
                parent.options[i].selected = true;
            }
        }

        parent.options[i] = new Option("root");
        if (document.getElementById("parentCategoryInput").value === "No parent category") {
            parent.options[i].selected = true;
        }
    }

    /**
     * Save changes for edited category.
     */
    saveEdited() {
        let title = document.getElementById("title").value;
        let parentCategory = document.getElementById("parentCategory").value;
        let code = document.getElementById("code").value;
        let description = document.getElementById("description").value;
        let id = Demoshop.Category.treeView.id;

        if (title !== "" && code !== "" && description !== "") {
            let p = Demoshop.Service.categoryService;
            p.updateCategory(id, title, parentCategory, code, description)
                .then(this.categoryUpdated, this.displayError);
        } else {
            Demoshop.CategoryPopups.popup.addPopups();
        }
    }

    /**
     * Refresh treeview after updating category.
     *
     * @param {object} data
     */
    categoryUpdated(data) {
        document.getElementById("tree").innerHTML = "";
        Demoshop.Category.treeView.drawTree(data, document.getElementById("tree"));
        Demoshop.Category.treeView.expand();
        Demoshop.Category.treeView.display();
        Demoshop.Category.editCategory.cancelEdit();
        Demoshop.Category.treeView.setEmpty();
        Demoshop.CategoryMessages.messages.displayInfo("Category updated.");
    }

    /**
     * Cancel editing category.
     */
    cancelEdit() {
        Demoshop.CategoryPopups.popup.removePopups();

        document.getElementById("header").innerText = "Selected category";

        document.getElementById("parentCategory").hidden = true;
        document.getElementById("parentCategoryInput").hidden = false;

        Demoshop.Category.treeView.disable();
        Demoshop.Category.treeView.treeViewEnable();
        Demoshop.Category.treeView.displayCategory("");

        document.getElementById("cancelEdit").hidden = true;
        document.getElementById("okEdit").hidden = true;
        document.getElementById("editBtn").hidden = false;
        document.getElementById("cancelBtn").hidden = true;
        document.getElementById("okBtn").hidden = true;
        document.getElementById("deleteBtn").disabled = false;
        document.getElementById("editBtn").disabled = false;
    }
};

window.Demoshop.Category.editCategory = new Demoshop.Category.EditCategory();