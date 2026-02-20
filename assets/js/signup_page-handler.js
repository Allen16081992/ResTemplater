"use strict";

(() => {
  /* -------------------- SIGNUP VISUAL GLOW -------------------- */
  const form = document.getElementById("signup_form");
  const visual = document.getElementById("signupVisual");

  if (form && visual) {
    const idleImg = 'url("assets/images/paperwitch_bold.png")';
    const burstImg = 'url("assets/images/paperwitch_glow.png")';

    // Preload images
    [idleImg, burstImg].forEach((src) => {
      const img = new Image();
      img.src = src.replace(/^url\(["']?(.+?)["']?\)$/, "$1");
    });

    // Set initial background
    visual.style.backgroundImage = idleImg;
    visual.style.filter = "brightness(1)";

    const isFilledInput = (el) => el.value.trim() !== "";

    const isValidSelect = (sel) => {
      const opt = sel.options[sel.selectedIndex];
      return sel.value !== "" && opt && !opt.disabled;
    };

    const dobIsValid = () => {
      const dateInput = form.querySelector('input[type="date"][required], input[type="date"]');
      const daySel = form.querySelector('select[name="day"]');
      const monthSel = form.querySelector('select[name="month"]');
      const yearSel = form.querySelector('select[name="year"]');
      const dateOk = dateInput ? isFilledInput(dateInput) : false;

      const selectsOk =
        !!daySel && !!monthSel && !!yearSel &&
        isValidSelect(daySel) && isValidSelect(monthSel) && isValidSelect(yearSel);

      return dateOk || selectsOk;
    };

    const allFilled = () => {
      // Required inputs, EXCEPT date (DOB special logic)
      const requiredInputs = [...form.querySelectorAll("input[required]")].filter(
        (el) => el.type !== "date"
      );
      const inputsOk = requiredInputs.every(isFilledInput);

      // Required selects, EXCEPT day/month/year (DOB special logic)
      const requiredSelects = [...form.querySelectorAll("select[required]")].filter(
        (sel) => !["day", "month", "year"].includes(sel.name)
      );
      const selectsOk = requiredSelects.every(isValidSelect);

      return inputsOk && selectsOk && dobIsValid();
    };

    const applyGlow = (on) => {
      visual.style.backgroundImage = on ? burstImg : idleImg;
      visual.style.filter = on ? "brightness(1.15)" : "brightness(1)";
    };

    const updateVisual = () => applyGlow(allFilled());
    form.addEventListener("input", updateVisual);
    form.addEventListener("change", updateVisual);

    // Initialize (for autofill)
    updateVisual();
  }

  /* -------------------- SIGNUP PASSWORD POLICY -------------------- */
  if (form) {
    const pwdInput = form.querySelector('input[name="pwd"]');

    if (pwdInput) {
      const rules = {
        min: document.getElementById("rule-min"),
        max: document.getElementById("rule-max"),
        visible: document.getElementById("rule-visible"),
      };

      const utfLen = (s) => [...s].length;
      const validate = (pwd) => {
        const len = utfLen(pwd);
        return {
          min: len >= 12,
          max: len <= 128,
          visible: /\S/u.test(pwd),
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
          Object.values(rules).forEach((el) => setState(el, "neutral"));
          return;
        }

        const r = validate(pwd);
        setState(rules.min, r.min ? "ok" : "bad");
        setState(rules.max, r.max ? "ok" : "bad");
        setState(rules.visible, r.visible ? "ok" : "bad");
      };

      pwdInput.addEventListener("input", update);
      update();
    }
  }

  /* -------------------- LOGIN VISUAL ROTATION -------------------- */
  const loginImg = document.getElementById("loginVisual");
  if (loginImg) {
    const BASE_PATH = "assets/images/";
    const IMAGES = [
      "paperwitch_dare.png",
      "paperwitch_dark.png",
      "paperwitch_despise.png",
      "paperwitch_resentful.png",
    ];

    // 48-hour deterministic rotation
    // const PERIOD_MS = 48 * 60 * 60 * 1000;
    const PERIOD_MS = 5 * 1000;
    const slot = Math.floor(Date.now() / PERIOD_MS);
    const file = IMAGES[slot % IMAGES.length];

    // Preload chosen image
    const pre = new Image();
    pre.src = `${BASE_PATH}${file}`;

    loginImg.style.backgroundImage = `url("${BASE_PATH}${file}")`;
  }
})();