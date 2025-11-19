<section id="sign_up" class="<?= ViewBook::setView_Error('sign_up'); ?>">
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

                    <form id="signup_form">
                        <!-- Personal Information -->
                        <h3 class="is-size-6 has-text-grey-light">Personal Information</h3>

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

                        <hr class="divider mt-3 mb-2">

                        <!-- Account Information -->
                        <h3 class="title is-6 has-text-grey-light">Account Details</h3>

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

                        <div class="field m-3">
                            <label class="checkbox">
                            <input type="checkbox" id="terms" name="terms" required>
                            I agree to the <a href="#">terms</a> and <a href="#">conditions</a>.
                            </label>
                        </div>

                        <div class="field mt-4">
                            <button type="submit" id="signupBtn" class="button is-medium btn-primary is-fullwidth">Sign Up</button>
                        </div>

                        <p class="footnote">Already have an account? <a class="has-text-info">Sign in here</a>.</p>
                    </form>
                </div>
            </div>
        </div>
        <!-- <h2>Registreren</h2>
        <form id="signup_form" action="src/account.src.php" method="post">
            <div class="wizard-info">
                <span class="step">Personal</span>
                <span class="step">Account</span>
            </div>

            <div class="tab">
                <div>   
                    <label for="firstname">Voornaam</label>
                    <input class="input" type="text" id="firstname" name="firstname" placeholder="Voornaam" required>
                </div>
                <div>
                    <label for="lastname">Achteraam</label>
                    <input class="input" type="text" id="lastname" name="lastname" placeholder="Achternaam" required>
                </div>
                <div class="date-options">
                    <label for="date">Geboortedatum</label>
                    <select class="day-select" name="day" required>
                        <option value="" selected disabled>Day</option>
                    </select>
                    <select class="month-select" name="month" required>
                        <option value="" selected disabled>Month</option>
                    </select>
                    <select class="year-select" name="year" required>
                        <option value="" selected disabled>Year</option>
                    </select>
                </div>
                <div>
                    <label for="username">Gebruikersnaam</label>
                    <input class="input" type="text" id="username" name="username" placeholder="(Optioneel)">
                </div>
            </div>

            <div class="tab">
                <div class="toggle-eye">
                    <label for="email">E-mailadres</label> 
                    <input class="input" type="email" id="email" name="email" placeholder="E-mailadres" required>
                    <label for="pwdField">Wachtwoord</label>
                    <input class="input" type="password" id="pwdField" name="pwd" placeholder="Wachtwoord" required>
                    <i class='bx bx-low-vision' aria-label="Toggle password visibility"></i>
                    <label for="terms"><input type="checkbox" id="terms" name="terms" required> I have read the <a href="#" data-section="policy">terms</a> and <a href="#" data-section="policy">conditions</a> and accept.</label>                    
                </div>
            </div>
            
            <div class="rotator">
                <button class="button is-link is-fullwidth" type="submit" id="prevBtn">Terug</button>
                <button class="button is-link is-fullwidth" type="submit" id="nextBtn" name="signupBtn">Verder</button>                        
            </div>
        </form> -->
    </div>
</section>