document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("registerForm");

    if (!form) {
        return;
    }

    form.addEventListener("submit", function (event) {

        const email = document.getElementById("email").value.trim();
        const phone = document.getElementById("contact").value.trim();
        const password = document.getElementById("password").value;

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const phoneRegex = /^[0-9+\-\s]{7,15}$/;

        const passwordRegex =
            /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>/?]).{8,}$/;

        // Remove previous errors
        const oldErrors = document.querySelectorAll(".js-error");
        oldErrors.forEach(function (error) {
            error.remove();
        });

        let hasError = false;

        // Email validation
        if (!emailRegex.test(email)) {
            showError(
                document.getElementById("email"),
                "Please enter a valid email address."
            );

            hasError = true;
        }

        // Phone validation
        if (!phoneRegex.test(phone)) {
            showError(
                document.getElementById("contact"),
                "Please enter a valid contact number."
            );

            hasError = true;
        }

        // Password validation
        if (!passwordRegex.test(password)) {
            showError(
                document.getElementById("password"),
                "Password must be at least 8 characters and contain an uppercase letter, lowercase letter, number, and special character."
            );

            hasError = true;
        }

        if (hasError) {
            event.preventDefault();
        }

    });

    function showError(input, message) {

        const error = document.createElement("div");

        error.className = "js-error";
        error.textContent = message;

        error.style.color = "red";
        error.style.marginTop = "5px";

        input.parentNode.appendChild(error);
    }

});