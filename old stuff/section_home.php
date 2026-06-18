<section id="home" class="<?= ViewBook::setVisibility('home'); ?> hero is-fullheight has-text-centered">
  <article class="hero is-dark is-fullheight">
    <div class="floating-sheets"></div>
    <div class="hero-body">
      <div class="container">
        <div class="columns is-vcentered">
          <div class="column has-text-left">
            <h1><?= htmlspecialchars($slogan = mixedGrimoire::setPhrase('slogan')); ?></h1>
            <p class="subtitle hero-sub mt-3"><?= htmlspecialchars($cta = mixedGrimoire::setPhrase('tagline')); ?></p>
            <form class="buttons mt-5" action="client.php" method="post">
                <button type="submit" id="cta" class="button is-medium" name="wizard" value="wizard">Create My Resume</button>
                <a href="#" class="button is-medium is-light" data-section="landing">🔮 New Homepage</a>
            </form>
          </div>
          <!-- <div class="column is-hidden-touch">
            <div class="template-card">
              <div class="template-thumb">Live Preview Placeholder</div>
            </div>
          </div> -->
        </div>
      </div>
    </div>
  </article>

  <!-- ========================================================
      SHOWCASE
  ========================================================= -->
  <!-- <article id="templates" class="section angled-top">
    <div class="container has-text-left">
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
  </article> -->
  
  <!-- ========================================================
      FEATURES
  ========================================================= -->
  <!-- <article class="section">
    <div class="container">
      <h2 class="title is-3">Why PaperWitch</h2>
      <div class="columns is-multiline is-centered">
        <div class="column is-6 is-3-desktop">
          <div class="feature-card has-text-left">
            <h3 class="title is-5">🧙 Stylish Templates</h3>
            <p class="has-text-grey-light">Curated layouts that look like 80's typewriter, modern, or minimalistic.</p>
          </div>
        </div>
        <div class="column is-6 is-3-desktop">
          <div class="feature-card has-text-left">
            <h3 class="title is-5">⚡ Fast & Simple</h3>
            <p class="has-text-grey-light">Fill your info and export directly. No learning curve, no paywall.</p>
          </div>
        </div>
        <div class="column is-6 is-3-desktop">
          <div class="feature-card has-text-left">
            <h3 class="title is-5">🎨 Custom Colors & Fonts</h3>
            <p class="has-text-grey-light">Make it yours with instant color palettes and clean font pairings.</p>
          </div>
        </div>
        <div class="column is-6 is-3-desktop">
          <div class="feature-card has-text-left">
            <h3 class="title is-5">💾 One‑Click PDF</h3>
            <p class="has-text-grey-light">FPDF engine under the hood for instant, ATS‑friendly exports.</p>
          </div>
        </div>
      </div>
    </div>
  </article> -->

  <!-- ========================================================
      HOW IT WORKS
  ========================================================= -->
  <!-- <article class="section angled-top">
    <div class="container">
      <h2 class="title is-3">How It Works</h2>
      <div class="how-steps mt-4">
        <div class="step has-text-left">
          <span class="num">1</span>
          <h3 class="title is-5">Fill Your Details</h3>
          <p class="has-text-grey-light">Experience, education, skills, contact details. We show a live preview at the end.</p>
        </div>
        <div class="step has-text-left">
          <span class="num">2</span>
          <h3 class="title is-5">Choose a Template</h3>
          <p class="has-text-grey-light">Pick from Modern, Late 80's vintage, Projects-orientated, and more to come...</p>
        </div>
        <div class="step has-text-left">
          <span class="num">3</span>
          <h3 class="title is-5">Download</h3>
          <p class="has-text-grey-light">Save as PDF. Ready to send or print.</p>
        </div>
      </div>
    </div>
  </article> -->

  <!-- ========================================================
      TESTIMONIALS
  ========================================================= -->
  <!-- <article class="section">
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
  </article> -->
  
  <!-- ========================================================
      PRICING / CTA BAND
  ========================================================= -->
  <!-- <article class="section angled-top">
    <img class="elegant-divider" src="assets/images/divider1.png" alt="elegant divider">
    <div class="container">
      <div class="cta-band p-6">
        <div class="columns is-vcentered">
          <div class="column has-text-left">
            <h2>Try PaperWitch for Free</h2>
            <p class="has-text-grey-light">Create your paper now!</p>
          </div>
          <form class="column has-text-right-desktop" action="client.php" method="post">
            <button type="submit" id="cta" class="button is-medium btn-cta" name="wizard" value="wizard">🔮 Start Creating</button>
          </form>
        </div>
      </div>
    </div>
  </article> -->
</section>