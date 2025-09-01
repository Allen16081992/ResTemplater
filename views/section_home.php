<section id="home" class="<?= ViewBook::Homepage(); ?> hero is-fullheight has-text-centered">
    <div class="hero-body">
        <div class="container">
            <h1 class="title is-size-2 has-text-white">PaperWitch</h1>
            <p class="subtitle is-size-4 has-text-light">Resumes with attitude.</p>
            <a href="client.php" id="cta" class="button is-medium is-danger is-outlined">🔮 Write Your Grimoire Today</a>
        </div>
    </div>
    
    <div class="full-width-wrapper" style="display:flex; justify-content:center; align-items:center;">
        <div class="resume-container" style="margin-top:2.5rem; margin-left: 3rem;">
            <div class="shape paper">
                <div class="paper-edge-top"></div>
                <div class="paper-edge-side"></div>
            </div>
            <div class="shape circle"></div>
            <div class="shape skills"></div>
            <!-- Lines will be dynamically generated here -->
        </div>
    </div>

    <div style="background: #212121;">
        <div class="page-wrapper">
            <div class="section-timeline-heading">
                <div class="contain-me">
                    <div class="padding-vertical-xlarge">
                        <div class="timeline-main-heading-wrapper">
                            <div class="margin-bottom-medium">
                                <h2>Rise of Noobs</h2>
                            </div>
                            <p class="paragraph-large">
                                Lorem ipsum dolor sit, amet consectetur adipisicing elit. 
                                Alias qui rerum, ducimus odio quasi veritatis saepe consectetur 
                                officia debitis? Doloribus facilis commodi obcaecati totam dolorum hic 
                                voluptates iure quia unde?
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pw-timeline" id="features">
            <div class="pw-line" aria-hidden="true"></div>

            <!-- Item -->
            <article class="pw-item">
                <div class="pw-marker"><div class="pw-emitter" aria-hidden="true"></div></div>
                <div class="pw-card">
                <h3>Choose a Template</h3>
                <p>Classic, Modern, or Compact — swap with one click.</p>
                </div>
            </article>

            <article class="pw-item">
                <div class="pw-marker"><div class="pw-emitter" aria-hidden="true"></div></div>
                <div class="pw-card">
                <h3>Live Preview</h3>
                <p>CSS Grid preview updates as you type.</p>
                </div>
            </article>

            <article class="pw-item">
                <div class="pw-marker"><div class="pw-emitter" aria-hidden="true"></div></div>
                <div class="pw-card">
                <h3>Instant Export</h3>
                <p>One-click PDF via TCPDF, consistent with the preview.</p>
                </div>
            </article>

            <article class="pw-item">
                <div class="pw-marker"><div class="pw-emitter" aria-hidden="true"></div></div>
                <div class="pw-card">
                <h3>Private by Default</h3>
                <p>Autosave in your browser; sync only when you choose.</p>
                </div>
            </article>
        </div>
    </div>
</section>