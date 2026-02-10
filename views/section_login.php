<section id="login" class="<?= ViewBook::setVisibility('login'); ?>"> 
    <div class="form-window">
        <div class="auth-card">
            <div class="auth-columns">
                <!-- Left: IMAGE -->
                <aside class="auth-visual" id="loginVisual">
                    <span class="badge">🧙‍♀️ PaperWitch • Witchy & Bold</span>
                </aside>

                <!-- Right: FORM -->
                <div class="auth-form">
                    <div class="control has-text-left">
                        <h1 class="auth-title">Welcome back</h1>
                        <p class="auth-sub">Sign in to continue crafting your resume.</p>
                    </div>
                    <form action="config/action_handler.conf.php" method="post">
                        <?= SessionBook::csrfField(); ?>
                        <div class="field">
                            <label class="label" for="email">Email</label>
                            <div class="control has-icons-left">
                                <input id="email" name="email" type="email" class="input" placeholder="you@domain.com" autocomplete="username" required>
                                <span class="icon is-small is-left">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 13.065 2.4 6.6h19.2L12 13.065Zm0 2.07L2.4 8.67V18h19.2V8.67L12 15.135Z"/></svg>
                                </span>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label" for="password">Password</label>
                            <div class="control has-icons-left">
                                <input id="password" name="pwd" type="password" class="input" placeholder="••••••••" autocomplete="current-password" required>
                                <span class="icon is-small is-left">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17 9V7a5 5 0 0 0-10 0v2H5v12h14V9h-2Zm-8 0V7a3 3 0 0 1 6 0v2H9Z"/></svg>
                                </span>
                            </div>
                        </div>

                        <div class="meta-row">
                            <a class="pw-link" href="#forgot">Forgot password?</a>
                        </div>

                        <div class="field mt-4">
                            <button type="submit" name="action" value="login" class="button is-medium btn-primary is-fullwidth">Sign In</button>
                        </div>

                        <div class="divider mt-3 mb-2">or</div>

                        <div class="buttons">
                            <button type="button" class="button is-light is-fullwidth">Sign in with Google</button>
                        </div>

                        <p class="footnote">No account yet? <a class="pw-link" href="#signup">Create one</a> and tuck away your resumes.</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>