"use strict";
document.addEventListener("DOMContentLoaded", () => {
  const sections = document.querySelectorAll(".profile-section");

  sections.forEach(section => {
    const editBtn = section.querySelector(".edit-btn");
    const inputs  = section.querySelectorAll("input");

    if (!editBtn) return; // safety

    editBtn.addEventListener("click", () => {
      const isEditing = editBtn.textContent === "Cancel";

      if (!isEditing) {
        // ---- ENTER EDIT MODE ----
        editBtn.textContent = "Cancel";

        // Create Save button
        const saveBtn = document.createElement("button");
        saveBtn.className = "button btn-cta save-btn";
        saveBtn.textContent = "Save Changes";

        const btnContainer = section.querySelector(".buttons");
        if (btnContainer) btnContainer.appendChild(saveBtn);

        // Enable inputs EXCEPT dob
        inputs.forEach(input => {
          if (input.id !== "dob") input.removeAttribute("disabled");
        });

        // Save button logic
        saveBtn.addEventListener("click", () => {
          console.log("Saving changes for section:", section.querySelector("h2")?.textContent);

          inputs.forEach(input => {
            if (input.id !== "dob") input.setAttribute("disabled", true);
          });

          editBtn.textContent = "Edit";
          editBtn.classList.remove("cancel-btn");
          editBtn.classList.add("is-light");

          saveBtn.remove();
        });

      } else {
        // ---- CANCEL EDIT MODE ----
        editBtn.textContent = "Edit";
        editBtn.classList.remove("cancel-btn");
        editBtn.classList.add("is-light");

        const saveBtn = section.querySelector(".save-btn");
        if (saveBtn) saveBtn.remove();

        // Disable all inputs again
        inputs.forEach(input => input.setAttribute("disabled", true));
      }
    });
  });
});