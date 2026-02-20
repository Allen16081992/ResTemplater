"use strict";

(() => {
  // Defensive: only run on pages that actually have profile UI
  const profileSections = document.querySelectorAll(".profile-section");
  const fileInput = document.getElementById("upload");
  const avatar = document.querySelector(".profile-avatar");
  const avToggle = document.getElementById("av");

  if (
    profileSections.length === 0 &&
    !fileInput &&
    !avatar &&
    !avToggle
  ) {
    return;
  }

  // Any field IDs in here will NEVER be toggled editable
  const IMMUTABLE_IDS = new Set(["dateOfBirth"]);

  // ─────────────────────────────────────
  //  PER-SECTION EDIT / SAVE HANDLING
  // ─────────────────────────────────────
  profileSections.forEach((section) => {
    const editBtn = section.querySelector(".edit-btn");
    if (!editBtn) return;

    const form = section.closest("form");
    const btnRow = section.querySelector(".buttons");
    if (!btnRow || !form) return;

    const inputs = Array.from(section.querySelectorAll("input"));

    // Create the Save button once
    const saveBtn = document.createElement("button");
    saveBtn.type = "submit";
    saveBtn.className = "button btn-cta save-btn";
    saveBtn.textContent = "Save Changes";

    // Name the button so PHP can see which section was saved
    if (form.id === "personal") saveBtn.name = "personal";
    if (form.id === "account") saveBtn.name = "account";

    let isEditing = false;

    function setEditing(on) {
      isEditing = on;

      // Toggle button label
      editBtn.textContent = on ? "Cancel" : "Edit";

      // Toggle inputs (skip immutable)
      inputs.forEach((input) => {
        if (IMMUTABLE_IDS.has(input.id)) return;

        if (on) input.removeAttribute("disabled");
        else input.setAttribute("disabled", "disabled");
      });

      // Toggle Save button
      if (on) {
        if (!saveBtn.isConnected) btnRow.appendChild(saveBtn);
      } else {
        if (saveBtn.isConnected) saveBtn.remove();
      }
    }

    // Initial state: view mode
    setEditing(false);

    // Edit / Cancel toggle
    editBtn.addEventListener("click", (event) => {
      event.preventDefault();
      setEditing(!isEditing);
    });

    // Optional: lock fields right before submit to avoid double-click spam
    saveBtn.addEventListener("click", () => {
      inputs.forEach((input) => input.setAttribute("disabled", "disabled"));
    });
  });

  // ─────────────────────────────────────
  //  AVATAR UPLOAD PREVIEW
  // ─────────────────────────────────────
  if (fileInput && avatar) {
    fileInput.addEventListener("change", () => {
      const file = fileInput.files?.[0];
      if (!file) return;

      const reader = new FileReader();
      reader.onload = (e) => {
        avatar.style.backgroundImage = `url('${e.target?.result ?? ""}')`;
      };
      reader.readAsDataURL(file);
    });
  }

  // ─────────────────────────────────────
  //  AVATAR TOGGLE (SESSION FLAG)
  // ─────────────────────────────────────
  if (avToggle) {
    avToggle.addEventListener("change", (e) => {
      const checked = /** @type {HTMLInputElement} */ (e.target).checked;

      fetch("/set-avatar.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded;charset=UTF-8",
        },
        body: new URLSearchParams({ avatar: checked ? "1" : "0" }).toString(),
        credentials: "same-origin",
      }).catch(() => {
        // optional: ignore network errors silently
      });
    });
  }
})();
