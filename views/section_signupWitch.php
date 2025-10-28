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
                <label class="label" for="password">Password</label>
                <div class="control has-icons-right">
                <input id="password" name="password" type="password" class="input" placeholder="••••••••" required>
                <span class="icon is-small is-right" id="togglePwd">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 5c-7 0-11 7-11 7s4 7 11 7 11-7 11-7-4-7-11-7Zm0 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8Z"/></svg>
                </span>
                </div>
            </div>

            <div class="field">
                <label class="checkbox">
                <input type="checkbox" id="terms" name="terms" required>
                I agree to the <a href="#">terms</a> and <a href="#">conditions</a>.
                </label>
            </div>

            <div class="field mt-4">
                <button class="button is-medium btn-primary is-fullwidth" type="submit" id="signupBtn">Sign Up</button>
            </div>

            <p class="footnote">Already have an account? <a class="has-text-info" href="login.html">Sign in here</a>.</p>
            </form>
        </div>
        </div>
    </div>
</section>