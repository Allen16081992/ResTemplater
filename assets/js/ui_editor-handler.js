"use strict";

(() => {
  const editor = document.querySelector(".pw-editor-shell");
  if (!editor) return;

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
  if (editor.querySelector("#panel-info")) activatePanel("info");
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

  function wireRemoveButtons(container) {
    const scope = findSectionRoot(container);
    const removeButtons = container.querySelectorAll(".pw-remove-item");

    removeButtons.forEach((btn) => {
      if (btn.dataset.wired === "1") return;
      btn.dataset.wired = "1";

      btn.addEventListener("click", () => {
        const item = btn.closest(".pw-repeater-item");
        if (item) item.remove();

        // After removal
        setSaveEnabled(scope, getItemCount(container) > 0);
      });
    });
  }

  addButtons.forEach((addBtn) => {
    const targetName = addBtn.dataset.repeaterTarget;
    if (!targetName) return;

    const container = editor.querySelector(`.pw-repeater[data-repeater="${targetName}"]`);
    const template = editor.querySelector(`#tpl-${targetName}-item`);
    if (!container || !template) return;

    const scope = findSectionRoot(addBtn);

    // Initial state (empty-by-default supported)
    wireRemoveButtons(container);
    setSaveEnabled(scope, getItemCount(container) > 0);

    addBtn.addEventListener("click", () => {
      const clone = template.content.cloneNode(true);
      container.appendChild(clone);

      wireRemoveButtons(container);
      setSaveEnabled(scope, true);

      // Optional: focus first input of the newly added item
      const lastItem = container.lastElementChild;
      const firstField = lastItem?.querySelector("input, textarea, select");
      if (firstField) firstField.focus();
    });
  });
})();