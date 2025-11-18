"use strict";
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("signup_form");
  const visual = document.getElementById("signupVisual");
  const passwordInput = document.getElementById("password");
  const signupBtn = document.getElementById("signupBtn");

  const idleImg = 'url("assets/images/paperwitch_bold.png")';
  const burstImg = 'url("assets/images/paperwitch_glow.png")';

  // Preload images (prevents flicker)
  [idleImg, burstImg].forEach(src => {
    const img = new Image();
    img.src = src.replace(/^url\(["']?(.+?)["']?\)$/, "$1");
  });

  // Set initial background
  visual.style.backgroundImage = idleImg;

  // Helper: check if all required fields are filled
  const allFilled = () =>
    [...form.querySelectorAll("input[required]")].every(
      input => input.value.trim() !== ""
    );

  // React on hover over the Sign-Up button
  signupBtn.addEventListener("mouseenter", () => {
    if (allFilled()) {
      visual.style.backgroundImage = burstImg;
      visual.style.filter = "brightness(1.15)";
    }
  });

  signupBtn.addEventListener("mouseleave", () => {
    visual.style.filter = "brightness(1)";
    visual.style.backgroundImage = idleImg;
  });
});