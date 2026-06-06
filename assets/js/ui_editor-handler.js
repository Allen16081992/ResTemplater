"use strict";

(() => {
  const editor = document.querySelector(".pw-editor-shell");
  if (!editor) return;

  function createCauldronExpBlock(i) {
    const item = document.createElement("div");
    item.className = "pw-repeater-item";
    item.dataset.experienceIndex = String(i);

    item.innerHTML = `
      <div class="field is-horizontal">
        <div class="field-body">
          <div class="field">
            <label class="label">Job title</label>
            <div class="control">
              <input class="input" type="text" name="experience[${i}][title]" placeholder="Role / Position">
            </div>
          </div>
          <div class="field">
            <label class="label">Company</label>
            <div class="control">
              <input class="input" type="text" name="experience[${i}][employer]" placeholder="Company / Organization">
            </div>
          </div>
        </div>
      </div>

      <div class="field is-horizontal">
        <div class="field-body">
          <div class="field">
            <label class="label">Start</label>     
            <div class="control">
              <input class="input" type="month" placeholder="e.g. Jan 2024 or 2024-01" name="experience[${i}][start_date]">
            </div>
          </div>
          <div class="field">
            <label class="label">End</label>
            <div class="control">
              <input class="input" type="month" placeholder="e.g. Jan 2024 or 2024-01" name="experience[${i}][end_date]">
            </div>
          </div>
        </div>
      </div>

      <div class="field">
        <label class="label">Description</label>
        <div class="control">
          <textarea class="textarea" name="experience[${i}][summary]" rows="3" placeholder="(Optional) What did you actually do, fix or improve?"></textarea>
        </div>
      </div>

      <button type="button" class="button is-text pw-remove-item" name="experience:delete">Remove this experience</button>
      <hr class="pw-repeater-divider">
    `;

    return item;
  }

  /* ---------------- SECTION TABS + MOBILE SELECT ---------------- */
  const tabs = editor.querySelectorAll(".pw-editor-tab");
  const panels = editor.querySelectorAll(".pw-editor-panel");
  const mobileSelect = editor.querySelector("#editorSectionSelect");

  function activatePanel(panelKey) {
    if (!panelKey) return;

    tabs.forEach((btn) => {
      btn.classList.toggle("is-active", btn.dataset.panel === panelKey);
    });

    panels.forEach((panel) => {
      panel.classList.toggle("is-active", panel.id === `panel-${panelKey}`);
    });

    if (mobileSelect && mobileSelect.value !== panelKey) {
      mobileSelect.value = panelKey;
    }
  }

  tabs.forEach((btn) => {
    btn.addEventListener("click", () => activatePanel(btn.dataset.panel));
  });

  if (mobileSelect) {
    mobileSelect.addEventListener("change", () => activatePanel(mobileSelect.value));
  }

  // Default panel
  if (editor.querySelector("#panel-newRes")) activatePanel("newRes");
  else if (tabs[0]?.dataset.panel) activatePanel(tabs[0].dataset.panel);

  /* ---------------- REPEATER (experience/education/social) ---------------- */
  const addButtons = editor.querySelectorAll(".pw-add-item");
  if (!addButtons.length) return;

  function findSectionRoot(el) {
    // Panel is enough; a form is also fine. Prefer panel to keep "Save" lookup easy.
    return el.closest(".pw-editor-panel") || editor;
  }

  function getSaveButton(scope) {
    return scope.querySelector(".pw-save-btn");
  }

  function getItemCount(container) {
    return container ? container.querySelectorAll(".pw-repeater-item").length : 0;
  }

  function setSaveEnabled(scope, enabled) {
    const saveBtn = getSaveButton(scope);
    if (!saveBtn) return;
    // If you want "Save clears section" behavior, delete these 2 lines.
    saveBtn.disabled = !enabled;
    saveBtn.classList.toggle("is-disabled", !enabled); // optional Bulma-ish hint
  }

  // function wireRemoveButtons(container) {
  //   const scope = findSectionRoot(container);
  //   const removeButtons = container.querySelectorAll(".pw-remove-item");

  //   removeButtons.forEach((btn) => {
  //     if (btn.dataset.wired === "1") return;
  //     btn.dataset.wired = "1";

  //     btn.addEventListener("click", () => {
  //       const item = btn.closest(".pw-repeater-item");
  //       if (item) item.remove();

  //       // After removal
  //       setSaveEnabled(scope, getItemCount(container) > 0);
  //     });
  //   });
  // }

  // No form submission prevent on remove item 
  function wireRemoveButtons(container) {
    const scope = findSectionRoot(container);
    const removeButtons = container.querySelectorAll(".pw-remove-item");

    removeButtons.forEach((btn) => {
      if (btn.dataset.wired === "1") return;
      btn.dataset.wired = "1";

      btn.addEventListener("click", (e) => {
        const item = btn.closest(".pw-repeater-item");
        const actionValue = btn.value;

        // If it's a real DB record (has a pipe | and an ID)
        if (actionValue && actionValue.includes('|')) {
          // DO NOTHING ELSE. 
          // Let the default "submit" behavior take over.
          // The browser will POST to action_handler.conf.php.
          return; 
        }

        // If it's just a newly added "ghost" row (no ID yet)
        // We handle this purely in JS because the DB doesn't know it exists.
        if (item) {
          item.remove();
          // Since it's a button type="button", it won't submit anyway.
        }

        setSaveEnabled(scope, getItemCount(container) > 0);
      });
    });
  }

  /* ---------------- REPEATER ADD LOOP ---------------- */
  addButtons.forEach((addBtn) => {
    const targetName = addBtn.dataset.repeaterTarget; // e.g., "experience"
    if (!targetName) return;

    // Find the container where rows should be injected
    const container = editor.querySelector(`.pw-repeater[data-repeater="${targetName}"]`);
    if (!container) return;

    const scope = findSectionRoot(addBtn);

    // Initial state check for existing items (pre-populated rows)
    wireRemoveButtons(container);
    setSaveEnabled(scope, getItemCount(container) > 0);

    // The updated click handler
    addBtn.addEventListener("click", () => {
      // 1. Generate a localized unique timestamp index
      const uniqueIndex = Date.now();
      let newBlock = null;

      // 2. Direct traffic to our programmatic generator
      if (targetName === "experience") {
        newBlock = createCauldronExpBlock(uniqueIndex);
      }
      // NOTE: When you build your education module later, you'll just add:
      // else if (targetName === "education") {
      //   newBlock = createCauldronEduBlock(uniqueIndex);
      // }

      // Safety check in case targetName doesn't match a generator yet
      if (!newBlock) return;

      // 3. Append the clean DOM node straight into the repeater area
      container.appendChild(newBlock);

      // 4. Wire events to the new element's removal button & light up Save
      wireRemoveButtons(container);
      setSaveEnabled(scope, true);

      // 5. Pop the user's focus smoothly into the first input field
      const lastItem = container.lastElementChild;
      const firstField = lastItem?.querySelector("input, textarea, select");
      if (firstField) firstField.focus();
    });
  });

})();

