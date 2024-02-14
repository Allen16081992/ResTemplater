<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Webapplicatie voor het maken van cv.">
    <meta name="author" content="">
    <meta property="og:title" content="CV Templater">
    <meta property="og:type" content="website"/>
    <meta property="og:description" content="Jouw cv editor voor het veroveren van functies.">
    <meta property="og:locale" content="nl_NL" />
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
    <link rel="manifest" href="images/favicon/site.webmanifest">
    <!-- Styling Sheets -->
    <link rel="stylesheet" href="styles/style.css"> 
    <title>Home | CV Templater - Koloniseer die Vacature!</title>
    <script defer src="assets/view-painter.js"></script>
    <script defer src="assets/form.multi-step.js"></script>
</head>
<body>
    <header>
        <div class="logo"><a href="#" id="home"><img src="images/CV-mascot.webp" alt="CV Mascot Logo - Click to visit the Homepage"></a></div>
        <nav>
            <a href="#" data-section="news">Nieuws</a>
            <a href="#" data-section="login">Inloggen</a>
            <a href="#" data-section="signup">Registreren</a>
            <a href="#" data-section="contact">Contact</a>
        </nav>
    </header>

    <main>
        <section id="home" class="current">
            <h1>Welcome</h1>
            <p>hey, I am still empty. You should fill me up.</p>
        </section>

        <section id="news" class="hidden">
            <h2>Nieuws</h2>
            <p>hey, I am still empty. You should fill me up.</p>
        </section>

        <section id="login" class="hidden">
            <div class="form-window">
                <h2>Inloggen</h2>
                <form>
                    <p class="error-msg"></p>
                    <label for="username">Gebruikersnaam</label>
                    <input type="text" name="username" placeholder="Gebruikersnaam">
                    <label for="pwd">Wachtwoord</label>
                    <input type="password" name="pwd" placeholder="Wachtwoord">
                    <input type='submit' name='signin' value='Inloggen'>
                </form>
            </div>
        </section>

        <section id="signup" class="hidden">
            <div class="form-window">
                <h2>Account maken</h2>
                <form id="rotateForm">
                    <div style="text-align:center;margin-top:10px; cursor:default;">
                        <span class="step"></span>
                        <span class="step"></span>
                    </div>
                    <p class="error-msg"></p>
                    <div class="tab">
                        <label for="username">Gebruikersnaam</label>
                        <input type="text" name="username" placeholder="Gebruikersnaam">
                        <label for="email">E-mailadres</label> 
                        <input type="text" name="email" placeholder="E-mailadres">
                    </div>
                    <div class="tab">
                        <label for="pwd">Wachtwoord</label>
                        <input type="password" name="pwd" placeholder="Wachtwoord">
                        <label for="pwd">Herhaal Wachtwoord</label>
                        <input type="password" name="pwdR" placeholder="Herhaal Wachtwoord">
                        <label for="terms"></label>
                        <span><input type="checkbox" title="terms" name="terms" required> Lees en accepteer de <a href="terms-and-conditions.php" target="_blank">algemene voorwaarden</a>.</span>
                    </div>
                    
                    <!-- <label for="firstname">Naam</label>
                    <input type="text" name="firstname" placeholder="Naam">
                    <label for="lastname">Achteraam</label>
                    <input type="text" name="lastname" placeholder="Achternaam"> -->

                    <!-- <label for="country">Nationaliteit</label>
                    <input type="text" name="country" placeholder="Nationaliteit"> 
                    <label for="birth">Geboortedatum</label>
                    <select name="day" id="day-birth">
                        <option selected>--</option>
                        
                            //for ($day = 1; $day <= 31; $day++) {
                            //    echo '<option value="'.$day.'">'.$day.'</option>';
                            //}
                        
                    </select>
                    <select name="month" id="month-birth">
                        <option selected>--</option>
                        
                            //for ($month = 1; $month <= 12; $month++) {
                            //    echo '<option value="'.$month.'">'.$month.'</option>';
                            //}
                        
                    </select>
                    <select name="year" id="year-birth">
                        <option selected>----</option>
                        
                            //$currentYear = date('Y');
                            //$targetYear = 1922;
                            //for ($year = $currentYear - 16; $year >= $targetYear; $year--) {
                            //    echo '<option value="'.$year.'">'.$year.'</option>';
                            //}
                        
                    </select> -->
                    <!-- <label for="phone">Telefoon Nr.</label> 
                    <input type="phone" name="phone" placeholder="Telefoon Nr."> -->
                    <!-- <label for="postal">Postalcode</label> 
                    <input type="text" name="postal" placeholder="Postcode"> -->
                    <!-- <label for="city">Stad</label> 
                    <input type="text" name="city" placeholder="Stad"> -->

                    <div class="rotator">
                        <button type="submit" id="prevBtn" onclick="nextPrev(-1)">Terug</button>
                        <button type="submit" id="nextBtn" onclick="nextPrev(1)">Verder</button>
                    </div>
                </form>
            </div>
        </section>

        <section id="contact" class="hidden">
            <div class="form-window">
                <h2>Contact opnemen</h2>
                <form>
                    <p class="error-msg"></p>
                    <label for="name">Naam</label>
                    <input type="text" name="name" placeholder="Naam">
                    <label for="email">Your Email</label>
                    <input type="text" name="email" placeholder="Email">
                    <label for="message">Jouw bericht</label>
                    <textarea name="message" placeholder="..." rows="3"></textarea>
                    <input type='submit' name='contact' value='Verzenden'>
                </form>
            </div>
        </section>

        <!-- Displayed if JavaScript is disabled -->
        <noscript>
            <div>
                <p>JavaScript is disabled in your browser. No worries, this site is designed to work without it. Who needs Javascript anyway? Websites should also function without, right?</p>
            </div>
        </noscript>   
    </main>
</body>
</html>