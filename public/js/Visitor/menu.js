var Demoshop = Demoshop || {};
Demoshop.Visitor = Demoshop.Visitor || {};

Demoshop.Visitor.Menu = class {
    /**
     * On click expand category with subcategories.
     *
     * @param {int} id
     */
    expand(id) {
        let parent = document.getElementById(id), i;
        let child = parent.parentElement.querySelector(".child, .active");

        if (child.classList.contains("child")) {
            child.classList.remove("child");
            child.classList.add("active");
        } else {
            if (child.parentElement.querySelector(".active")) {
                let activeChildren = child.parentElement.getElementsByClassName("active");

                for (i = activeChildren.length - 1; i >= 0; i--) {
                    activeChildren[i].classList.add("child");
                    activeChildren[i].classList.remove("active");
                }
            }

            child.classList.remove("active");
            child.classList.add("child");
        }
    }

    /**
     * On change submit form.
     */
    submitForm() {
        let form = document.getElementById("configuration");
        form.submit();
    }
};

Demoshop.Visitor.Menu.menu = new Demoshop.Visitor.Menu();