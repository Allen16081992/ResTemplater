"use strict";
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("signup_form");
  const visual = document.getElementById("signupVisual");
  const idleImg  = 'url("assets/images/paperwitch_bold.png")';
  const burstImg = 'url("assets/images/paperwitch_glow.png")';

  // Preload images
  [idleImg, burstImg].forEach(src => {
    const img = new Image();
    img.src = src.replace(/^url\(["']?(.+?)["']?\)$/, "$1");
  });

  // Set initial background
  visual.style.backgroundImage = idleImg;
  visual.style.filter = "brightness(1)";

  // --- Helpers -------------------------------------------------

  const isFilledInput = (el) => el.value.trim() !== "";

  const isValidSelect = (sel) => {
    const opt = sel.options[sel.selectedIndex];
    return sel.value !== "" && opt && !opt.disabled;
  };

  // DOB is valid if:
  // - date input filled OR
  // - day+month+year selects are all valid
  const dobIsValid = () => {
    const dateInput = form.querySelector('input[type="date"][required], input[type="date"]');

    const daySel = form.querySelector('select[name="day"]');
    const monthSel = form.querySelector('select[name="month"]');
    const yearSel = form.querySelector('select[name="year"]');

    const dateOk = dateInput ? isFilledInput(dateInput) : false;

    const selectsOk =
      daySel && monthSel && yearSel &&
      isValidSelect(daySel) && isValidSelect(monthSel) && isValidSelect(yearSel);

    // If you have BOTH on the page, accept either method:
    return dateOk || selectsOk;
  };

  // All required fields except DOB fields must be filled.
  // Then DOB must be valid (via dobIsValid).
  const allFilled = () => {
    // Required inputs, EXCEPT date (because DOB has special logic)
    const requiredInputs = [...form.querySelectorAll("input[required]")].filter(
      el => el.type !== "date"
    );

    const inputsOk = requiredInputs.every(isFilledInput);

    // Required selects, EXCEPT day/month/year (because DOB has special logic)
    const requiredSelects = [...form.querySelectorAll("select[required]")].filter(
      sel => !["day", "month", "year"].includes(sel.name)
    );

    const selectsOk = requiredSelects.every(isValidSelect);

    return inputsOk && selectsOk && dobIsValid();
  };

  const applyGlow = (on) => {
    visual.style.backgroundImage = on ? burstImg : idleImg;
    visual.style.filter = on ? "brightness(1.15)" : "brightness(1)";
  };

  const updateVisual = () => applyGlow(allFilled());

  // --- Events --------------------------------------------------

  form.addEventListener("input", updateVisual);
  form.addEventListener("change", updateVisual); // required for selects

  // Initialize (for autofill)
  updateVisual();

  //-------------------Password Policy----------------------------
  const pwdInput = form.querySelector('input[name="pwd"]');
  if (!pwdInput) return;

  const rules = {
    min: document.getElementById("rule-min"),
    max: document.getElementById("rule-max"),
    visible: document.getElementById("rule-visible")
  };

  const utfLen = (s) => [...s].length;

  const validate = (pwd) => {
    const len = utfLen(pwd);
    return {
      min: len >= 12,
      max: len <= 128,
      visible: /\S/u.test(pwd)
    };
  };

  const setState = (el, state) => {
    if (!el) return;
    el.classList.toggle("ok", state === "ok");
    el.classList.toggle("bad", state === "bad");
    el.classList.toggle("neutral", state === "neutral");
  };

  const update = () => {
    const pwd = pwdInput.value;
    if (pwd.length === 0) {
      Object.values(rules).forEach(el => setState(el, "neutral"));
      return;
    }
    const r = validate(pwd);
    setState(rules.min, r.min ? "ok" : "bad");
    setState(rules.max, r.max ? "ok" : "bad");
    setState(rules.visible, r.visible ? "ok" : "bad");
  };

  pwdInput.addEventListener("input", update);
  update();

  //---------------------LOGIN PAGE---------------------

  // Login page helper
  const loginImg = document.getElementById("loginVisual");
  if (!loginImg) return;

  // Configure
  const BASE_PATH = 'assets/images/';
  const IMAGES = [
    "paperwitch_dare.png",
    "paperwitch_dark.png",
    "paperwitch_despise.png",
    "paperwitch_resentful.png"
  ];

  // 48-hour deterministic rotation
  // const PERIOD_MS = 48 * 60 * 60 * 1000;
  const PERIOD_MS = 5 * 1000; // 5 seconds for testing
  const slot = Math.floor(Date.now() / PERIOD_MS);
  const file = IMAGES[slot % IMAGES.length];
  const bg = `url("${BASE_PATH}${file}")`;

  // Preload chosen image (avoids first-paint pop on some browsers)
  const pre = new Image();
  pre.src = `${BASE_PATH}${file}`;

  // Apply background (matches your signup script approach)
  loginImg.style.backgroundImage = bg;
});