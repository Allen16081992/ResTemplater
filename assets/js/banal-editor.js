"use strict";
(() => {
  const root = document.getElementById("pwBaseline");
  if (!root) return; // important: only run if this widget exists on the page

  if (root.dataset.pwInit) return;
  root.dataset.pwInit = "1";

  const STORAGE_KEY = "pw_baseline_resume_v1";

  const stage = root.querySelector("#pwStage");
  const cards = Array.from(stage.querySelectorAll(".pw-card"));
  const progressEl = root.querySelector("#pwProgress");
  const toast = root.querySelector("#pwToast");

  const btnBack = root.querySelector("#btnBack");
  const btnNext = root.querySelector("#btnNext");
  const btnReset = root.querySelector("#btnReset");

  const summaryGrid = root.querySelector("#summaryGrid");

  if (!stage || !progressEl || !toast || !btnBack || !btnNext || !btnReset || !summaryGrid) {
    return;
  }

  // Canonical data (minimal)
  const data = {
    fullName: "",
    headline: "",
    studying: null,   // "yes" | "no"
    hasExp: null,     // "yes" | "no"
    experience: [],
    education: [],
    contact: { email: "", city: "", country: "", website: "", phone: "" }
  };

  const stepsSemantic = [
    { key: "basics", label: "Basics" },
    { key: "experience", label: "Experience" },
    { key: "education", label: "Education" },
    { key: "contact", label: "Contact" },
    { key: "review", label: "Review" },
    { key: "download", label: "Download" }
  ];

  // Build progress chips
  const chips = stepsSemantic.map(s => {
    const chip = document.createElement("div");
    chip.className = "pw-stepchip";
    chip.dataset.pwchip = s.key;
    chip.innerHTML = `<span class="dot"></span><span>${s.label}</span>`;
    progressEl.appendChild(chip);
    return chip;
  });

  let route = [];
  let index = 0;

  function showToast() {
    toast.classList.add("is-show");
    clearTimeout(showToast._t);
    showToast._t = setTimeout(() => toast.classList.remove("is-show"), 850);
  }

  function saveLocal() {
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
      showToast();
    } catch (e) {}
  }

  function loadLocal() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      if (!raw) return;
      const parsed = JSON.parse(raw);
      Object.assign(data, parsed);
      data.contact = Object.assign({ email:"", city:"", country:"", website:"", phone:"" }, parsed.contact || {});
      data.experience = Array.isArray(parsed.experience) ? parsed.experience : [];
      data.education  = Array.isArray(parsed.education) ? parsed.education : [];
    } catch (e) {}
  }

  function setActive(stepKey) {
    cards.forEach(c => c.classList.toggle("is-active", c.dataset.step === stepKey));
    updateNavButtons();
    syncProgressChips(stepKey);
    if (stepKey === "review") renderSummary();
  }

  function syncProgressChips(activeStepKey) {
    const bucket = toSemanticBucket(activeStepKey);
    const activeIdx = stepsSemantic.findIndex(s => s.key === bucket);

    chips.forEach((chip, i) => {
      chip.classList.toggle("is-active", i === activeIdx);
      chip.classList.toggle("is-done", i < activeIdx);
    });
  }

  function toSemanticBucket(stepKey) {
    if (["welcome","basics","studying"].includes(stepKey)) return "basics";
    if (["hasExp","expOne","expMore"].includes(stepKey)) return "experience";
    if (["eduOne","eduMore"].includes(stepKey)) return "education";
    if (["contact"].includes(stepKey)) return "contact";
    if (["review"].includes(stepKey)) return "review";
    return "download";
  }

  function buildRoute() {
    const r = ["welcome", "basics", "studying", "hasExp"];

    if (data.hasExp === "yes") r.push("expOne", "expMore");
    if (data.studying === "yes") r.push("eduOne", "eduMore");

    r.push("contact", "review", "download");

    route = r;
    if (index >= route.length) index = route.length - 1;
  }

  function goTo(i) {
    index = Math.max(0, Math.min(i, route.length - 1));
    setActive(route[index]);
  }

  function next() {
    const step = route[index];
    if (!validateStep(step)) return;

    if (step === "expMore" && data._expMore === "yes") { goTo(index - 1); return; }
    if (step === "eduMore" && data._eduMore === "yes") { goTo(index - 1); return; }

    goTo(index + 1);
  }

  function back() {
    goTo(index - 1);
  }

  function updateNavButtons() {
    const step = route[index];
    btnBack.disabled = (step === "welcome");
    btnReset.disabled = false;

    if (step === "welcome") {
      btnNext.textContent = "Continue";
      btnNext.disabled = true;
      return;
    }

    if (step === "download") {
      btnNext.disabled = true;
      btnNext.style.visibility = "hidden";
    } else {
      btnNext.disabled = false;
      btnNext.style.visibility = "visible";
      btnNext.textContent = "Continue";
    }
  }

  function validateStep(step) {
    if (step === "welcome") return true;

    if (step === "basics") {
      const fullName = root.querySelector("#fullName").value.trim();
      if (!fullName) { pulseInvalid("fullName"); return false; }
      data.fullName = fullName;
      data.headline = root.querySelector("#headline").value.trim();
      saveLocal();
      return true;
    }

    if (step === "studying") {
      if (!data.studying) return false;
      saveLocal();
      return true;
    }

    if (step === "hasExp") {
      if (!data.hasExp) return false;
      buildRoute();
      saveLocal();
      return true;
    }

    if (step === "expOne") return data.experience.length >= 1;
    if (step === "expMore") { if (!data._expMore) return false; saveLocal(); return true; }
    if (step === "eduOne") return data.education.length >= 1;
    if (step === "eduMore") { if (!data._eduMore) return false; saveLocal(); return true; }

    if (step === "contact") {
      const email = root.querySelector("#email").value.trim();
      if (!email) { pulseInvalid("email"); return false; }
      data.contact.email = email;
      data.contact.city = root.querySelector("#city").value.trim();
      data.contact.country = root.querySelector("#country").value.trim();
      data.contact.website = root.querySelector("#website").value.trim();
      data.contact.phone = root.querySelector("#phone").value.trim();
      saveLocal();
      return true;
    }

    return true;
  }

  function pulseInvalid(id) {
    const el = root.querySelector("#" + id);
    if (!el) return;
    const old = el.style.borderColor;
    el.style.borderColor = "rgba(239,68,68,.85)";
    el.focus({ preventScroll: false });
    setTimeout(() => el.style.borderColor = old || "", 850);
  }

  function clearChoiceGroup(groupName) {
    root.querySelectorAll(`.pw-choice[data-choice="${groupName}"]`)
      .forEach(el => el.classList.remove("is-selected"));
  }

  function setChoice(groupName, value) {
    clearChoiceGroup(groupName);
    const el = root.querySelector(`.pw-choice[data-choice="${groupName}"][data-value="${value}"]`);
    if (el) el.classList.add("is-selected");
  }

  function wireChoices() {
    stage.addEventListener("click", (ev) => {
      const choice = ev.target.closest(".pw-choice");
      if (!choice) return;

      const group = choice.dataset.choice;
      const value = choice.dataset.value;

      if (group === "studying") {
        data.studying = value;
        setChoice("studying", value);
        buildRoute();
        saveLocal();
        next();
        return;
      }

      if (group === "hasExp") {
        data.hasExp = value;
        setChoice("hasExp", value);
        buildRoute();
        saveLocal();
        next();
        return;
      }

      if (group === "expMore") {
        data._expMore = value;
        setChoice("expMore", value);
        saveLocal();
        next();
        return;
      }

      if (group === "eduMore") {
        data._eduMore = value;
        setChoice("eduMore", value);
        saveLocal();
        next();
        return;
      }
    });
  }

  function wireButtons() {
    btnBack.addEventListener("click", back);
    btnNext.addEventListener("click", next);

    btnReset.addEventListener("click", () => {
      localStorage.removeItem(STORAGE_KEY);

      data.fullName = "";
      data.headline = "";
      data.studying = null;
      data.hasExp = null;
      data.experience = [];
      data.education = [];
      data.contact = { email:"", city:"", country:"", website:"", phone:"" };
      data._expMore = null;
      data._eduMore = null;

      ["fullName","headline","email","city","country","website","phone",
       "expRole","expCompany","expStart","expEnd","expDesc",
       "eduProgram","eduSchool","eduStart","eduEnd","eduDesc"
      ].forEach(id => {
        const el = root.querySelector("#" + id);
        if (el) el.value = "";
      });

      ["studying","hasExp","expMore","eduMore"].forEach(clearChoiceGroup);

      buildRoute();
      goTo(0);
    });

    root.querySelector('[data-action="start"]').addEventListener("click", () => {
      buildRoute();
      goTo(1);
    });

    root.querySelector('[data-action="saveExpOne"]').addEventListener("click", (ev) => {
      ev.preventDefault();
      const role = root.querySelector("#expRole").value.trim();
      const company = root.querySelector("#expCompany").value.trim();
      if (!role) { pulseInvalid("expRole"); return; }
      if (!company) { pulseInvalid("expCompany"); return; }

      const start = root.querySelector("#expStart").value;
      const end = root.querySelector("#expEnd").value;
      const descRaw = (root.querySelector("#expDesc").value || "").trim();

      const bullets = descRaw.split(/\r?\n/).map(s => s.trim()).filter(Boolean);

      data.experience.push({ role, company, start, end, bullets });

      ["expRole","expCompany","expStart","expEnd","expDesc"].forEach(id => root.querySelector("#"+id).value = "");

      data._expMore = null;
      clearChoiceGroup("expMore");

      saveLocal();

      const expMoreIndex = route.indexOf("expMore");
      if (expMoreIndex !== -1) goTo(expMoreIndex);
    });

    root.querySelector('[data-action="saveEduOne"]').addEventListener("click", (ev) => {
      ev.preventDefault();
      const program = root.querySelector("#eduProgram").value.trim();
      const school = root.querySelector("#eduSchool").value.trim();
      if (!program) { pulseInvalid("eduProgram"); return; }
      if (!school) { pulseInvalid("eduSchool"); return; }

      const start = root.querySelector("#eduStart").value;
      const end = root.querySelector("#eduEnd").value;
      const note = (root.querySelector("#eduDesc").value || "").trim();

      data.education.push({ program, school, start, end, note });

      ["eduProgram","eduSchool","eduStart","eduEnd","eduDesc"].forEach(id => root.querySelector("#"+id).value = "");

      data._eduMore = null;
      clearChoiceGroup("eduMore");

      saveLocal();

      const eduMoreIndex = route.indexOf("eduMore");
      if (eduMoreIndex !== -1) goTo(eduMoreIndex);
    });

    root.querySelector('[data-action="download"]').addEventListener("click", () => {
      const payload = { schema: "paperwitch.resume.v1", createdAt: new Date().toISOString(), resume: data };

      const blob = new Blob([JSON.stringify(payload, null, 2)], { type: "application/json" });
      const url = URL.createObjectURL(blob);

      const a = document.createElement("a");
      a.href = url;
      a.download = (data.fullName ? data.fullName.replace(/\s+/g, "_") : "resume") + "_paperwitch.json";
      document.body.appendChild(a);
      a.click();
      a.remove();

      URL.revokeObjectURL(url);
    });
  }

  function renderSummary() {
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

    addCard("Name", data.fullName || "—", data.headline ? data.headline : "");
    addCard("Experience", `${data.experience.length} role(s)`, data.experience.length ? data.experience[0].role : "—");
    addCard("Education", `${data.education.length} entry(s)`, data.education.length ? data.education[0].program : (data.studying === "yes" ? "—" : "Not included"));
    addCard("Contact", data.contact.email || "—", [data.contact.city, data.contact.country].filter(Boolean).join(", "));
  }

  function escapeHtml(str) {
    return String(str).replace(/[&<>"']/g, s => ({
      "&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;"
    }[s]));
  }

  function hydrateUIFromData() {
    root.querySelector("#fullName").value = data.fullName || "";
    root.querySelector("#headline").value = data.headline || "";

    if (data.studying) setChoice("studying", data.studying);
    if (data.hasExp) setChoice("hasExp", data.hasExp);
    if (data._expMore) setChoice("expMore", data._expMore);
    if (data._eduMore) setChoice("eduMore", data._eduMore);

    root.querySelector("#email").value = data.contact.email || "";
    root.querySelector("#city").value = data.contact.city || "";
    root.querySelector("#country").value = data.contact.country || "";
    root.querySelector("#website").value = data.contact.website || "";
    root.querySelector("#phone").value = data.contact.phone || "";
  }

  // Init
  loadLocal();
  hydrateUIFromData();
  buildRoute();
  goTo(0);

  wireChoices();
  wireButtons();
  buildRoute();
})();