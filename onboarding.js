// Handle habit creation validation + syncing with PHP
document.addEventListener("DOMContentLoaded", () => {

    // Select form and error box
    const form = document.querySelector("form");
    const errorBox = document.getElementById("errorBox");

    // Select duration and tracking buttons
    const durationButtons = document.querySelectorAll(".chip");
    const trackingButtons = document.querySelectorAll(".segment");

    // Hidden inputs 
    const durationInput = document.getElementById("durationInput");
    const trackingInput = document.getElementById("trackingInput");

    let selectedDuration = durationInput && durationInput.value ? durationInput.value : null;
    let selectedTracking = trackingInput && trackingInput.value ? trackingInput.value : null;

    // ===== Duration selection =====
    durationButtons.forEach(btn => {

        // (validation error)
        if (durationInput && durationInput.value && btn.dataset.duration === durationInput.value) {
            btn.classList.add("is-selected");
        }

        btn.addEventListener("click", () => {
            durationButtons.forEach(b => b.classList.remove("is-selected"));
            btn.classList.add("is-selected");
            selectedDuration = btn.dataset.duration;

            if (durationInput) {
                durationInput.value = selectedDuration;
            }
        });
    });

    // ===== Tracking selection =====
    trackingButtons.forEach(btn => {

        if (trackingInput && trackingInput.value && btn.dataset.tracking === trackingInput.value) {
            btn.classList.add("is-selected");
        }

        btn.addEventListener("click", () => {
            trackingButtons.forEach(b => b.classList.remove("is-selected"));
            btn.classList.add("is-selected");
            selectedTracking = btn.dataset.tracking;

            if (trackingInput) {
                trackingInput.value = selectedTracking;
            }
        });
    });

    // ===== Form validation on submit =====
    form.addEventListener("submit", (e) => {
        let errors = [];

        const title = document.getElementById("title").value.trim();

        // Validate title
        if (title === "") {
            errors.push("Habit name is required.");
        }

        // Validate duration selection
        if (!selectedDuration && (!durationInput || !durationInput.value)) {
            errors.push("Please select a duration.");
        }

        // Validate tracking selection
        if (!selectedTracking && (!trackingInput || !trackingInput.value)) {
            errors.push("Please select a tracking type.");
        }

        // Show errors if found
        if (errors.length > 0) {
            e.preventDefault();
            if (errorBox) {
                errorBox.innerHTML = errors.map(msg => "\u274C " + msg).join("<br>");
                errorBox.style.display = "block";
            }
        }
    });
});