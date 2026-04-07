<section id="home" class="<?= ViewBook::setVisibility('home'); ?>">
    <div class="pw-editor">
        <article class="pw-panel">
            <h1 class="is-size-1">How would you like to build your resume?</h1>
            <p class="intro">Choose a mode and begin. The Wizard is more guided. The Editor gives you direct control.</p>

            <div class="pw-option">
                <div class="row">
                    <div>
                        <h2>Wizard</h2>
                        <p>A guided, step-by-step flow for building a clean first draft.</p>
                    </div>
                    <a class="pw-btn" data-section="wizard">Start Wizard</a>
                </div>
            </div>
            <?php if (isset($_SESSION['session_data']['user_id'])) { ?>
                <div class="pw-option">
                    <div class="row">
                        <div>
                            <h2>Editor</h2>
                            <p>A direct editing workspace for users who want more freedom.</p>
                        </div>
                        <a class="pw-btn" data-section="builder">Open Editor</a>
                    </div>
                </div>
            <?php } ?>
            <div class="pw-option">
                <div class="row">
                    <div>
                        <h2>Hiring Havoc (mini-game)</h2>
                        <p>Take a break, raze corporates in this tiny side-scroller.</p>
                    </div>
                    <a class="pw-btn" style="color:grey;" disabled>Arcade</a>
                </div>
            </div>
        </article>
    </div>
</section>