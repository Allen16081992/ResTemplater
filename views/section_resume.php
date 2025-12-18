<section id="home" class="<?= ViewBook::Homepage(); ?>">
  <section class="pw-baseline" id="pwBaseline">
    <header class="pw-header">
      <div>
        <h1 class="pw-title">PaperWitch Quick Resume</h1>
        <p class="pw-subtitle">
          Answer a few questions. We’ll turn it into a clean resume.
        </p>
      </div>
      <div class="pw-status" aria-live="polite">
        <div class="pw-pill"><span>🧪</span><span>Saved</span></div>
        <div style="opacity:.9">Mode: <strong>Quick Start</strong></div>
      </div>
    </header>

    <div class="pw-progress" id="pwProgress" aria-label="Progress">
      <!-- chips injected by JS -->
    </div>

    <div class="pw-stage" id="pwStage">
      <div class="pw-toast" id="pwToast"><span>✓</span><span>Saved</span></div>

      <!-- STEP: Welcome -->
      <article class="pw-card" data-step="welcome">
        <h2>Let’s make your resume</h2>
        <p class="helptext">This takes about 10 minutes.</p>
        <div class="pw-form">
          <button class="pw-btn pw-btn-primary" data-action="start">Start</button>
          <div class="pw-hint">You can come back later. Your draft stays saved in this browser.</div>
        </div>
      </article>

      <!-- STEP: Basics -->
      <article class="pw-card" data-step="basics">
        <h2>Basics</h2>
        <p class="helptext">Just the essentials.</p>

        <div class="pw-form">
          <div>
            <label class="pw-label" for="fullName">Full name *</label>
            <input class="pw-input" id="fullName" name="fullName" type="text" placeholder="e.g. Allen Pieter" autocomplete="name">
          </div>

          <div>
            <label class="pw-label" for="headline">Headline</label>
            <input class="pw-input" id="headline" name="headline" type="text" placeholder="e.g. IT student • Junior dev • Curious tinkerer">
            <div class="pw-hint">Optional. One line that says who you are.</div>
          </div>
        </div>
      </article>

      <!-- STEP: Studying? (2 choices) -->
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

      <!-- STEP: Experience? (2 choices) -->
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

      <!-- STEP: Add one experience -->
      <article class="pw-card" data-step="expOne">
        <h2>Experience</h2>
        <p class="helptext">Tell us about one role.</p>

        <div class="pw-form">
          <div class="pw-row">
            <div>
              <label class="pw-label" for="expRole">Role *</label>
              <input class="pw-input" id="expRole" type="text" placeholder="e.g. Junior IT Support (Intern)">
            </div>
            <div>
              <label class="pw-label" for="expCompany">Company *</label>
              <input class="pw-input" id="expCompany" type="text" placeholder="e.g. TechCorp">
            </div>
          </div>

          <div class="pw-row">
            <div>
              <label class="pw-label" for="expStart">Start</label>
              <input class="pw-input" id="expStart" type="month">
            </div>
            <div>
              <label class="pw-label" for="expEnd">End</label>
              <input class="pw-input" id="expEnd" type="month">
            </div>
          </div>

          <div>
            <label class="pw-label" for="expDesc">What did you do?</label>
            <textarea class="pw-textarea" id="expDesc" placeholder="Write 2–5 short lines."></textarea>
            <div class="pw-hint">Tip: press Enter after each responsibility.</div>
          </div>

          <button class="pw-btn pw-btn-primary" data-action="saveExpOne">Save role</button>
        </div>
      </article>

      <!-- STEP: Add another experience? (2 choices) -->
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

      <!-- STEP: Education (if studying OR optional path) -->
      <article class="pw-card" data-step="eduOne">
        <h2>Education</h2>
        <p class="helptext">Add one education entry.</p>

        <div class="pw-form">
          <div class="pw-row">
            <div>
              <label class="pw-label" for="eduProgram">Program / Degree *</label>
              <input class="pw-input" id="eduProgram" type="text" placeholder="e.g. BSc Information Technology">
            </div>
            <div>
              <label class="pw-label" for="eduSchool">Institute *</label>
              <input class="pw-input" id="eduSchool" type="text" placeholder="e.g. Hogeschool Rotterdam">
            </div>
          </div>

          <div class="pw-row">
            <div>
              <label class="pw-label" for="eduStart">Start</label>
              <input class="pw-input" id="eduStart" type="month">
            </div>
            <div>
              <label class="pw-label" for="eduEnd">End</label>
              <input class="pw-input" id="eduEnd" type="month">
            </div>
          </div>

          <div>
            <label class="pw-label" for="eduDesc">Optional note</label>
            <textarea class="pw-textarea" id="eduDesc" placeholder="Optional. One or two lines."></textarea>
          </div>

          <button class="pw-btn pw-btn-primary" data-action="saveEduOne">Save education</button>
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

      <!-- STEP: Contact -->
      <article class="pw-card" data-step="contact">
        <h2>Contact</h2>
        <p class="helptext">Where can someone reach you?</p>

        <div class="pw-form">
          <div>
            <label class="pw-label" for="email">Email *</label>
            <input class="pw-input" id="email" type="email" placeholder="you@example.com" autocomplete="email">
          </div>

          <div class="pw-row">
            <div>
              <label class="pw-label" for="city">City</label>
              <input class="pw-input" id="city" type="text" placeholder="e.g. Rotterdam" autocomplete="address-level2">
            </div>
            <div>
              <label class="pw-label" for="country">Country</label>
              <input class="pw-input" id="country" type="text" placeholder="e.g. Netherlands" autocomplete="country-name">
            </div>
          </div>

          <div class="pw-row">
            <div>
              <label class="pw-label" for="website">Website</label>
              <input class="pw-input" id="website" type="url" placeholder="https://example.com">
            </div>
            <div>
              <label class="pw-label" for="phone">Phone</label>
              <input class="pw-input" id="phone" type="tel" placeholder="+31 6 …">
            </div>
          </div>
        </div>
      </article>

      <!-- STEP: Review (confidence echo) -->
      <article class="pw-card" data-step="review">
        <h2>Check</h2>
        <p class="helptext">Here’s what we’ll put on your resume.</p>

        <div class="pw-summary" id="summaryGrid"></div>

        <div class="pw-hint" style="margin-top:.85rem">
          You can go back if you want to change something.
        </div>
      </article>

      <!-- STEP: Download (final step has ONLY the button) -->
      <article class="pw-card" data-step="download">
        <div class="pw-final-only">
          <button class="pw-btn pw-btn-primary" data-action="download">
            Let’s download
          </button>
        </div>
      </article>
    </div>

    <footer class="pw-nav">
      <div class="left">
        <button class="pw-btn" id="btnBack">Back</button>
        <button class="pw-btn pw-btn-danger" id="btnReset" title="Clear local draft">Reset</button>
      </div>
      <div class="right">
        <button class="pw-btn pw-btn-primary" id="btnNext">Continue</button>
      </div>
    </footer>
  </section>
</section>