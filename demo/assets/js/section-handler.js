"use strict";

(() => {
  // Defensive: if none of the features exist, do nothing
  const logoLink = document.getElementById('logo');
  const sections = document.querySelectorAll("main > section, main");
  const navLinks = document.querySelectorAll(
    "nav a[data-section], p a[data-section], div a[data-section], button[data-section]"
  );
  const pwdID = document.getElementById("pwdField");
  const eye = document.querySelector(".toggle-eye i");
  const serverMsg = document.getElementById("server-msg");

  if (
    sections.length === 0 &&
    navLinks.length === 0 &&
    !eye &&
    !serverMsg
  ) {
    return;
  }

  // Navigation elements
  let activeLink = null;

  // Section visibility
  function paintSection(sectionId) {
    sections.forEach((section) => {
      if (section.id === sectionId) {
        section.classList.replace("hidden", "current");
      } else {
        section.classList.replace("current", "hidden");
      }
    });
  }

  if (logoLink) {
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
  }

  function updateActiveLink(sectionId) {
    navLinks.forEach((link) => {
      link.classList.toggle("current", link.getAttribute("data-section") === sectionId);
    });

    const activeNavLink = document.querySelector(`nav a[data-section='${sectionId}']`);
    if (!activeNavLink) return;

    if (activeLink) activeLink.classList.remove("current");
    activeNavLink.classList.add("current");
    activeLink = activeNavLink;
  }

  // Navigation Bar
  if (navLinks.length) {
    navLinks.forEach((link) => {
      link.addEventListener("click", (event) => {
        event.preventDefault();
        const sectionId = link.getAttribute("data-section");
        if (!sectionId) return;

        paintSection(sectionId);
        updateActiveLink(sectionId);
      });
    });
  }

  // Password Eye
  if (eye && pwdID) {
    eye.addEventListener("click", () => {
      if (pwdID.type === "password") {
        pwdID.type = "text";
        eye.classList.replace("bx-low-vision", "bx-show");
      } else {
        pwdID.type = "password";
        eye.classList.replace("bx-show", "bx-low-vision");
      }
    });
  }

  // Remove messages after a certain duration
  if (serverMsg) {
    setTimeout(() => {
      serverMsg.style.transition = "opacity 0.3s ease";
      serverMsg.style.opacity = "0";
      setTimeout(() => {
        serverMsg.style.display = "none";
      }, 4500);
    }, 5000);
  }
})();