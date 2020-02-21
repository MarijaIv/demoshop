class CreateCategory {

    /**
     * Add new root category.
     */
    addRootCategory() {
        treeView.setEmpty();
        treeView.treeViewDisable();

        document.getElementById("header").innerText = "Create category";

        document.getElementById("parentCategoryInput").hidden = true;
        document.getElementById("parentCategory").hidden = false;
        document.getElementById("parentCategory").disabled = true;
        document.getElementById("parentCategory").options[0] = new Option("root");
        document.getElementById("parentCategory").options[0].selected = true;

        document.getElementById("title").disabled = false;
        document.getElementById("code").disabled = false;
        document.getElementById("description").disabled = false;

        document.getElementById("deleteBtn").hidden = true;
        document.getElementById("editBtn").hidden = true;
        document.getElementById("cancelBtn").hidden = false;
        document.getElementById("okBtn").hidden = false;
    }

    /**
     * Add new subcategory.
     */
    addSubcategory() {
        createCategory.addRootCategory();
        document.getElementById("parentCategory").disabled = false;
        let p = new CategoryService();
        p.listAllCategories().then(this.addOptionsToSelect, message.displayError);
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

        document.getElementById("parentCategory").value = treeView.id;
    }

    /**
     * Cancel adding new category or subcategory.
     */
    cancelAddCategory() {
        popup.removePopups();
        treeView.displayCategory("");

        document.getElementById("header").innerText = "Selected category";

        document.getElementById("parentCategory").hidden = true;
        document.getElementById("parentCategoryInput").hidden = false;

        document.getElementById("cancelBtn").hidden = true;
        document.getElementById("okBtn").hidden = true;
        document.getElementById("editBtn").hidden = false;
        document.getElementById("deleteBtn").hidden = false;

        treeView.setEmpty();
        treeView.disable();
        treeView.treeViewEnable();
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
            let p = new CategoryService();
            p.addNewCategory(title, parentCategory, code, description).then(this.categoryAdded, message.displayError);
        } else {
            popup.addPopups();
        }
    }

    /**
     * Refresh treeview and alert user that new category was added.
     *
     * @param {object} data
     */
    categoryAdded(data) {
        document.getElementById("tree").innerHTML = "";
        treeView.drawTree(data, document.getElementById("tree"));
        treeView.expand();
        treeView.display();
        message.displayInfo("CategoryService added.");
        createCategory.cancelAddCategory();
    }
}

createCategory = new CreateCategory();