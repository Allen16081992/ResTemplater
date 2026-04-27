<section id="builder" class="<?= ViewBook::setVisibility('builder'); ?>">
  <div class="pw-editor-shell">
    <header class="pw-editor-header">
      <div>
        <h1 class="pw-editor-title">Job Scroll Builder</h1>
        <p class="pw-editor-sub">Brew, tweak and trim your resume sections before sending them into the wild.</p>
      </div>
      <div class="pw-editor-status">
        <!-- <span class="pw-editor-status-pill">
          <span>🧪</span> Draft saved locally
        </span> -->
        <?php if (isset($_SESSION['session_data']['user_id'])) { ?>
          <a class="pw-btn" data-section="home">Switch Editor</a>
        <?php } if (!empty($data['resdata'])) { ?>
          <small>Selected: <strong><?= htmlspecialchars($data['resdata']['title']) ?></strong></small>
        <?php } ?>
      </div>
    </header>

    <!-- Mobile section selector -->
    <div class="pw-editor-mobile-select">
      <div class="select is-fullwidth">
        <select id="editorSectionSelect">
          <option value="newRes">+ Create a Resume</option>
          <?php if (isset($data['papers']) && !empty($data['papers'])) { ?>
            <option value="delRes">- Delete a Resume</option>
            <option value="fetchRes"># My List</option>
            <?php if (isset($data['resdata']['title'])) { ?>
              <option value="info" selected>Resume Info</option>
              <option value="experience">Experience</option>
              <option value="education">Education</option>
              <option value="projects">Projects</option>
              <option value="skills">Skills</option>
              <option value="social">Social Media</option>
            <?php } ?>
          <?php } ?>
          <option value="template">Templates</option>
        </select>
      </div>
    </div>

    <div class="pw-editor-layout">
      <!-- SIDEBAR -->
      <aside class="pw-editor-sidebar" aria-label="Resume sections">
        <div class="pw-nav-title">Actions</div>
        <button class="pw-editor-tab" data-panel="newRes"><span>➕ New</span></button>
        <?php if (isset($data['papers']) && !empty($data['papers'])) { ?>
          <button class="pw-editor-tab" data-panel="delRes"><span>❌ Delete</span></button>
          <button class="pw-editor-tab" data-panel="fetchRes"><span>💼 My List</span></button>
        <?php } ?>
        <hr>
        <?php if (isset($data['resdata']['title'])) { ?>
          <div class="pw-nav-title">Resume</div>
          <button class="pw-editor-tab is-active" data-panel="info"><span class="icon">📃</span><span>Resume Info</span></button>
          <button class="pw-editor-tab" data-panel="experience"><span class="icon">🏹</span><span>Experience</span></button>
          <button class="pw-editor-tab" data-panel="education"><span class="icon">🎓</span><span>Education</span></button>
          <button class="pw-editor-tab" data-panel="projects"><span class="icon">🚀</span><span>Projects</span></button>
          <button class="pw-editor-tab" data-panel="skills"><span class="icon">⚗️</span><span>Hard & Soft Skills</span></button>
          <button class="pw-editor-tab" data-panel="social"><span class="icon">📷</span><span>Social Media</span></button>
          <hr>
          <button class="pw-editor-tab" data-panel="template"><span class="icon">🗂️</span><span>Templates</span></button>
        <?php } else { ?>
          <span>Make a new resume to see more options!</span>
        <?php } ?>
      </aside>

      <!-- MAIN PANELS -->
      <main class="pw-editor-main">
        <!-- CREATE RESUME -->
        <section id="panel-newRes" class="pw-editor-panel">
          <header class="pw-panel-header">
            <div>
              <h2>Create a resume</h2>
              <p>First, let's give it a name.</p>
            </div>
          </header>

          <form id="form-newRes" class="pw-panel-form" action="./config/action_handler.conf.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="user_id" value="<?= isset($_SESSION['session_data']['user_id']) ? $uid = $_SESSION['session_data']['user_id'] : ''; ?>">
            <input type="hidden" name="create">
            <div class="field is-horizontal">
              <div class="field-body">
                <div class="field">
                  <div class="is-flex">
                    <label class="label">Title</label>
                    <div id="server-field"><?= htmlspecialchars($_SESSION['error']['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                  </div>
                  <div class="control">
                    <input class="input" type="text" name="title" placeholder="...">
                  </div>
                </div>
              </div>
            </div>
            <button type="submit" class="button is-link" name="action" value="resume">Create</button>
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
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['session_data']['user_id'] ?? '') ?>">
            <input type="hidden" name="delete">
            <div class="field is-horizontal">
              <div class="field-body">
                <div class="field">
                  <label for="pwDeleteResumeSelect" class="label">Resume</label>
                  <div class="select is-fullwidth">
                    <select id="pwDeleteResumeSelect" name="resume_id">
                      <?php if (!empty($list['papers'])) { ?>
                        <option disabled>: Select</option>
                        <?php foreach ($list['papers'] as $paper) { ?>
                          <option value="<?= htmlspecialchars($paper['id']) ?>"><?= htmlspecialchars($paper['title']) ?></option>
                        <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <button type="submit" class="button is-danger" style="margin-top:7px;" name="action" value="resume">Delete</button>
          </form>
        </section>

        <!-- LIST RESUME -->
        <section id="panel-fetchRes" class="pw-editor-panel">
          <header class="pw-panel-header">
            <div>
              <h2>My List</h2>
              <p>Select your job scroll below to view and edit.</p>
            </div>
          </header>

          <form id="form-fetchRes" class="pw-panel-form" action="#" method="post">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['session_data']['user_id'] ?? '') ?>">
            <input type="hidden" name="selectCV">
            <div class="radio-card-grid animate__animated animate__fadeIn" id="resumeSelector">
              <?php if (!empty($resumes)): foreach ($resumes as $resume):
                  // Set last updated info
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
                  <span>🧾 <strong><?= htmlspecialchars($resume['title']) ?></strong><br><small>Last edited: <?= $timeAgo ?></small></span>
                </label>
              <?php endforeach; ?>
                <!-- <label>
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
                </label> -->
              <?php endif; ?>
            </div>
          </form>
        </section>

        <!-- RESUME INFO PANEL -->
        <section id="panel-info" class="pw-editor-panel is-active">
          <?php if (isset($data['papers']) && !empty($data['papers'])) { ?>
          <header class="pw-panel-header">
            <div>
              <h2>Resume Info</h2>
              <p>Rename your resume or write a short summary about yourself, it's optional.</p>
            </div>
          </header>
          <!-- Toggle for include Account photo or not. -->

          <form id="form-info" class="pw-panel-form" action="./config/action_handler.conf.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['session_data']['user_id'] ?? '') ?>">
            <input type="hidden" name="resume_id" value="<?= htmlspecialchars($data['resdata']['id'] ?? '') ?>">
            <input type="hidden" name="update">
            <div class="field">
              <label class="label">Resume Title</label>
              <div class="control">
                <input class="input" type="text" name="title" placeholder="Name of this resume" value="<?= htmlspecialchars($data['resdata']['title'] ?? '') ?>">
              </div>
            </div>

            <div class="field">
              <label class="label">Summary</label>
              <div class="control">
                <textarea class="textarea" name="summary" rows="3" placeholder="Enthusiastic junior techie with a soft spot for clean code, tinkering and learning-by-doing."><?= htmlspecialchars($data['resdata']['summary'] ?? '') ?></textarea>
              </div>
            </div>

            <div class="pw-panel-actions">
              <button type="submit" name="action" value="resume" class="button btn-cta pw-save-btn">Save</button>
            </div>
          </form>
          <?php } else { ?>
            <header class="pw-panel-header">
              <div>
                <h2>Create a resume</h2>
                <p>First, let's give it a name.</p>
              </div>
            </header>

            <form id="form-newRes" class="pw-panel-form" action="./config/action_handler.conf.php" method="post">
              <input type="hidden" name="user_id">
              <input type="hidden" name="create">
              <div class="field is-horizontal">
                <div class="field-body">
                  <div class="field">
                    <div class="is-flex">
                      <label class="label">Title</label>
                      <div id="server-field"><?= htmlspecialchars($_SESSION['error']['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                    <div class="control">
                      <input class="input" type="text" name="title" placeholder="...">
                    </div>
                  </div>
                </div>
              </div>
              <button type="submit" class="button is-link" name="action" value="resume">Create</button>
            </form>
          <?php } ?>
        </section>

        <!-- EXPERIENCE PANEL -->
        <section id="panel-experience" class="pw-editor-panel">
          <header class="pw-panel-header">
            <div>
              <h2>Experience</h2>
              <p>Jobs and side-quests (holiday work) that prove you can actually do things.</p>
              <ul style="margin:10px; font-size:0.8rem;">Hint: for dates, you may use:
                  <li>● Slashes, dots, or minus</li>
                  <li>● Words as today, tomorrow or yesterday</li>
                  <li>● Leaving the day out or not</li>
                  <li>● If you leave End field empty, it will default to 'Present'</li>
              </ul>
            </div>
          </header>

          <form id="form-experience" class="pw-panel-form" action="./config/action_handler.conf.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['session_data']['user_id'] ?? '') ?>">
            <input type="hidden" name="resume_id" value="<?= htmlspecialchars($data['resdata']['id'] ?? '') ?>">
            <div class="pw-repeater" data-repeater="experience">
              <!-- EXPERIENCE ITEM -->
              <?php if (!empty($data['experience'])) { ?>
                <?php foreach ($data['experience'] as $exp) { ?>
                  <div class="pw-repeater-item">
                    <div class="field is-horizontal">
                      <div class="field-body">
                        <div class="field">
                          <label class="label">Job title</label>
                          <div class="control">
                            <input class="input" type="text" name="title[]" value="<?= htmlspecialchars($exp['title']) ?>">
                          </div>
                        </div>
                        <div class="field">
                          <label class="label">Company</label>
                          <div class="control">
                            <input class="input" type="text" name="employer[]" value="<?= htmlspecialchars($exp['employer']) ?>">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="field is-horizontal">
                      <div class="field-body">
                        <div class="field">
                          <label class="label">Start</label>           
                          <div class="control">
                            <input class="input" type="month" name="start_date[]" value="<?= htmlspecialchars($exp['start_date']) ?>">
                          </div>
                        </div>
                        <div class="field">
                          <label class="label">End</label>
                          <div class="control">
                            <input class="input" type="month" name="end_date[]" value="<?= htmlspecialchars($exp['end_date']) ?>">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="field">
                      <label class="label">Description</label>
                      <div class="control">
                        <textarea class="textarea" name="summary[]" rows="3"><?= htmlspecialchars($exp['summary'] ?? '') ?></textarea>
                      </div>
                    </div>
                    <button type="button" class="button is-text pw-remove-item" name="delete" disabled>Remove this experience</button>
                    <hr class="pw-repeater-divider">
                  </div>
                  <input type="hidden" name="exp_id[]" value="<?= htmlspecialchars($exp['id']) ?>">
                <?php } ?>
              <?php } ?>
            </div>
            <button type="button" class="button is-dark is-small pw-add-item" data-repeater-target="experience">+ Add an experience</button>

            <div class="pw-panel-actions">
              <button type="submit" name="action" value="experience" class="button btn-cta pw-save-btn" disabled>Save</button>
            </div>
          </form>

          <!-- TEMPLATE FOR JS CLONING -->
          <template id="tpl-experience-item">
            <div class="pw-repeater-item">
              <div class="field is-horizontal">
                <div class="field-body">
                  <div class="field">
                    <label class="label">Job title</label>
                    <div class="control">
                      <input class="input" type="text" name="title[]" placeholder="Role / Position">
                    </div>
                  </div>
                  <div class="field">
                    <label class="label">Company</label>
                    <div class="control">
                      <input class="input" type="text" name="employer[]" placeholder="Company / Organization">
                    </div>
                  </div>
                </div>
              </div>

              <div class="field is-horizontal">
                <div class="field-body">
                  <div class="field">
                    <label class="label">Start</label>
                    <div class="control">
                      <input class="input" type="month" name="start_date[]">
                    </div>
                  </div>
                  <div class="field">
                    <label class="label">End</label>
                    <div class="control">
                      <input class="input" type="month" name="end_date[]">
                    </div>
                  </div>
                </div>
              </div>

              <div class="field">
                <label class="label">Description</label>
                <div class="control">
                  <textarea class="textarea" name="summary[]" rows="3" placeholder="(Optional) What did you actually do, fix or improve?"></textarea>
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
              <p>Courses and internships.</p>
              <ul style="margin:10px; font-size:0.8rem;">Hint: for dates, you may use:
                <li>● Slashes, dots, or minus</li>
                <li>● Words as today, tomorrow or yesterday</li>
                <li>● Leaving the day out or not</li>
                <li>● If you leave End field empty, it will default to 'Present'</li>
              </ul>
            </div>
          </header>

          <form id="form-education" class="pw-panel-form" action="./config/action_handler.conf.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['session_data']['user_id'] ?? '') ?>">
            <input type="hidden" name="resume_id" value="<?= htmlspecialchars($data['resdata']['id'] ?? '') ?>">
            <div class="pw-repeater" data-repeater="education">
              <!-- Education ITEM -->
              <?php if (!empty($data['education'])) { ?>
                <?php foreach ($data['education'] as $edu) { ?>
                  <div class="pw-repeater-item">
                    <div class="field is-horizontal">
                      <div class="field-body">
                        <div class="field">
                          <label class="label">Course</label>
                          <div class="control">
                            <input class="input" type="text" name="program[]" value="<?= htmlspecialchars($edu['program']) ?>">
                          </div>
                        </div>
                        <div class="field">
                          <label class="label">Institute</label>
                          <div class="control">
                            <input class="input" type="text" name="school[]" value="<?= htmlspecialchars($edu['school']) ?>">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="field is-horizontal">
                      <div class="field-body">
                        <div class="field">
                          <label class="label">Start</label>
                          <div class="control">
                            <input class="input" type="month" name="start_date[]" value="<?= htmlspecialchars($edu['start_date']) ?>">
                          </div>
                        </div>
                        <div class="field">
                          <label class="label">End</label>
                          <div class="control">
                            <input class="input" type="month" name="end_date[]" value="<?= htmlspecialchars($edu['end_date']) ?>">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="field">
                      <label class="label">Description</label>
                      <div class="control">
                        <textarea class="textarea" name="summary[]" rows="3"><?= htmlspecialchars($edu['summary'] ?? '') ?></textarea>
                      </div>
                    </div>
                    <button type="button" class="button is-text pw-remove-item" disabled>Remove this education</button>
                    <hr class="pw-repeater-divider">
                  </div>
                  <input type="hidden" name="edu_id[]" value="<?= htmlspecialchars($edu['id']) ?>">
                <?php } ?>
              <?php } ?>
            </div>
            <button type="button" class="button is-dark is-small pw-add-item" data-repeater-target="education">
              + Add an education
            </button>

            <div class="pw-panel-actions">
              <button type="submit" name="action" value="education" class="button btn-cta pw-save-btn" disabled>Save</button>
            </div>
          </form>

          <!-- TEMPLATE FOR JS CLONING -->
          <template id="tpl-education-item">
            <div class="pw-repeater-item">
              <div class="field is-horizontal">
                <div class="field-body">
                  <div class="field">
                    <label class="label">Program</label>
                    <div class="control">
                      <input class="input" type="text" name="program[]" placeholder="Program / Course">
                    </div>
                  </div>
                  <div class="field">
                    <label class="label">School</label>
                    <div class="control">
                      <input class="input" type="text" name="school[]" placeholder="School / Organization">
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="field is-horizontal">
                <div class="field-body">
                  <div class="field">
                    <label class="label">Start</label>
                    <div class="control">
                      <input class="input" type="month" name="start_date[]">
                    </div>
                  </div>
                  <div class="field">
                    <label class="label">End</label>
                    <div class="control">
                      <input class="input" type="month" name="end_date[]">
                    </div>
                  </div>
                </div>
              </div>

              <div class="field">
                <label class="label">Description</label>
                <div class="control">
                  <textarea class="textarea" name="summary[]" rows="3" placeholder="(Optional) What did you study or learn?"></textarea>
                </div>
              </div>

              <button type="button" class="button is-text pw-remove-item" name="action" value="education">Remove this education</button>
              <hr class="pw-repeater-divider">
            </div>
          </template>
        </section>

        <!-- Projects PANEL -->
        <section id="panel-projects" class="pw-editor-panel">
          <header class="pw-panel-header">
            <div>
              <h2>Projects</h2>
              <p>Show off the things you built, the code you wrote, or the systems you designed.</p>
              <ul style="margin:10px; font-size:0.8rem;">Hint:
                  <li>● Use the summary to list your <strong>Tech Stack</strong> (e.g., PHP, MySQL).</li>
                  <li>● Focus on the "Problem" you solved and the "Result" you got.</li>
                  <li>● If you have a GitHub link, put it in the Social Media section for a QR code.</li>
              </ul>
            </div>
          </header>

          <form id="form-projects" class="pw-panel-form" action="./config/action_handler.conf.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['session_data']['user_id'] ?? '') ?>">
            <input type="hidden" name="resume_id" value="<?= htmlspecialchars($data['resdata']['id'] ?? '') ?>">
            
            <!-- Projects ITEM -->
            <div class="pw-repeater" data-repeater="projects">
              <?php if (!empty($data['projects'])) { ?>
                <?php foreach ($data['projects'] as $proj) { ?>
                  <div class="pw-repeater-item">
                    <div class="field is-horizontal">
                      <div class="field-body">
                        <div class="field">
                          <label class="label">Project Name</label>
                          <div class="control">
                            <input class="input" type="text" name="title[]" value="<?= htmlspecialchars($proj['title']) ?>">
                          </div>
                        </div>
                        <div class="field">
                          <label class="label">Your Role</label>
                          <div class="control">
                            <input class="input" type="text" name="role[]" value="<?= htmlspecialchars($proj['role'] ?? 'Lead Developer') ?>">
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="field">
                      <label class="label">Project Summary & Tech Stack</label>
                      <div class="control">
                        <textarea class="textarea" name="summary[]" rows="3"><?= htmlspecialchars($proj['summary'] ?? '') ?></textarea>
                      </div>
                    </div>
                    
                    <button type="button" class="button is-text pw-remove-item" name="delete">Remove this project</button>
                    <hr class="pw-repeater-divider">
                    <input type="hidden" name="project_id[]" value="<?= htmlspecialchars($proj['id']) ?>">
                  </div>
                <?php } ?>
              <?php } ?>
            </div>
            
            <button type="button" class="button is-dark is-small pw-add-item" data-repeater-target="projects">+ Add a project</button>

            <div class="pw-panel-actions">
              <button type="submit" name="action" value="projects" class="button btn-cta pw-save-btn" disabled>Save</button>
            </div>
          </form>

          <!-- TEMPLATE FOR JS CLONING -->
          <template id="tpl-projects-item">
            <div class="pw-repeater-item">
              <div class="field is-horizontal">
                <div class="field-body">
                  <div class="field">
                    <label class="label">Project Name</label>
                    <div class="control">
                      <input class="input" type="text" name="title[]" placeholder="e.g. ResTemplater Engine">
                    </div>
                  </div>
                  <div class="field">
                    <label class="label">Your Role</label>
                    <div class="control">
                      <input class="input" type="text" name="role[]" placeholder="e.g. Backend Architect">
                    </div>
                  </div>
                </div>
              </div>

              <div class="field">
                <label class="label">Project Summary & Tech Stack</label>
                <div class="control">
                  <textarea class="textarea" name="summary[]" rows="3" placeholder="What was the goal? What tech did you use? (PHP, SQL, etc.)"></textarea>
                </div>
              </div>

              <button type="button" class="button is-text pw-remove-item">Remove this project</button>
              <hr class="pw-repeater-divider">
            </div>
          </template>
        </section>

        <!-- SKILLS PANEL -->
        <section id="panel-skills" class="pw-editor-panel">
          <header class="pw-panel-header">
            <div>
              <h2>Hard & Soft Skills</h2>
              <p>What skills did you obtain as you grew up and define what you can?</p>
            </div>
          </header>
          <!-- Toggle for include Account photo or not. -->

          <form id="form-skills" class="pw-panel-form" action="./config/action_handler.conf.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['session_data']['user_id'] ?? '') ?>">
            <input type="hidden" name="resume_id" value="<?= htmlspecialchars($data['resdata']['id'] ?? '') ?>">
            <input type="hidden" name="action" value="skill">
            <div class="pw-repeater" data-repeater="skills">
              <!-- Skills ITEM -->
              <?php if (!empty($data['skills'])) { ?>
                <?php foreach ($data['skills'] as $i => $skill) { ?>
                <div class="pw-repeater-item">                         
                  <div class="field is-grouped skill-row">
                    <div class="control">
                      <input type="text" name="skill[<?= $i ?>][name]" class="pw-input" value="<?= htmlspecialchars($skill['name']) ?>">
                    </div>
                    <div class="control">
                      <select class="pw-select" name="skill[<?= $i ?>][category]">
                        <option disabled>Select a Category:</option>
                        <option <?= $skill['category'] === 'Software / Tools' ? 'selected' : '' ?>>Software / Tools</option>
                        <option <?= $skill['category'] === 'Languages' ? 'selected' : '' ?>>Languages</option>
                        <option <?= $skill['category'] === 'Technical' ? 'selected' : '' ?>>Technical</option>
                        <option <?= $skill['category'] === 'Certificate' ? 'selected' : '' ?>>Certificate</option>
                        <option <?= $skill['category'] === 'Soft Skills' ? 'selected' : '' ?>>Soft Skills</option>
                        <option <?= $skill['category'] === 'Hard Skills' ? 'selected' : '' ?>>Hard Skills</option>
                        <option <?= $skill['category'] === 'Other' ? 'selected' : '' ?>>Other</option>
                      </select>
                    </div>
                    <input type="hidden" name="skill[<?= $i ?>][id]" value="<?= htmlspecialchars($skill['id']) ?>">
                    <button type="submit" class="remove" name="delete" value="<?= htmlspecialchars($skill['id']) ?>">✕</button>
                  </div>  
                </div>
                <?php } ?>
              <?php } ?>
            </div>
            <div class="skill-actions">
              <button type="button" class="button is-dark is-small pw-add-item" data-repeater-target="skills" id="add-skill">+ Add Skill</button>
              <p id="skillWarning" class="help is-danger"></p>
            </div>

            <div class="pw-panel-actions">
              <button type="submit" name="save" class="button btn-cta pw-save-btn" disabled>Save</button>
            </div>
          </form>

          <!-- TEMPLATE FOR JS CLONING -->
          <template id="tpl-skills-item">
            <div class="pw-repeater-item">
              <div class="field is-grouped skill-row">
                <div class="control">
                  <input type="text" name="skill[]['name']" class="pw-input" placeholder="...">
                </div>
                <div class="control">
                  <select class="pw-select" name="skill[]['category']">
                    <option selected disabled>Select a Category:</option>
                    <option>Software / Tools</option>
                    <option>Languages</option>
                    <option>Technical</option>
                    <option>Certificate</option>
                    <option>Soft Skills</option>
                    <option>Hard Skills</option>
                    <option>Other</option>
                  </select>
                </div>
                <button type="button" class="remove is-text pw-remove-item">✕</button>
              </div>
            </div>
          </template>
        </section>

        <!-- SOCIAL MEDIA PANEL -->
        <section id="panel-social" class="pw-editor-panel">
          <header class="pw-panel-header">
            <div>
              <h2>Social Media</h2>
              <p>Do you have a portfolio or projects you like recruiters to see?</p>
            </div>
          </header>

          <form id="form-social" class="pw-panel-form" action="./config/action_handler.conf.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['session_data']['user_id'] ?? '') ?>">
            <input type="hidden" name="resume_id" value="<?= htmlspecialchars($data['resdata']['id'] ?? '') ?>">
            <input type="hidden" name="action" value="social">
            <div class="pw-repeater" data-repeater="social">
              <!-- Social ITEM -->
              <?php if (!empty($data['social'])) { ?>
                <?php foreach ($data['social'] as $media) { ?>
                  <div class="pw-repeater-item">
                    <div class="field">
                      <label class="label">Link</label>
                      <div class="control">
                        <input class="input" type="url" name="social[<?= $i ?>][media_url]" value="<?= htmlspecialchars($media['media_url']) ?>">
                      </div>
                    </div>
                    <input type="hidden" name="social[<?= $i ?>][id]" value="<?= htmlspecialchars($media['id']) ?>">
                    <button type="submit" class="button is-text pw-remove-item" name="delete" value="<?= htmlspecialchars($media['id']) ?>">Remove this link</button>
                  </div>
                <?php } ?>
              <?php } ?>
            </div>
            <button type="button" class="button is-dark is-small pw-add-item" data-repeater-target="social">+ Add a link</button>

            <div class="pw-panel-actions">
              <button type="submit" class="button btn-cta pw-save-btn" name="action" value="set_social" disabled>Save</button>
            </div>
          </form>

          <!-- TEMPLATE FOR JS CLONING -->
          <template id="tpl-social-item">
            <div class="pw-repeater-item">
              <div class="field">
                <label class="label">Link</label>
                <div class="control">
                  <input class="input" type="url" name="social[]" placeholder="https://example.com">
                </div>
              </div>
              <button type="button" class="button is-text pw-remove-item" name="action" value="delete_social">Remove this link</button>
            </div>
          </template>
        </section>

        <!-- TEMPLATES -->
        <section id="panel-template" class="pw-editor-panel">
          <header class="pw-panel-header">
            <div>
              <h2>Templates</h2>
              <p>Pick a style for a visual representation.</p>
            </div>
          </header>

          <form id="form-template" class="pw-panel-form" action="./config/action_handler.conf.php" target="_blank" method="post">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['session_data']['user_id'] ?? '') ?>">
            <input type="hidden" name="resume_id" value="<?= htmlspecialchars($data['resdata']['id'] ?? '') ?>">
            <input type="hidden" name="action" value="template">
            <div class="radio-card-grid animate__animated animate__fadeIn" id="resumeSelector">
              <button type="submit" class="radio-card" name="template" value="vintage">
                <span>Vintage (1970 - 1980)</span>
              </button>
              <button type="submit" class="radio-card" name="template" value="default">
                <span>Normal (Default)</span>
              </button>
              <button type="submit" class="radio-card" name="template" value="new_contro">
                <span>New Controverse (Business)</span>
              </button>
            </div>
          </form>
        </section>

      </main>
    </div>
  </div>
</section>