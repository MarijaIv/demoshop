class TreeView {

    /**
     * @type {number}
     */
    id = 0;

    /**
     * Get category details and call displayCategoryDetails to display them.
     *
     * @param {string} id
     */
    displayCategory(id) {
        let toggler = document.getElementsByTagName("SPAN"), i;
        for (i = 0; i < toggler.length; i++) {
            if (toggler[i].classList.contains("selected")) {
                toggler[i].classList.remove("selected");
                toggler[i].classList.toggle("active");
            }

        }
        if(id !== "") {
            treeView.id = parseInt(id);
        } else {
            id = "" + treeView.id;
        }
        document.getElementById("" + id).classList.toggle("selected");
        let p = new CategoryService();
        p.displayCategory(parseInt(id))
            .then(this.displayCategoryDetails, message.displayError);
    }

    /**
     * Display category details.
     *
     * @param {object} data
     */
    displayCategoryDetails(data) {
        treeView.disable();
        treeView.id = data['id'];
        document.getElementById("title").value = data['title'];
        document.getElementById("parentCategoryInput").value =
            (data['parentId'] ? data['parentId'] : "No parent category");
        document.getElementById("code").value = data['code'];
        document.getElementById("description").value = data['description'];
        document.getElementById("editBtn").disabled = false;
    }

    /**
     * Check if category can be deleted.
     */
    confirmDelete() {
        let id = treeView.id;
        if (document.getElementById("" + id).className.indexOf("root") > -1) {
            document.getElementById("popup").classList.remove("popup");
            document.getElementById("popup").classList.add("popup-show");
            document.getElementById("message").value = "CategoryService has subcategories, it can't be deleted.";
        } else {
            message.displayConfirm("Do you want to delete this category?");
        }
    }

    /**
     *  Delete category.
     */
    delete() {
        let p = new CategoryService();
        p.deleteCategory(treeView.id).then(this.deleteCategory, message.deleteError);
    }

    /**
     * Alert user when category is deleted and reload page.
     *
     * @param {object} data
     */
    deleteCategory(data) {
        document.getElementById("tree").innerHTML = "";
        treeView.drawTree(data, document.getElementById("tree"));
        treeView.expand();
        treeView.display();
        treeView.disable();
        treeView.setEmpty();
        message.closeConfirm();
        message.displayInfo("CategoryService deleted.");
    }

    /**
     * Get all categories and call drawTreeWrapper to display them.
     */
    listCategory() {
        document.getElementById("selectedCategory").style.display = "block";
        let p = new CategoryService();
        p.listCategory()
            .then(treeView.drawTreeWrapper, message.displayError);
    }

    /**
     * Wrapper for drawTree method.
     *
     * @param {object} data
     */
    drawTreeWrapper(data) {
        let parentNode = document.getElementById("tree");
        treeView.drawTree(data, parentNode);
        treeView.expand();
        treeView.display();
        treeView.disable();
    }

    /**
     * Draws tree view for given data.
     *
     * @param {object} data
     * @param {object} parentNode
     */
    drawTree(data, parentNode) {
        let i;

        for (i = 0; i < data.length; i++) {
            let childNode = document.createElement("LI");
            let spanNode = document.createElement("SPAN");
            let name = document.createTextNode(data[i]['title']);
            spanNode.appendChild(name);
            spanNode.id = data[i]['id'];

            if (data[i]['nodes'].length > 0) {
                spanNode.className = 'root';
                childNode.appendChild(spanNode);
                let newNode = document.createElement("UL");
                newNode.className = 'child';
                this.drawTree(data[i]['nodes'], newNode);
                childNode.appendChild(newNode);
            } else {
                childNode.appendChild(spanNode);
            }

            parentNode.appendChild(childNode);
        }
    }

    /**
     * Add onclick event listener to root elements of tree view.
     */
    expand() {
        let toggler = document.getElementsByClassName("root"), i;
        for (i = 0; i < toggler.length; i++) {
            toggler[i].addEventListener("click", function () {
                let child = this.parentElement.querySelector(".child");

                if (child.querySelector(".child") &&
                    child.querySelector(".child").classList.contains("active")) {
                    child.querySelector(".child").classList.toggle("active");
                    child.querySelector("SPAN").classList.toggle("child-down");
                }

                this.classList.toggle("child-down");
                child.classList.toggle("active");
            });
        }
    }

    /**
     * Add onclick event listener for displaying category details to all tree view nodes.
     */
    display() {
        let nodes = document.getElementsByTagName("SPAN"), i;
        for (i = 0; i < nodes.length; i++) {
            if(nodes[i]['id']) {
                nodes[i].addEventListener("click", function () {
                    treeView.displayCategory(this['id']);
                });
            }
        }
    }

    /**
     * Set input text fields empty.
     */
    setEmpty() {
        document.getElementById("title").value = "";
        document.getElementById("parentCategoryInput").value = "";
        document.getElementById("code").value = "";
        document.getElementById("description").value = "";
    }

    /**
     * Disable input text fields.
     */
    disable() {
        document.getElementById("title").disabled = true;
        document.getElementById("parentCategoryInput").disabled = true;
        document.getElementById("code").disabled = true;
        document.getElementById("description").disabled = true;
        document.getElementById("editBtn").disabled = true;
    }

    /**
     * Enable treeview.
     */
    treeViewEnable() {
        document.getElementById("treeViewDiv").classList.remove("disable");
        document.getElementById("treeViewDiv").classList.add("column1");
    }

    /**
     * Disable treeview.
     */
    treeViewDisable() {
        document.getElementById("treeViewDiv").classList.remove("column1");
        document.getElementById("treeViewDiv").classList.add("disable");
    }

}

treeView = new TreeView();
window.onload = treeView.listCategory;
