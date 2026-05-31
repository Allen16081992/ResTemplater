<?php
  // Variable "Shortcuts"
  $master = $data['active_paper']['master'] ?? null;
  $sections = $data['active_paper']['sections'] ?? [];
  $experience = $sections['experience'] ?? [];
  $education = $sections['education'] ?? [];
  $projects = $sections['projects'] ?? [];
  $skills = $sections['skills'] ?? [];
  $socialurl = $sections['socials'] ?? [];
?>
<section id="builder" class="<?= ViewBook::setVisibility('builder'); ?>">
  <div class="pw-editor-shell">
    <header class="pw-editor-header">
      <div>
        <h1 class="pw-editor-title">Job Scroll Builder</h1>
        <p class="pw-editor-sub">Brew, tweak and trim your resume sections before sending them into the wild.</p>
      </div>
      <div class="pw-editor-status">
        <span class="pw-editor-status-pill">
          <span>🧪</span> Always hit save!
        </span>
        <?php if (isset($_SESSION['session_data']['user_id'])) { ?>
          <a class="pw-btn" data-section="home">Switch Editor</a>
        <?php } if (!empty($data['active_paper']['master'])) { ?>
          <small>Selected: <strong><?= htmlspecialchars($data['active_paper']['master']['title']) ?></strong></small>
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
          <?php } ?>
          <hr>
          <?php if (!empty($data['active_paper']['master'])) { ?>
              <option value="info" selected>Resume Info</option>
              <option value="experience">Experience</option>
              <option value="education">Education</option>
              <option value="projects">Projects</option>
              <option value="skills">Skills</option>
              <option value="social">Social Media</option>
              <hr>
              <option value="template">Templates</option>
          <?php } else { ?>
            <span>Ready to make one?</span>
          <?php } ?>
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
        <?php if (!empty($data['active_paper']['master'])) { ?>
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
          <span>Select or make a new resume to see more options!</span>
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

          <form id="form-newRes" class="pw-panel-form" action="config/action_handler.conf.php" method="post">
            <?= SessionBook::csrfField(); ?>
            <div class="field is-horizontal">
              <div class="field-body">
                <div class="field">
                  <div class="is-flex">
                    <label class="label" for="title">Title</label>
                    <div class="server-field animate__animated animate__shakeX"><?php ViewBook::getError('title') ?></div>
                  </div>
                  <div class="control">
                    <input type="text" id="title" name="title" class="pw-input" placeholder="...">
                  </div>
                </div>
              </div>
            </div>
            <button type="submit" class="button is-contrast" name="action" value="resume:create">Create</button>
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

          <form id="form-delRes" class="pw-panel-form" action="config/action_handler.conf.php" method="post">
            <?= SessionBook::csrfField(); ?>
            <div class="field is-horizontal">
              <div class="field-body">
                <div class="field">
                  <label for="pwDeleteResumeSelect" class="label">Resume</label>
                  <div class="select is-fullwidth">
                    <select id="pwDeleteResumeSelect" name="resume_id">
                      <?php if (isset($data['papers']) && !empty($data['papers'])) { ?>
                        <option disabled selected>Select:</option>
                        <?php foreach ($data['papers'] as $resume) { ?>
                          <option value="<?= htmlspecialchars($resume['id']) ?>"><?= htmlspecialchars($resume['title']) ?></option>
                        <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <button type="submit" class="button is-danger" style="margin-top:7px;" name="action" value="resume:delete">Delete</button>
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

          <div class="pw-panel-form">
            <div class="radio-card-grid animate__animated animate__fadeIn" id="resumeSelector">
              <?php if (!empty($data['papers'])): foreach ($data['papers'] as $resume): ?>
                <a href="client.php?resume_id=<?= $resume['id'] ?>" class="edit-link">
                  <?= ViewBook::getPaperIcon($resume['id'], $resume['title']) ?> 
                  <strong><?= htmlspecialchars($resume['title']) ?></strong>
                  <small>Last edited: <?= ViewBook::timeAgo($resume['updated_at']) ?></small>
                </a>

                <!-- <label>
                  <input type="radio" name="resume_id" value="<?php //htmlspecialchars($resume['id']) ?>" onchange="this.form.submit()">
                  <span><?php //ViewBook::setEmoji($resume['id'], $resume['title']) ?><strong><?php //htmlspecialchars($resume['title']) ?></strong><br><small>Last edited: <?php //$timeAgo ?></small></span>
                </label> -->
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
                </label>
                <label>
                  <input type="radio">
                  <span>💋 <strong>OnlyFans</strong><br><small>Last edited: 3 months ago</small></span>
                </label> -->
              <?php endif; ?>
            </div>
          </div>
        </section>

        <!-- RESUME INFO PANEL -->
        <section id="panel-info" class="pw-editor-panel is-active">
          <?php if (isset($data['papers']) && !empty($data['papers'])) { ?>
          <header class="pw-panel-header">
            <div>
              <h2>Resume Info</h2>
              <p>Title of your document and a short summary.</p>
              <ul style="margin:10px; font-size:0.8rem;">
                <li>● Rename your resume (Optional)</li>
                <li>● Write a short summary about yourself (Optional)</li>
              </ul>
              <div class="server-field animate__animated animate__shakeX"><?php ViewBook::getError('resume_id') ?></div>
            </div>
          </header>
          <!-- Toggle for include Account photo or not. -->

          <form id="form-info" class="pw-panel-form" action="config/action_handler.conf.php" method="post" target="_blank">
            <?= SessionBook::csrfField(); ?>
            <input type="hidden" name="resume_id" value="<?= htmlspecialchars($master['id'] ?? '') ?>">
            <div class="field">
              <label class="label">Resume Title</label>
              <div class="server-field animate__animated animate__shakeX"><?php ViewBook::getError('title') ?></div>
              <div class="control">
                <input class="input" type="text" name="title" placeholder="..." value="<?= htmlspecialchars($master['title'] ?? 'IT Revisionist') ?>">
              </div>
            </div>

            <div class="field">
              <label class="label">Headline</label>
              <div class="control">
                <textarea class="textarea" name="headline" maxlength="850" rows="3" placeholder="Max 850 characters."><?= htmlspecialchars($master['headline'] ?? '') ?></textarea>
              </div>
            </div>

            <div class="pw-panel-actions">
              <button type="submit" name="action" value="resume:clone" class="button is-contrast">Clone</button>
              <button type="submit" name="action" value="resume:update" class="button btn-cta pw-save-btn">Save</button>
            </div>
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

          <form id="form-experience" class="pw-panel-form" action="config/action_handler.conf.php" method="post">
            <?= SessionBook::csrfField(); ?>
            <input type="hidden" name="resume_id" value="<?= htmlspecialchars($master['id'] ?? '') ?>">
            <div class="pw-repeater" data-repeater="experience">
              <!-- EXPERIENCE ITEM -->
              <?php if (!empty($experience)) { ?>
                <?php foreach ($experience as $i => $exp) { ?>
                  <input type="hidden" name="experience[<?= $i ?>][id]" value="<?= htmlspecialchars($exp['id']) ?>">

                  <div class="pw-repeater-item">
                    <div class="field is-horizontal">
                      <div class="field-body">
                        <div class="field">
                          <label class="label">Job title</label>
                          <div class="control">
                            <input class="input" type="text" name="experience[<?= $i ?>][title]" value="<?= htmlspecialchars($exp['title']) ?>">
                          </div>
                        </div>
                        <div class="field">
                          <label class="label">Company</label>
                          <div class="control">
                            <input class="input" type="text" name="experience[<?= $i ?>][employer]" value="<?= htmlspecialchars($exp['employer']) ?>">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="field is-horizontal">
                      <div class="field-body">
                        <div class="field">
                          <label class="label">Start</label>           
                          <div class="control">
                            <input class="input" type="month" placeholder="e.g. Jan 2024 or 2024-01" name="experience[<?= $i ?>][start_date]" value="<?= !empty($exp['start_date']) ? date('Y-m', strtotime($exp['start_date'])) : '' ?>">
                          </div>
                        </div>
                        <div class="field">
                          <label class="label">End</label>
                          <div class="control">
                            <input class="input" type="month" placeholder="e.g. Jan 2024 or 2024-01" name="experience[<?= $i ?>][end_date]" value="<?= !empty($exp['end_date']) ? date('Y-m', strtotime($exp['end_date'])) : '' ?>">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="field">
                      <label class="label">Description</label>
                      <div class="control">
                        <textarea class="textarea" name="experience[<?= $i ?>][summary]" rows="3"><?= htmlspecialchars($exp['summary'] ?? '') ?></textarea>
                      </div>
                    </div>
                    <button type="submit" class="button is-text pw-remove-item" name="action" value="experience:delete|<?= htmlspecialchars($exp['id']) ?>">Remove this experience</button>
                    <hr class="pw-repeater-divider">
                  </div>
                <?php } ?>
              <?php } ?>
            </div>
            <button type="button" class="button is-dark is-small pw-add-item" data-repeater-target="experience">+ Add an experience</button>

            <div class="pw-panel-actions">
              <button type="submit" name="action" value="experience:save" class="button btn-cta pw-save-btn" disabled>Save</button>
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
                      <input class="input" type="text" name="experience[INDEX][title]" placeholder="Role / Position">
                    </div>
                  </div>
                  <div class="field">
                    <label class="label">Company</label>
                    <div class="control">
                      <input class="input" type="text" name="experience[INDEX][employer]" placeholder="Company / Organization">
                    </div>
                  </div>
                </div>
              </div>

              <div class="field is-horizontal">
                <div class="field-body">
                  <div class="field">
                    <label class="label">Start</label>
                    <div class="control">
                      <input class="input" type="month" name="experience[INDEX][start_date]">
                    </div>
                  </div>
                  <div class="field">
                    <label class="label">End</label>
                    <div class="control">
                      <input class="input" type="month" name="experience[INDEX][end_date]">
                    </div>
                  </div>
                </div>
              </div>

              <div class="field">
                <label class="label">Description</label>
                <div class="control">
                  <textarea class="textarea" name="experience[INDEX][summary]" rows="3" placeholder="(Optional) What did you actually do, fix or improve?"></textarea>
                </div>
              </div>

              <button type="button" class="button is-text pw-remove-item" name="experience:delete">Remove this experience</button>
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

          <form id="form-education" class="pw-panel-form" action="config/action_handler.conf.php" method="post">
            <?= SessionBook::csrfField(); ?>
            <input type="hidden" name="resume_id" value="<?= htmlspecialchars($master['id'] ?? '') ?>">
            <div class="pw-repeater" data-repeater="education">
              <!-- Education ITEM -->
              <?php if (!empty($education)) { ?>
                <?php foreach ($education as $i => $edu) { ?>
                  <input type="hidden" name="education[<?= $i ?>][id]" value="<?= htmlspecialchars($edu['id']) ?>">

                  <div class="pw-repeater-item">
                    <div class="field is-horizontal">
                      <div class="field-body">
                        <div class="field">
                          <label class="label">Program</label>
                          <div class="control">
                            <input class="input" type="text" name="education[<?= $i ?>][program]" value="<?= htmlspecialchars($edu['program']) ?>">
                          </div>
                        </div>
                        <div class="field">
                          <label class="label">School</label>
                          <div class="control">
                            <input class="input" type="text" name="education[<?= $i ?>][school]" value="<?= htmlspecialchars($edu['school']) ?>">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="field is-horizontal">
                      <div class="field-body">
                        <div class="field">
                          <label class="label">Start</label>
                          <div class="control">
                            <input class="input" type="month" name="education[<?= $i ?>][start_date]" value="<?= !empty($edu['start_date']) ? date('Y-m', strtotime($edu['start_date'])) : '' ?>">
                          </div>
                        </div>
                        <div class="field">
                          <label class="label">End</label>
                          <div class="control">
                            <input class="input" type="month" name="education[<?= $i ?>][end_date]" value="<?= !empty($edu['end_date']) ? date('Y-m', strtotime($edu['end_date'])) : '' ?>">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="field">
                      <label class="label">Description</label>
                      <div class="control">
                        <textarea class="textarea" name="education[<?= $i ?>][summary]" rows="3"><?= htmlspecialchars($edu['summary'] ?? '') ?></textarea>
                      </div>
                    </div>
                    <button type="submit" class="button is-text pw-remove-item" name="action" value="education:delete|<?= htmlspecialchars($edu['id']) ?>">Remove this education</button>
                    <hr class="pw-repeater-divider">
                  </div>
                <?php } ?>
              <?php } ?>
            </div>
            <button type="button" class="button is-dark is-small pw-add-item" data-repeater-target="education">+ Add an education</button>

            <div class="pw-panel-actions">
              <button type="submit" class="button btn-cta pw-save-btn" name="action" value="education:save" disabled>Save</button>
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
                      <input class="input" type="text" name="education[INDEX][program]" placeholder="Program / Course">
                    </div>
                  </div>
                  <div class="field">
                    <label class="label">School</label>
                    <div class="control">
                      <input class="input" type="text" name="education[INDEX][school]" placeholder="School / Organization">
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="field is-horizontal">
                <div class="field-body">
                  <div class="field">
                    <label class="label">Start</label>
                    <div class="control">
                      <input class="input" type="month" name="education[INDEX][start_date]">
                    </div>
                  </div>
                  <div class="field">
                    <label class="label">End</label>
                    <div class="control">
                      <input class="input" type="month" name="education[INDEX][end_date]">
                    </div>
                  </div>
                </div>
              </div>

              <div class="field">
                <label class="label">Description</label>
                <div class="control">
                  <textarea class="textarea" name="education[INDEX][summary]" rows="3" placeholder="(Optional) What did you study or learn?"></textarea>
                </div>
              </div>

              <button type="button" class="button is-text pw-remove-item" name="education:delete">Remove this education</button>
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
            <?= SessionBook::csrfField(); ?>
            <input type="hidden" name="resume_id" value="<?= htmlspecialchars($master['id'] ?? '') ?>">
            <!-- Projects ITEM -->
            <div class="pw-repeater" data-repeater="projects">
              <?php if (!empty($projects)) { ?>
                <?php foreach ($projects as $i => $pro) { ?>
                  <input type="hidden" name="projects[<?= $i ?>][id]" value="<?= htmlspecialchars($pro['id']) ?>">

                  <div class="pw-repeater-item">
                    <div class="field is-horizontal">
                      <div class="field-body">
                        <div class="field">
                          <label class="label">Project Name</label>
                          <div class="control">
                            <input class="input" type="text" name="projects[<?= $i ?>][title]" value="<?= htmlspecialchars($pro['title']) ?>">
                          </div>
                        </div>
                        <div class="field">
                          <label class="label">Your Role</label>
                          <div class="control">
                            <input class="input" type="text" name="projects[<?= $i ?>][role]" value="<?= htmlspecialchars($pro['role']) ?>">
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="field">
                      <label class="label">Project Summary & Tech Stack</label>
                      <div class="control">
                        <textarea class="textarea" name="projects[<?= $i ?>][summary]" rows="3"><?= htmlspecialchars($pro['summary'] ?? '') ?></textarea>
                      </div>
                    </div>
                    
                    <button type="submit" class="button is-text pw-remove-item" name="action" value="projects:delete|<?= htmlspecialchars($pro['id']) ?>">Remove this project</button>
                    <hr class="pw-repeater-divider">
                  </div>
                <?php } ?>
              <?php } ?>
            </div>
            <button type="button" class="button is-dark is-small pw-add-item" data-repeater-target="projects">+ Add a project</button>

            <div class="pw-panel-actions">
              <button type="submit" name="action" value="projects:save" class="button btn-cta pw-save-btn" disabled>Save</button>
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
                      <input class="input" type="text" name="projects[INDEX][title]" placeholder="e.g. ResTemplater Engine">
                    </div>
                  </div>
                  <div class="field">
                    <label class="label">Your Role</label>
                    <div class="control">
                      <input class="input" type="text" name="projects[INDEX][role]" placeholder="e.g. Backend Architect">
                    </div>
                  </div>
                </div>
              </div>

              <div class="field">
                <label class="label">Project Summary & Tech Stack</label>
                <div class="control">
                  <textarea class="textarea" name="projects[INDEX][summary]" rows="3" placeholder="What was the goal?"></textarea>
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

          <form id="form-skills" class="pw-panel-form" action="config/action_handler.conf.php" method="post">
            <?= SessionBook::csrfField(); ?>
            <input type="hidden" name="resume_id" value="<?= htmlspecialchars($master['id'] ?? '') ?>">
            <div class="pw-repeater" data-repeater="skills">
              <!-- Skills ITEM -->
              <?php if (!empty($skills)) { ?>
                <?php foreach ($skills as $i => $ski) { ?>
                  <input type="hidden" name="skills[<?= $i ?>][id]" value="<?= htmlspecialchars($ski['id']) ?>">

                  <div class="pw-repeater-item">                         
                    <div class="field is-grouped skill-row">
                      <div class="control">
                        <input type="text" name="skills[<?= $i ?>][name]" class="pw-input" value="<?= htmlspecialchars($ski['name']) ?>">
                      </div>
                      <div class="control">
                        <select class="pw-select" name="skills[<?= $i ?>][category]">
                          <option disabled>Select a Category:</option>
                          <option value="tool" <?= $ski['category'] === 'tool' ? 'selected' : '' ?>>Software / Tools</option>
                          <option value="language" <?= $ski['category'] === 'language' ? 'selected' : '' ?>>Languages</option>
                          <option value="technical" <?= $ski['category'] === 'technical' ? 'selected' : '' ?>>Technical</option>
                          <option value="certificate" <?= $ski['category'] === 'certificate' ? 'selected' : '' ?>>Certificate</option>
                          <option value="soft-skill" <?= $ski['category'] === 'soft-skill' ? 'selected' : '' ?>>Soft Skills</option>
                          <option value="hard-skill" <?= $ski['category'] === 'hard-skill' ? 'selected' : '' ?>>Hard Skills</option>
                          <option value="Other" <?= $ski['category'] === 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                      </div>
                      <button type="submit" class="remove" name="action" value="skills:delete|<?= htmlspecialchars($ski['id']) ?>">✕</button>
                    </div>  
                  </div>
                <?php } ?>
              <?php } ?>
            </div>
            <div class="skill-actions">
              <button type="button" class="button is-dark is-small pw-add-item" data-repeater-target="skills" id="add-skill">+ Add Skill</button>
              <p id="skillWarning" class="help is-danger"></p>
            </div>

            <div class="pw-panel-actions"><!-- If any skill exist, disabled attribute is removed by JS -->
              <button type="submit" name="action" value="skills:save" class="button btn-cta pw-save-btn" disabled>Save</button>
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
                    <option value="tool">Software / Tools</option>
                    <option value="language">Languages</option>
                    <option value="technical">Technical</option>
                    <option value="certificate">Certificate</option>
                    <option value="soft-skill">Soft Skills</option>
                    <option value="hard-skill">Hard Skills</option>
                    <option value="Other">Other</option>
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

          <form id="form-social" class="pw-panel-form" action="/config/action_handler.conf.php" method="post">
            <?= SessionBook::csrfField(); ?>
            <input type="hidden" name="resume_id" value="<?= htmlspecialchars($master['id'] ?? '') ?>">
            <div class="pw-repeater" data-repeater="social">
              <!-- Social ITEM -->
              <?php if (!empty($socialurl)) { ?>
                <?php foreach ($socialurl as $i => $url) { ?>
                  <div class="pw-repeater-item">
                    <div class="field">
                      <label class="label">Link</label>
                      <div class="control">
                        <input class="input" type="url" name="socials[<?= $i ?>][media_url]" value="<?= htmlspecialchars($url['media_url']) ?>" placeholder="https://linkedin.com/in/...">
                      </div>
                    </div>
                    <input type="hidden" name="socials[<?= $i ?>][id]" value="<?= htmlspecialchars($url['id']) ?>">
                    <button type="submit" class="button is-text pw-remove-item" name="action" value="socials:delete|<?= htmlspecialchars($url['id']) ?>">Remove this link</button>
                  </div>
                <?php } ?>
              <?php } ?>
            </div>
            <button type="button" class="button is-dark is-small pw-add-item" data-repeater-target="social">+ Add a link</button>

            <div class="pw-panel-actions">
              <button type="submit" class="button btn-cta pw-save-btn" name="action" value="socials:save" disabled>Save</button>
            </div>
          </form>

          <!-- TEMPLATE FOR JS CLONING -->
          <template id="tpl-social-item">
            <div class="pw-repeater-item">
              <div class="field">
                <label class="label">Link</label>
                <div class="control">
                  <input class="input" type="url" name="socials[INDEX][media_url]" placeholder="https://example.com">
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

          <form id="form-template" class="pw-panel-form" action="/config/action_handler.conf.php" target="_blank" method="post">
            <?= SessionBook::csrfField(); ?>
            <input type="hidden" name="resume_id" value="<?= htmlspecialchars($master['id']) ?>">
            <div class="radio-card-grid animate__animated animate__fadeIn" id="resumeSelector">
              <button type="submit" class="radio-card" name="action" value="template:read|vintage">
                <span>Vintage (1970 - 1980)</span>
              </button>
              <button type="submit" class="radio-card" name="action" value="template:read|business">
                <span>Business (Default)</span>
              </button>
              <button type="submit" class="radio-card" name="action" value="template:read|contra">
                <span>Contra (Creative-Tech)</span>
              </button>
            </div>
          </form>
        </section>

      </main>
    </div>
  </div>
</section>