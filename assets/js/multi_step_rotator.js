"use strict";
document.addEventListener("DOMContentLoaded", function() {
    var currentTab = 0; // Start at the first tab
    var tabs = document.getElementsByClassName("tab");
    var steps = document.getElementsByClassName("step");
    var prevBtn = document.getElementById("prevBtn");
    var nextBtn = document.getElementById("nextBtn");
    var loginBtn = document.getElementById("loginBtn");

    /* ───────────────────────────────────── */
    /*              NAVIGATION               */
    /* ───────────────────────────────────── */

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

        // Toggle button classes
        if (n === tabs.length - 1) {
            nextBtn.classList.remove("is-link");
            nextBtn.classList.add("is-success", "is-outlined");
        } else {
            nextBtn.classList.remove("is-success", "is-outlined");
            nextBtn.classList.add("is-link");
        } 

        fixStepIndicator(n);
    }

    function fixStepIndicator(n) {
        for (var i = 0; i < steps.length; i++) {
            steps[i].classList.remove("finish");
        }
        steps[n].classList.add("finish");
    }

    /* ───────────────────────────────────── */
    /*      SIGNUP WIZARD BUTTON EVENTS      */
    /* ───────────────────────────────────── */

    if (prevBtn || nextBtn) {
        prevBtn.addEventListener('click', function() {
            nextPrev(-1);
        });
        nextBtn.addEventListener('click', function() {
            nextPrev(1);
        });
    }

    /* ───────────────────────────────────── */
    /*         MULTI-STEP FORM LOGIC         */
    /* ───────────────────────────────────── */

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
                showErrorMessage(terms, "Lees ons privacybeleid en algemene voorwaarden");
                currentTab--; // Decrement the tab index to stay on the last tab
                showTab(currentTab); // Show the last tab again
                return false;
            }

            return;
        }
        showTab(currentTab);
    };

    /* ──────────────────────────────────────── */
    /*          SIGNUP FORM VALIDATION          */
    /* ──────────────────────────────────────── */

    function validateForm() {
        var valid = true;
        var inputs = tabs[currentTab].getElementsByTagName("input");
        var selects = tabs[currentTab].getElementsByTagName("select");

        // Remove existing error messages at the beginning of validation
        var existingErrors = tabs[currentTab].getElementsByClassName("error-msg");
        while (existingErrors.length > 0) {
            existingErrors[0].parentNode.removeChild(existingErrors[0]);
        }
        
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].value === "" && inputs[i].required) {
                inputs[i].classList.add("invalid");
                showErrorMessage(inputs[i], "Dit veld is verplicht.");
                valid = false;
            } else if (inputs[i].type === "email") {
                // Email-specific validation
                if (!validateEmail(inputs[i].value)) {
                    inputs[i].classList.add("invalid");
                    showErrorMessage(inputs[i], "Vul een geldig e-mailadres in.");
                    valid = false;
                }
            } else if (inputs[i].type === "password") {
                // Enhanced password validation
                var passwordErrors = validatePassword(inputs[i].value);
                if (passwordErrors.length > 0) {
                    inputs[i].classList.add("invalid");
                    showErrorMessage(inputs[i], passwordErrors[0]);
                    valid = false;
                }
            } else {
                inputs[i].classList.remove("invalid");
            }
        }

        let invalidSelect = Array.from(selects).some(select => 
            select.selectedIndex === 0 || select.options[select.selectedIndex].disabled
        );
    
        if (invalidSelect) {
            showErrorMessage(selects[0], "Selecteer een geldige optie."); // Shows message under first <select>
            valid = false;
        }

        return valid;
    }

    /* ───────────────────────────────────── */
    /*          LOGIN FORM VALIDATION        */
    /* ───────────────────────────────────── */

    if (loginBtn) {
        loginBtn.addEventListener('click', function(event) {
            event.preventDefault();
    
            // Remove existing error messages
            var existingErrors = document.getElementsByClassName("error-msg");
            while (existingErrors.length > 0) {
                existingErrors[0].parentNode.removeChild(existingErrors[0]);
            }
    
            var inputs = document.querySelectorAll("#login_form input[required]");
            var valid = true;
    
            inputs.forEach(function(input) {
                if (input.value.trim() === "") {
                    input.classList.add("invalid");
                    showErrorMessage(input, "Dit veld is verplicht.");
                    valid = false;
                } else if (input.type === "email" && !validateEmail(input.value)) {
                    input.classList.add("invalid");
                    showErrorMessage(input, "Vul een geldig e-mailadres in.");
                    valid = false;
                } else {
                    input.classList.remove("invalid");
                }
            });
    
            if (valid) {
                document.getElementById("login_form").submit();
            }
        });
    }

    /* ───────────────────────────────────── */
    /*          UTILITY FUNCTIONS            */
    /* ───────────────────────────────────── */
    
    function showErrorMessage(inputElement, message) {
        var errorMsg = document.createElement("span");
        errorMsg.className = "error-msg";
        errorMsg.textContent = message;
        errorMsg.style.display = "block";
        inputElement.parentNode.insertBefore(errorMsg, inputElement.nextSibling);
    }

    function validateEmail(email) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
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