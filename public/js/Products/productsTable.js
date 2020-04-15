class ProductsTable {
    /**
     * Sorting direction for title, brand, category,
     * short description and price.
     *
     * @type {string[]}
     */
    dir = ["", "asc", "", "asc", "asc", "asc", "asc"];
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

    checkboxSubmit(sku, url)
    {
        let form = document.getElementById("productsTable");
        form.setAttribute('action', url);
        let postVar = document.createElement('input');
        postVar.setAttribute('name', 'sku');
        postVar.setAttribute('type', 'hidden');
        postVar.setAttribute('value', sku);
        form.appendChild(postVar);
        form.submit();
    }
}


productsTable = new ProductsTable();