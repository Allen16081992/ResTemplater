<section id="home" class="<?= ViewBook::Homepage(); ?> hero is-fullheight has-text-centered">
  <article class="hero is-dark is-fullheight">
    <div class="floating-sheets"></div>
    <div class="hero-body">
      <div class="container">
        <div class="columns is-vcentered">
          <div class="column">
            <h1 class="title hero-title is-size-1"><?= htmlspecialchars($slogan = SetPhrase('slogan')); ?></h1>
            <p class="subtitle hero-sub mt-3"><?= htmlspecialchars($cta = SetPhrase('tagline')); ?></p>
            <div class="buttons mt-5">
              <a href="#start" id="cta" class="button is-medium btn-cta">Create My Resume</a>
              <a href="#templates" class="button is-medium is-light">🔮 Browse Templates</a>
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
    
    <article class="full-width-wrapper" style="display:flex; justify-content:center; align-items:center;">
        <div class="resume-container" style="margin-top:2.5rem; margin-left: 3rem;">
            <div class="shape paper">
                <div class="paper-edge-top"></div>
                <div class="paper-edge-side"></div>
            </div>
            <div class="shape circle"></div>
            <div class="shape skills"></div>
            <!-- Lines will be dynamically generated here -->
        </div>
    </article>
</section>