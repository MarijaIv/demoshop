var Demoshop = Demoshop || {};
Demoshop.CategoryPopups = Demoshop.CategoryPopups || {};

Demoshop.CategoryPopups.Popup = class {
    /**
     * Remove all displayed popups.
     */
    removePopups() {
        if(document.getElementById("title").classList.contains("required")) {
            document.getElementById("title").classList.add("input-right");
            document.getElementById("title").classList.remove("required");
        }

        if(document.getElementById("code").classList.contains("required")) {
            document.getElementById("code").classList.add("input-right");
            document.getElementById("code").classList.remove("required");
        }

        if(document.getElementById("description").classList.contains("ta-required")) {
            document.getElementById("description").classList.add("ta-right");
            document.getElementById("description").classList.remove("ta-required");
        }

        if(document.getElementById("popupRequiredTitle").classList.contains("show")) {
            document.getElementById("popupRequiredTitle").classList.toggle("show");
        }

        if(document.getElementById("popupRequiredCode").classList.contains("show")) {
            document.getElementById("popupRequiredCode").classList.toggle("show");
        }

        if(document.getElementById("popupRequiredDesc").classList.contains("show")) {
            document.getElementById("popupRequiredDesc").classList.toggle("show");
        }
    }

    /**
     * Display popups for required fields.
     */
    addPopups() {
        if (document.getElementById("title").value === "") {
            document.getElementById("title").classList.remove("input-right");
            document.getElementById("title").classList.add("required");
            if (!document.getElementById("popupRequiredTitle").classList.contains("show")) {
                document.getElementById("popupRequiredTitle").classList.toggle("show");
            }
        }

        if (document.getElementById("code").value === "") {
            document.getElementById("code").classList.remove("input-right");
            document.getElementById("code").classList.add("required");
            if (!document.getElementById("popupRequiredCode").classList.contains("show")) {
                document.getElementById("popupRequiredCode").classList.toggle("show");
            }
        }

        if (document.getElementById("description").value === "") {
            document.getElementById("description").classList.remove("ta-right");
            document.getElementById("description").classList.add("ta-required");
            if (!document.getElementById("popupRequiredDesc").classList.contains("show")) {
                document.getElementById("popupRequiredDesc").classList.toggle("show");
            }
        }
    }

    /**
     * Remove popup when required field is filled out.
     */
    filledOut() {
        let focusedElement = document.activeElement;

        if(focusedElement.id === "title" && document.getElementById("popupRequiredTitle").classList.contains("show")) {
            document.getElementById("popupRequiredTitle").classList.toggle("show");
            focusedElement.classList.remove("required");
            focusedElement.classList.add("input-right");
        }

        if(focusedElement.id === "code" && document.getElementById("popupRequiredCode").classList.contains("show")) {
            document.getElementById("popupRequiredCode").classList.toggle("show");
            focusedElement.classList.remove("required");
            focusedElement.classList.add("input-right");
        }

        if(focusedElement.id === "description" && document.getElementById("popupRequiredDesc").classList.contains("show")) {
            document.getElementById("popupRequiredDesc").classList.toggle("show");
            focusedElement.classList.remove("ta-required");
            focusedElement.classList.add("ta-right");
        }
    }
};

window.Demoshop.CategoryPopups.popup = new Demoshop.CategoryPopups.Popup();