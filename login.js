// Handle login form validation
document.addEventListener("DOMContentLoaded", () => {

    // Select form and error box
    const form = document.querySelector("form");
    const errorBox = document.getElementById("errorBox");

    form.addEventListener("submit", (e) => {
        let errors = [];

        // Read input values
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value.trim();

        // Validate email
        if (email === "") {
            errors.push("Email is required.");
        } else if (!/^\S+@\S+\.\S+$/.test(email)) {
            errors.push("Invalid email format.");
        }

        // Validate password
        if (password === "") {
            errors.push("Password is required.");
        } else if (password.length < 8) {
            errors.push("Password must be at least 8 characters.");
        }

        // Show errors if any
        if (errors.length > 0) {
            e.preventDefault();
            errorBox.innerHTML = errors.map(e => "\u274C " + e).join("<br>");
            errorBox.style.display = "block";
        }
    });
});