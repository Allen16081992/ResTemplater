"use strict";
// Single Page Application
document.addEventListener('DOMContentLoaded', function () {
    // Select the navigation bar element
    const nav = document.querySelector('nav');

    // Select the logo link using its ID
    const logoLink = document.getElementById('logo');

    // Select all sections
    const sections = document.querySelectorAll('main section');

    // Select the account creation link using its data-section attribute
    // const signupLink = document.querySelector('main section#login a[data-section="sign_up"]');

    // Toggle visibility of sections based on the selected sectionId
    function paintSection(sectionId) {
        // Remove 'current' class from all sections
        sections.forEach(section => section.classList.remove('current'));

        // Add 'current' class to the selected section
        const selectedSection = document.getElementById(sectionId);
        if (selectedSection) {
            selectedSection.classList.add('current');
        }

        // Hide all sections
        sections.forEach(section => section.classList.toggle('hidden', section.id !== sectionId));
    }

    // Event listener for company logo
    logoLink.addEventListener('click', function (event) {
        event.preventDefault();
        nav.querySelectorAll('a').forEach(link => link.classList.remove('current'));
        const sectionId = 'home'; // Assuming 'home' is the ID of the section you want to show
        paintSection(sectionId);
    });

    // Event listener for navigation bar
    nav.addEventListener('click', function (event) {
        // Remove 'current' class from all navigation links
        nav.querySelectorAll('a').forEach(link => link.classList.remove('current'));

        if (!(event.target.id === 'logout')) {
            // Handle navigation within the single-page application
            event.preventDefault();
            const sectionId = event.target.getAttribute('data-section');
            paintSection(sectionId);

            // Add 'current' class to the clicked navigation link
            event.target.classList.add('current');
        }
    });

    // Verify if we can signup. (if not, then its no homepage)
    // if (signupLink) {
    //     // Event listener for account creation
    //     signupLink.addEventListener('click', function (event) {
    //         event.preventDefault();
    //         const sectionId = 'sign_up'; // Assuming 'sign_up' is the ID of the section you want to show
    //         paintSection(sectionId);
    //     });
    // }

    // Display the 'home' section when the page loads
    // We call the first section of pages 'home' to indicate the main or most important content.
    paintSection('home');
});

// JavaScript to remove messages after a certain duration
window.onload = function() {
    var serverMsg = document.getElementById('server-msg');

    // Set timeout to remove error message after 5 seconds
    if (serverMsg) {
        setTimeout(function() {
            serverMsg.style.transition = 'opacity 0.3s ease'; 
            serverMsg.style.opacity = '0'; 
            setTimeout(function() {
                serverMsg.style.display = 'none'; 
            }, 4500);
        }, 5000); // 5000 milliseconds = 5 seconds
    }
};