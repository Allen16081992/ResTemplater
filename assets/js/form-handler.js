"use strict";
document.addEventListener("DOMContentLoaded", () => {
  // Any field IDs in here will NEVER be toggled editable
  const IMMUTABLE_IDS = new Set(["dateOfBirth"]);

  // ─────────────────────────────────────
  //  PER-SECTION EDIT / SAVE HANDLING
  // ─────────────────────────────────────
  document.querySelectorAll(".profile-section").forEach(section => {
    const editBtn = section.querySelector(".edit-btn");
    if (!editBtn) return; // safety

    const form      = section.closest("form");
    const inputs    = Array.from(section.querySelectorAll("input"));
    const btnRow    = section.querySelector(".buttons");

    if (!btnRow || !form) return;

    // Create the Save button once
    const saveBtn = document.createElement("button");
    saveBtn.type = "submit";
    saveBtn.className = "button btn-cta save-btn";
    saveBtn.textContent = "Save Changes";

    // Name the button so PHP can see which section was saved
    if (form.id === "personal") saveBtn.name = "personal";
    if (form.id === "account")  saveBtn.name = "account";

    let isEditing = false;

    function setEditing(on) {
      isEditing = on;

      // Toggle button label
      editBtn.textContent = on ? "Cancel" : "Edit";

      // Toggle inputs (skip immutable)
      inputs.forEach(input => {
        if (IMMUTABLE_IDS.has(input.id)) return;

        if (on) {
          input.removeAttribute("disabled");
        } else {
          input.setAttribute("disabled", "disabled");
        }
      });

      // Toggle Save button
      if (on) {
        if (!saveBtn.isConnected) btnRow.appendChild(saveBtn);
      } else {
        if (saveBtn.isConnected) saveBtn.remove();
      }
    }

    // Edit / Cancel toggle
    editBtn.addEventListener("click", (event) => {
      event.preventDefault(); // in case it's inside <form>
      setEditing(!isEditing);
    });

    // Optional: add a tiny bit of pre-submit safety
    saveBtn.addEventListener("click", () => {
      // Let the form submit normally.
      // If you want: temporarily lock fields to avoid double-click spam:
      inputs.forEach(input => input.setAttribute("disabled", "disabled"));
      // Do NOT call setEditing(false) here; the page will reload anyway.
    });
  });

  // ─────────────────────────────────────
  //  AVATAR UPLOAD PREVIEW
  // ─────────────────────────────────────
  const fileInput = document.getElementById("upload");
  const avatar    = document.querySelector(".profile-avatar");

  if (fileInput && avatar) {
    fileInput.addEventListener("change", () => {
      const file = fileInput.files[0];
      if (!file) return;

      const reader = new FileReader();
      reader.onload = e => {
        avatar.style.backgroundImage = `url('${e.target.result}')`;
      };
      reader.readAsDataURL(file);
    });
  }
});