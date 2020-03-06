var Demoshop = Demoshop || {};
Demoshop.Category = Demoshop.Category || {};

Demoshop.Category.TreeView = class {

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
                toggler[i].classList.add("active");
            }
        }

        if (id !== "") {
            Demoshop.Category.treeView.id = parseInt(id);
        } else {
            id = "" + Demoshop.Category.treeView.id;
        }

        if (Demoshop.Category.treeView.id) {
            document.getElementById("" + id).classList.add("selected");
            let p = Demoshop.Service.categoryService;
            p.displayCategory(parseInt(id))
                .then(Demoshop.Category.treeView.displayCategoryDetails, this.displayError);
        }
    }

    /**
     * Display category details.
     *
     * @param {object} data
     */
    displayCategoryDetails(data) {
        Demoshop.Category.treeView.disable();
        Demoshop.Category.treeView.id = data['id'];
        document.getElementById("title").value = data['title'];
        document.getElementById("parentCategoryInput").value =
            (data['parentCode'] ? data['parentCode'] : "No parent category");
        document.getElementById("code").value = data['code'];
        document.getElementById("description").value = data['description'];
        document.getElementById("editBtn").disabled = false;
    }

    /**
     * Check if category can be deleted.
     */
    confirmDelete() {
        let id = Demoshop.Category.treeView.id;
        if (document.getElementById("" + id).className.indexOf("root") > -1) {
            document.getElementById("popup").classList.remove("popup");
            document.getElementById("popup").classList.add("popup-show");
            document.getElementById("message").value = "CategoryService has subcategories, it can't be deleted.";
        } else {
            Demoshop.CategoryMessages.messages.displayConfirm("Do you want to delete this category?");
        }
    }

    /**
     *  Delete category.
     */
    delete() {
        let p = Demoshop.Service.categoryService;
        p.deleteCategory(Demoshop.Category.treeView.id).then(this.deleteCategory, this.deleteError);
    }

    /**
     * Call Messages.deleteError.
     *
     * @param {object} data
     */
    deleteError(data) {
        Demoshop.CategoryMessages.messages.deleteError(data);
    }

    /**
     * Alert user when category is deleted and reload page.
     *
     * @param {object} data
     */
    deleteCategory(data) {
        document.getElementById("tree").innerHTML = "";
        Demoshop.Category.treeView.drawTree(data, document.getElementById("tree"));
        Demoshop.Category.treeView.expand();
        Demoshop.Category.treeView.display();
        Demoshop.Category.treeView.disable();
        Demoshop.Category.treeView.setEmpty();
        Demoshop.CategoryMessages.messages.closeConfirm();
        Demoshop.CategoryMessages.messages.displayInfo("Category deleted.");
    }

    /**
     * Get all categories and call drawTreeWrapper to display them.
     */
    listCategory() {
        document.getElementById("selectedCategory").style.display = "block";
        let p = Demoshop.Service.categoryService;
        p.listCategory()
            .then(this.drawTreeWrapper, this.displayError);
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
     * Wrapper for drawTree method.
     *
     * @param {object} data
     */
    drawTreeWrapper(data) {
        let parentNode = document.getElementById("tree");
        Demoshop.Category.treeView.drawTree(data, parentNode);
        Demoshop.Category.treeView.expand();
        Demoshop.Category.treeView.display();
        Demoshop.Category.treeView.disable();
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

            if (data[i]['children'].length > 0) {
                spanNode.className = 'root';
                childNode.appendChild(spanNode);
                let newNode = document.createElement("UL");
                newNode.className = 'child';
                this.drawTree(data[i]['children'], newNode);
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
                    (child.querySelector(".child").classList.contains("active")
                        || child.querySelector(".child").classList.contains("selected"))) {
                    let selectedChildren = child.parentElement.getElementsByClassName("selected");

                    for(i = selectedChildren.length - 1; i >= 0; i--) {
                        if (selectedChildren[i].tagName === 'SPAN') {
                            selectedChildren[i].classList.remove("child-down");
                        }
                        selectedChildren[i].classList.remove("active");
                    }

                    let activeChildren = child.parentElement.getElementsByClassName("active");

                    for (i = activeChildren.length - 1; i >= 0; i--) {
                        if (activeChildren[i].tagName === 'SPAN') {
                            activeChildren[i].classList.remove("child-down");
                        }
                        activeChildren[i].classList.remove("active");
                    }
                } else {
                    if(child.classList.contains("active")) {
                        this.classList.remove("child-down");
                        child.classList.remove("active");
                    } else {
                        this.classList.add("child-down");
                        child.classList.add("active");
                    }
                }
            });
        }
    }

    /**
     * Add onclick event listener for displaying category details to all tree view nodes.
     */
    display() {
        let nodes = document.getElementsByTagName("SPAN"), i;
        for (i = 0; i < nodes.length; i++) {
            if (nodes[i]['id']) {
                nodes[i].addEventListener("click", function () {
                    Demoshop.Category.treeView.displayCategory(this['id']);
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

};

window.Demoshop.Category.treeView = new Demoshop.Category.TreeView();
