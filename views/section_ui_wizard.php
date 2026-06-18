<section id="wizard" class="<?= ViewBook::setVisibility('wizard'); ?>">
  <div class="pw-baseline" id="pwBaseline">
    <header class="pw-header">
      <div>
        <h1 class="pw-title">Job Scroll Wizard</h1>
        <p class="pw-subtitle">Answer a few questions. We’ll turn it into a clean resume.</p>
      </div>
      <div class="pw-status" aria-live="polite">
        <div class="pw-pill"><span>🧪</span><span>Draft saved locally</span></div>
        <?php if (isset($_SESSION['session_data']['user_id'])) { ?>
          <a class="pw-btn" data-section="home">Switch Editor</a>
        <?php } else { ?>
          <a class="pw-btn" href="index.php">Homepage</a>
        <?php } ?>
        <div style="opacity:.9">Mode: <strong>Quick Start</strong></div>
      </div>
    </header>

    <div class="pw-progress" id="pwProgress" aria-label="Progress">
      <!-- chips injected by JS -->
    </div>

    <div id="pwStage" class="pw-stage">
      <div class="pw-toast" id="pwToast"><span>✓</span><span>Saved</span></div>

      <!-- STEP: Welcome -->
      <article class="pw-card" data-step="welcome">
        <h2>Let’s make your resume</h2>
        <p class="helptext">This takes about 10 minutes.</p>
        <div class="pw-form">
          <button type="button" class="pw-btn" data-action="start">Start</button>
          <div class="pw-hint">You can come back later. Your draft stays saved in this browser.</div>
        </div>
      </article>

      <!-- STEP: Basics -->
      <form action="config/action_handler.php" method="post" target="_blank">
        <?= SessionBook::csrfField(); ?>
        <article class="pw-card" data-step="basics">
          <h2>Basics</h2>
          <p class="helptext">Just the essentials.</p>

          <div class="pw-form">
            <div>
              <label class="pw-label" for="fullname">Full name *</label>
              <div class="server-field animate__animated animate__shakeX"><?php ViewBook::getError('fullname') ?></div>
              <input type="text" id="fullname" name="fullname" class="pw-input" placeholder="..." <?= isset($_SESSION['session_data']['user_id']) ? 'value="'.($contact['fullname'] ?? '').'"' : 'autocomplete="fullname"'; ?>>
            </div>
            <div>
              <label for="headline" class="pw-label">Headline</label>
              <input type="text" id="headline" name="headline" class="pw-input" placeholder="e.g. IT student • Junior dev • Curious tinkerer">
              <div class="pw-hint">Optional. One line that says who you are.</div>
            </div>
          </div>
        </article>

        <!-- STEP: Studying? -->
        <article class="pw-card" data-step="studying">
          <div class="pw-qhead">
            <div class="pw-qicon" aria-hidden="true">🎓</div>
            <div class="pw-qtext">
              <h2>Are you currently studying?</h2>
              <p class="helptext">This decides whether we include an Education section.</p>
            </div>
          </div>

          <div class="pw-choices" role="group" aria-label="Studying choice">
            <div class="pw-choice" data-choice="studying" data-value="yes">
              <strong>Yes, I am</strong>
              <span>I’m in school / college / university right now.</span>
            </div>
            <div class="pw-choice" data-choice="studying" data-value="no">
              <strong>No, I’m not</strong>
              <span>I’m not studying at the moment.</span>
            </div>
          </div>
        </article>

        <!-- STEP: Experience? -->
        <article class="pw-card" data-step="hasExp">
          <div class="pw-qhead">
            <div class="pw-qicon" aria-hidden="true">🧰</div>
            <div class="pw-qtext">
              <h2>Do you have any work experience?</h2>
              <p class="helptext">Jobs, internships, side gigs, volunteer work. Anything counts.</p>
            </div>
          </div>

          <div class="pw-choices" role="group" aria-label="Experience choice">
            <div class="pw-choice" data-choice="hasExp" data-value="yes">
              <strong>Yes</strong>
              <span>I can add at least one role.</span>
            </div>
            <div class="pw-choice" data-choice="hasExp" data-value="no">
              <strong>No</strong>
              <span>Skip experience for now.</span>
            </div>
          </div>
        </article>

        <!-- STEP: Experience entry (JS injects experience[0][...] here automatically on hasExp=yes) -->
        <article class="pw-card" data-step="expOne">
          <h2>Experience</h2>
          <p class="helptext">Tell us about one role.</p>

          <div class="pw-form" id="expMount" aria-label="Experience entry form">
            <!-- JS injects the current experience block here, e.g.:
                experience[0][title], experience[0][employer], ...
            -->
          </div>

          <div class="pw-form">
            <button type="button" class="pw-btn pw-btn-primary" data-action="saveExpOne">Save role</button>
          </div>
        </article>

        <!-- STEP: Add another experience? -->
        <article class="pw-card" data-step="expMore">
          <div class="pw-qhead">
            <div class="pw-qicon" aria-hidden="true">➕</div>
            <div class="pw-qtext">
              <h2>Add another role?</h2>
              <p class="helptext">You can add more, or move on.</p>
            </div>
          </div>

          <div class="pw-choices" role="group" aria-label="More experience">
            <div class="pw-choice" data-choice="expMore" data-value="yes">
              <strong>Yes</strong>
              <span>Add one more role.</span>
            </div>
            <div class="pw-choice" data-choice="expMore" data-value="no">
              <strong>No</strong>
              <span>Continue.</span>
            </div>
          </div>
        </article>

        <!-- STEP: Education entry (JS injects education[0][...] here automatically on studying=yes) -->
        <article class="pw-card" data-step="eduOne">
          <h2>Education</h2>
          <p class="helptext">Add one education entry.</p>

          <div class="pw-form" id="eduMount" aria-label="Education entry form">
            <!-- JS injects the current education block here, e.g.:
                education[0][program], education[0][school], ...
            -->
          </div>

          <div class="pw-form">
            <button type="button" class="pw-btn pw-btn-primary" data-action="saveEduOne">Save education</button>
          </div>
        </article>

        <!-- STEP: Add another education? -->
        <article class="pw-card" data-step="eduMore">
          <div class="pw-qhead">
            <div class="pw-qicon" aria-hidden="true">📚</div>
            <div class="pw-qtext">
              <h2>Add another education entry?</h2>
              <p class="helptext">Only if you need it.</p>
            </div>
          </div>

          <div class="pw-choices" role="group" aria-label="More education">
            <div class="pw-choice" data-choice="eduMore" data-value="yes">
              <strong>Yes</strong>
              <span>Add one more entry.</span>
            </div>
            <div class="pw-choice" data-choice="eduMore" data-value="no">
              <strong>No</strong>
              <span>Continue.</span>
            </div>
          </div>
        </article>

        <!-- STEP: Skills -->
        <article class="pw-card" data-step="skills">
          <h2>Skills</h2>
          <p class="helptext">Add skills that match the kind of work you want.</p>

          <div class="pw-form">
            <div id="skills">
              <div class="field is-grouped skill-row">
                <div class="control">
                  <input type="text" name="skills[0][name]" class="pw-input" placeholder="...">
                </div>
                <div class="control">
                  <select class="pw-select" name="skills[0][category]">
                    <option selected disabled>Select a Category:</option>
                    <option value="tool">Software / Tools</option>
                    <option value="language">Languages</option>
                    <option value="technical">Technical</option>
                    <option value="certificate">Certificate</option>
                    <option value="soft-skill">Soft Skills</option>
                    <option value="hard-skill">Hard Skills</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <button type="button" class="remove">✕</button>
              </div>
            </div>

            <div class="skill-actions">
              <button type="button" class="button is-dark is-small pw-add-item" id="add-skill">+ Add Skill</button>
              <p id="skillWarning" class="help is-danger"></p>
            </div>

            <div class="pw-hint">Need ideas? Tap a few that fit you.</div>
            <div class="pw-chip-row">
              <button type="button" class="pw-chip" data-skill="Teamwork">Teamwork</button>
              <button type="button" class="pw-chip" data-skill="Communication">Communication</button>
              <button type="button" class="pw-chip" data-skill="Planning">Planning</button>
              <button type="button" class="pw-chip" data-skill="English">English</button>
              <button type="button" class="pw-chip" data-skill="Spanish">Spanish</button>
              <button type="button" class="pw-chip" data-skill="Problem solving">Problem solving</button>
              <button type="button" class="pw-chip" data-skill="Welding">Welding</button>
              <button type="button" class="pw-chip" data-skill="Python">Python</button>
              <button type="button" class="pw-chip" data-skill="Jira">Jira</button>
              <button type="button" class="pw-chip" data-skill="Photoshop">Photoshop</button>
              <button type="button" class="pw-chip" data-skill="Illustrator">Illustrator</button>
              <button type="button" class="pw-chip" data-skill="Time Management">Time Management</button>
              <button type="button" class="pw-chip" data-skill="Microsoft Excel">Microsoft Excel</button>
              <button type="button" class="pw-chip" data-skill="First Aid">First Aid</button>
              <button type="button" class="pw-chip" data-skill="HACCP">HACCP</button>
            </div>
          </div>
        </article>

        <!-- JS dynamic fields -->
        <div id="experienceFields" hidden></div>
        <div id="educationFields" hidden></div>

        <!-- STEP: Contact -->
        <article class="pw-card" data-step="contact">
          <h2>Contact</h2>
          <p class="helptext">Where can someone reach you?</p>

          <div class="pw-form">
            <div class="pw-row">
              <div>
                <label class="pw-label" for="email">Email *</label>
                <div class="server-field animate__animated animate__shakeX"><?php ViewBook::getError('email') ?></div>
                <input type="email" id="email" name="email" class="pw-input" placeholder="you@example.com" <?= isset($_SESSION['session_data']['user_id']) ? 'value="'.($account['email'] ?? '').'"' : 'autocomplete="email"'; ?> value="jade_greenhill32@hotmail.com">
              </div>
              <div>
                <label class="pw-label" for="phone">Phone *</label>
                <div class="server-field animate__animated animate__shakeX"><?php ViewBook::getError('phone') ?></div>
                <input type="tel" id="phone" name="phone" class="pw-input" placeholder="+31…" <?= isset($_SESSION['session_data']['user_id']) ? 'value="'.($contact['phone'] ?? '').'"' : 'autocomplete="tel"'; ?> value="063166457">
              </div>
            </div>

            <div class="pw-row">
              <div>
                <label class="pw-label" for="city">City *</label>
                <div class="server-field animate__animated animate__shakeX"><?php ViewBook::getError('city') ?></div>
                <input type="text" id="city" name="city" class="pw-input" placeholder="..." <?= isset($_SESSION['session_data']['user_id']) ? 'value="'.($contact['city'] ?? '').'"' : 'autocomplete="address-level2"'; ?> value="Rotterdam">
              </div>
              <div>
                <label class="pw-label" for="country">Country *</label>
                <div class="server-field animate__animated animate__shakeX"><?php ViewBook::getError('country') ?></div>
                <input type="text" id="country" name="country" class="pw-input" placeholder="..." <?= isset($_SESSION['session_data']['user_id']) ? 'value="'.($contact['country'] ?? '').'"' : 'autocomplete="country-name"'; ?> value="Netherlands">
              </div>
            </div>
          </div>
        </article>

        <!-- STEP: Review -->
        <article class="pw-card" data-step="review">
          <h2>Check</h2>
          <p class="helptext">Here’s what we’ll put on your resume.</p>
          <div class="pw-summary" id="summaryGrid"></div>
          <div class="pw-hint" style="margin-top:.85rem">You can go back if you want to change something.</div>
        </article>

        <!-- STEP: Download -->
        <article class="pw-card" data-step="download">
          <div class="pw-final-only">
            <?php if (isset($_SESSION['session_data']['user_id'])) { ?>
              <button type="submit" class="pw-btn pw-btn-primary" name="action" value="wizard:save">Save now</button>
            <?php } else { ?>
              <button type="submit" class="pw-btn pw-btn-primary" name="action" value="template:read|vintage">Let’s go Vintage</button>
              <button type="submit" class="pw-btn pw-btn-primary" name="action" value="template:read|business">Let’s go Modern</button>
              <button type="submit" class="pw-btn pw-btn-primary" name="action" value="template:read|contra">Go Creative/Tech</button>
            <?php } ?>
          </div>
        </article>
      </form>

      <!-- Hidden step values (JS fills these) -->
      <input type="hidden" name="studying" id="studyingVal">
      <input type="hidden" name="hasExp" id="hasExpVal">
      <input type="hidden" name="expMore" id="expMoreVal">
      <input type="hidden" name="eduMore" id="eduMoreVal">
    </div>

    <footer class="pw-nav">
      <div class="left">
        <button type="button" class="pw-btn" id="btnBack">Back</button>
        <button type="button" class="pw-btn pw-btn-danger" id="btnReset" title="Clear local draft">Reset</button>
      </div>
      <div class="right">
        <button type="button" class="pw-btn pw-btn-primary" id="btnNext">Continue</button>
      </div>
    </footer>
  </div>
</section>