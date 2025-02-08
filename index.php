<?php 
    // Load PHP files
    include_once "./src/session_manager.src.php"; 
    logoutRequest();
?>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/3d_illustration.css">
    <!-- <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'> -->
    <title>Jouw Mobiele CV Editor | PaperTiger</title>
    <!-- Javascript -->
    <script defer src="assets/js/section-handler.js"></script>
    <script defer src="assets/js/3d_dummy_lines.js"></script>
    <!-- <script defer src="assets/js/multi_step_rotator.js"></script> -->
</head>
<body>
    <header> 
        <a href="index.php" id="logo"><img src="assets/images/falcon250x.webp" alt="Brand logo"></a>
        <nav role="navigation" aria-label="main navigation">
            <a href="#" data-section="home">Home</a><!-- Mobile only -->
            <!-- <a href="#" data-section="author">Tips</a> -->
            <a href="#" data-section="policy">Privacy</a>
            <!-- <a href="#" data-section="contact">Contact</a> -->
            <a href="#" data-section="login">Log in</a>
            <a href="#" data-section="sign_up">Sign Up</a>
        </nav>
    </header>
    <div class="skew"></div>
    
    <main>
        <section id="home" class="<?= Homepage(); ?> hero is-fullheight has-text-centered">
            <!-- <h1>Eenvoudig en snel je eigen professionele cv samenstellen.</h1> -->
            <div class="hero-body">
                <div class="container">
                    <h1 class="title is-size-2 has-text-white">PaperWitch</h1>
                    <p class="subtitle is-size-4 has-text-light">Resumes with attitude.</p>
                    <a href="#features" class="button is-medium is-danger is-outlined">🔮 Unleash Your Resume</a>
                </div>
            </div>
            
            <div class="full-width-wrapper" style="display:flex; justify-content:center; align-items:center;">
                <div class="resume-container" style="margin-top:2.5rem; margin-left: 3rem;">
                    <div class="shape paper">
                        <div class="paper-edge-top"></div>
                        <div class="paper-edge-side"></div>
                    </div>
                    <div class="shape circle"></div>
                    <div class="shape skills"></div>
                    <!-- Lines will be dynamically generated here -->
                </div>
            </div>

            <div class="section is-medium has-text-centered">
                <div class="container">
                    <h2 class="title is-size-2 has-text-white">Features that make you stand out</h2>
                    <div class="columns is-multiline mt-6">
                        <div class="column is-4">
                            <div class="box has-background-grey-dark has-text-white">
                                <h3 class="title is-size-4">🔥 Bold Templates</h3>
                                <p>Break the mold with unique resume styles.</p>
                            </div>
                        </div>
                        <div class="column is-4">
                            <div class="box has-background-grey-dark has-text-white">
                                <h3 class="title is-size-4">🎭 Express Yourself</h3>
                                <p>Customization that lets your personality shine.</p>
                            </div>
                        </div>
                        <div class="column is-4">
                            <div class="box has-background-grey-dark has-text-white">
                                <h3 class="title is-size-4">🧙‍♂️ No Login Needed</h3>
                                <p>Instantly craft your resume—no accounts, no hassle.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        

        <section id="author" class="hidden"></section>

        <section id="policy" class="hidden">
            <div class="grid-ad-container">
                <div class="ads"></div>
                <div>
                    <h2>Privacybeleid en veiligheid</h2>
                    <p>Dit privacybeleid legt uit hoe we uw persoonlijke gegevens verzamelen, 
                        gebruiken en beschermen in overeenstemming met de Algemene Verordening Gegevensbescherming (AVG).
                        Lees ons privacybeleid en algemene voorwaarden voordat u gebruik maakt van onze service (CV Templater).
                    </p>
    
                    <div>
                        <h2>Algemene Voorwaarden</h2>
                        <p>Door gebruik te maken van onze diensten gaat u akkoord met de verwerking van de door u verstrekte gegevens.</p>
                        <strong>Uw Gebruikersaccount</strong>
                        <p>Als u onze dienst (CV Templater) wenst te gebruiken, dan heeft u een account en internet verbinding nodig. Ook bent u minimaal 16 jaar, of
                           u heeft goedkeuring van uw ouders of wettelijke voogd.</p>
    
                        <h2>Privacybeleid</h2>
                        <h3>1. Informatie die we verzamelen</h3>
                        <p>Wij verzamelen de volgende persoonlijke gegevens via onze website wanneer u zich registreert.</p>
                        <ul>
                            <li>Voornaam</li>
                            <li>Achternaam</li>
                            <li>Nationaliteit</li>
                            <li>Geboortedatum</li>
                            <li>Telefoonnummer</li>
                            <li>Postcode en Woonplaats</li>
                            <li>Emailadres</li>
                        </ul>
            
                        <h3>2. Hoe we deze informatie gebruiken</h3>
                        <p>Wij gebruiken uw persoonlijke gegevens voor de volgende doeleinden.</p>
                        <ul>
                            <li>Om onze dienst (CV Templater) aan u beschikbaar te stellen</li>
                            <li>Om uw account te kunnen laten beheren door uzelf</li>
                            <li>Om uw account te voorzien van de juiste rechten</li>
                            <li>Om onze website functionaliteit te verbeteren</li>
                        </ul>
    
                        <h3>3. Juridische Grondslag voor Verwerking</h3>
                        <p>We verwerken uw persoonlijke gegevens op basis van de volgende juridische gronden.</p>
                        <ul>
                            <li>Door deze te verstrekken, stemt u in met de verwerking comform onze doeleinden.</li>
                            <li>Verwerking is noodzakelijk voor onze gerechtvaardigde belangen, zoals het verbeteren van onze diensten en het waarborgen van de veiligheid van onze website.</li>
                        </ul>
    
                        <h3>4. Gegevensbeveiliging</h3>
                        <p>We nemen de veiligheid van uw persoonlijke gegevens serieus en implementeren 
                            passende technische beveiligingsmaatregelen om deze te beschermen tegen ongeautoriseerd toegang, 
                            het wijzigen, vernietigen of diefstal door kwaadwillenden. Deze maatregelen omvatten:
                        </p>
                        <ul>
                            <li>Beveiligde servers bij onze webhosting aanbieder</li>
                            <li>Versleutelde verbindingen (HTTPS/SSL)</li>
                            <li>Periodieke beveiligingsaudits</li>
                            <li>Encryptie van wachtwoorden</li>
                            <li>Sterk wachtwoordbeleid</li>
                        </ul>
    
                        <h3>5. Gegevensbewaring</h3>
                        <p>We bewaren uw persoonlijke gegevens zo lang als nodig is om onze in deze privacybeleid beschreven doeleinden te vervullen, 
                            of zoals wettelijk vereist. Wanneer deze gegevens niet langer nodig zijn, bijvoorbeeld wanneer u besluit uw account te sluiten,
                            zullen wij deze in hun volledigheid verwijderen uit ons systeem. Deze kunnen dan ook niet meer worden opgevraagd.
                        </p>
    
                        <h3>6. Wijzigingen in dit Privacybeleid</h3>
                        <p>Wij kunnen dit privacybeleid van tijd tot tijd aanpassen. 
                            We zullen u op de hoogte stellen van eventuele wijzigingen door het nieuwe privacybeleid op onze website te plaatsen. 
                            Daarom raden wij u ook aan om dit privacybeleid periodiek door te nemen, want het is ook niet lang om te lezen.
                        </p>
                        <p>Als u vragen heeft over ons privacybeleid, neem dan contact op via onze contact pagina.</p>
                    </div>
                </div>
                <div class="ads"></div>
            </div>
        </section>

        <section id="contact" class="hidden">
            <div class="sheet">
                <h2>Contact Opnemen</h2>
                <p><strong>Let op!</strong> Om spam en bots tegen te gaan, gebruiken wij geen contact formulier.</p>
                <div class="box">
                    <strong>Email</strong>
                    <p>info@donkereheiligdom en vergeet niet .nl erbij te zetten.</p>
                </div>
            </div>
        </section>

        <section id="login" class="<?= serverLogin(); ?>">
            <div class="form-window">
                <h2>Aanmelden</h2>
                <form id="login_form" action="src/account.src.php" method="post">
                    <label for="email">E-mailadres</label>
                    <input type="email" id="email" name="email" placeholder="Email" required>
                    <label for="pwd">Wachtwoord</label>
                    <input type="password" id="pwd" name="pwd" placeholder="Wachtwoord" required>
                    <!-- Hidden field is needed since js submit() instantly sends, ignoring form modifications -->
                    <!-- <input type="hidden" name="login"> -->
                    <button type="submit" id="loginBtn" name="loginBtn">Log in</button>
                    <span>Nog geen account? <a href="#" data-section="sign_up">Maak hier een nieuwe</a></span>
                </form>
            </div>
        </section>

        <section id="sign_up" class="<?= serverSignup(); ?>">
            <div class="form-window">
                <h2>Registreren</h2>
                <form id="signup_form" action="src/account.src.php" method="post">
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
                            <select class="day-select" name="day" required>
                                <option value="" selected disabled>--</option>
                            </select>
                            <select class="month-select" name="month" required>
                                <option value="" selected disabled>--</option>
                            </select>
                            <select class="year-select" name="year" required>
                                <option value="" selected disabled>----</option>
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
                        <button type="submit" id="nextBtn" name="signupBtn">Verder</button>                        
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

    <footer style="background:black;">
        <p>CV Templater © 2023 - 2024</p>
    </footer>
</body>
</html>
<?php session_unset(); session_destroy(); ?>