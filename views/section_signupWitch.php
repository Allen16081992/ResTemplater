<section id="sign_up" class="<?= ViewBook::setView_Error('signup'); ?>">
    <div class="signup-card">
        <div class="signup-columns">
        <!-- Left: IMAGE PANEL -->
        <aside class="signup-visual" id="signupVisual">
            <!-- Replace this image with your default witch image -->
            <!-- background-image: url('images/witch_calm.png'); -->
            <span class="badge">✨ PaperWitch • Begin the Ritual</span>
        </aside>

        <!-- Right: SIGNUP FORM -->
        <div class="signup-form">
            <header>
            <h1 class="signup-title">Create your account</h1>
            <p class="signup-sub">Start your journey to craft the perfect resume.</p>
            </header>

            <form id="signup_form" action="src/account.src.php" method="post" novalidate>

            <!-- Personal Information -->
            <h3 class="title is-6 has-text-grey-light">Personal Information</h3>

            <div class="field is-horizontal">
                <div class="field-body">
                <div class="field">
                    <label class="label" for="date">Date of Birth</label>
                    <div class="control">
                    <input id="date" name="date" type="date" class="input" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label" for="username">Username (optional)</label>
                    <div class="control">
                    <input id="username" name="username" type="text" class="input" placeholder="Choose a username">
                    </div>
                </div>
                </div>
            </div>

            <hr class="divider">

            <!-- Account Information -->
            <h3 class="title is-6 has-text-grey-light">Account Details</h3>

            <div class="field">
                <label class="label" for="email">Email address</label>
                <div class="control">
                <input id="email" name="email" type="email" class="input" placeholder="you@domain.com" required>
                </div>
            </div>

            <div class="field">
                <i class='bx bx-low-vision' aria-label="Toggle password visibility"></i>
              <label class="label" for="pwdField">Password</label>
              <div class="control has-icons-right">
                    
                    <input id="pwdField" name="pwd" type="password" class="input" placeholder="••••••••" required>
              </div>
            </div>

            <!-- <div class="field">
                <label class="label" for="password_repeat">Confirm Password</label>
                <div class="control">
                    <input id="password_repeat" name="password_repeat" type="password" class="input" placeholder="••••••••" required>
                </div>
            </div> -->

            <div class="field">
                <label class="checkbox">
                <input type="checkbox" id="terms" name="terms" required>
                I agree to the <a href="#">terms</a> and <a href="#">conditions</a>.
                </label>
            </div>

            <div class="field mt-4">
                <!-- 🔒 Used by JS to swap image (do not remove id) -->
                <button type="submit" id="signupBtn" class="button is-medium btn-primary is-fullwidth">Sign Up</button>
            </div>

            <p class="footnote">Already have an account? <a class="has-text-info" data-section="login">Sign in here</a>.</p>
            </form>
        </div>
        </div>
    </div>
</section>