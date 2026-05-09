<section id="home" class="section <?= ViewBook::setVisibility('home'); ?>">
    <div class="container">
        <div class="has-text-centered mb-6">
            <h1 class="title is-1">Pick your Editor</h1>
            <p class="subtitle is-5">Choose the tool that best fits your current flow.</p>
        </div>

        <div class="gallery">
            <a class="card" data-section="wizard">
                <div class="frame">
                    <div class="painting scroll"></div>
                </div>
                <div class="label">Wizard</div>
                <div class="sub">Step-by-step builder</div>
            </a>

            <?php if (isset($_SESSION['session_data']['user_id'])) { ?>
                <a class="card" data-section="builder">
                    <div class="frame">
                        <div class="painting cauldron"></div>
                    </div>
                    <div class="label">Cauldron</div>
                    <div class="sub">Advanced editor</div>
                </a>
            <?php } ?>

            <div class="card">
                <div class="frame">
                    <div class="painting chaos"></div>
                </div>
                <div class="label">Archive</div>
                <div class="sub">Templates & experiments</div>
            </div>
        </div>
    </div>
</section>