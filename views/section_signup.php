<section id="sign_up" class="<?= ViewBook::setVisibility('sign_up'); // ViewBook::setView_Error('sign_up'); ?>">
    <div class="form-window">
        <div class="auth-card">
            <div class="auth-columns">
                <!-- Left: IMAGE PANEL -->
                <aside class="auth-visual" id="signupVisual">
                    <span class="badge">✨ PaperWitch • Begin the Ritual</span>
                </aside>

                <!-- Right: SIGNUP FORM -->
                <div class="auth-form">
                    <div class="control has-text-left">
                        <h1 class="auth-title">Create your account</h1>
                        <p class="auth-sub">Start your journey to craft the perfect resume.</p>
                    </div>

                    <form id="signup_form" action="config/action_handler.conf.php" method="post">
                        <!-- Personal Information -->
                        <h3 class="title is-size-5 has-text-grey-light">Personal Information</h3>
                        <div class="field is-horizontal">
                            <div class="field-body">
                                <?php ViewBook::render('form_date_format.php'); ?>
                                <div class="field">
                                    <label class="label" for="username">Username</label>
                                    <input id="username" name="username" type="text" class="input" placeholder="(optional)">  
                                </div>
                            </div>
                        </div>
                        <hr class="divider mt-3 mb-2">

                        <!-- Account Information -->
                        <h3 class="title is-size-5 has-text-grey-light">Account Details</h3>
                        <div class="field">
                            <label class="label" for="email">Email address</label>
                            <div class="control has-icons-left">
                                <input id="email" name="email" type="email" class="input" placeholder="you@domain.com" required>
                                <span class="icon is-small is-left">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 13.065 2.4 6.6h19.2L12 13.065Zm0 2.07L2.4 8.67V18h19.2V8.67L12 15.135Z"/></svg>
                                </span>
                            </div>
                        </div>

                        <div class="toggle-eye">
                            <label class="label" for="pwdField">Password</label>                     
                            <input type="password" id="pwdField" name="pwd" class="input" placeholder="••••••••" required>
                            <i class='bx bx-low-vision' aria-label="Toggle password visibility"></i>                      
                        </div>

                        <ul class="pwd-rules">
                            <li>● At least one lowercase letter</li>
                            <li>● At least one uppercase letter</li>
                            <li>● At least one number</li>
                            <li>● At least one special character</li>
                            <li>● Atleast 8 characters in length</li>
                        </ul>

                        <div class="field m-3">
                            <label class="checkbox">
                            <input type="checkbox" id="terms" name="terms" required>
                            I agree to the <a href="#">terms</a> and <a href="#">conditions</a>.
                            </label>
                        </div>

                        <div class="field mt-4">
                            <button type="submit" id="signupBtn" name="action" value="signup" class="button is-medium btn-primary is-fullwidth">Sign Up</button>
                        </div>
                        <p class="footnote">Already have an account? <a class="has-text-info">Sign in here</a>.</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
