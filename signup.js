// Handle signup form validation
document.addEventListener("DOMContentLoaded", () => {

    const form = document.querySelector("form");
    const errorBox = document.getElementById("errorBox");

    form.addEventListener("submit", (e) => {
        let errors = [];

        const fullName = document.getElementById("fullName").value.trim();
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value.trim();
        const confirmPassword = document.getElementById("confirmPassword").value.trim();

        // Validate name
        if (fullName === "") {
            errors.push("Full name is required.");
        }

        // Validate email
        if (email === "") {
            errors.push("Email is required.");
        } else if (!/^\S+@\S+\.\S+$/.test(email)) {
            errors.push("Invalid email format.");
        }

        // Validate password strength
        if (password === "") {
            errors.push("Password is required.");
        } else {

            if (password.length < 8) {
                errors.push("Password must be at least 8 characters.");
            }

            if (!/[A-Z]/.test(password)) {
                errors.push("Password must contain at least one uppercase letter.");
            }

            if (!/[a-z]/.test(password)) {
                errors.push("Password must contain at least one lowercase letter.");
            }

            if (!/[0-9]/.test(password)) {
                errors.push("Password must contain at least one number.");
            }

            if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                errors.push("Password must contain at least one special character.");
            }
        }

        // Confirm password
        if (confirmPassword === "") {
            errors.push("Confirm password is required.");
        } else if (password !== confirmPassword) {
            errors.push("Passwords do not match.");
        }

        // Show errors
        if (errors.length > 0) {
            e.preventDefault();
            errorBox.innerHTML = errors.map(msg => "\u274C " + msg).join("<br>");
            errorBox.style.display = "block";
        }
    });
});
