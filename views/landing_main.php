<main id="home" class="<?= ViewBook::Homepage(); ?>">
  <!-- ========================================================
      HERO SECTION
  ========================================================= -->
  <article class="hero is-dark is-fullheight">
    <div class="floating-sheets"></div>
    <div class="hero-body">
      <div class="container">
        <div class="columns is-vcentered">
          <div class="column">
            <h1 class="title hero-title"><?= htmlspecialchars($slogan = SetPhrase('slogan')); ?></h1>
            <p class="subtitle hero-sub mt-3"><?= htmlspecialchars($cta = SetPhrase('tagline')); ?></p>
            <div class="buttons mt-5">
              <a href="#start" id="cta" class="button is-medium btn-cta">Create My Resume</a>
              <a href="#templates" class="button is-medium is-light">Browse Templates</a>
            </div>
          </div>
          <div class="column is-hidden-touch">
            <!-- Placeholder device/mockup -->
            <div class="template-card">
              <div class="template-thumb">Live Preview Placeholder</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </article>

  <!-- ========================================================
      SHOWCASE
  ========================================================= -->
  <article id="templates" class="section angled-top">
    <div class="container">
      <h2 class="title is-3">See the Magic</h2>
      <p class="subtitle is-6 has-text-grey-light">Pick a vibe. Tweak the colors. Download as PDF.</p>
      <div class="template-grid mt-4">
        <div class="template-card"><div class="template-thumb">Modern</div></div>
        <div class="template-card"><div class="template-thumb">Creative</div></div>
        <div class="template-card"><div class="template-thumb">Minimal</div></div>
        <div class="template-card"><div class="template-thumb">Witchy</div></div>
      </div>
      <div class="buttons mt-5">
        <a class="button is-light" href="#start">Try a Demo</a>
        <a class="button btn-cta" href="#start">Start Now</a>
      </div>
    </div>
  </article>

  <!-- ========================================================
      FEATURES
  ========================================================= -->
  <article class="section">
    <div class="container">
      <h2 class="title is-3">Why PaperWitch</h2>
      <div class="columns is-multiline">
        <div class="column is-6 is-3-desktop">
          <div class="feature-card">
            <h3 class="title is-5">🧙 Stylish Templates</h3>
            <p class="has-text-grey-light">Curated designs that feel modern, bold, and job‑ready—no corporate boredom.</p>
          </div>
        </div>
        <div class="column is-6 is-3-desktop">
          <div class="feature-card">
            <h3 class="title is-5">⚡ Fast & Simple</h3>
            <p class="has-text-grey-light">Fill your info and export in minutes—no learning curve, no paywall.</p>
          </div>
        </div>
        <div class="column is-6 is-3-desktop">
          <div class="feature-card">
            <h3 class="title is-5">🎨 Custom Colors & Fonts</h3>
            <p class="has-text-grey-light">Make it yours with instant color palettes and clean font pairings.</p>
          </div>
        </div>
        <div class="column is-6 is-3-desktop">
          <div class="feature-card">
            <h3 class="title is-5">💾 One‑Click PDF</h3>
            <p class="has-text-grey-light">TCPDF engine under the hood for crisp, ATS‑friendly exports.</p>
          </div>
        </div>
      </div>
    </div>
  </article>

  <!-- ========================================================
      HOW IT WORKS
  ========================================================= -->
  <article class="section angled-top">
    <div class="container">
      <h2 class="title is-3">How It Works</h2>
      <div class="how-steps mt-4">
        <div class="step">
          <span class="num">1</span>
          <h3 class="title is-5">Fill Your Details</h3>
          <p class="has-text-grey-light">Experience, education, skills—live preview as you type.</p>
        </div>
        <div class="step">
          <span class="num">2</span>
          <h3 class="title is-5">Choose a Template</h3>
          <p class="has-text-grey-light">Pick from Modern, Minimal, Creative, or Witchy collections.</p>
        </div>
        <div class="step">
          <span class="num">3</span>
          <h3 class="title is-5">Download</h3>
          <p class="has-text-grey-light">Save as .PDF — Ready to send or print.</p>
        </div>
      </div>
    </div>
  </article>

  <!-- ========================================================
      TESTIMONIALS
  ========================================================= -->
  <article class="section">
    <div class="container">
      <h2 class="title is-3">Loved by Job‑Hunters</h2>
      <div class="columns is-multiline">
        <div class="column is-4">
          <div class="testimonial">
            <p>“Got my interview in 48 hours. The Witchy template slapped.”</p>
            <p class="has-text-grey-light mt-2">— Lina, Graphic Designer</p>
          </div>
        </div>
        <div class="column is-4">
          <div class="testimonial">
            <p>“Fast, clean, and not boring. Recruiter replied the same day.”</p>
            <p class="has-text-grey-light mt-2">— Ahmed, Front‑end Dev</p>
          </div>
        </div>
        <div class="column is-4">
          <div class="testimonial">
            <p>“I finally felt confident sending my resume. PDF looked crisp.”</p>
            <p class="has-text-grey-light mt-2">— Zoe, Marketing</p>
          </div>
        </div>
      </div>
    </div>
  </article>

  <!-- ========================================================
      PRICING / CTA BAND
  ========================================================= -->
  <article class="section angled-top">
    <div class="container">
      <div class="cta-band p-6">
        <div class="columns is-vcentered">
          <div class="column">
            <h2 class="title is-3">Try PaperWitch for Free</h2>
            <p class="has-text-grey-light">Start building now. Upgrade anytime for premium templates & palettes.</p>
          </div>
          <div class="column has-text-right-desktop">
            <a class="button is-medium btn-cta" href="#start">Start Creating</a>
          </div>
        </div>
      </div>
    </div>
  </article>

  <footer class="footer" style="background: #0a0a10">
    <div class="container">
      <div class="columns">
        <div class="column">
          <strong>PaperWitch</strong>
          <p class="has-text-grey">Resumes with Attitude.</p>
        </div>
        <div class="column">
          <p><a href="#privacy">Privacy Policy</a></p>
          <p><a href="#terms">Terms of Service</a></p>
        </div>
        <div class="column">
          <p><a href="#terms">Legal Notice</a></p>
          <p><a href="#contact">Contact</a></p>
        </div>
      </div>
      <p class="has-text-grey-light">© 2025 PaperWitch</p>
    </div>
  </footer>
</main>