"use strict";

/**
 * FINAL Wizard JS — compatible with your FINAL MARKUP (ids + data-steps)
 *
 * Goals:
 * - Wizard UI stays functional even if optional elements (progress/toast/summary) are missing.
 * - Start button is visible (relies on your existing CSS: .pw-card.is-active).
 * - No localStorage. No JS submit. Final submit is normal <button type="submit">.
 * - Auto-inject first Experience entry on hasExp=yes.
 * - Auto-inject first Education entry on studying=yes.
 * - Experience/Education submit as arrays:
 *     experience[0][job], experience[0][company], ...
 *     education[0][program], education[0][school], ...
 *
 * Required in markup:
 * - #pwBaseline, #pwStage
 * - .pw-card[data-step]
 * - #btnBack #btnNext #btnReset
 * - [data-action="start"] [data-action="saveExpOne"] [data-action="saveEduOne"]
 * - #expMount #eduMount
 * - #experienceFields #educationFields (hidden containers inside the <form>)
 * - #studyingVal #hasExpVal #expMoreVal #eduMoreVal (hidden inputs)
 */

(() => {
  const root = document.getElementById("pwBaseline");
  if (!root) return;

  if (root.dataset.pwInit) return;
  root.dataset.pwInit = "1";

  const stage = root.querySelector("#pwStage");
  if (!stage) return;

  const cards = Array.from(stage.querySelectorAll(".pw-card"));
  if (cards.length === 0) return;

  const btnBack = root.querySelector("#btnBack");
  const btnNext = root.querySelector("#btnNext");
  const btnReset = root.querySelector("#btnReset");
  if (!btnBack || !btnNext || !btnReset) return;

  // Optional
  const progressEl = root.querySelector("#pwProgress");
  const toast = root.querySelector("#pwToast");
  const summaryGrid = root.querySelector("#summaryGrid");

  // Hidden choice values
  const studyingVal = root.querySelector("#studyingVal");
  const hasExpVal = root.querySelector("#hasExpVal");
  const expMoreVal = root.querySelector("#expMoreVal");
  const eduMoreVal = root.querySelector("#eduMoreVal");

  // Dynamic mounts + submit containers
  const expMount = root.querySelector("#expMount");
  const eduMount = root.querySelector("#eduMount");
  const experienceFields = root.querySelector("#experienceFields");
  const educationFields = root.querySelector("#educationFields");
  if (!expMount || !eduMount || !experienceFields || !educationFields) return;

  // Inputs
  const fullNameEl = root.querySelector("#fullName");
  const headlineEl = root.querySelector("#headline");
  const emailEl = root.querySelector("#email");
  const cityEl = root.querySelector("#city");
  const countryEl = root.querySelector("#country");
  const websiteEl = root.querySelector("#website");
  const phoneEl = root.querySelector("#phone");

  const startBtn = root.querySelector('[data-action="start"]');
  const saveExpBtn = root.querySelector('[data-action="saveExpOne"]');
  const saveEduBtn = root.querySelector('[data-action="saveEduOne"]');
  if (!startBtn || !saveExpBtn || !saveEduBtn) return;

  // --------------------------
  // Progress chips (optional)
  // --------------------------
  const stepsSemantic = [
    { key: "basics", label: "Basics" },
    { key: "experience", label: "Experience" },
    { key: "education", label: "Education" },
    { key: "contact", label: "Contact" },
    { key: "review", label: "Review" },
    { key: "download", label: "Download" }
  ];

  const chips = [];
  if (progressEl) {
    progressEl.innerHTML = "";
    for (const s of stepsSemantic) {
      const chip = document.createElement("div");
      chip.className = "pw-stepchip";
      chip.dataset.pwchip = s.key;
      chip.innerHTML = `<span class="dot"></span><span>${escapeHtml(s.label)}</span>`;
      progressEl.appendChild(chip);
      chips.push(chip);
    }
  }

  function toSemanticBucket(stepKey) {
    if (["welcome", "basics", "studying"].includes(stepKey)) return "basics";
    if (["hasExp", "expOne", "expMore"].includes(stepKey)) return "experience";
    if (["eduOne", "eduMore"].includes(stepKey)) return "education";
    if (["contact"].includes(stepKey)) return "contact";
    if (["review"].includes(stepKey)) return "review";
    return "download";
  }

  function syncProgressChips(activeStepKey) {
    if (!chips.length) return;
    const bucket = toSemanticBucket(activeStepKey);
    const activeIdx = stepsSemantic.findIndex((s) => s.key === bucket);

    chips.forEach((chip, i) => {
      chip.classList.toggle("is-active", i === activeIdx);
      chip.classList.toggle("is-done", i < activeIdx);
    });
  }

  // --------------------------
  // Toast (optional)
  // --------------------------
  function showToast(msg = "Saved") {
    if (!toast) return;
    const spans = toast.querySelectorAll("span");
    if (spans.length >= 2) spans[1].textContent = msg;
    toast.classList.add("is-show");
    clearTimeout(showToast._t);
    showToast._t = setTimeout(() => toast.classList.remove("is-show"), 850);
  }

  // --------------------------
  // State + route
  // --------------------------
  const existingSteps = new Set(cards.map((c) => String(c.dataset.step || "")));

  const state = {
    studying: null, // "yes" | "no"
    hasExp: null,   // "yes" | "no"
    expMore: null,  // "yes" | "no"
    eduMore: null,  // "yes" | "no"
    expSaved: 0,
    eduSaved: 0,
    expCurrent: null, // current block in expMount (not yet moved to experienceFields)
    eduCurrent: null
  };

  let route = [];
  let index = 0;

  function buildRoute() {
    const r = ["welcome", "basics", "studying", "hasExp"];
    if (state.hasExp === "yes") r.push("expOne", "expMore");
    if (state.studying === "yes") r.push("eduOne", "eduMore");
    r.push("contact", "review", "download");

    route = r.filter((k) => existingSteps.has(k));
    if (index >= route.length) index = route.length - 1;
    if (index < 0) index = 0;
  }

  function currentStep() {
    return route[index] || "welcome";
  }

  function setActive(stepKey) {
    // IMPORTANT: use is-active class for wizard visibility
    cards.forEach((c) => c.classList.toggle("is-active", c.dataset.step === stepKey));

    updateNavButtons();
    syncProgressChips(stepKey);

    if (stepKey === "review") renderSummary();
  }

  function goTo(i) {
    index = Math.max(0, Math.min(i, route.length - 1));
    setActive(route[index]);
  }

  function next() {
    const step = currentStep();
    if (!validateStep(step)) return;

    // Loop back logic
    if (step === "expMore" && state.expMore === "yes") {
      ensureExpBlock(); // next blank entry
      state.expMore = null;
      if (expMoreVal) expMoreVal.value = "";
      clearChoiceGroup("expMore");
      const expOneIdx = route.indexOf("expOne");
      if (expOneIdx !== -1) goTo(expOneIdx);
      return;
    }

    if (step === "eduMore" && state.eduMore === "yes") {
      ensureEduBlock();
      state.eduMore = null;
      if (eduMoreVal) eduMoreVal.value = "";
      clearChoiceGroup("eduMore");
      const eduOneIdx = route.indexOf("eduOne");
      if (eduOneIdx !== -1) goTo(eduOneIdx);
      return;
    }

    goTo(index + 1);
  }

  function back() {
    goTo(index - 1);
  }

  function updateNavButtons() {
    const step = currentStep();

    btnBack.disabled = (step === "welcome");

    if (step === "welcome") {
      btnNext.textContent = "Continue";
      btnNext.disabled = true; // Start button advances from welcome
      btnNext.style.visibility = "visible";
      return;
    }

    if (step === "download") {
      btnNext.disabled = true;
      btnNext.style.visibility = "hidden";
      return;
    }

    btnNext.disabled = false;
    btnNext.style.visibility = "visible";
    btnNext.textContent = "Continue";
  }

  // --------------------------
  // Validation
  // --------------------------
  function required(v) {
    return v != null && String(v).trim().length > 0;
  }

  function markInvalid(el, on) {
    if (!el) return;
    el.style.borderColor = on ? "rgba(239,68,68,.85)" : "";
  }

  function isValidEmail(v) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
  }

  function isValidUrl(v) {
    const s = String(v || "").trim();
    if (!s) return true; // empty allowed

    try {
      const u = new URL(s.startsWith("http://") || s.startsWith("https://") ? s : "https://" + s);

      // Only allow http(s)
      if (!["http:", "https:"].includes(u.protocol)) return false;

      const host = u.hostname;

      // Allow localhost explicitly (optional; remove if you don't want it)
      if (host === "localhost") return true;

      // Reject single-label hosts like "ddd" or "intranet"
      if (!host.includes(".")) return false;

      // Basic: avoid trailing dot like "example.com."
      if (host.endsWith(".")) return false;

      return true;
    } catch {
      return false;
    }
  }

  function normalizeUrl(v) {
    const s = String(v || "").trim();
    if (!s) return "";

    // If scheme already present, keep it
    if (/^https?:\/\//i.test(s)) return s;

    // If user typed www.example.com or example.com
    return "https://" + s;
  }

  function pulseInvalid(el) {
    if (!el) return;
    const old = el.style.borderColor;
    el.style.borderColor = "rgba(239,68,68,.85)";
    el.focus({ preventScroll: false });
    setTimeout(() => (el.style.borderColor = old || ""), 850);
  }

  function validateCurrentExpBlock() {
    if (!state.expCurrent) return false;
    const job = state.expCurrent.querySelector('input[name$="[job]"]');
    const company = state.expCurrent.querySelector('input[name$="[company]"]');
    if (!required(job?.value)) { pulseInvalid(job); return false; }
    if (!required(company?.value)) { pulseInvalid(company); return false; }
    return true;
  }

  function validateCurrentEduBlock() {
    if (!state.eduCurrent) return false;
    const program = state.eduCurrent.querySelector('input[name$="[program]"]');
    const school = state.eduCurrent.querySelector('input[name$="[school]"]');
    if (!required(program?.value)) { pulseInvalid(program); return false; }
    if (!required(school?.value)) { pulseInvalid(school); return false; }
    return true;
  }

  function validateStep(step) {
    if (step === "welcome") return true;

    if (step === "basics") {
      if (!fullNameEl) return true;
      if (!required(fullNameEl.value)) { pulseInvalid(fullNameEl); return false; }
      return true;
    }

    if (step === "studying") {
      return !!state.studying;
    }

    if (step === "hasExp") {
      return !!state.hasExp;
    }

    if (step === "expOne") return state.expSaved >= 1;
    if (step === "expMore") return !!state.expMore;

    if (step === "eduOne") return state.eduSaved >= 1;
    if (step === "eduMore") return !!state.eduMore;

    if (step === "contact") {
      const email = emailEl.value.trim();
      const websiteRaw = websiteEl.value.trim();

      // Email required
      if (!email) {
        markInvalid(emailEl, true);
        emailEl.focus();
        return false;
      }

      // Email must be valid
      if (!isValidEmail(email)) {
        markInvalid(emailEl, true);
        emailEl.focus();
        return false;
      }
      markInvalid(emailEl, false);

      // Website optional, but if filled must be valid
      if (websiteRaw && !isValidUrl(websiteRaw)) {
        markInvalid(websiteEl, true);
        websiteEl.focus();
        return false;
      }

      // Normalize once accepted (only if filled)
      if (websiteRaw) {
        websiteEl.value = normalizeUrl(websiteRaw);
      }
      markInvalid(websiteEl, false);

      return true;
    }

    return true;
  }

  // --------------------------
  // Choice UI
  // --------------------------
  function clearChoiceGroup(groupName) {
    root.querySelectorAll(`.pw-choice[data-choice="${groupName}"]`)
      .forEach((el) => el.classList.remove("is-selected"));
  }

  function setChoice(groupName, value) {
    clearChoiceGroup(groupName);
    const el = root.querySelector(`.pw-choice[data-choice="${groupName}"][data-value="${value}"]`);
    if (el) el.classList.add("is-selected");
  }

  stage.addEventListener("click", (ev) => {
    const choice = ev.target.closest(".pw-choice");
    if (!choice) return;

    const group = choice.dataset.choice;
    const value = choice.dataset.value;

    if (group === "studying") {
      state.studying = value;
      if (studyingVal) studyingVal.value = value;
      setChoice("studying", value);

      buildRoute();

      // Auto-inject first education block on yes
      if (value === "yes") ensureEduBlock();

      showToast("Saved");
      next();
      return;
    }

    if (group === "hasExp") {
      state.hasExp = value;
      if (hasExpVal) hasExpVal.value = value;
      setChoice("hasExp", value);

      buildRoute();

      // Auto-inject first experience block on yes
      if (value === "yes") ensureExpBlock();

      showToast("Saved");
      next();
      return;
    }

    if (group === "expMore") {
      state.expMore = value;
      if (expMoreVal) expMoreVal.value = value;
      setChoice("expMore", value);
      showToast("Saved");
      next();
      return;
    }

    if (group === "eduMore") {
      state.eduMore = value;
      if (eduMoreVal) eduMoreVal.value = value;
      setChoice("eduMore", value);
      showToast("Saved");
      next();
      return;
    }
  });

  // --------------------------
  // Dynamic blocks (Experience/Education)
  // --------------------------
  function createExpBlock(i) {
    const wrap = document.createElement("div");
    wrap.className = "pw-exp-block";
    wrap.dataset.expIndex = String(i);

    wrap.innerHTML = `
      <div class="pw-row">
        <div>
          <label class="pw-label">Role *</label>
          <input class="pw-input" type="text" name="experience[${i}][job]" placeholder="e.g. Junior IT Support (Intern)" autocomplete="organization-title">
        </div>
        <div>
          <label class="pw-label">Company *</label>
          <input class="pw-input" type="text" name="experience[${i}][company]" placeholder="e.g. TechCorp" autocomplete="organization">
        </div>
      </div>

      <div class="pw-row">
        <div>
          <label class="pw-label">Start</label>
          <input class="pw-input" type="month" name="experience[${i}][start]">
        </div>
        <div>
          <label class="pw-label">End</label>
          <input class="pw-input" type="month" name="experience[${i}][end]">
        </div>
      </div>

      <div>
        <label class="pw-label">What did you do?</label>
        <textarea class="pw-textarea" name="experience[${i}][desc]" placeholder="Write 2–5 short lines."></textarea>
        <div class="pw-hint">Tip: press Enter after each responsibility.</div>
      </div>
    `;
    return wrap;
  }

  function createEduBlock(i) {
    const wrap = document.createElement("div");
    wrap.className = "pw-edu-block";
    wrap.dataset.eduIndex = String(i);

    wrap.innerHTML = `
      <div class="pw-row">
        <div>
          <label class="pw-label">Program / Degree *</label>
          <input class="pw-input" type="text" name="education[${i}][program]" placeholder="e.g. BSc Information Technology">
        </div>
        <div>
          <label class="pw-label">Institute *</label>
          <input class="pw-input" type="text" name="education[${i}][school]" placeholder="e.g. Hogeschool Rotterdam">
        </div>
      </div>

      <div class="pw-row">
        <div>
          <label class="pw-label">Start</label>
          <input class="pw-input" type="month" name="education[${i}][start]">
        </div>
        <div>
          <label class="pw-label">End</label>
          <input class="pw-input" type="month" name="education[${i}][end]">
        </div>
      </div>

      <div>
        <label class="pw-label">Optional note</label>
        <textarea class="pw-textarea" name="education[${i}][desc]" placeholder="Optional. One or two lines."></textarea>
      </div>
    `;
    return wrap;
  }

  function ensureExpBlock() {
    if (state.expCurrent) return;
    const i = state.expSaved;
    const block = createExpBlock(i);
    expMount.innerHTML = "";
    expMount.appendChild(block);
    state.expCurrent = block;
  }

  function ensureEduBlock() {
    if (state.eduCurrent) return;
    const i = state.eduSaved;
    const block = createEduBlock(i);
    eduMount.innerHTML = "";
    eduMount.appendChild(block);
    state.eduCurrent = block;
  }

  saveExpBtn.addEventListener("click", () => {
    ensureExpBlock();
    if (!validateCurrentExpBlock()) return;

    const block = state.expCurrent;
    expMount.innerHTML = "";
    experienceFields.appendChild(block);

    state.expCurrent = null;
    state.expSaved += 1;

    // must answer expMore again
    state.expMore = null;
    if (expMoreVal) expMoreVal.value = "";
    clearChoiceGroup("expMore");

    showToast("Role saved");

    const expMoreIdx = route.indexOf("expMore");
    if (expMoreIdx !== -1) goTo(expMoreIdx);
  });

  saveEduBtn.addEventListener("click", () => {
    ensureEduBlock();
    if (!validateCurrentEduBlock()) return;

    const block = state.eduCurrent;
    eduMount.innerHTML = "";
    educationFields.appendChild(block);

    state.eduCurrent = null;
    state.eduSaved += 1;

    state.eduMore = null;
    if (eduMoreVal) eduMoreVal.value = "";
    clearChoiceGroup("eduMore");

    showToast("Education saved");

    const eduMoreIdx = route.indexOf("eduMore");
    if (eduMoreIdx !== -1) goTo(eduMoreIdx);
  });

  // --------------------------
  // Buttons
  // --------------------------
  btnBack.addEventListener("click", back);
  btnNext.addEventListener("click", next);

  btnReset.addEventListener("click", () => {
    stage.reset();

    // Clear dynamic blocks + saved
    expMount.innerHTML = "";
    eduMount.innerHTML = "";
    experienceFields.innerHTML = "";
    educationFields.innerHTML = "";

    state.studying = null;
    state.hasExp = null;
    state.expMore = null;
    state.eduMore = null;
    state.expSaved = 0;
    state.eduSaved = 0;
    state.expCurrent = null;
    state.eduCurrent = null;

    if (studyingVal) studyingVal.value = "";
    if (hasExpVal) hasExpVal.value = "";
    if (expMoreVal) expMoreVal.value = "";
    if (eduMoreVal) eduMoreVal.value = "";

    ["studying", "hasExp", "expMore", "eduMore"].forEach(clearChoiceGroup);

    buildRoute();
    goTo(0);
  });

  startBtn.addEventListener("click", () => {
    buildRoute();
    const basicsIdx = route.indexOf("basics");
    goTo(basicsIdx !== -1 ? basicsIdx : 1);
  });

  // --------------------------
  // Review (optional)
  // --------------------------
  function renderSummary() {
    if (!summaryGrid) return;

    summaryGrid.innerHTML = "";

    const addCard = (title, val, mut) => {
      const el = document.createElement("div");
      el.className = "pw-summary-card";
      el.innerHTML = `
        <h3>${escapeHtml(title)}</h3>
        <div class="val">${escapeHtml(val)}</div>
        ${mut ? `<div class="mut" style="margin-top:.25rem">${escapeHtml(mut)}</div>` : ""}
      `;
      summaryGrid.appendChild(el);
    };

    const name = fullNameEl?.value?.trim() || "—";
    const head = headlineEl?.value?.trim() || "";
    const email = emailEl?.value?.trim() || "—";
    const city = cityEl?.value?.trim() || "";
    const country = countryEl?.value?.trim() || "";

    addCard("Name", name, head);
    addCard("Experience", `${state.expSaved} role(s)`, state.hasExp === "yes" ? "" : "Not included");
    addCard("Education", `${state.eduSaved} entry(s)`, state.studying === "yes" ? "" : "Not included");
    addCard("Contact", email, [city, country].filter(Boolean).join(", "));
  }

  function escapeHtml(str) {
    return String(str).replace(/[&<>"']/g, (s) => ({
      "&": "&amp;",
      "<": "&lt;",
      ">": "&gt;",
      '"': "&quot;",
      "'": "&#39;"
    }[s]));
  }

  // --------------------------
  // Init (guarantees Start visible)
  // --------------------------
  buildRoute();
  goTo(0); // welcome => .is-active => Start shows (CSS-controlled)
})();