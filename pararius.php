<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <style>
/* Scope everything so we don't fight existing CSS */
.pw-editor-shell {
  max-width: 1120px;
  margin: 2.5rem auto;
  padding: 1.75rem 1.75rem 2rem;
  border-radius: 24px;
  background: rgba(15,17,24,.92);
  border: 1px solid rgba(255,255,255,.08);
  box-shadow:
    0 18px 60px rgba(0,0,0,.65),
    inset 0 1px 0 rgba(255,255,255,.05);
  backdrop-filter: blur(8px);
}

/* Header */
.pw-editor-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1.5rem;
  margin-bottom: 1.8rem;
}
.pw-editor-title {
  font-family: Unna, Georgia, serif;
  font-size: clamp(1.9rem, 3vw, 2.4rem);
  margin-bottom: .25rem;
}
.pw-editor-sub {
  margin: 0;
  color: var(--muted, #a7a7b3);
}
.pw-editor-status {
  text-align: right;
  font-size: .85rem;
  color: var(--muted, #a7a7b3);
}
.pw-editor-status-pill {
  display: inline-flex;
  align-items: center;
  gap: .35rem;
  padding: .3rem .7rem;
  border-radius: 999px;
  background: rgba(22,163,74,.1);
  border: 1px solid rgba(22,163,74,.45);
  color: #bbf7d0;
  font-weight: 600;
}
.pw-editor-status small {
  display: block;
  margin-top: .3rem;
}

/* Layout */
.pw-editor-layout {
  display: grid;
  grid-template-columns: 230px minmax(0, 1fr);
  gap: 1.75rem;
}

/* Sidebar */
.pw-editor-sidebar {
  display: flex;
  flex-direction: column;
  gap: .5rem;
  padding: .75rem;
  border-radius: 18px;
  background: radial-gradient(circle at top left, rgba(139,92,246,.16), rgba(15,17,24,1) 55%);
  border: 1px solid rgba(255,255,255,.06);
}
.pw-editor-tab {
  display: flex;
  align-items: center;
  gap: .55rem;
  padding: .55rem .7rem;
  border-radius: 999px;
  border: none;
  background: transparent;
  color: #e5e5ff;
  font-size: .9rem;
  cursor: pointer;
  text-align: left;
  transition: background .18s ease, transform .12s ease, box-shadow .18s ease;
}
.pw-editor-tab .icon {
  display: inline-flex;
  width: 1.6rem;
  justify-content: center;
}
.pw-editor-tab:hover {
  background: rgba(15,23,42,.9);
  /* transform: translateX(1px); */
}
.pw-editor-tab.is-active {
  background: linear-gradient(135deg, var(--accent, #8b5cf6), var(--accent-2, #4f46e5));
  box-shadow: 0 10px 28px rgba(88,81,219,.6);
}

/* Mobile section select (hidden on desktop) */
.pw-editor-mobile-select {
  display: none;
  margin-bottom: 1rem;
}

/* Main panels */
.pw-editor-main {
  min-height: 320px;
}
.pw-editor-panel {
  display: none;
  padding: 1.25rem 1.3rem 1.5rem;
  border-radius: 18px;
  background: rgba(9,9,15,.9);
  border: 1px solid rgba(255,255,255,.06);
}
.pw-editor-panel.is-active {
  display: block;
}

.pw-panel-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1rem;
  margin-bottom: 1.2rem;
}
.pw-panel-header h2 {
  margin: 0;
  font-size: 1.3rem;
}
.pw-panel-header p {
  margin: .2rem 0 0;
  font-size: .9rem;
  color: var(--muted, #a7a7b3);
}

/* Actions */
.pw-panel-actions {
  display: flex;
  justify-content: flex-end;
  gap: .6rem;
  margin-top: 1.25rem;
}
.pw-panel-actions .button {
  min-width: 140px;
}

/* Repeater (Experience list) */
.pw-repeater-item {
  margin-bottom: 1.25rem;
  padding: 1rem 1rem 1.1rem;
  border-radius: 14px;
  background: rgba(15,23,42,.95);
  border: 1px solid rgba(148,163,184,.35);
}
.pw-repeater-item .button.is-text {
  color: #9ca3af;
  font-size: .85rem;
}
.pw-repeater-divider {
  border: none;
  border-top: 1px dashed rgba(148,163,184,.5);
  margin-top: .9rem;
}

/* Buttons: reuse your existing .btn-cta look if defined */
.btn-cta {
  background: linear-gradient(135deg, var(--accent, #8b5cf6), var(--accent-2, #4f46e5));
  border: none;
  color: #fff;
}

/* Responsive tweaks */
@media (max-width: 960px) {
  .pw-editor-header {
    flex-direction: column;
    align-items: flex-start;
    text-align: left;
  }
  .pw-editor-status {
    text-align: left;
  }
}

@media (max-width: 860px) {
  .pw-editor-shell {
    margin: 1.25rem;
    padding: 1.4rem 1.1rem 1.6rem;
  }
  .pw-editor-layout {
    grid-template-columns: minmax(0, 1fr);
  }
  .pw-editor-sidebar {
    display: none;
  }
  .pw-editor-mobile-select {
    display: block;
  }
}

@media (max-width: 480px) {
  .pw-editor-shell {
    margin: 1rem .75rem 1.25rem;
    border-radius: 18px;
  }
}

/* ------------------------------------------------------------ */
.radio-card-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  max-height: 400px;
  overflow-y: auto;
  padding: 1rem 0;
}

.radio-card-grid label {
  flex: 1 1 calc(25% - 1rem); /* 4 per row */
  min-width: 190px;
  background: #1f2937;
  color: #e5e7eb;
  border: 1px solid #374151;
  border-radius: 10px;
  padding: 0.8rem;
  cursor: pointer;
  transition: all 0.2s ease;
  box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

.radio-card-grid label:hover {
  background: #111827;
  border-color: #60a5fa;
}

.radio-card-grid input[type="radio"] {
  display: none;
}

.radio-card-grid input[type="radio"]:checked + span {
  font-weight: bold;
  border-left: 4px solid #60a5fa;
  padding-left: 0.5rem;
}

.radio-card-grid span {
  display: block;
  line-height: 1.4;
}
    </style>
</head>
<section id="resume_editor" class="current">
  <div class="pw-editor-shell">
    <header class="pw-editor-header">
      <div>
        <h1 class="pw-editor-title">Job Scroll Cauldron</h1>
        <p class="pw-editor-sub">
          Brew, tweak and trim your resume sections before sending them into the wild.
        </p>
      </div>
      <div class="pw-editor-status">
        <span class="pw-editor-status-pill">
          <span>🧪</span> Draft saved locally
        </span>
        <small>Template: <strong>Neo-Gothic Internship</strong></small>
      </div>
    </header>

    <!-- Mobile section selector -->
    <div class="pw-editor-mobile-select">
      <div class="select is-fullwidth">
        <select id="editorSectionSelect">
          <option value="newRes">+ New Resume</option>
          <option value="delRes">- Delete Resume</option>
          <option value="getRekt"># Resume List</option>
          <option value="info" selected>Resume Info</option>
          <option value="profile">Profile</option>
          <option value="contact">Contact</option>
          <option value="experience">Experience</option>
          <option value="education">Education</option>
        </select>
      </div>
    </div>

    <div class="pw-editor-layout">
      <!-- SIDEBAR -->
      <aside class="pw-editor-sidebar" aria-label="Resume sections">
        <button class="button pw-editor-tab" data-panel="newRes">
          <span>New</span>
        </button>
        <button class="button pw-editor-tab" data-panel="delRes">
          <span>Delete</span>
        </button>
          <button class="button pw-editor-tab" data-panel="getRekt">
          <span>View List</span>
        </button>
        <hr>
        <button class="pw-editor-tab is-active" data-panel="info">
          <span class="icon">⚗️</span>
          <span>Resume Info</span>
        </button>
        <button class="pw-editor-tab" data-panel="profile">
          <span class="icon">🎭</span>
          <span>Profile</span>
        </button>
        <button class="pw-editor-tab" data-panel="contact">
          <span class="icon">📞</span>
          <span>Contact</span>
        </button>
        <button class="pw-editor-tab" data-panel="experience">
          <span class="icon">🏹</span>
          <span>Experience</span>
        </button>
        <button class="pw-editor-tab" data-panel="education">
          <span class="icon">🎓</span>
          <span>Education</span>
        </button>
      </aside>

      <!-- MAIN PANELS -->
      <main class="pw-editor-main">
        <!-- CREATE RESUME -->
        <section id="panel-newRes" class="pw-editor-panel">
          <header class="pw-panel-header">
            <div>
              <h2>New Resume</h2>
              <p>Let's create a new job scroll.</p>
            </div>
          </header>

          <form method="post" action="client.php#resume_editor" class="pw-panel-form" id="form-newRes">
            <div class="field is-horizontal">
              <div class="field-body">
                <div class="field">
                  <label class="label">Title</label>
                  <div class="control">
                    <input class="input" type="text" name="title" placeholder="...">
                  </div>
                </div>
              </div>
            </div>

            <button type="submit" class="button" name="save_resume">
              Create
            </button>
          </form>
        </section>

        <!-- DELETE RESUME -->
        <section id="panel-delRes" class="pw-editor-panel">
          <header class="pw-panel-header">
            <div>
              <h2>Delete Resume</h2>
              <p>This action cannot be undone.</p>
            </div>
          </header>

          <form method="post" action="client.php#resume_editor" class="pw-panel-form" id="form-delRes">
            <div class="field is-horizontal">
              <div class="field-body">
                <div class="field">
                  <label for="pwDeleteResumeSelect" class="label">Resume</label>
                  <div class="select is-fullwidth">
                    <select id="pwDeleteResumeSelect" name="resume_id">
                      <option value="1" selected>Neo-Gothic Internship</option>
                      <option value="2">Minimalist Student</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <button type="submit" class="button is-danger" name="del_resume">
              Delete
            </button>
          </form>
        </section>

        <!-- VIEW LIST RESUME -->
        <section id="panel-getRekt" class="pw-editor-panel">
          <header class="pw-panel-header">
            <div>
              <h2>View List</h2>
              <p>Pick a resume you want to view.</p>
            </div>
          </header>

          <form method="post" action="#" class="pw-panel-form" id="form-getRekt">
            <div class="radio-card-grid animate__animated animate__fadeIn" id="resumeSelector">
              <!-- Resume Option -->
              <label>
                <input type="radio" name="resume_id" value="1" onchange="this.form.submit()">
                <span>🧾 <strong>Internship - 2024</strong><br>
                  <small>Last edited: 2 days ago</small>
                </span>
              </label>

              <label>
                <input type="radio" name="resume_id" value="2" onchange="this.form.submit()">
                <span>🪄 <strong>Frontend Witcher</strong><br>
                  <small>Last edited: 1 week ago</small>
                </span>
              </label>

              <label>
                <input type="radio" name="resume_id" value="3" onchange="this.form.submit()">
                <span>🧠 <strong>Research (PhD)</strong><br>
                  <small>Last edited: 3 months ago</small>
                </span>
              </label>

              <!-- Add more options dynamically -->
            </div>
          </form>
        </section>

        <!-- RESUME INFO PANEL -->
        <section id="panel-info" class="pw-editor-panel is-active">
          <header class="pw-panel-header">
            <div>
              <h2>Resume Info</h2>
              <!-- <p>Who are you on this scroll? Keep it short, sharp and human.</p> -->
            </div>
            <button type="button" class="button is-light pw-edit-btn">Edit</button>
          </header>
          <!-- Toggle for include Account photo or not. -->

          <form method="post" action="client.php#resume_editor" class="pw-panel-form" id="form-info">
            <div class="field is-horizontal">
              <div class="field-body">
                <div class="field">
                  <label class="label">Title</label>
                  <div class="control">
                    <input class="input" type="text" name="title" placeholder="..." disabled>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Headline</label>
                  <div class="control">
                    <input class="input" type="text" name="info_headline"
                      value="IT student • Junior dev • Curious tinkerer" disabled>
                  </div>
                </div>
              </div>
            </div>

            <div class="field">
              <label class="label">Short summary</label>
              <div class="control">
                <textarea class="textarea" name="info_summary" rows="3" disabled>Enthusiastic junior techie with a soft spot for clean code, tinkering and learning-by-doing.</textarea>
              </div>
            </div>

            <div class="pw-panel-actions">
              <button type="submit" class="button btn-cta pw-save-btn" name="save_info" hidden>
                Save
              </button>
              <button type="button" class="button is-light pw-cancel-btn" hidden>
                Cancel
              </button>
            </div>
          </form>
        </section>

        <!-- PROFILE PANEL -->
        <section id="panel-profile" class="pw-editor-panel">
          <header class="pw-panel-header">
            <div>
              <h2>Profile</h2>
              <p>Who are you on? Keep it short, sharp and human.</p>
            </div>
            <button type="button" class="button is-light pw-edit-btn">
              Edit
            </button>
          </header>

          <form method="post" action="client.php#resume_editor" class="pw-panel-form" id="form-profile">
            <div class="field is-horizontal">
              <div class="field-body">
                <div class="field">
                  <label class="label">Full name</label>
                  <div class="control">
                    <input class="input" type="text" name="profile_name" value="Allen Pieter" disabled>
                  </div>
                </div>
                <div class="field">
                  <label class="label">Headline</label>
                  <div class="control">
                    <input class="input" type="text" name="profile_headline"
                      value="IT student • Junior dev • Curious tinkerer" disabled>
                  </div>
                </div>
              </div>
            </div>

            <div class="field">
              <label class="label">Short summary</label>
              <div class="control">
                <textarea class="textarea" name="profile_summary" rows="3" disabled>Enthusiastic junior techie with a soft spot for clean code, tinkering and learning-by-doing.</textarea>
              </div>
            </div>

            <div class="pw-panel-actions">
              <button type="submit" class="button btn-cta pw-save-btn" name="save_profile" hidden>
                Save
              </button>
              <button type="button" class="button is-light pw-cancel-btn" hidden>
                Cancel
              </button>
            </div>
          </form>
        </section>

        <!-- CONTACT PANEL -->
        <section id="panel-contact" class="pw-editor-panel">
          <header class="pw-panel-header">
            <div>
              <h2>Contact</h2>
              <p>Where should recruiters send ravens, emails or internship contracts?</p>
            </div>
            <button type="button" class="button is-light pw-edit-btn">
              Edit
            </button>
          </header>

          <form method="post" action="client.php#resume_editor" class="pw-panel-form" id="form-contact">
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

            <div class="field">
              <label class="label">Portfolio / Website</label>
              <div class="control">
                <input class="input" type="url" name="contact_website"
                  value="https://example.com" disabled>
              </div>
            </div>

            <div class="pw-panel-actions">
              <button type="submit" class="button btn-cta pw-save-btn" name="save_contact" hidden>
                Save changes
              </button>
              <button type="button" class="button is-light pw-cancel-btn" hidden>
                Cancel
              </button>
            </div>
          </form>
        </section>

        <!-- EXPERIENCE PANEL -->
        <section id="panel-experience" class="pw-editor-panel">
          <header class="pw-panel-header">
            <div>
              <h2>Experience</h2>
              <p>Projects, jobs and side-quests that prove you can actually do things.</p>
            </div>
            <button type="button" class="button is-light pw-edit-btn">
              Edit
            </button>
          </header>

          <form method="post" action="client.php#resume_editor" class="pw-panel-form" id="form-experience">
            <div class="pw-repeater" data-repeater="experience">
              <!-- EXPERIENCE ITEM -->
              <div class="pw-repeater-item">
                <div class="field is-horizontal">
                  <div class="field-body">
                    <div class="field">
                      <label class="label">Role</label>
                      <div class="control">
                        <input class="input" type="text" name="exp_role[]" value="Junior IT Support (Intern)" disabled>
                      </div>
                    </div>
                    <div class="field">
                      <label class="label">Company</label>
                      <div class="control">
                        <input class="input" type="text" name="exp_company[]" value="TechCorp" disabled>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="field is-horizontal">
                  <div class="field-body">
                    <div class="field">
                      <label class="label">Start</label>
                      <div class="control">
                        <input class="input" type="month" name="exp_start[]" value="2024-01" disabled>
                      </div>
                    </div>
                    <div class="field">
                      <label class="label">End</label>
                      <div class="control">
                        <input class="input" type="month" name="exp_end[]" value="2024-06" disabled>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="field">
                  <label class="label">Description</label>
                  <div class="control">
                    <textarea class="textarea" name="exp_desc[]" rows="3" disabled>
Helped maintain workstations, solved ticketed issues and documented fixes in a friendly way.
                    </textarea>
                  </div>
                </div>

                <button type="button" class="button is-text pw-remove-item" disabled>
                  Remove this experience
                </button>

                <hr class="pw-repeater-divider">
              </div>
            </div>

            <button type="button" class="button is-dark is-small pw-add-item" data-repeater-target="experience">
              + Add another experience
            </button>

            <div class="pw-panel-actions">
              <button type="submit" class="button btn-cta pw-save-btn" name="save_experience" hidden>
                Save changes
              </button>
              <button type="button" class="button is-light pw-cancel-btn" hidden>
                Cancel
              </button>
            </div>
          </form>

          <!-- TEMPLATE FOR JS CLONING -->
          <template id="tpl-experience-item">
            <div class="pw-repeater-item">
              <div class="field is-horizontal">
                <div class="field-body">
                  <div class="field">
                    <label class="label">Role</label>
                    <div class="control">
                      <input class="input" type="text" name="exp_role[]" placeholder="Role / Position">
                    </div>
                  </div>
                  <div class="field">
                    <label class="label">Company</label>
                    <div class="control">
                      <input class="input" type="text" name="exp_company[]" placeholder="Company / Organization">
                    </div>
                  </div>
                </div>
              </div>

              <div class="field is-horizontal">
                <div class="field-body">
                  <div class="field">
                    <label class="label">Start</label>
                    <div class="control">
                      <input class="input" type="month" name="exp_start[]">
                    </div>
                  </div>
                  <div class="field">
                    <label class="label">End</label>
                    <div class="control">
                      <input class="input" type="month" name="exp_end[]">
                    </div>
                  </div>
                </div>
              </div>

              <div class="field">
                <label class="label">Description</label>
                <div class="control">
                  <textarea class="textarea" name="exp_desc[]" rows="3"
                    placeholder="What did you actually do, fix or ship?"></textarea>
                </div>
              </div>

              <button type="button" class="button is-text pw-remove-item">
                Remove this experience
              </button>

              <hr class="pw-repeater-divider">
            </div>
          </template>
        </section>

        <!-- Education PANEL -->
        <section id="panel-education" class="pw-editor-panel">
          <header class="pw-panel-header">
            <div>
              <h2>Education</h2>
              <p>Projects, jobs and side-quests that prove you can actually do things.</p>
            </div>
            <button type="button" class="button is-light pw-edit-btn">
              Edit
            </button>
          </header>

          <form method="post" action="client.php#resume_editor" class="pw-panel-form" id="form-education">
            <div class="pw-repeater" data-repeater="education">
              <!-- Education ITEM -->
              <div class="pw-repeater-item">
                <div class="field is-horizontal">
                  <div class="field-body">
                    <div class="field">
                      <label class="label">Role</label>
                      <div class="control">
                        <input class="input" type="text" name="edu_role[]" value="Software Developer (Intern)" disabled>
                      </div>
                    </div>
                    <div class="field">
                      <label class="label">Institute</label>
                      <div class="control">
                        <input class="input" type="text" name="edu_company[]" value="TechCorp" disabled>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="field is-horizontal">
                  <div class="field-body">
                    <div class="field">
                      <label class="label">Start</label>
                      <div class="control">
                        <input class="input" type="month" name="edu_start[]" value="2024-01" disabled>
                      </div>
                    </div>
                    <div class="field">
                      <label class="label">End</label>
                      <div class="control">
                        <input class="input" type="month" name="edu_end[]" value="2024-06" disabled>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="field">
                  <label class="label">Description</label>
                  <div class="control">
                    <textarea class="textarea" name="edu_desc[]" rows="3" disabled>
Helped maintain workstations, solved ticketed issues and documented fixes in a friendly way.
                    </textarea>
                  </div>
                </div>

                <button type="button" class="button is-text pw-remove-item" disabled>
                  Remove this education
                </button>

                <hr class="pw-repeater-divider">
              </div>
            </div>

            <button type="button" class="button is-dark is-small pw-add-item" data-repeater-target="education">
              + Add another education
            </button>

            <div class="pw-panel-actions">
              <button type="submit" class="button btn-cta pw-save-btn" name="save_education" hidden>
                Save changes
              </button>
              <button type="button" class="button is-light pw-cancel-btn" hidden>
                Cancel
              </button>
            </div>
          </form>

          <!-- TEMPLATE FOR JS CLONING -->
          <template id="tpl-education-item">
            <div class="pw-repeater-item">
              <div class="field is-horizontal">
                <div class="field-body">
                  <div class="field">
                    <label class="label">Role</label>
                    <div class="control">
                      <input class="input" type="text" name="edu_role[]" placeholder="Role / Position">
                    </div>
                  </div>
                  <div class="field">
                    <label class="label">Institute</label>
                    <div class="control">
                      <input class="input" type="text" name="edu_company[]" placeholder="Company / Organization">
                    </div>
                  </div>
                </div>
              </div>

              <div class="field is-horizontal">
                <div class="field-body">
                  <div class="field">
                    <label class="label">Start</label>
                    <div class="control">
                      <input class="input" type="month" name="edu_start[]">
                    </div>
                  </div>
                  <div class="field">
                    <label class="label">End</label>
                    <div class="control">
                      <input class="input" type="month" name="edu_end[]">
                    </div>
                  </div>
                </div>
              </div>

              <div class="field">
                <label class="label">Description</label>
                <div class="control">
                  <textarea class="textarea" name="edu_desc[]" rows="3"
                    placeholder="What did you actually study?"></textarea>
                </div>
              </div>

              <button type="button" class="button is-text pw-remove-item">
                Remove this education
              </button>

              <hr class="pw-repeater-divider">
            </div>
          </template>
        </section>

      </main>
    </div>
  </div>
</section>
<script>
    document.addEventListener("DOMContentLoaded", () => {
  const editor = document.querySelector(".pw-editor-shell");
  if (!editor) return;

  /* ------------- SECTION TABS + MOBILE SELECT ------------- */
  const tabs = editor.querySelectorAll(".pw-editor-tab");
  const panels = editor.querySelectorAll(".pw-editor-panel");
  const mobileSelect = editor.querySelector("#editorSectionSelect");

  function activatePanel(panelKey) {
    // Sidebar buttons
    tabs.forEach(btn => {
      btn.classList.toggle("is-active", btn.dataset.panel === panelKey);
    });

    // Panels
    panels.forEach(panel => {
      panel.classList.toggle(
        "is-active",
        panel.id === `panel-${panelKey}`
      );
    });

    // Sync mobile select if present
    if (mobileSelect && mobileSelect.value !== panelKey) {
      mobileSelect.value = panelKey;
    }
  }

  tabs.forEach(btn => {
    btn.addEventListener("click", () => {
      activatePanel(btn.dataset.panel);
    });
  });

  if (mobileSelect) {
    mobileSelect.addEventListener("change", () => {
      activatePanel(mobileSelect.value);
    });
  }

  // Default panel
  activatePanel("info");


  /* ------------- EDIT / SAVE / CANCEL HANDLING ------------- */
  const panelsWithForms = editor.querySelectorAll(".pw-editor-panel");

  panelsWithForms.forEach(panel => {
    const editBtn   = panel.querySelector(".pw-edit-btn");
    const saveBtn   = panel.querySelector(".pw-save-btn");
    const cancelBtn = panel.querySelector(".pw-cancel-btn");
    const form      = panel.querySelector("form");
    if (!editBtn || !saveBtn || !cancelBtn || !form) return;

    const inputs = form.querySelectorAll("input, textarea, select");

    function setEditing(isEditing) {
      inputs.forEach(input => {
        // If you ever want non-editable fields, guard by id or data-attr here
        if (!isEditing) {
          input.setAttribute("disabled", "disabled");
        } else {
          input.removeAttribute("disabled");
        }
      });

      editBtn.hidden   = isEditing;
      saveBtn.hidden   = !isEditing;
      cancelBtn.hidden = !isEditing;
    }

    // Initial: view mode
    setEditing(false);

    editBtn.addEventListener("click", () => {
      setEditing(true);
    });

    cancelBtn.addEventListener("click", () => {
      // Simple version: just lock again and let PHP reload “real” values on next page load
      setEditing(false);
    });

    // You can let the form submit normally; no extra JS needed for saveBtn
    // saveBtn just submits with its name attribute for PHP handling
  });


  /* ------------- REPEATER (EXPERIENCE) ------------- */
  const repeaterButtons = editor.querySelectorAll(".pw-add-item");

  repeaterButtons.forEach(addBtn => {
    const targetName = addBtn.dataset.repeaterTarget;
    const container = editor.querySelector(
      `.pw-repeater[data-repeater="${targetName}"]`
    );
    const template = editor.querySelector(`#tpl-${targetName}-item`);
    if (!container || !template) return;

    addBtn.addEventListener("click", () => {
      const clone = template.content.cloneNode(true);
      container.appendChild(clone);

      // ensure new remove buttons work
      wireRemoveButtons(container);
    });

    // initial wiring
    wireRemoveButtons(container);
  });

  function wireRemoveButtons(scope) {
    const removeButtons = scope.querySelectorAll(".pw-remove-item");
    removeButtons.forEach(btn => {
      if (btn.dataset.wired === "1") return;
      btn.dataset.wired = "1";

      btn.addEventListener("click", () => {
        const item = btn.closest(".pw-repeater-item");
        if (item) item.remove();
      });
    });
  }
});
</script>
</html>