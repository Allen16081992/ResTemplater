<section id="home" class="<?= ViewBook::setVisibility('home'); ?> hero is-fullheight">
  <div class="magic-bg"></div>

  <!-- ========================================================
      HERO SECTION (THE HOOK)
  ========================================================= -->
    <div class="hero-body">
    <div class="container">
      <div class="columns is-vcentered">
        <div class="column is-6 has-text-left">
          <div class="badge mb-4">✨ No Paywalls. No Bullshit. Just Magic.</div>
          <h1 class="title is-1 witch-title">
            <?= mixedGrimoire::castMagicalHeader(); ?>
          </h1>
          <p class="subtitle mt-4 is-5 has-text-grey-light">
            Stop fighting with Word docs. PaperWitch uses a touch of code and a dash of style to brew professional PDFs in minutes.
          </p>
          <form class="buttons mt-6" action="client.php" method="post">
            <button type="submit" class="button is-medium btn-glow" name="wizard" value="wizard">🔮 Start Brewing</button>
            <button class="button is-medium is-ghost has-text-white">
              View Grimoire (Templates)
            </button>
          </form>
        </div>
        
        <div class="column is-6 is-hidden-touch">
            <div class="mockup-container">
                
                <!-- Background Sheet 1 (250px x 350px) -->
                <div class="floating-sheet sheet-1" id="hero-sheet-1">
                    <div class="sheet-glass-header contra-style"></div>
                    <div class="sheet-glass-headline"></div>
                    
                    <div class="paper-content micro-gap">
                        <div class="line micro-line"></div>
                    </div>
                    
                    <div class="glass-section-title">EXPERIENCE</div>
                    <div class="sheet-glass-exp"></div>
                    
                    <div class="glass-section-title top-space-sm">CORE LOGIC SKILLS</div>
                    <div class="sheet-glass-skills"></div>
                    
                    <div class="glass-section-title top-space-md">EDUCATION</div>
                    <div class="sheet-glass-edu"></div>
                </div>

                <div class="floating-sheet sheet-2" id="hero-sheet-2">
                    <div class="sheet-glass-header contra-style"></div>
                    <div class="sheet-glass-headline"></div>
                    
                    <div class="paper-content micro-gap">
                        <div class="line micro-line"></div>
                    </div>
                    
                    <div class="glass-section-title">EXPERIENCE</div>
                    <div class="sheet-glass-exp"></div>
                    
                    <div class="glass-section-title top-space-sm">CORE LOGIC SKILLS</div>
                    <div class="sheet-glass-skills"></div>
                    
                    <div class="glass-section-title top-space-md">EDUCATION</div>
                    <div class="sheet-glass-edu"></div>
                </div>
                
                <div class="main-preview" id="hero-main-preview">
                    <div id="hero-main-name" class="cursor-blink"></div>
                    <div id="hero-main-headline"></div>
                    
                    <div class="paper-content standard-gap">
                        <div class="line divider-main"></div>
                    </div>
                    
                    <div class="paper-section-title exp-title-space">EXPERIENCE</div>
                    <div id="hero-main-exp"></div>
                    
                    <div class="paper-section-title skills-title-space">CORE LOGIC SKILLS</div>
                    <div id="hero-main-skills"></div>
                    
                    <div class="paper-section-title edu-title-space">EDUCATION</div>
                    <div id="hero-main-edu"></div>
                    
                    <div class="paper-content footer-push">
                        <div class="line divider-main no-margin-bottom"></div>
                    </div>
                </div>

            </div>
        </div>

      </div>
    </div>
  </div>

  <div class="section pt-0">
    <div class="container">
      <div class="bento-grid">
        <div class="bento-item main-feature">
          <h3>Retro-Modern Styles</h3>
          <p>From 80s Typewriter to Ultra-Clean Minimalist.</p>
          <div class="bento-visual">✨</div>
        </div>
        <div class="bento-item">
          <h3>One-Click PDF</h3>
          <p>Instant FPDF Export.</p>
        </div>
        <div class="bento-item">
          <h3>ATS-Ready</h3>
          <p>The spell that beats the bot.</p>
        </div>
      </div>
    </div>
  </div>
  
  <section class="section showcase-area">
      <div class="container">
          <div class="has-text-centered mb-6">
              <h2 class="title is-2 witch-title">Crafting, not Coding.</h2>
              <p class="subtitle has-text-grey-light">Click inside the workspace below to test the magic.</p>
          </div>

          <div class="workbench">
              <!-- Part 1: The Interactive Input -->
              <div class="editor-side">
                  <div class="glass-input">
                      <span class="label-ghost">Name</span>
                      <!-- contenteditable makes this standard div behave like an input field -->
                      <div class="input-line" id="ghost-name" contenteditable="plaintext-only" spellcheck="false">Alex Sterling</div>
                  </div>
                  <div class="glass-input">
                      <span class="label-ghost">Headline</span>
                      <div class="input-line" id="ghost-headline" contenteditable="plaintext-only" spellcheck="false">Full-Stack Alchemist</div>
                  </div>
                  <div class="glass-input active-input">
                      <span class="label-ghost">Experience</span>
                      <div class="input-line cursor-blink" id="ghost-exp" contenteditable="plaintext-only" spellcheck="false">Working on Paper Witch</div>
                  </div>
              </div>

              <!-- Part 2: The Live Output (The Resume Paper) -->
              <div class="preview-side">
                  <div class="resume-paper contra-style">
                      <div class="paper-header">
                          <!-- These IDs match our JavaScript targets -->
                          <h3 id="paper-name">ALEX STERLING</h3>
                          <p id="paper-headline">Full-Stack Alchemist</p>
                      </div>
                      <hr>
                      <div class="paper-content">
                          <h4 class="paper-section-title">EXPERIENCE</h4>
                          <p class="paper-exp-text" id="paper-exp">Working on Paper Witch</p>
                          <div class="line"></div>
                          <div class="line short"></div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </section>

  <!-- ========================================================
      THE MANIFESTO (WHY PAPERWITCH)
  ========================================================= -->
  <section class="section manifesto-section">
    <div class="container">
      
      <!-- Header -->
      <div class="has-text-centered mb-6">
        <h2 class="title is-2 witch-title">The Anti‑SaaS Resume Builder</h2>
        <p class="subtitle has-text-grey-light">No subscriptions. No account tracking.</p>
      </div>

      <!-- The Core Pillars -->
      <div class="columns is-multiline is-centered mt-5">
        
        <!-- Card 1: Paywall Attack -->
        <div class="column is-12 is-4-desktop">
          <div class="manifesto-card">
            <div class="card-icon">🚫</div>
            <h3 class="title is-4">No Hidden Paywalls</h3>
            <p class="has-text-grey-light">
              Every major resume builder tricks you into spending 20 minutes filling out your details, only to demand a credit card when you click export. Paper Witch is honest upfront.
          </div>
        </div>

        <!-- Card 2: ATS/Technical Focus -->
        <div class="column is-12 is-4-desktop">
          <div class="manifesto-card">
            <div class="card-icon">🤖</div>
            <h3 class="title is-4">100% ATS-Friendly</h3>
            <p class="has-text-grey-light">
              Paper Witch is custom-made, lightweight, simple under the hood, and starved from features that frameworks like Wordpress and Laravel bring. That makes our website fast, responsive and usable on game consoles.
            </p>
          </div>
        </div>

        <!-- Card 3: Design Philosophy -->
        <div class="column is-12 is-4-desktop">
          <div class="manifesto-card">
            <div class="card-icon">📉</div>
            <h3 class="title is-4">Anti-Bloat Aesthetics</h3>
            <p class="has-text-grey-light">
              Ditch the over-designed infographics and silly progress bars that do more harm than good. Distinguish yourself from the generic, left bar layouts. We use modest layouts, like vintage <strong>80's Typewriter</strong>, high-focus, centerpiece <strong>Contra</strong>, and experimental <strong>Professionalism</strong>.
            </p>
          </div>
        </div>

      </div>

      <!-- Privacy Note (Reinforcing the "Helpful Peer" trust) -->
      <div class="has-text-centered mt-6">
        <div class="privacy-badge">
          <span>🔒 <strong>Your data is yours:</strong> We don't save your history or harvest your information. Yet...</span>
        </div>
      </div>

    </div>
  </section>
</section>