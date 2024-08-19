<?php 
  // Start a session for handling data and error messages.
  require_once 'src/session_manager.src.php'; 
  //Unauthorized_Access(); // Verify access
  //sessionRegenTimer(); // Regenerate the session periodically
  // Load PHP files to retrieve data
  //require_once "config/ViewResumes.config.php";
  //require_once "config/FetchResumeTables.config.php";
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
    <link rel="canonical" href="https://www.donkereheiligdom.nl">
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="assets/images/favicon/site.webmanifest">
    <!-- Styling Sheets -->
    <link rel="stylesheet" href="assets/css/main.css">
    <!-- <link rel="stylesheet" href="assets/css/3d_illustration.css"> -->
    <title>Mijn Templater | CV Templater</title>
    <!-- Javascript -->
    <script defer src="assets/js/section-handler.js"></script>
</head>
<body>
    <header>
        <div class="logo">
            <a href="portal.php" id="logo"><img src="assets/images/falcon250x.webp" alt="CV Templater Logo"></a>
        </div>
        <nav>
            <a href="#" data-section="user"><?= isset($_SESSION['user_name']) ? $_SESSION['user_name'] : (isset($_SESSION['firstname']) ? $_SESSION['firstname'] : "Gebruiker"); ?></a>
            <a href="#" data-section="home">Mijn CV</a><!-- Mobile only -->
            <a href="#" data-section="guide">Onze gids</a>
            <a href="#" data-section="logout">Uitloggen</a>
        </nav>
    </header>
    <div class="skew"></div>

    <main>
        <section id="home" class="current">
            <div class="profile">
                <div class="form-window">
                    <h2>CV Templater</h2>
                    <!-- <button class="avatar">Profiel Foto</button> -->
                    <form>
                        <h3>Mijn Curriculum Vitae</h3>
                        <button type="submit" data-section="create-res">Nieuwe CV</button> 

                        <label for="cvname">CV Ophalen:</label>
                        <select name="cvname">
                            <option value="default">---------</option>
                        </select> 

                        <div class="tab">
                            <div class="button-wrapper">
                                <button type="button" data-section="style-res" >Downloaden</button>
                                <button type="button" data-section="delete-res" >Verwijderen</button>
                            </div>
                        </div>
                        <div class="account-section-divider"></div>
                    </form>
                </div>
                
                <div id="accordion">
                    <!-- Curriculum Vitae -->
                    <button class="accordion">Curriculum Vitae</button>
                    <div class="panel form-window">
                        <form>
                            <div class="tab">
                                <div>
                                    <label for="cvid">CV ID</label>
                                    <input type="text" id="cvid" placeholder="*ID is Protected." disabled>
                                </div>
                                <div>
                                    <label for="title">Titel</label>
                                    <input type="text" id="title" placeholder="Professional Dredger" disabled>
                                </div>
                                <input type="hidden" name="editInfo"> 
                                <button type="submit" name="edit">Wijzigen</button>
                            </div>
                        </form>
                    </div>
                    <!-- Werkervaring / Stages -->
                    <button class="accordion">Werkervaring / Stages</button>
                    <div class="panel form-window">
                        <form>
                            <div class="tab">
                                <div>
                                    <label for="job">Functie</label>
                                    <input type="text" id="job" placeholder="Mijn functie" disabled>
                                </div>
                                <div>
                                    <label for="company">Bedrijf</label>
                                    <input type="text" id="company" placeholder="Mijn werkgever" disabled>
                                </div>
                                <div class="date-options">
                                    <label for="day-select">In dienst</label>
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
                                <div class="date-options">
                                    <label for="day-select">Uit dienst</label>
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
                                <label for="workdesc">Beschrijving</label>
                                <textarea id="workdesc" rows="4" placeholder="Write your job description here..."></textarea>
                            </div>
                            <div class="button-wrapper">
                                <button type="button">Wijzigen</button>
                                <button type="button">Verwijderen</button>
                            </div>
                            <div class="account-section-divider"></div>
                        </form>
                    </div>
                    <!-- Opleiding / Cursussen -->
                    <button class="accordion">Opleiding / Cursussen</button>
                    <div class="panel form-window">
                        <form>
                            <div class="tab">
                                <div>
                                    <label for="course">Studie</label>
                                    <input type="text" id="course" placeholder="Mijn studie" disabled>
                                </div>
                                <div>
                                    <label for="college">Instituut</label>
                                    <input type="text" id="college" placeholder="Mijn Instituut" disabled>
                                </div>
                                <div class="date-options">
                                    <label for="day-select">Van</label>
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
                                <div class="date-options">
                                    <label for="day-select">Tot</label>
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
                                <label for="edudesc">Beschrijving</label>
                                <textarea id="edudesc" rows="4" placeholder="Write your course description here..."></textarea> 
                            </div>
                            <div class="button-wrapper">
                                <button type="button">Wijzigen</button>
                                <button type="button">Verwijderen</button>
                            </div>
                            <div class="account-section-divider"></div>
                        </form>
                    </div>
                    <!-- Vaardigheden -->
                    <button class="accordion">Vaardigheden</button>
                    <div class="panel form-window">
                        <form>
                            <div class="tab">        
                                <label for="technical">Technische</label>
                                <input type="text" name="technical" placeholder="Office 365">

                                <label for="language">Talen</label>
                                <input type="text" name="language" placeholder="Swedish">
                                
                                <label for="interest">Interesses</label>
                                <input type="text" name="interest" placeholder="Theatre">
                            </div>
                            <div class="button-wrapper">
                                <button type="button">Wijzigen</button>
                                <button type="button">Verwijderen</button>
                            </div>
                            <div class="account-section-divider"></div>
                        </form>
                    </div>
                    <!-- Overige -->
                    <button class="accordion">Overige</button>
                    <div class="panel">
                        <h2>Foto</h2>
                        <form action="">
                            <label for="file-upload"></label>
                            <input type="file" class="avatar" name="file-upload">
                            <p>Tip: Gebruik geen foto, dan zetten wij jouw initialen erin.</p>
                            <div class="button-wrapper">
                                <button>Wijzigen</button>
                                <button>Verwijderen</button>
                            </div>
                        </form>
                        
                        <div class="account-section-divider"></div>
                        <p>Motivatiebrief</p>
                        <form action="">
                            <textarea name="letter" rows="4" placeholder="Write your summary"></textarea>
                            <div class="button-wrapper">
                                <button type="button">Wijzigen</button>
                                <button type="button">Verwijderen</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        
        <section id="user" class="hidden">
            <div class="profile">
                <div class="form-window">
                    <h2>Account</h2>
                    <!-- <button class="avatar">Profiel Foto</button> -->
                    <form>
                        <h3>Mijn gegevens</h3>
                        <div class="tab">
                            <div>
                                <label for="firstname">Voornaam</label>
                                <input type="text" name="firstname" placeholder="Zara" disabled>
                            </div>
                            <div>
                                <label for="lastname">Achternaam</label>
                                <input type="text" name="lastname" placeholder="Arkmenedih" disabled> 
                            </div>
                            <div > 
                                <label for="postalcode">Postcode</label>
                                <input type="text" name="postalcode" placeholder="Postcode" disabled>             
                            </div> 
                            <div> 
                                <label for="city">Woonplaats</label>
                                <input type="text" name="city" placeholder="Woonplaats" disabled>          
                            </div> 
                            <div>
                                <label for="nationality">Nationaliteit</label>
                                <input type="text" name="nationality" placeholder="Nationaliteit" disabled> 
                            </div>
                            <div>
                                <label for="phone">Telefoon</label>
                                <input type="text" name="phone" placeholder="Mobile Number" disabled> 
                            </div>
                            <input type="hidden" name="editInfo"> 
                            <button type="submit" name="edit">Wijzigen</button>
                        </div>
                        <div class="account-section-divider"></div>
                    </form>
                    <form>
                        <h3>Mijn Account</h3>
                        <div class="tab">
                            <div>
                                <label for="username">Gebruikersnaam</label>
                                <input type="text" id="username" name="username" placeholder="(Optioneel)" disabled>
                            </div>
                            <div>
                                <label for="email">E-mailadres</label>
                                <input type="email" id="email" name="email" placeholder="Email" disabled>
                            </div>
                            <div>
                                <label for="pwd">Wachtwoord</label>
                                <input type="password" id="pwd" name="pwd" placeholder="Wachtwoord" disabled>
                            </div>
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
                        </div>

                        <!-- Hidden field is needed since js submit() instantly sends, ignoring form modifications -->
                        <input type="hidden" name="account">
                        <button type="submit" name="edit">Wijzigen</button>
                        <div class="account-section-divider"></div>
                    </form>
                    <p>Deze actie kan niet ongedaan worden gemaakt.</p>
                    <button type="submit" data-section="close">Account Sluiten</button>     
                </div>
            </div>
        </section>

        <section id="guide" class="hidden">
            <h2>Onze gids</h2>

        </section>

        <section id="logout" class="hidden">
            <h2>Weet je zeker dat je wilt uitloggen?</h2>
            <form action="" method="post">
                <button type="submit" name="logout">Uitloggen</button>
            </form> 
        </section>

        <section id="create-res" class="hidden">
            <div class="form-window">
                <button class="back" data-section="home">Terug</button>
                <h2>Nieuw CV Maken</h2>
                <form>
                    <label for="cvname">Titel</label>
                    <input type="text" id="cvname" name="cvname" placeholder="Geef het een naam." required>
                    <button type="submit" name="creResume">Maak mijn cv</button>

                    <!-- Hidden field is needed since js submit() instantly sends, ignoring form modifications -->
                    <input type="hidden" name="creResume">
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>

        <section id="style-res" class="hidden">
            <div class="form-window">
                <button class="back" data-section="home">Terug</button>
                <h2>Downloaden</h2>
                <form>
                    <p>Kies een template</p>
                    <button type="submit">Standaard</button>
                    <button type="submit">Professioneel</button>
                    <button type="submit">Carrièretijger</button>

                    <!-- Hidden field is needed since js submit() instantly sends, ignoring form modifications -->
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>

        <section id="delete-res" class="hidden">

            <div class="form-window">
                <button class="back" data-section="home">Terug</button>
                <h2>Verwijderen</h2>
                <form>
                    <label for="selectCv">Welk cv wil je verwijderen?</label>
                    <select name="cvname">
                        <option value="">(None selected)</option><!-- the value="" is needed for javascript -->
                        <?php if (!empty($resumeData)) { ?>
                        <?php foreach ($resumeData as $resume): ?>
                        <option><?php echo htmlspecialchars($resume['resumetitle']); ?></option>
                        <?php endforeach; ?> <?php } ?>
                    </select>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>

                    <!-- Hidden field is needed since js submit() instantly sends, ignoring form modifications -->
                    <input type="hidden" name="delResume">
                    <button class="Del" type="submit" name="delResume">Verwijderen</button>
                </form>
            </div>

        </section>
        
        <section id="close" class="hidden">

            <div class="form-window">
                <button class="back" data-section="home">Terug</button>
                <h2>Wat jammer dat je vertrekt.</h2>
                <form>
                    <p>Let op: Hiermee worden al jouw gegevens verwijderd. <br>Wil je echt jouw account verwijderen?</p>
                    <label for="pwd">Wachtwoord</label>
                    <input type="password" id="pwd" name="pwd" placeholder="Vul nog 1 keer je wachtwoord in" required>

                    <!-- Hidden field is needed since js submit() instantly sends, ignoring form modifications -->
                    <input type="hidden" name="username" value=""> 
                    <input type="hidden" name="uid" value=""> 
                    <input type="hidden" name="deleteMe">
                    <button type="submit" name="deleteMe">Verwijder mij</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>
    </main>

    <noscript><!-- Als Javascript is uitgeschakeld -->
        <p>Het lijkt erop dat JavaScript is uitgeschakeld in uw browser. Hierdoor werkt de site niet meer.</p>
    </noscript>

    <footer>
        <p>CV Templater © 2023 - 2024</p>
    </footer>
</body>
</html>