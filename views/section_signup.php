<section id="sign_up" class="<?= ViewBook::isVisible('signup'); ?>">
    <div class="form-window">
        <h2>Registreren</h2>
        <form id="signup_form" action="src/account.src.php" method="post">
            <div class="wizard-info">
                <span class="step">Personal</span>
                <!-- <span class="step">Contact</span> -->
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
        </form>
    </div>
</section>