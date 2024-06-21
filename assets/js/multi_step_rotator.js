"use strict";
document.addEventListener("DOMContentLoaded", function() {
    var currentTab = 0; // Start at the first tab
    var tabs = document.getElementsByClassName("tab");
    var steps = document.getElementsByClassName("step");
    var prevBtn = document.getElementById("prevBtn");
    var nextBtn = document.getElementById("nextBtn");
    var errorMsg = document.getElementById("error-msg");

    // Display the current tab
    showTab(currentTab);

    function showTab(n) {
        // Hide all tabs and show only the current one
        for (var i = 0; i < tabs.length; i++) {
            tabs[i].style.display = "none";
        }
        tabs[n].style.display = "flex";

        // Configure the buttons
        prevBtn.style.display = (n === 0) ? "none" : "inline";
        nextBtn.innerHTML = (n === tabs.length - 1) ? "Account Maken" : "Verder";
        fixStepIndicator(n);
    }

    function fixStepIndicator(n) {
        for (var i = 0; i < steps.length; i++) {
            steps[i].classList.remove("finish");
        }
        steps[n].classList.add("finish");
    }

    // Setup event listeners for navigation buttons
    prevBtn.addEventListener('click', function() {
        nextPrev(-1);
    });
    nextBtn.addEventListener('click', function() {
        nextPrev(1);
    });

    function nextPrev(direction) {
        if (direction == 1 && !validateForm()) return; // Stop if form is not valid

        // Hide the current tab
        tabs[currentTab].style.display = "none";
        currentTab += direction;

        // Submit form or show next tab
        if (currentTab >= tabs.length) {
            const terms = document.getElementById("terms");
            if (!terms.checked) {
                terms.classList.add("invalid");
                currentTab--; // Decrement the tab index to stay on the last tab
                showTab(currentTab); // Show the last tab again
                return false;
            }

            document.getElementById("signup_form").submit();
            return;
        }
        showTab(currentTab);
    };

    // Validation Logic
    function validateForm() {
        var valid = true;
        var inputs = tabs[currentTab].getElementsByTagName("input");
        
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].value === "" && inputs[i].required) {
                inputs[i].classList.add("invalid");
                valid = false;
            } else {
                inputs[i].classList.remove("invalid");
            }

            // Enhanced password validation
            if (inputs[i].type === "password") {
                // Check if the password meets all requirements
                var passwordErrors = validatePassword(inputs[i].value);
                if (passwordErrors.length > 0) {
                    // Display the first error message from the list
                    errorMsg.textContent = passwordErrors[0];
                    errorMsg.style.display = "block";
                    inputs[i].classList.add("invalid");
                    valid = false;
                } else {
                    errorMsg.style.display = "none";
                }
            }
        }
        return valid;
    }

    // Separate function to handle password validation rules
    function validatePassword(password) {
        var errors = [];
        if (password.length < 8) {
            errors.push("Wachtwoord moet minstens 8 tekens lang zijn.");
        }
        if (!/[a-z]/.test(password)) {
            errors.push("Wachtwoord moet minstens één kleine letter hebben.");
        }
        if (!/[A-Z]/.test(password)) {
            errors.push("Wachtwoord moet minstens één hoofdletter hebben.");
        }
        if (!/[0-9]/.test(password)) {
            errors.push("Wachtwoord moet minstens één getal hebben.");
        }
        if (!/[!@#$%^&*()_+=[\]{};:'",<.>/?\\|~-]/.test(password)) {
            errors.push("Wachtwoord moet minstens één speciale teken hebben.");
        }
        
        return errors;
    }
});