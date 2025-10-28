"use strict";
document.addEventListener('DOMContentLoaded', function () {
    // Navigation elements
    let activeLink = null;
    const logoLink = document.getElementById('logo');
    const sections = document.querySelectorAll('main, section');
    const navLinks = document.querySelectorAll('nav a[data-section], span a[data-section], label a[data-section], button[data-section]');

    // Section visibility
    function paintSection(sectionId) {
        sections.forEach(section => {
            if (section.id === sectionId) {
                section.classList.replace('hidden', 'current');
            } else {
                section.classList.replace('current', 'hidden');
            }
        });
    }

    // Logo Homepage
    logoLink.addEventListener('click', function(event) {
        event.preventDefault();

        // Reset active link and show the home section
        if (activeLink) {
            activeLink.classList.remove('current');
            activeLink = null; // Ensure no link is marked as active
        }
        
        paintSection('home'); // Assume 'home' is the default section to show
    });

    // Navigation Bar
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
        navLinks.forEach(link => {
            if (link.getAttribute('data-section') === sectionId) {
                link.classList.add('current');
            } else {
                link.classList.remove('current');
            }
        });

        // Find the active nav link and set it as the active link
        const activeNavLink = document.querySelector(`nav a[data-section='${sectionId}']`);
        if (activeNavLink) {
            if (activeLink) {
                activeLink.classList.remove('current');
            }
            activeNavLink.classList.add('current');
            activeLink = activeNavLink; 
        }
    }

    ////////////////// Accordions /////////////////////
    const tab = document.querySelectorAll('#home .accordion');
    const pwdID = document.getElementById('pwdField');

    function toggleAccordion() {
        var panel = this.nextElementSibling;
        var isOpen = panel.style.maxHeight;
    
        // Close all panels within the "home" section
        Array.from(tab).forEach(function(accordion) {
            accordion.classList.remove("active");
            accordion.nextElementSibling.style.maxHeight = null;
        });
    
        // Open this panel if it was previously closed
        if (!isOpen) {
            this.classList.add("active");
            panel.style.maxHeight = panel.scrollHeight + "px";
        }
    }
    
    if (tab) { // Accordion Tabs
        tab.forEach(function(accordion) {
            accordion.addEventListener('click', toggleAccordion);
        });
    }

    ////////////////////// 🆕 Tabs Interface (For Resume Builder) 🆕 //////////////////////
    const tabButtons = document.querySelectorAll('.tab-buttons button');
    const tabSections = document.querySelectorAll('.tab-content .tab-section');

    // function switchTab(tabId) {
    //     tabSections.forEach(section => {
    //         section.classList.toggle('current', section.id === tabId);
    //         section.classList.toggle('hidden', section.id !== tabId);
    //     });
    // }
    function switchTab(tabId) {
        tabSections.forEach(section => {
            if (section.id === tabId) {
                section.classList.add('fade-in');
                section.classList.remove('hidden');
            } else {
                section.classList.remove('fade-in');
                section.classList.add('hidden');
            }
        });
    }

    tabButtons.forEach(button => {
        button.addEventListener('click', function () {
            const tabId = this.getAttribute('data-tab');
            switchTab(tabId);
        });
    });

    // Set default active tab (First tab is shown by default)
    switchTab('contact');

    ///////////////// Miscellaneous Effects ////////////////////
    const eye = document.querySelector('.toggle-eye i');
    const selectCv = document.getElementById('selectCv');
    const dateOptions = document.querySelectorAll('.date-options');

    if (eye) { // Password Eye
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
    
    if (selectCv) { // Resume Selector
        selectCv.addEventListener('change', function() {
            this.form.submit(); // Submit the form that this select belongs to
        });
    }
    
    if (dateOptions) { // Date Options
        dateOptions.forEach(group => {
            const daySelect = group.querySelector('.day-select');
            const monthSelect = group.querySelector('.month-select');
            const yearSelect = group.querySelector('.year-select');

            // Check if all date elements exist in the current group
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