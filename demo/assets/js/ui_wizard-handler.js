"use strict";

/** FINAL Wizard JS  */
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

  // Skills
  const skillsContainer = root.querySelector("#skills");
  const addSkillBtn = root.querySelector("#add-skill");
  //const skillTemplateRow = skillsContainer?.querySelector(".skill-row") || null;

  // Inputs
  const fullNameEl = root.querySelector("#fullname");
  const headlineEl = root.querySelector("#headline");
  const emailEl = root.querySelector("#email");
  const cityEl = root.querySelector("#city");
  const countryEl = root.querySelector("#country");
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
    { key: "skills", label: "Skills" },
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
    if (["skills"].includes(stepKey)) return "skills";
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
    studying: null,
    hasExp: null,
    expMore: null,
    eduMore: null,
    expSaved: 0,
    eduSaved: 0,
    expCurrent: null,
    eduCurrent: null
  };

  let route = [];
  let index = 0;

  function buildRoute() {
    const r = ["welcome", "basics", "studying", "hasExp"];
    if (state.hasExp === "yes") r.push("expOne", "expMore");
    if (state.studying === "yes") r.push("eduOne", "eduMore");
    r.push("skills", "contact", "review", "download");

    route = r.filter((k) => existingSteps.has(k));
    if (index >= route.length) index = route.length - 1;
    if (index < 0) index = 0;
  }

  function currentStep() {
    return route[index] || "welcome";
  }

  function setActive(stepKey) {
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

    if (step === "expMore" && state.expMore === "yes") {
      ensureExpBlock();
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
      btnNext.disabled = true;
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

  function isValidPhone(v) {
    const s = String(v || "").trim();
    if (!s) return true; // optional field

    // Added '-' to the allowed character list inside the brackets
    return /^\+?[0-9-]{6,20}$/.test(s);
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
    const title = state.expCurrent.querySelector('input[name$="[title]"]');
    const employer = state.expCurrent.querySelector('input[name$="[employer]"]');
    if (!required(title?.value)) { pulseInvalid(title); return false; }
    if (!required(employer?.value)) { pulseInvalid(employer); return false; }
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

  function getSkillRows() {
    if (!skillsContainer) return [];
    return Array.from(skillsContainer.querySelectorAll(".skill-row"));
  }

  function getSkillData() {
    return getSkillRows()
      .map((row) => {
        const nameEl = row.querySelector('input[name="skills[name][]"]');
        const categoryEl = row.querySelector('select[name="skills[category][]"]');
        return {
          row,
          name: nameEl ? nameEl.value.trim() : "",
          category: categoryEl ? categoryEl.value.trim() : ""
        };
      })
      .filter((item) => item.name.length > 0);
  }

  function updateSkillWarning() {
    const count = getSkillData().length;
    const el = document.getElementById("skillWarning");
    if (!el) return;

    if (count >= 10) {
      el.textContent = "Tip: Most resumes list around 8–12 skills.";
    } else {
      el.textContent = "";
    }
  }

  function validateSkillsStep() {
    const rows = getSkillRows();
    if (!rows.length) return false;

    const filled = getSkillData();
    if (filled.length < 1) {
      const firstName = rows[0].querySelector('input[name="name[]"]');
      pulseInvalid(firstName);
      return false;
    }
    return true;
  }

  function validateStep(step) {
    if (step === "welcome") return true;

    if (step === "basics") {
      if (!fullNameEl) return true;
      if (!required(fullNameEl.value)) { pulseInvalid(fullNameEl); return false; }
      return true;
    }

    if (step === "studying") return !!state.studying;
    if (step === "hasExp") return !!state.hasExp;
    if (step === "expOne") return state.expSaved >= 1;
    if (step === "expMore") return !!state.expMore;
    if (step === "eduOne") return state.eduSaved >= 1;
    if (step === "eduMore") return !!state.eduMore;

    if (step === "skills") {
      return validateSkillsStep();
    }

    if (step === "contact") {
      const email = emailEl.value.trim();
      const phone = phoneEl.value.trim();

      // Email 
      if (!email) {
        markInvalid(emailEl, true);
        emailEl.focus();
        return false;
      }
      if (!isValidEmail(email)) {
        markInvalid(emailEl, true);
        emailEl.focus();
        return false;
      }
      markInvalid(emailEl, false);

      // Phone
      if (!phone) {
        markInvalid(phoneEl, true);
        phoneEl.focus();
        return false;
      }
      if (!isValidPhone(phone)) {
        markInvalid(phoneEl, true);
        phoneEl.focus();
        return false;
      }
      markInvalid(phoneEl, false);

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
          <input class="pw-input" type="text" name="experience[${i}][title]" placeholder="e.g. Junior IT Support (Intern)" autocomplete="organization-title">
        </div>
        <div>
          <label class="pw-label">Employer *</label>
          <input class="pw-input" type="text" name="experience[${i}][employer]" placeholder="e.g. TechCorp" autocomplete="organization">
        </div>
      </div>

      <div class="pw-row">
        <div>
          <label class="pw-label">Start</label>
          <input class="pw-input" type="month" name="experience[${i}][start_date]">
        </div>
        <div>
          <label class="pw-label">End</label>
          <input class="pw-input" type="month" name="experience[${i}][end_date]">
        </div>
      </div>

      <div>
        <label class="pw-label">What did you do?</label>
        <textarea class="pw-textarea" name="experience[${i}][summary]" placeholder="Write 2–5 short lines."></textarea>
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
          <label class="pw-label">School *</label>
          <input class="pw-input" type="text" name="education[${i}][school]" placeholder="e.g. Hogeschool Rotterdam">
        </div>
      </div>

      <div class="pw-row">
        <div>
          <label class="pw-label">Start</label>
          <input class="pw-input" type="month" name="education[${i}][start_date]">
        </div>
        <div>
          <label class="pw-label">End</label>
          <input class="pw-input" type="month" name="education[${i}][end_date]">
        </div>
      </div>

      <div>
        <label class="pw-label">Optional note</label>
        <textarea class="pw-textarea" name="education[${i}][summary]" placeholder="Optional. One or two lines."></textarea>
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
  // Skills
  // --------------------------
  function getSkillRows() {
    if (!skillsContainer) return [];
    return Array.from(skillsContainer.querySelectorAll(".skill-row"));
  }

  function getSkillNameInput(row) {
    return row?.querySelector('input[name$="[name]"]') || null;
  }

  function getSkillCategorySelect(row) {
    return row?.querySelector('select[name$="[category]"]') || null;
  }

  function reindexSkillRows() {
    getSkillRows().forEach((row, i) => {
      row.dataset.skillIndex = String(i);

      const input = getSkillNameInput(row);
      const select = getSkillCategorySelect(row);

      if (input) {
        input.name = `skills[${i}][name]`;
        input.removeAttribute("id");
      }

      if (select) {
        select.name = `skills[${i}][category]`;
      }
    });
  }

  function clearSkillRow(row) {
    if (!row) return;

    const input = getSkillNameInput(row);
    const select = getSkillCategorySelect(row);

    if (input) {
      input.value = "";
      input.removeAttribute("id");
    }

    if (select) {
      select.selectedIndex = 0;
    }
  }

  function getSkillData() {
    return getSkillRows()
      .map((row) => {
        const nameEl = getSkillNameInput(row);
        const categoryEl = getSkillCategorySelect(row);

        return {
          row,
          name: nameEl ? nameEl.value.trim() : "",
          category: categoryEl ? categoryEl.value.trim() : ""
        };
      })
      .filter((item) => item.name.length > 0);
  }

  function updateSkillWarning() {
    const count = getSkillData().length;
    const el = root.querySelector("#skillWarning");
    if (!el) return;

    if (count >= 10) {
      el.textContent = "Tip: Most resumes list around 8–12 skills.";
    } else {
      el.textContent = "";
    }
  }

  function validateSkillsStep() {
    const rows = getSkillRows();
    if (!rows.length) return false;

    const filled = getSkillData();
    if (filled.length < 1) {
      const firstName = getSkillNameInput(rows[0]);
      pulseInvalid(firstName);
      return false;
    }
    return true;
  }

  function detectSkillCategory(skill) {
    const lower = String(skill || "").trim().toLowerCase();

    if ([
      "teamwork",
      "communication",
      "planning",
      "organization",
      "problem solving",
      "attention to detail",
      "risk analysis"
    ].includes(lower)) {
      return "Soft Skills";
    }

    if ([
      "first aid"
    ].includes(lower)) {
      return "Certificate";
    }

    if ([
      "microsoft excel"
    ].includes(lower)) {
      return "Software / Tools";
    }
    return "";
  }

  function createSkillRow(i, name = "", category = "") {
    const row = document.createElement("div");
    row.className = "field is-grouped skill-row";
    row.dataset.skillIndex = String(i);

    row.innerHTML = `
      <div class="control">
        <input type="text" name="skills[${i}][name]" class="pw-input" placeholder="..." value="${escapeHtml(name)}">
      </div>
      <div class="control">
        <select class="pw-select" name="skills[${i}][category]">
          <option disabled ${!category ? "selected" : ""}>Select a Category:</option>
          <option value="tool" ${category === "tool" ? "selected" : ""}>Software / Tools</option>
          <option value="language" ${category === "language" ? "selected" : ""}>Languages</option>
          <option value="technical" ${category === "technical" ? "selected" : ""}>Technical</option>
          <option value="certificate" ${category === "certificate" ? "selected" : ""}>Certificate</option>
          <option value="soft-skill" ${category === "soft-skill" ? "selected" : ""}>Soft Skills</option>
          <option value="hard-skill" ${category === "hard-skill" ? "selected" : ""}>Hard Skills</option>
          <option value="other" ${category === "other" ? "selected" : ""}>Other</option>
        </select>
      </div>
      <button type="button" class="remove">✕</button>
    `;
    return row;
  }

  function ensureOneSkillRow() {
    if (!skillsContainer) return;
    if (getSkillRows().length > 0) return;

    const row = createSkillRow(0);
    skillsContainer.appendChild(row);
  }

  function syncSkillChips() {
    const skillNames = getSkillData().map((item) => item.name.toLowerCase());

    root.querySelectorAll(".pw-chip[data-skill]").forEach((chip) => {
      const skill = String(chip.dataset.skill || "").trim().toLowerCase();
      chip.classList.toggle("is-selected", skillNames.includes(skill));
    });
  }

  function findFirstEmptySkillInput() {
    const rows = getSkillRows();
    for (const row of rows) {
      const input = getSkillNameInput(row);
      if (input && !input.value.trim()) return input;
    }
    return null;
  }

  if (skillsContainer) {
    reindexSkillRows();

    const firstRow = getSkillRows()[0];
    if (firstRow) clearSkillRow(firstRow);

    if (addSkillBtn) {
      addSkillBtn.addEventListener("click", () => {
        const row = createSkillRow(getSkillRows().length);
        skillsContainer.appendChild(row);
        reindexSkillRows();

        const input = getSkillNameInput(row);
        if (input) input.focus();

        updateSkillWarning();
      });
    }

    skillsContainer.addEventListener("click", (ev) => {
      const removeBtn = ev.target.closest(".remove");
      if (!removeBtn) return;

      const row = removeBtn.closest(".skill-row");
      if (!row) return;

      const rows = getSkillRows();

      if (rows.length <= 1) {
        clearSkillRow(row);
      } else {
        row.remove();
        reindexSkillRows();
      }

      syncSkillChips();
      showToast("Skill removed");
      updateSkillWarning();
    });

    skillsContainer.addEventListener("input", (ev) => {
      if (ev.target.matches('input[name$="[name]"]')) {
        syncSkillChips();
        updateSkillWarning();
      }
    });

    skillsContainer.addEventListener("change", (ev) => {
      if (ev.target.matches('select[name$="[category]"]')) {
        updateSkillWarning();
      }
    });
  }

  stage.addEventListener("click", (ev) => {
    const chip = ev.target.closest(".pw-chip[data-skill]");
    if (!chip || !skillsContainer) return;

    const skill = String(chip.dataset.skill || "").trim();
    if (!skill) return;

    const existing = getSkillData().find((item) => item.name.toLowerCase() === skill.toLowerCase());

    if (existing) {
      existing.row.remove();
      ensureOneSkillRow();
      reindexSkillRows();
      syncSkillChips();
      showToast("Skill removed");
      updateSkillWarning();
      return;
    }

    let input = findFirstEmptySkillInput();

    if (!input) {
      const row = createSkillRow(
        getSkillRows().length,
        skill,
        detectSkillCategory(skill)
      );
      skillsContainer.appendChild(row);
      reindexSkillRows();
      syncSkillChips();
      showToast("Skill added");
      updateSkillWarning();
      return;
    }

    input.value = skill;

    const row = input.closest(".skill-row");
    const select = getSkillCategorySelect(row);
    if (select) {
      const detected = detectSkillCategory(skill);
      if (detected) {
        select.value = detected;
      }
    }

    syncSkillChips();
    showToast("Skill added");
    updateSkillWarning();
  });

  // --------------------------
  // Buttons
  // --------------------------
  btnBack.addEventListener("click", back);
  btnNext.addEventListener("click", next);

  const form = root.querySelector("form");
  btnReset.addEventListener("click", () => {
    form.reset();

    expMount.innerHTML = "";
    eduMount.innerHTML = "";
    experienceFields.innerHTML = "";
    educationFields.innerHTML = "";

    if (skillsContainer) {
      skillsContainer.innerHTML = "";
      const row = createSkillRow();
      if (row) skillsContainer.appendChild(row);
    }

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

    root.querySelectorAll(".pw-chip.is-selected").forEach((chip) => {
      chip.classList.remove("is-selected");
    });

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
    const skills = getSkillData();

    addCard("Name", name, head);
    addCard("Experience", `${state.expSaved} role(s)`, state.hasExp === "yes" ? "" : "Not included");
    addCard("Education", `${state.eduSaved} entry(s)`, state.studying === "yes" ? "" : "Not included");
    addCard("Skills", skills.length ? skills.map((s) => s.name).join(", ") : "—");
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
  // Init
  // --------------------------
  buildRoute();
  syncSkillChips();
  goTo(0);
})();