class ProductsTable {

    /**
     * Current page number.
     *
     * @type {number}
     */
    currentPage = 1;
    /**
     * List of all products.
     */
    products;
    /**
     * Number of products displayed per page.
     *
     * @type {number}
     */
    productsPerPage = 5;
    /**
     * Sorting direction for title, brand, category,
     * short description and price.
     *
     * @type {string[]}
     */
    dir = ["", "asc", "", "asc", "asc", "asc", "asc"];

    /**
     * Get all products and call drawTable to display them.
     */
    listProducts() {
        let p = new ProductsService();
        p.listProducts()
            .then(productsTable.drawTable, message.displayError);
    }

    /**
     * Draw table for given data.
     *
     * @param {object} data
     */
    drawTable(data) {
        productsTable.products = data;
        productsTable.changePage(1);
    }

    /**
     * Add new row to table.
     *
     * @param {object} data
     */
    addTableRow(data) {
        let newRow = document.createElement("TR");

        let newField = document.createElement("TD");
        let selected = document.createElement("INPUT");
        selected.setAttribute("type", "checkbox");
        newField.appendChild(selected);
        newRow.appendChild(newField);

        newField = productsTable.insertNewData(data['title']);
        newRow.appendChild(newField);

        newField = productsTable.insertNewData(data['sku']);
        newRow.appendChild(newField);

        newField = productsTable.insertNewData(data['brand']);
        newRow.appendChild(newField);

        newField = productsTable.insertNewData(data['category']);
        newRow.appendChild(newField);

        newField = productsTable.insertNewData(data['shortDesc']);
        newRow.appendChild(newField);

        newField = productsTable.insertNewData(data['price']);
        newRow.appendChild(newField);

        newField = document.createElement("TD");
        let enabled = document.createElement("INPUT");
        enabled.setAttribute("type", "checkbox");
        enabled.checked = data['enabled'];
        newField.appendChild(enabled);
        newRow.appendChild(newField);

        newField = document.createElement("TD");
        let editBtn = document.createElement("INPUT");
        editBtn.setAttribute("type", "button");
        editBtn.classList.add("edit");
        newField.appendChild(editBtn);
        newRow.appendChild(newField);

        newField = document.createElement("TD");
        let deleteBtn = document.createElement("INPUT");
        deleteBtn.setAttribute("type", "button");
        deleteBtn.classList.add("delete");
        newField.appendChild(deleteBtn);
        newRow.appendChild(newField);

        return newRow;
    }

    /**
     * Insert new data into table row.
     *
     * @param {string} text
     */
    insertNewData(text) {
        let newField = document.createElement("TD");
        newField.innerText = (text);

        return newField;
    }

    /**
     * Go to first page.
     */
    firstPage() {
        productsTable.currentPage = 1;
        productsTable.changePage(productsTable.currentPage);
    }

    /**
     * Go to previous page.
     */
    prevPage() {
        if (productsTable.currentPage > 1) {
            productsTable.currentPage--;
            productsTable.changePage(productsTable.currentPage);
        }
    }

    /**
     * Go to next page.
     */
    nextPage() {
        if (productsTable.currentPage < Math.ceil(productsTable.products.length / productsTable.productsPerPage)) {
            productsTable.currentPage++;
            productsTable.changePage(productsTable.currentPage);
        }
    }

    /**
     * Go to last page.
     */
    lastPage() {
        productsTable.currentPage = Math.ceil(productsTable.products.length / productsTable.productsPerPage);
        productsTable.changePage(productsTable.currentPage);
    }

    /**
     * Change displaying page.
     *
     * @param {int} page
     */
    changePage(page) {
        let table = document.getElementById("products"), i;
        table.innerHTML = "";

        table.appendChild(productsTable.addHeader());

        for (i = (page - 1) * productsTable.productsPerPage;
             i < page * productsTable.productsPerPage && i < productsTable.products.length; i++) {
            table.appendChild(productsTable.addTableRow(productsTable.products[i]));
        }

        document.getElementById("page").innerHTML = page + "/" +
            Math.ceil(productsTable.products.length / productsTable.productsPerPage);
    }

    /**
     * Adds table header.
     */
    addHeader() {
        let newRow = document.createElement("TR");
        let newField = document.createElement("TH");
        newField.innerText = "Selected";
        newRow.appendChild(newField);

        newField = document.createElement("TH");
        newField.innerText = "Title";
        newField.onclick = function () {
            productsTable.sort(1);
        };
        newField.classList.add("sortable");
        newRow.appendChild(newField);

        newField = document.createElement("TH");
        newField.innerText = "SKU";
        newRow.appendChild(newField);

        newField = document.createElement("TH");
        newField.innerText = "Brand";
        newField.onclick = function () {
            productsTable.sort(3);
        };
        newField.classList.add("sortable");
        newRow.appendChild(newField);

        newField = document.createElement("TH");
        newField.innerText = "Category";
        newField.onclick = function () {
            productsTable.sort(4);
        };
        newField.classList.add("sortable");
        newRow.appendChild(newField);

        newField = document.createElement("TH");
        newField.innerText = "Short description";
        newField.onclick = function () {
            productsTable.sort(5);
        };
        newField.classList.add("sortable");
        newRow.appendChild(newField);

        newField = document.createElement("TH");
        newField.innerText = "Price";
        newField.onclick = function () {
            productsTable.sort(6);
        };
        newField.classList.add("sortable");
        newRow.appendChild(newField);

        newField = document.createElement("TH");
        newField.innerText = "Enable";
        newRow.appendChild(newField);

        newField = document.createElement("TH");
        newField.innerText = "";
        newRow.appendChild(newField);

        newField = document.createElement("TH");
        newField.innerText = "";
        newRow.appendChild(newField);

        return newRow;
    }

    /**
     * Sort elements by title, brand, category, short description or price.
     *
     * @param {int} n
     */
    sort(n) {
        let table, rows, i, j, x, y;

        table = document.getElementById("products");

        rows = table.rows;

        for (i = 0; i < rows.length - 1; i++) {
            for (j = 1; j < rows.length - i - 1; j++) {
                x = rows[j].getElementsByTagName("TD")[n];
                y = rows[j + 1].getElementsByTagName("TD")[n];

                if (this.dir[n] === "asc") {
                    if (n === 6) {
                        if (parseFloat(x.innerHTML) > parseFloat(y.innerHTML)) {
                            rows[j].parentNode.insertBefore(rows[j + 1], rows[j]);
                        }
                    } else if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        rows[j].parentNode.insertBefore(rows[j + 1], rows[j]);
                    }
                } else {
                    if (n === 6) {
                        if (parseFloat(x.innerHTML) < parseFloat(y.innerHTML)) {
                            rows[j].parentNode.insertBefore(rows[j + 1], rows[j]);
                        }
                    } else {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            rows[j].parentNode.insertBefore(rows[j + 1], rows[j]);
                        }
                    }
                }
            }
        }
        if (this.dir[n] === "asc") {
            this.dir[n] = "desc";
            rows[0].getElementsByTagName("TH")[n].classList.remove("sortable");
            rows[0].getElementsByTagName("TH")[n].classList.add("sortable-clk");
        } else {
            this.dir[n] = "asc";
            rows[0].getElementsByTagName("TH")[n].classList.remove("sortable-clk");
            rows[0].getElementsByTagName("TH")[n].classList.add("sortable");
        }
    }
}

productsTable = new ProductsTable();
window.onload = function () {
    productsTable.listProducts();
};