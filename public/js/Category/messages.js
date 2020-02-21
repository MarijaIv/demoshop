class Messages {

    /**
     * Alert user that category can't be deleted.
     *
     * @param {object} data
     */
    deleteError(data) {
        message.closeConfirm();
        document.getElementById("popup").classList.toggle("popup-show");
        document.getElementById("message").value = data["message"];
    }

    /**
     * Default error.
     *
     * @param {object} data
     */
    displayError(data) {
        document.getElementById("popup").classList.remove("popup");
        document.getElementById("popup").classList.add("popup-show");
        document.getElementById("message").value = data["message"];
    }

    /**
     * Display confirm box.
     *
     * @param {string} message
     */
    displayConfirm(message) {
        document.getElementById("confirm").classList.remove("popup");
        document.getElementById("confirm").classList.add("popup-show");
        document.getElementById("messageConf").value = message;
    }

    /**
     * Display info box.
     *
     * @param {string} message
     */
    displayInfo(message) {
        document.getElementById("info").classList.remove("popup");
        document.getElementById("info").classList.add("popup-show");
        document.getElementById("messageInfo").value = message;
    }

    /**
     * Close popup window.
     */
    closePopup() {
        document.getElementById("popup").classList.remove("popup-show");
        document.getElementById("popup").classList.add("popup");
    }

    /**
     * Close confirm window.
     */
    closeConfirm() {
        document.getElementById("confirm").classList.remove("popup-show");
        document.getElementById("confirm").classList.add("popup");
    }

    /**
     * Close info window.
     */
    closeInfo() {
        document.getElementById("info").classList.remove("popup-show");
        document.getElementById("info").classList.add("popup");
    }
}

message = new Messages();