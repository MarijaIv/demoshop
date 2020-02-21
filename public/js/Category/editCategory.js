class EditCategory {

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

        treeView.treeViewDisable();

        let p = new CategoryService();
        p.listAllCategories().then(this.addOptionsToSelect, treeView.displayError);
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
        let id = treeView.id;

        if (title !== "" && code !== "" && description !== "") {
            let p = new CategoryService();
            p.updateCategory(id, title, parentCategory, code, description)
                .then(this.categoryUpdated, message.displayError);
        } else {
            popup.addPopups();
        }
    }

    /**
     * Refresh treeview after updating category.
     *
     * @param {object} data
     */
    categoryUpdated(data) {
        document.getElementById("tree").innerHTML = "";
        treeView.drawTree(data, document.getElementById("tree"));
        treeView.expand();
        treeView.display();
        editCategory.cancelEdit();
        treeView.setEmpty();
        message.displayInfo("CategoryService updated.");
    }

    /**
     * Cancel editing category.
     */
    cancelEdit() {
        popup.removePopups();

        document.getElementById("header").innerText = "Selected category";

        document.getElementById("parentCategory").hidden = true;
        document.getElementById("parentCategoryInput").hidden = false;

        treeView.disable();
        treeView.treeViewEnable();
        treeView.displayCategory("");

        document.getElementById("cancelEdit").hidden = true;
        document.getElementById("okEdit").hidden = true;
        document.getElementById("editBtn").hidden = false;
        document.getElementById("cancelBtn").hidden = true;
        document.getElementById("okBtn").hidden = true;
        document.getElementById("deleteBtn").disabled = false;
        document.getElementById("editBtn").disabled = false;
    }
}

editCategory = new EditCategory();