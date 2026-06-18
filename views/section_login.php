<section id="login" class="<?= ViewBook::setVisibility('login'); ?>"> 
    <div class="form-window">
        <div class="auth-card">
            <div class="auth-columns">
                <!-- Left: IMAGE -->
                <aside class="auth-visual" id="loginVisual">
                    <span class="badge">🧙‍♀️ Paper Witch • Witchy & Bold</span>
                </aside>

                <!-- Right: FORM -->
                <div class="auth-form">
                    <div class="control has-text-left">
                        <h1 class="auth-title">Welcome back</h1>
                        <p class="auth-sub">Sign in to continue crafting your resume.</p>
                    </div>
                    <form action="engine/action_handler.php" method="post">
                        <?= SessionBook::csrfField(); ?>
                        <div class="field">
                            <label class="label" for="email">Email</label>
                            <div class="server-field animate__animated animate__shakeX"><?= ViewBook::getError('email') ?></div>
                            <div class="control has-icons-left">
                                <input id="email" name="email" type="email" class="input" value="jade_greenhill32zzz@gmail.com <?php //ViewBook::setOldForm('email'); ?>" placeholder="you@domain.com" autocomplete="email" required>
                                <span class="icon is-small is-left"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 13.065 2.4 6.6h19.2L12 13.065Zm0 2.07L2.4 8.67V18h19.2V8.67L12 15.135Z"/></svg></span>
                            </div>
                        </div>
                        
                        <div class="field">
                            <label class="label" for="password">Password</label>
                            <div class="server-field animate__animated animate__shakeX"><?= ViewBook::getError('pwd') ?></div>
                            <div class="control has-icons-left">
                                <input id="password" name="pwd" type="password" class="input" placeholder="••••••••" autocomplete="current-password" required value="Jade Greenhill 101">
                                <span class="icon is-small is-left"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17 9V7a5 5 0 0 0-10 0v2H5v12h14V9h-2Zm-8 0V7a3 3 0 0 1 6 0v2H9Z"/></svg></span>
                            </div>
                        </div>
                        <div class="meta-row">
                            <a class="pw-link" href="#forgot">Forgot password?</a>
                        </div>

                        <div class="field mt-4">
                            <button type="submit" name="action" value="login" class="button is-medium btn-primary is-fullwidth">Login</button>
                        </div>
                        <div class="divider mt-3 mb-2">or</div>
                        <div class="buttons">
                            <button type="button" class="button is-light is-fullwidth">
                                <svg width="18" height="18" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.9 31.9 29.4 35 24 35c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.8 5.1 29.7 3 24 3 12.3 3 3 12.3 3 24s9.3 21 21 21 19.5-8.3 19.5-21c0-1.2-.1-2.3-.3-3.5z"/><path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.7 16.6 19 13 24 13c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.8 5.1 29.7 3 24 3 16 3 9.2 7.2 6.3 14.7z"/><path fill="#4CAF50" d="M24 45c5.3 0 10.2-2 13.8-5.2l-6.4-5.2C29.4 35 26.8 36 24 36c-5.4 0-9.9-3.1-11.7-7.4l-6.6 5.1C8.6 41.1 15.8 45 24 45z"/><path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-1 2.9-3.1 5.3-5.8 6.8l.1.1 6.4 5.2C34.9 41 39 37 40.5 31.5c.9-2.8 1.3-5.8 1.3-9 0-1.2-.1-2.3-.2-3.5z"/></svg>
                                Continue with Google
                            </button>
                        </div>

                        <p class="footnote">No account yet? <a class="pw-link" data-section="sign_up">Create one</a> and tuck away your resumes.</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php ViewBook::clearOldForm(); ?>