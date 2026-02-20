<section id="home" class="<?= ViewBook::setVisibility('home'); ?>">
  <div class="pw-editor-shell">
    <header class="pw-editor-header">
      <div>
        <h1 class="pw-editor-title">Job Scroll Builder</h1>
        <p class="pw-editor-sub">Brew, tweak and trim your resume sections before sending them into the wild.</p>
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
          <option value="newRes">+ Create a Resume</option>
          <option value="delRes">- Delete a Resume</option>
          <option value="fetchRes"># My List</option>
          <option value="info" selected>Resume Info</option>
          <option value="experience">Experience</option>
          <option value="education">Education</option>
          <option value="social">Social Media</option>
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
          <button class="button pw-editor-tab" data-panel="fetchRes">
          <span>My List</span>
        </button>
        <hr>
        <button class="pw-editor-tab is-active" data-panel="info">
          <span class="icon">⚗️</span>
          <span>Resume Info</span>
        </button>
        <button class="pw-editor-tab" data-panel="experience">
          <span class="icon">🏹</span>
          <span>Experience</span>
        </button>
        <button class="pw-editor-tab" data-panel="education">
          <span class="icon">🎓</span>
          <span>Education</span>
        </button>
        <button class="pw-editor-tab" data-panel="social">
          <span class="icon">📷</span>
          <span>Social Media</span>
        </button>
      </aside>

      <!-- MAIN PANELS -->
      <main class="pw-editor-main">
        <!-- CREATE RESUME -->
        <section id="panel-newRes" class="pw-editor-panel">
          <header class="pw-panel-header">
            <div>
              <h2>Create a resume</h2>
              <p>First, let's give it a name. Preferably short & concise.</p>
            </div>
          </header>

          <form id="form-newRes" class="pw-panel-form" action="./config/action_handler.conf.php" method="post">
            <div class="field is-horizontal">
              <div class="field-body">
                <div class="field">
                  <label class="label">Title</label>
                  <div class="control">
                    <input class="input" type="text" name="resume_title" placeholder="...">
                  </div>
                </div>
              </div>
            </div>
            <button type="submit" class="button" name="action" value="resume">Create</button>
            <input type="hidden" name="create" value="create">
            <input type="hidden" name="user_id" value="">
          </form>
        </section>

        <!-- DELETE RESUME -->
        <section id="panel-delRes" class="pw-editor-panel">
          <header class="pw-panel-header">
            <div>
              <h2>Remove a resume</h2>
              <p>Select a job scroll to erase from your list.</p>
            </div>
          </header>

          <form id="form-delRes" class="pw-panel-form" action="./config/action_handler.conf.php" method="post">
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

            <button type="submit" class="button is-danger" name="action" value="resume">Delete</button>
            <input type="hidden" name="delete" value="delete">
            <input type="hidden" name="user_id" value="">
          </form>
        </section>

        <!-- VIEW LIST RESUME -->
        <section id="panel-fetchRes" class="pw-editor-panel">
          <header class="pw-panel-header">
            <div>
              <h2>My List</h2>
              <p>Select your job scroll below to view and edit.</p>
            </div>
          </header>

          <form id="form-fetchRes" class="pw-panel-form" action="./config/action_handler.conf.php" method="post">
            <input type="hidden" name="action" value="resume">
            <input type="hidden" name="fetch" value="fetch">
            <input type="hidden" name="resume_id" value="">
            <input type="hidden" name="user_id" value="">
            <div class="radio-card-grid animate__animated animate__fadeIn" id="resumeSelector">
              <?php if (!empty($resumes)): foreach ($resumes as $resume): ?>
                <?php
                  $updated = new DateTime($resume['updated_at']);
                  $now = new DateTime();
                  $diff = $now->diff($updated);

                  if ($diff->d > 0) {
                    $timeAgo = $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
                  } elseif ($diff->h > 0) {
                    $timeAgo = $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
                  } elseif ($diff->i > 0) {
                    $timeAgo = $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
                  } else {
                    $timeAgo = 'Just now';
                  }
                ?>
                <label>
                  <input type="radio" name="resume_id" value="<?= htmlspecialchars($resume['resume_id']) ?>" onchange="this.form.submit()">
                  <span>🧾 <strong><?= htmlspecialchars($resume['resume_title']) ?></strong><br><small>Last edited: <?= $timeAgo ?></small></span>
                </label>
              <?php endforeach; ?>
              <?php else: ?>
                <label>
                  <input type="radio">
                  <span>🧾 <strong>Internship - 2024</strong><br><small>Last edited: 2 days ago</small></span>
                </label>

                <label>
                  <input type="radio">
                  <span>🪄 <strong>Frontend Witcher</strong><br><small>Last edited: 1 week ago</small></span>
                </label>

                <label>
                  <input type="radio">
                  <span>🧠 <strong>Research (PhD)</strong><br><small>Last edited: 3 months ago</small></span>
                </label>
              <?php endif; ?>
            </div>
          </form>
        </section>

        <!-- RESUME INFO PANEL -->
        <section id="panel-info" class="pw-editor-panel is-active">
          <header class="pw-panel-header">
            <div>
              <h2>Resume Info</h2>
              <p>Rename your resume or write a short summary about yourself, it's optional.</p>
            </div>
          </header>
          <!-- Toggle for include Account photo or not. -->

          <form id="form-info" class="pw-panel-form" action="./config/action_handler.conf.php" method="post">
            <div class="field">
              <label class="label">Resume Title</label>
              <div class="control">
                <input class="input" type="text" name="resume_title" placeholder="Name of this resume">
              </div>
            </div>

            <div class="field">
              <label class="label">Summary</label>
              <div class="control">
                <textarea class="textarea" name="summary" rows="3" placeholder="Enthusiastic junior techie with a soft spot for clean code, tinkering and learning-by-doing."></textarea>
              </div>
            </div>

            <div class="pw-panel-actions">
              <button type="submit" class="button btn-cta pw-save-btn" name="action" value="resume">Save</button>
            </div>
            <input type="hidden" name="action" value="resume">
            <input type="hidden" name="update" value="update">
            <input type="hidden" name="resume_id" value="">
            <input type="hidden" name="user_id" value="">
          </form>
        </section>

        <!-- EXPERIENCE PANEL -->
        <section id="panel-experience" class="pw-editor-panel">
          <header class="pw-panel-header">
            <div>
              <h2>Experience</h2>
              <p>Projects, jobs and side-quests that prove you can actually do things.</p>
            </div>
          </header>

          <form id="form-experience" class="pw-panel-form" action="./config/action_handler.conf.php" method="post">
            <div class="pw-repeater" data-repeater="experience">
              <!-- EXPERIENCE ITEM -->
              <!-- <div class="pw-repeater-item">
                <div class="field is-horizontal">
                  <div class="field-body">
                    <div class="field">
                      <label class="label">Role</label>
                      <div class="control">
                        <input class="input" type="text" name="exp_role[]" placeholder="Junior IT Support (Intern)">
                      </div>
                    </div>
                    <div class="field">
                      <label class="label">Company</label>
                      <div class="control">
                        <input class="input" type="text" name="exp_company[]" placeholder="TechCorp">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="field is-horizontal">
                  <div class="field-body">
                    <div class="field">
                      <label class="label">Start</label>           
                      <div class="control">
                        <input class="input" type="date" name="exp_start[]">
                      </div>
                    </div>
                    <div class="field">
                      <label class="label">End</label>
                      <div class="control">
                        <input class="input" type="date" name="exp_end[]">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="field">
                  <label class="label">Description</label>
                  <div class="control">
                    <textarea class="textarea" name="exp_desc[]" rows="3" placeholder="..."></textarea>
                  </div>
                </div>

                <button type="button" class="button is-text pw-remove-item" disabled>Remove this experience</button>
                <hr class="pw-repeater-divider">
              </div> -->
            </div>

            <button type="button" class="button is-dark is-small pw-add-item" data-repeater-target="experience">
              + Add an experience
            </button>

            <div class="pw-panel-actions">
              <button type="submit" class="button btn-cta pw-save-btn" name="action" value="experience" disabled>Save</button>
            </div>
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="resume_id" value="">
            <input type="hidden" name="experience_id" value="">
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
                      <input class="input" type="month" name="exp_start[]" placeholder="YYYY-MM" pattern="\d{4}-\d{2}">
                    </div>
                  </div>
                  <div class="field">
                    <label class="label">End</label>
                    <div class="control">
                      <input class="input" type="month" name="exp_end[]" placeholder="YYYY-MM" pattern="\d{4}-\d{2}">
                    </div>
                  </div>
                </div>
              </div>

              <div class="field">
                <label class="label">Description</label>
                <div class="control">
                  <textarea class="textarea" name="exp_desc[]" rows="3" placeholder="What did you actually do, fix or ship?"></textarea>
                </div>
              </div>

              <button type="button" class="button is-text pw-remove-item" name="action" value="delete_exp">Remove this experience</button>
              <hr class="pw-repeater-divider">
            </div>
          </template>
        </section>

        <!-- Education PANEL -->
        <section id="panel-education" class="pw-editor-panel">
          <header class="pw-panel-header">
            <div>
              <h2>Education</h2>
              <p>Projects, courses and internships that limit gaps in your years.</p>
            </div>
          </header>

          <form id="form-education" class="pw-panel-form" action="./config/action_handler.conf.php" method="post">
            <div class="pw-repeater" data-repeater="education">
              <!-- Education ITEM -->
              <!-- <div class="pw-repeater-item">
                <div class="field is-horizontal">
                  <div class="field-body">
                    <div class="field">
                      <label class="label">Role</label>
                      <div class="control">
                        <input class="input" type="text" name="edu_role[]" placeholder="Software Developer (Intern)">
                      </div>
                    </div>
                    <div class="field">
                      <label class="label">Institute</label>
                      <div class="control">
                        <input class="input" type="text" name="edu_company[]" placeholder="TechCorp">
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
                    <textarea class="textarea" name="edu_desc[]" rows="3" placeholder="..."></textarea>
                  </div>
                </div>

                <button type="button" class="button is-text pw-remove-item" disabled>Remove this education</button>
                <hr class="pw-repeater-divider">
              </div> -->
            </div>

            <button type="button" class="button is-dark is-small pw-add-item" data-repeater-target="education">
              + Add an education
            </button>

            <div class="pw-panel-actions">
              <button type="submit" class="button btn-cta pw-save-btn" name="action" value="education" disabled>Save</button>
            </div>
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="resume_id" value="">
            <input type="hidden" name="education_id" value="">
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
                  <textarea class="textarea" name="edu_desc[]" rows="3" placeholder="What did you actually study?"></textarea>
                </div>
              </div>
              <form action="">
                <input type="hidden" name="edu_id" value="">
                <input type="hidden" name="user_id" value="">
                <input type="hidden" name="resume_id" value="">
                <input type="hidden" name="delete_edu" value="delete_edu">
                <button type="button" class="button is-text pw-remove-item" name="action" value="education">Remove this education</button>
              </form>
              <hr class="pw-repeater-divider">
            </div>
          </template>
        </section>

        <!-- SOCIAL MEDIA PANEL -->
        <section id="panel-social" class="pw-editor-panel">
          <header class="pw-panel-header">
            <div>
              <h2>Social Media</h2>
              <p>Do you have a portfolio or completed projects you'd like to point recruiters to?</p>
              <p>Keep it relevant to the job. Between 1–3 links is ideal.</p>
            </div>
          </header>

          <form id="form-social" class="pw-panel-form" action="./config/action_handler.conf.php" method="post">
            <div class="pw-repeater" data-repeater="social">
              <!-- Social ITEM -->
              <!-- <div class="pw-repeater-item">
                <div class="field">
                  <label class="label">Link</label>
                  <div class="control">
                    <input class="input" type="url" name="social_website[]" placeholder="https://example.com">
                  </div>
                </div>
                <button type="button" class="button is-text pw-remove-item" disabled>Remove this url</button>
              </div> -->
            </div>

            <button type="button" class="button is-dark is-small pw-add-item" data-repeater-target="social">
              + Add a link
            </button>

            <div class="pw-panel-actions">
              <button type="submit" class="button btn-cta pw-save-btn" name="action" value="set_social" disabled>Save</button>
            </div>
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="resume_id" value="">
          </form>

          <!-- TEMPLATE FOR JS CLONING -->
          <template id="tpl-social-item">
            <div class="pw-repeater-item">
              <div class="field">
                <label class="label">Link</label>
                <div class="control">
                  <input class="input" type="url" name="social_website[]" placeholder="https://example.com">
                </div>
              </div>
              <form action="./config/action_handler.conf.php" method="post">
                <input type="hidden" name="user_id" value="">
                <input type="hidden" name="resume_id" value="">
                <input type="hidden" name="social_id" value="">
                <button type="button" class="button is-text pw-remove-item" name="action" value="delete_social">Remove this link</button>
              </form>
            </div>
          </template>
        </section>

      </main>
    </div>
  </div>
</section>