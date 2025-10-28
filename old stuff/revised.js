"use strict";
// Single Page Application
document.addEventListener('DOMContentLoaded', function () {
    // Select the navigation elements
    const nav = document.querySelector('nav'); // <===
    const logoLink = document.getElementById('logo'); // <===
    const sections = document.querySelectorAll('main section'); // <===
    const signupLink = document.querySelector('main section#login a[data-section="sign_up"]'); // <===
    const signupAction = document.querySelector('main section#home .grid-container .box a[data-section="sign_up"]'); // <===
    let activeLink = null; // keep track of the currently active link

    // Toggle visibility of sections based on the selected sectionId
    function paintSection(sectionId) { // <===
        // Iterate over all sections
        sections.forEach(section => {
            if (section.id === sectionId) {
                // Show the selected section and add 'current' class
                section.classList.remove('hidden');
                section.classList.add('current');
            } else {
                // Hide other sections and remove 'current' class
                section.classList.add('hidden');
                section.classList.remove('current');
            }
        });
    }    

    // Event listener for company logo
    logoLink.addEventListener('click', function (event) { // <===
        event.preventDefault();
        
        // If there is an active link, remove the 'current' class and reset the variable
        if (activeLink) {
            activeLink.classList.remove('current');
            activeLink = null; // Reset active link as no link should be active
        }

        // Call the function to show the home section
        const sectionId = 'home';
        paintSection(sectionId);
    });

    // Event listener for navigation bar
    nav.addEventListener('click', function (event) { // <===
        if (!(event.target.id === 'logout')) {
            event.preventDefault();
    
            const sectionId = event.target.getAttribute('data-section');
            paintSection(sectionId);
    
            if (activeLink) {
                activeLink.classList.remove('current');
            }
            event.target.classList.add('current');
            activeLink = event.target; // Update the currently active link
        }
    });

    // Verify if we can signup. (if not, then its no homepage)
    if (signupLink || signupAction) { // <===
        // Event listener for account creation
        signupLink.addEventListener('click', function (event) {
            event.preventDefault();
            const sectionId = 'sign_up'; // Show sign up page
            paintSection(sectionId);
        });
        signupAction.addEventListener('click', function (event) {
            event.preventDefault();
            const sectionId = 'sign_up'; // Show sign up page
            paintSection(sectionId);
        });
    }
});