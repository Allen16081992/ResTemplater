<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta http-equiv="Content-Security-Policy" content="default-src 'self'; upgrade-insecure-requests;"> -->
    <meta name="description" content="Trek je oude cv uit de sloot met ons handige web applicatie.">
    <!-- Open Graph meta tags for social sharing -->
    <meta property="og:title" content="CV Templater">
    <meta property="og:description" content="Het Curriculum Vitae Ombouw Kistje van Nederlandse Bodem.">
    <meta property="og:image" content="assets/images/falcon250.webp">
    <meta property="og:locale" content="nl_NL">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.donkereheiligdom.nl">
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="assets/images/favicon/site.webmanifest">
    <!-- Styling Sheets -->
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/3d_illustration.css">
    <!-- Mobile Sheets -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Account maken | CV Templater</title>
    <!-- Javascript -->
    <script defer src="assets/js/section-handler.js"></script>
    <script defer src="assets/js/multi_step_rotator.js"></script>
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.html"><img src="assets/images/falcon250x.webp" alt="CV Templater Logo"></a>
        </div>
        <nav>
            <a href="index.html" style="border: 2px solid #f3f3f3;">Terug</a>
        </nav>
    </header>
    <div class="skew"></div>

    <main>
        <section id="home" class="current">
            <div class="form-window">
                <h2>Registreren</h2>
                <form id="signup_form" action="src/req_handler.src.php" method="post">
                    <div class="wizard-info">
                        <span class="step">Algemeen</span>
                        <span class="step">Contact</span>
                        <span class="step">Account</span>
                    </div>
 
                    <div class="tab">
                        <div>   
                            <label for="firstname">Voornaam</label>
                            <input type="text" id="firstname" name="firstname" placeholder="Voornaam" required>
                        </div>
                        <div>
                            <label for="lastname">Achteraam</label>
                            <input type="text" id="lastname" name="lastname" placeholder="Achternaam" required>
                        </div>
                        <div class="input-group">
                            <label for="country">Nationaliteit</label>
                            <input type="text" id="country" name="country" placeholder="(Optioneel)">
                        </div>
                        <div class="input-group">
                            <label for="username">Gebruikersnaam</label>
                            <input type="text" id="username" name="username" placeholder="(Optioneel)">
                        </div>
                    </div>

                    <div class="tab">
                        <div class="date-options">
                            <label for="day-select">Geboortedatum</label>
                            <select id="day-select" name="day" required>
                                <option value="" selected disabled>--</option>
                                <!-- Populated with JS -->
                            </select>
                            <select id="month-select" name="month" required>
                                <option value="" selected disabled>--</option>
                                <!-- Populated with JS -->
                            </select>
                            <select id="year-select" name="year" required>
                                <option value="" selected disabled>----</option>
                                <!-- Populated with JS -->
                            </select>
                        </div>
                        <div>
                            <label for="phone">Telefoonnummer</label>
                            <input type="text" id="phone" name="phone" placeholder="Telefoonnummer" required>
                        </div>
                        <div class="input-group">
                            <label for="city">Woonplaats</label>
                            <input type="text" id="city" name="city" placeholder="Woonplaats" required>
                        </div>
                        <div class="input-group">
                            <label for="postal">Postcode</label>
                            <input type="text" id="postal" name="postal" placeholder="Postcode" required>
                        </div>
                    </div>

                    <div class="tab">
                        <div class="toggle-eye">
                            <label for="email">E-mailadres</label> 
                            <input type="email" id="email" name="email" placeholder="E-mailadres" required>
                            <label for="pwdField">Wachtwoord</label>
                            <input type="password" id="pwdField" name="pwd" aria-describedby="passwordError" placeholder="Wachtwoord" required>
                            <i class='bx bx-low-vision'></i>
                            <label for="terms">Ik heb de <a href="#" data-section="policy">privacyverklaring</a> en <a href="#" data-section="policy">algemene voorwaarden</a> gelezen en ga hiermee akkoord. <input type="checkbox" id="terms" name="terms" required></label>                    
                        </div>
                    </div>
                    
                    <div class="rotator">
                        <button type="submit" id="prevBtn">Terug</button>
                        <button type="submit" id="nextBtn" name="sign_up">Verder</button>                        
                    </div>
                </form>
                <!-- <form id="signup_form">
                    <strong>Gegevens</strong>
                    <label for="firstname">Voornaam</label>
                    <input type="text" id="firstname" name="firstname" placeholder="Voornaam" required>
                    <label for="lastname">Achteraam</label>
                    <input type="text" id="lastname" name="lastname" placeholder="Achternaam" required>
                    <label for="day month year">Geboortedatum</label>
                    <select id="day-select">
                        <option selected>--</option>
                    </select>
                    <select id="month-select">
                        <option selected>--</option>
                    </select>
                    <select id="year-select">
                      <option selected>----</option>
                    </select>
                    <hr class="divider" />
                    <strong>Account</strong>
                    <label for="email">E-mailadres</label> 
                    <input type="text" id="email" name="email" placeholder="E-mailadres" required>

                    <div class="toggle-eye">
                        <label for="pwdField">Wachtwoord</label>
                        <input type="password" id="pwdField" name="pwd" placeholder="Wachtwoord" required>
                        <i class='bx bx-low-vision' aria-label="Toggle password visibility"></i>
                    </div>
                    
                    <label for="terms">Ik heb de <a href="#" data-section="policy">privacyverklaring</a> en <a href="#" data-section="policy">algemene voorwaarden</a> gelezen en ga hiermee akkoord. <input type="checkbox" id="terms" name="terms" required></label>                    
                    <button type="submit" id="nextBtn">Maak mijn Account</button>
                </form> -->
            </div>
        </section>
    </main>

    <noscript><!-- Als Javascript is uitgeschakeld -->
        <p>Het lijkt erop dat JavaScript is uitgeschakeld in uw browser. Hierdoor werkt de site niet meer.</p>
    </noscript>

    <footer>
        <p>Donkere Heiligdom © 2011-2024</p>
    </footer>
</body>
</html>