"use strict";
document.addEventListener("DOMContentLoaded", function() {
    var currentTab = 0; // Start at the first tab
    var tabs = document.getElementsByClassName("tab");
    var steps = document.getElementsByClassName("step");
    var prevBtn = document.getElementById("prevBtn");
    var nextBtn = document.getElementById("nextBtn");
    var loginBtn = document.getElementById("loginBtn");

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
    // document.getElementById('login_form').addEventListener('submit', function(event) {
    //     event.preventDefault(); // Prevent default form submission
    //     // Remove existing error messages at the beginning of validation
    //     var existingErrors = document.getElementsByClassName("error-msg");
    //     while (existingErrors.length > 0) {
    //         existingErrors[0].parentNode.removeChild(existingErrors[0]);
    //     }
    //     submitFormAjax(this); // Call the AJAX function
    // });
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

            // Submit the signup form via AJAX
            submitFormAjax(document.getElementById("signup_form"));
            return;
        }
        showTab(currentTab);
    };

    // Validation Logic
    function validateForm() {
        var valid = true;
        var inputs = tabs[currentTab].getElementsByTagName("input");

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
            }
            else {
                inputs[i].classList.remove("invalid");
            }
        }
        return valid;
    }

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

    // Ajax Form Submission (async looks more like php)
    // async function submitFormAjax(form) {
    //     const formData = new FormData(form);
    //     const submitButton = form.querySelector('button[type="submit"]');
    //     if (submitButton) {
    //         formData.append(submitButton.name, submitButton.value);
    //     }
    
    //     try {
    //         const response = await fetch(form.action, {
    //             method: 'POST',
    //             body: formData,
    //         });
    
    //         if (response.redirected) {
    //             // If the response is a redirect, follow it
    //             window.location.href = response.url;
    //             return;
    //         }
    
    //         const contentType = response.headers.get("content-type");
    //         const responseText = await response.text(); // Get raw text for debugging
    
    //         if (contentType && contentType.includes("application/json")) {
    //             try {
    //                 const data = JSON.parse(responseText); // Attempt to parse JSON
    
    //                 // Handle the JSON response from the server
    //                 if (!data.success) {
    //                     for (const key in data.errors) {
    //                         if (data.errors.hasOwnProperty(key)) {
    //                             const inputElement = form.querySelector(`[name="${key}"]`);
    //                             if (inputElement) {
    //                                 showErrorMessage(inputElement, data.errors[key]);
    //                             }
    //                         }
    //                     }
    //                 }
    //             } catch (jsonError) {
    //                 // If JSON parsing fails, handle the non-JSON response gracefully
    //                 console.error("Invalid JSON response:", responseText);
    //                 showErrorMessage(form, "Invalid JSON response from server.");
    //             }
    //         } else {
    //             // Handle non-JSON response
    //             if (response.ok) {
    //                 // If response is ok but not JSON, handle accordingly
    //                 console.log("Non-JSON response received:", responseText);
    //                 showErrorMessage(form, "Received unexpected response from server.");
    //             } else {
    //                 // Handle server errors that are not JSON
    //                 showErrorMessage(form, "Server Error: " + response.statusText);
    //             }
    //         }
    
    //     } catch (error) {
    //         // Enhanced error handling
    //         showErrorMessage(form, "Server Error: " + error.message);
    //     }
    // }       
});