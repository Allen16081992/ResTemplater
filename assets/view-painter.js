"use strict"; // Dhr. Allen Pieter
// Single Page Application
document.addEventListener('DOMContentLoaded', function () {
    // Select the navigation bar element
    const nav = document.querySelector('nav');

    // Select the logo link using its ID
    const logoLink = document.getElementById('home');

    // Select all sections
    const sections = document.querySelectorAll('main section');

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

    // Event delegation for navigation buttons
    nav.addEventListener('click', function (event) {
        if (event.target.tagName === 'A') {
            event.preventDefault();
            const sectionId = event.target.getAttribute('data-section');
            paintSection(sectionId);
        }
    });

    // Event listener for the logo link
    logoLink.addEventListener('click', function (event) {
        event.preventDefault();
        const sectionId = 'home'; // Assuming 'home' is the ID of the section you want to show
        paintSection(sectionId);
    });

    // Display the 'home' section when the page loads
    // We call the first section of pages 'home' to indicate the main or most important content.
    paintSection('home');
});