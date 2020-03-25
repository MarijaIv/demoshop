class AddEditProduct {

    loadImage(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById("imgPlaceHolder").setAttribute("src", e.target.result + "");
            };
            reader.readAsDataURL(input.files[0]);
            document.getElementById("imgPlaceHolder").style.opacity = '1';
        }
    }
}

addEditProduct = new AddEditProduct();