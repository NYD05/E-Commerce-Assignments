// js/product_validate.js — friendly checks BEFORE upload (UX only).
// The PHP side repeats every check, because anyone can switch JavaScript off.
document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("productForm");
    if (!form) {
        return;
    }

    const imageInput = document.getElementById("product_image");
    const preview = document.getElementById("imagePreview");

    const ALLOWED = ["image/jpeg", "image/png", "image/gif", "image/webp"];
    const MAX_BYTES = 2 * 1024 * 1024; // 2 MB — must match PHP

    function clearErrors() {
        document.querySelectorAll(".js-error").forEach(function (el) {
            el.remove();
        });
    }

    function showError(input, message) {
        const error = document.createElement("div");
        error.className = "js-error";
        error.textContent = message;
        error.style.color = "red";
        error.style.marginTop = "5px";
        input.parentNode.appendChild(error);
    }

    // Returns an error message, or "" if the chosen file is fine (or no file chosen).
    function checkImage() {
        const file = imageInput.files[0];
        if (!file) {
            return "";
        }
        if (!ALLOWED.includes(file.type)) {
            return "Only JPG, PNG, GIF or WEBP images are allowed.";
        }
        if (file.size > MAX_BYTES) {
            return "Image is too large (max 2 MB).";
        }
        return "";
    }

    // Live preview + instant feedback when a file is picked.
    imageInput.addEventListener("change", function () {
        clearErrors();
        const problem = checkImage();

        if (problem) {
            showError(imageInput, problem);
            preview.style.display = "none";
            return;
        }

        const file = imageInput.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = "block";
        } else {
            preview.style.display = "none";
        }
    });

    form.addEventListener("submit", function (event) {
        clearErrors();
        let hasError = false;

        const price = parseFloat(document.getElementById("product_price").value);
        if (isNaN(price) || price <= 0) {
            showError(document.getElementById("product_price"), "Please enter a price greater than 0.");
            hasError = true;
        }

        const problem = checkImage();
        if (problem) {
            showError(imageInput, problem);
            hasError = true;
        }

        if (hasError) {
            event.preventDefault();
        }
    });
});
