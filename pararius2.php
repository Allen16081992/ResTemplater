<!DOCTYPE html>
<html lang="eng">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <style>
.pw-acc-shell {
  max-width: 860px;
  margin: 2.5rem auto;
  padding: 1.5rem 1.4rem 2rem;
  border-radius: 24px;
  background: rgba(15,17,24,.9);
  border: 1px solid rgba(255,255,255,.08);
  box-shadow:
    0 18px 60px rgba(0,0,0,.65),
    inset 0 1px 0 rgba(255,255,255,.05);
  backdrop-filter: blur(8px);
}
.pw-acc-header {
  margin-bottom: 1.4rem;
}
.pw-acc-title {
  font-family: Unna, Georgia, serif;
  font-size: clamp(1.9rem, 3vw, 2.3rem);
  margin-bottom: .25rem;
}
.pw-acc-sub {
  margin: 0;
  color: var(--muted, #a7a7b3);
}

/* Items */
.pw-acc-item + .pw-acc-item {
  margin-top: .8rem;
}
.pw-acc-toggle {
  width: 100%;
  border: none;
  border-radius: 16px;
  padding: .75rem .9rem;
  background: rgba(15,23,42,.95);
  color: #e5e5ff;
  display: flex;
  justify-content: space-between;
  align-items: center;
  cursor: pointer;
  text-align: left;
  transition: background .18s ease, box-shadow .18s ease, transform .12s ease;
}
.pw-acc-label {
  display: flex;
  align-items: center;
  gap: .6rem;
}
.pw-acc-label .icon {
  width: 2rem;
  display: inline-flex;
  justify-content: center;
}
.pw-acc-label span small {
  display: block;
  font-size: .8rem;
  color: var(--muted, #a7a7b3);
}
.pw-acc-chevron {
  font-size: .9rem;
  opacity: .7;
}
.pw-acc-toggle:hover {
  background: rgba(15,23,42,1);
  transform: translateY(-1px);
  box-shadow: 0 10px 24px rgba(0,0,0,.5);
}
.pw-acc-toggle.is-open {
  background: linear-gradient(135deg, rgba(139,92,246,.26), rgba(15,23,42,1));
}

/* Body */
.pw-acc-body {
  max-height: 0;
  overflow: hidden;
  transition: max-height .3s ease;
}
.pw-acc-body-inner {
  /* optional extra wrapper if you want padding control */
}

.pw-acc-item.is-open .pw-acc-body {
  /* JS will set max-height; this class just marks state */
}
.pw-acc-item.is-open .pw-acc-chevron {
  transform: rotate(180deg);
}

/* Inside body */
.pw-acc-body {
  padding: 0 .2rem;
}
.pw-acc-body > form,
.pw-acc-body > .pw-acc-body-header {
  padding: 1.1rem .9rem 1.2rem;
}
.pw-acc-body-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1rem;
  border-bottom: 1px solid rgba(55,65,81,.7);
  margin-bottom: .5rem;
}
.pw-acc-body-header h2 {
  margin: 0;
  font-size: 1.2rem;
}
.pw-acc-body-header p {
  margin: .2rem 0 0;
  font-size: .85rem;
  color: var(--muted, #a7a7b3);
}

/* Actions */
.pw-acc-actions {
  display: flex;
  justify-content: flex-end;
  gap: .6rem;
  margin-top: 1rem;
}

/* Repeater same as before */
.pw-repeater-item {
  margin-bottom: 1.1rem;
  padding: .9rem .8rem 1rem;
  border-radius: 14px;
  background: rgba(15,23,42,.95);
  border: 1px solid rgba(148,163,184,.35);
}
.pw-repeater-item .button.is-text {
  font-size: .8rem;
  color: #9ca3af;
}
.pw-repeater-divider {
  border: none;
  border-top: 1px dashed rgba(148,163,184,.5);
  margin-top: .7rem;
}

/* reuse your existing .btn-cta */
.btn-cta {
  background: linear-gradient(135deg, var(--accent, #8b5cf6), var(--accent-2, #4f46e5));
  border: none;
  color: #fff;
}

@media (max-width: 540px) {
  .pw-acc-shell {
    margin: 1rem .75rem 1.25rem;
    padding: 1.25rem 1rem 1.6rem;
    border-radius: 18px;
  }
}
    </style>
</head>
<section id="resume_editor" class="current pw-acc-editor">
  <div class="pw-acc-shell">
    <header class="pw-acc-header">
      <h1 class="pw-acc-title">Your Job Scroll Cauldron</h1>
      <p class="pw-acc-sub">
        Open a section, tweak the fields, and seal your resume one piece at a time.
      </p>
    </header>

    <!-- PROFILE ACCORDION -->
    <article class="pw-acc-item">
      <button class="pw-acc-toggle" type="button">
        <span class="pw-acc-label">
          <span class="icon">🎭</span>
          <span>
            Profile
            <small>Headline & short summary</small>
          </span>
        </span>
        <span class="pw-acc-chevron">▾</span>
      </button>

      <div class="pw-acc-body">
        <header class="pw-acc-body-header">
          <h2>Profile</h2>
          <button type="button" class="button is-light pw-edit-btn">Edit</button>
        </header>

        <form id="form-profile" method="post" action="client.php#resume_editor">
          <div class="field is-horizontal">
            <div class="field-body">
              <div class="field">
                <label class="label">Full name</label>
                <div class="control">
                  <input class="input" type="text" name="profile_name"
                    value="Allen Pieter" disabled>
                </div>
              </div>
              <div class="field">
                <label class="label">Headline</label>
                <div class="control">
                  <input class="input" type="text" name="profile_headline"
                    value="IT student • Junior dev" disabled>
                </div>
              </div>
            </div>
          </div>

          <div class="field">
            <label class="label">Short summary</label>
            <div class="control">
              <textarea class="textarea" name="profile_summary" rows="3" disabled>
Curious techie who likes building, breaking and fixing things in equal measure.
              </textarea>
            </div>
          </div>

          <div class="pw-acc-actions">
            <button type="submit" name="save_profile"
                    class="button btn-cta pw-save-btn" hidden>
              Save changes
            </button>
            <button type="button" class="button is-light pw-cancel-btn" hidden>
              Cancel
            </button>
          </div>
        </form>
      </div>
    </article>

    <!-- CONTACT ACCORDION -->
    <article class="pw-acc-item">
      <button class="pw-acc-toggle" type="button">
        <span class="pw-acc-label">
          <span class="icon">📮</span>
          <span>
            Contact
            <small>Email, phone & location</small>
          </span>
        </span>
        <span class="pw-acc-chevron">▾</span>
      </button>

      <div class="pw-acc-body">
        <header class="pw-acc-body-header">
          <h2>Contact</h2>
          <button type="button" class="button is-light pw-edit-btn">Edit</button>
        </header>

        <form id="form-contact" method="post" action="client.php#resume_editor">
          <div class="field is-horizontal">
            <div class="field-body">
              <div class="field">
                <label class="label">Email</label>
                <div class="control">
                  <input class="input" type="email" name="contact_email"
                    value="you@example.com" disabled>
                </div>
              </div>
              <div class="field">
                <label class="label">Phone</label>
                <div class="control">
                  <input class="input" type="tel" name="contact_phone"
                    value="+31 6 1234 5678" disabled>
                </div>
              </div>
            </div>
          </div>

          <div class="field is-horizontal">
            <div class="field-body">
              <div class="field">
                <label class="label">City</label>
                <div class="control">
                  <input class="input" type="text" name="contact_city"
                    value="Rotterdam" disabled>
                </div>
              </div>
              <div class="field">
                <label class="label">Country</label>
                <div class="control">
                  <input class="input" type="text" name="contact_country"
                    value="Netherlands" disabled>
                </div>
              </div>
            </div>
          </div>

          <div class="field">
            <label class="label">Portfolio / Website</label>
            <div class="control">
              <input class="input" type="url" name="contact_website"
                value="https://example.com" disabled>
            </div>
          </div>

          <div class="pw-acc-actions">
            <button type="submit" name="save_contact"
                    class="button btn-cta pw-save-btn" hidden>
              Save changes
            </button>
            <button type="button" class="button is-light pw-cancel-btn" hidden>
              Cancel
            </button>
          </div>
        </form>
      </div>
    </article>

    <!-- EXPERIENCE ACCORDION (with repeater) -->
    <article class="pw-acc-item">
      <button class="pw-acc-toggle" type="button">
        <span class="pw-acc-label">
          <span class="icon">🏹</span>
          <span>
            Experience
            <small>Internships, jobs, serious projects</small>
          </span>
        </span>
        <span class="pw-acc-chevron">▾</span>
      </button>

      <div class="pw-acc-body">
        <header class="pw-acc-body-header">
          <h2>Experience</h2>
          <button type="button" class="button is-light pw-edit-btn">Edit</button>
        </header>

        <form id="form-experience" method="post" action="client.php#resume_editor">
          <div class="pw-repeater" data-repeater="experience">
            <div class="pw-repeater-item">
              <div class="field is-horizontal">
                <div class="field-body">
                  <div class="field">
                    <label class="label">Role</label>
                    <div class="control">
                      <input class="input" type="text" name="exp_role[]"
                        value="Junior IT Support (Intern)" disabled>
                    </div>
                  </div>
                  <div class="field">
                    <label class="label">Company</label>
                    <div class="control">
                      <input class="input" type="text" name="exp_company[]"
                        value="TechCorp" disabled>
                    </div>
                  </div>
                </div>
              </div>

              <div class="field is-horizontal">
                <div class="field-body">
                  <div class="field">
                    <label class="label">Start</label>
                    <div class="control">
                      <input class="input" type="month" name="exp_start[]"
                        value="2024-01" disabled>
                    </div>
                  </div>
                  <div class="field">
                    <label class="label">End</label>
                    <div class="control">
                      <input class="input" type="month" name="exp_end[]"
                        value="2024-06" disabled>
                    </div>
                  </div>
                </div>
              </div>

              <div class="field">
                <label class="label">Description</label>
                <div class="control">
                  <textarea class="textarea" name="exp_desc[]" rows="3" disabled>
Helped maintain workstations, solved ticketed issues and documented fixes.
                  </textarea>
                </div>
              </div>

              <button type="button" class="button is-text pw-remove-item" disabled>
                Remove this experience
              </button>

              <hr class="pw-repeater-divider">
            </div>
          </div>

          <button type="button" class="button is-dark is-small pw-add-item"
                  data-repeater-target="experience">
            + Add another experience
          </button>

          <div class="pw-acc-actions">
            <button type="submit" name="save_experience"
                    class="button btn-cta pw-save-btn" hidden>
              Save changes
            </button>
            <button type="button" class="button is-light pw-cancel-btn" hidden>
              Cancel
            </button>
          </div>
        </form>

        <!-- template for JS cloning -->
        <template id="tpl-experience-item">
          <div class="pw-repeater-item">
            <!-- same structure as above but empty values -->
          </div>
        </template>
      </div>
    </article>

    <!-- You can add Education, Skills, etc the same pattern -->
  </div>
</section>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const editor = document.querySelector(".pw-acc-shell");
  if (!editor) return;

  /* ------- ACCORDION TOGGLING ------- */
  const items   = editor.querySelectorAll(".pw-acc-item");
  const toggles = editor.querySelectorAll(".pw-acc-toggle");

  toggles.forEach(toggle => {
    toggle.addEventListener("click", () => {
      const item  = toggle.closest(".pw-acc-item");
      const body  = item.querySelector(".pw-acc-body");

      const isOpen = item.classList.contains("is-open");

      // If you want "only one open at a time":
      items.forEach(other => {
        if (other !== item) {
          other.classList.remove("is-open");
          const otherBody = other.querySelector(".pw-acc-body");
          if (otherBody) otherBody.style.maxHeight = 0;
          const otherToggle = other.querySelector(".pw-acc-toggle");
          if (otherToggle) otherToggle.classList.remove("is-open");
        }
      });

      if (!isOpen) {
        item.classList.add("is-open");
        toggle.classList.add("is-open");
        body.style.maxHeight = body.scrollHeight + "px";
      } else {
        item.classList.remove("is-open");
        toggle.classList.remove("is-open");
        body.style.maxHeight = 0;
      }
    });
  });

  /* ------- EDIT / SAVE / CANCEL ------- */
  const panels = editor.querySelectorAll(".pw-acc-item");

  panels.forEach(panel => {
    const form      = panel.querySelector("form");
    const editBtn   = panel.querySelector(".pw-edit-btn");
    const saveBtn   = panel.querySelector(".pw-save-btn");
    const cancelBtn = panel.querySelector(".pw-cancel-btn");

    if (!form || !editBtn || !saveBtn || !cancelBtn) return;

    const inputs = form.querySelectorAll("input, textarea, select");

    function setEditing(isEditing) {
      inputs.forEach(input => {
        if (isEditing) {
          input.removeAttribute("disabled");
        } else {
          input.setAttribute("disabled", "disabled");
        }
      });

      editBtn.hidden   = isEditing;
      saveBtn.hidden   = !isEditing;
      cancelBtn.hidden = !isEditing;
    }

    // View mode by default
    setEditing(false);

    editBtn.addEventListener("click", () => {
      setEditing(true);
      // Ensure accordion is open when editing starts
      const body = panel.querySelector(".pw-acc-body");
      const toggle = panel.querySelector(".pw-acc-toggle");
      if (body && !panel.classList.contains("is-open")) {
        panel.classList.add("is-open");
        toggle.classList.add("is-open");
        body.style.maxHeight = body.scrollHeight + "px";
      }
    });

    cancelBtn.addEventListener("click", () => {
      // Simple: revert to locked mode, trust backend to restore values on next reload
      setEditing(false);
    });

    // Save button just submits form; no extra JS needed
  });

  /* ------- REPEATER (EXPERIENCE) ------- */
  const addButtons = editor.querySelectorAll(".pw-add-item");

  addButtons.forEach(btn => {
    const targetName = btn.dataset.repeaterTarget;
    const container = editor.querySelector(
      `.pw-repeater[data-repeater="${targetName}"]`
    );
    const template = editor.querySelector(`#tpl-${targetName}-item`);
    if (!container || !template) return;

    btn.addEventListener("click", () => {
      const clone = template.content.cloneNode(true);
      container.appendChild(clone);
      wireRemove(container);
    });

    wireRemove(container);
  });

  function wireRemove(scope) {
    const removeBtns = scope.querySelectorAll(".pw-remove-item");
    removeBtns.forEach(btn => {
      if (btn.dataset.bound === "1") return;
      btn.dataset.bound = "1";

      btn.addEventListener("click", () => {
        const item = btn.closest(".pw-repeater-item");
        if (item) item.remove();
      });
    });
  }
});
</script>
</html>