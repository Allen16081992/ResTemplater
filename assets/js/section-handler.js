"use strict";
// Single Page Application
document.addEventListener('DOMContentLoaded', function () {
    // Select the navigation elements
    const logoLink = document.getElementById('logo');
    const sections = document.querySelectorAll('main section');
    const navLinks = document.querySelectorAll('nav a[data-section], span a[data-section], label a[data-section], button[data-section]');
    let activeLink = null; // keep track of the currently active link

    // Password visibility toggle
    const eye = document.querySelector('.toggle-eye i');
    const pwdID = document.getElementById('pwdField');

    // Date Selector
    const dateOptions = document.querySelectorAll('.date-options');

    // Resume
    const cvTab = document.querySelectorAll('#home .accordion');

    // Toggle visibility of sections based on the selected sectionId
    function paintSection(sectionId) {
        sections.forEach(section => {
            if (section.id === sectionId) {
                section.classList.replace('hidden', 'current');
            } else {
                section.classList.replace('current', 'hidden');
            }
        });
    }   

    // Event listener for navigation bar
    navLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default link action
            const sectionId = this.getAttribute('data-section'); // Get the data-section value

            // Paint the section based on the clicked link
            paintSection(sectionId);

            // Update active link across all related links
            updateActiveLink(sectionId);
        });
    });

    function updateActiveLink(sectionId) {
        // Remove 'current' class from all links
        navLinks.forEach(link => {
            if (link.getAttribute('data-section') === sectionId) {
                link.classList.add('current');
            } else {
                link.classList.remove('current');
            }
        });

        // Find the active navigation link and set it as the active link
        const activeNavLink = document.querySelector(`nav a[data-section='${sectionId}']`);
        if (activeNavLink) {
            if (activeLink) {
                activeLink.classList.remove('current');
            }
            activeNavLink.classList.add('current');
            activeLink = activeNavLink; // Update the globally active link
        }
    }

    // Event listener for company logo
    logoLink.addEventListener('click', function(event) {
        event.preventDefault();

        // Reset active link and show the home section
        if (activeLink) {
            activeLink.classList.remove('current');
            activeLink = null; // Ensure no link is marked as active
        }

        paintSection('home'); // Assume 'home' is the default section to show
    });

    // Password show/hide icon
    if (eye) {
        eye.addEventListener('click', () => {
            // Toggle the type attribute
            if (pwdID.type === "password") {
                pwdID.type = "text";
                eye.classList.replace('bx-low-vision', 'bx-show');
            } else {
                pwdID.type = 'password';
                eye.classList.replace('bx-show', 'bx-low-vision');
            }
        });
    }

    // Date Selector, Iterate through each group
    if (dateOptions) {
        dateOptions.forEach(group => {
            const daySelect = group.querySelector('#day-select');
            const monthSelect = group.querySelector('#month-select');
            const yearSelect = group.querySelector('#year-select');

            // Check if all select elements exist in the current group
            if (daySelect && monthSelect && yearSelect) {
                // Populate days
                for (let day = 1; day <= 31; day++) {
                    const formatDay = ('0' + day).slice(-2); // Ensure two digits
                    const dayOption = new Option(formatDay, formatDay);
                    daySelect.add(dayOption);
                }
                
                // Populate months
                for (let month = 1; month <= 12; month++) {
                    const formatMonth = ('0' + month).slice(-2); // Ensure two digits
                    const monthOption = new Option(formatMonth, formatMonth);
                    monthSelect.add(monthOption);
                }
                
                // Populate years
                const currentYear = new Date().getFullYear();
                const targetYear = 1954;
                for (let year = currentYear; year >= targetYear; year--) {
                    const yearOption = new Option(year, year);
                    yearSelect.add(yearOption);
                }
            }
        });
    }


    function toggleAccordion() {
        var panel = this.nextElementSibling;
        var isOpen = panel.style.maxHeight;
    
        // Close all panels within the "home" section
        Array.from(cvTab).forEach(function(accordion) {
            accordion.classList.remove("active");
            accordion.nextElementSibling.style.maxHeight = null;
        });
    
        // Open this panel if it was previously closed
        if (!isOpen) {
            this.classList.add("active");
            panel.style.maxHeight = panel.scrollHeight + "px";
        }
    }

    // Add click event listener to each accordion button in the "home" section
    if (cvTab) {
        cvTab.forEach(function(accordion) {
            accordion.addEventListener('click', toggleAccordion);
        });
    }
    
});

// Remove messages after a certain duration
// window.onload = function() {
//     var serverMsg = document.getElementById('server-msg');

//     // Set timeout to remove error message after 5 seconds
//     if (serverMsg) {
//         setTimeout(function() {
//             serverMsg.style.transition = 'opacity 0.3s ease'; 
//             serverMsg.style.opacity = '0'; 
//             setTimeout(function() {
//                 serverMsg.style.display = 'none'; 
//             }, 4500);
//         }, 5000); // 5000 milliseconds = 5 seconds
//     }
// };