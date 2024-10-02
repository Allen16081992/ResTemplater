<?php 
  // Load PHP files
  require_once './src/session_manager.src.php'; 
  require_once './src/data_loader.src.php';

  // Start session prerequisites
  //Unauthorized_Access(); // Verify access
  //sessionRegenTimer(); // Regenerate the session periodically
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
            <a href="#" data-section="user"><?= addUsername(); ?></a>
            <a href="#" data-section="home">Mijn CV</a>
            <a href="#" data-section="guide">Onze gids</a>
            <a href="#" data-section="logout">Uitloggen</a>
        </nav>
    </header>
    <div class="skew"></div>

    <main>
        <section id="home" class="<?= serverHome(); ?>">
            <div class="grid-ad-container">
                <div class="ads"></div>
                <div class="sheet">
                    <h2>Mijn Curriculum Vitae</h2>
                    <div class="button-wrapper">
                        <button type="button" data-section="create-res">Nieuwe CV</button> 
                        <button type="button" data-section="delete-res">Verwijder CV</button>
                    </div>
                    <form action="src/resume.src.php" method="post">
                        <label for="selectCv">CV Ophalen:</label>
                        <select id="selectCv" name="cvname">
                            <option selected disabled hidden>---------</option>
                            <?php // Check if there is resume data to display
                            if (!empty($resumeData['resume'])): 
                                // Loop through each resume and create an option element
                                foreach ($resumeData['resume'] as $resume): ?>
                                    <option value="<?= htmlspecialchars($resume['resumeID']) ?>">
                                        <?= htmlspecialchars($resume['resumetitle']) ?>
                                    </option>
                                <?php endforeach; 
                            endif; ?>
                        </select>
                        
                        <div class="tab">
                            <button type="submit" data-section="select-res">Downloaden</button>   
                        </div>
                        
                        <div class="account-section-divider"></div>
                    </form>     
                
                    <!-- Curriculum Vitae -->
                    <button class="accordion">Curriculum Vitae / Foto</button>
                    <div class="panel">    
                        <form action="src/resume.src.php" method="post">
                            <div class="tab">
                                <div>
                                    <label for="cvid">CV ID</label>
                                    <input type="text" id="resid" placeholder="*ID is Protected." disabled>
                                </div>
                                <div>
                                    <label for="title">Titel</label>
                                    <input type="text" id="title" name="resumetitle" placeholder="Professional Dredger" disabled>
                                </div>
                                <input type="hidden" name="resumeID" value=""> 
                                <input type="hidden" name="userID" value=""> 
                                <button type="submit" name="saveResume">Wijzigen</button>
                            </div>
                        </form>
                        <div class="account-section-divider"></div>
                        <h2>Foto</h2>
                        <form action="src/resume.src.php" method="post">
                            <label for="file-upload"></label>
                            <input type="file" class="avatar" name="file-upload">
                            <p>Tip: Gebruik geen foto, dan zetten wij jouw initialen erin.</p>
                            <input type="hidden" name="resumeID" value=""> 
                            <input type="hidden" name="userID" value=""> 
                            <div class="button-wrapper">
                                <button name="saveImg">Wijzigen</button>
                                <button name="delImg">Verwijderen</button>
                            </div>
                        </form>
                    </div>

                    <!-- Werkervaring / Stages -->
                    <button class="accordion">Werkervaring / Stages</button>
                    <div class="panel">
                        <form class="workinfo"> 
                            <!-- Functie --> 
                            <strong>Cabin Attendant</strong>
                            <!-- Bedrijf -->
                            <strong>KLM</strong>
                            <!-- In dienst -->
                            <p>06/12/2016</p>
                            <!-- Uit dienst -->
                            <p>21/06/2024</p> 
                            <!-- Beschrijving -->
                            <p style="margin-top:-5px;max-width:40.7em;">
                                Energieke en klantgerichte cabine-assistente gericht op passagierscomfort en veiligheid aan boord. Vaardig in het omgaan met noodgevallen. Vraag naar mijn ervaringen hiermee.
                            </p>

                            <div class="button-wrapper">
                                <input type="hidden" name="workid" value="">
                                <button type="button" data-section="edit-work">Wijzigen</button>
                                <button type="button" data-section="trash-work">Verwijderen</button>
                            </div>
                            <div class="account-section-divider"></div>
                        </form>
                    </div>

                    <!-- Opleiding / Cursussen -->
                    <button class="accordion">Opleiding / Cursussen</button>
                    <div class="panel">
                        <form class="workinfo"> 
                            <!-- Functie --> 
                            <strong>Mediavormgever</strong>
                            <!-- Bedrijf -->
                            <strong>Grafisch Lyceum</strong>
                            <!-- In dienst -->
                            <p>06/12/2016</p>
                            <!-- Uit dienst -->
                            <p>21/06/2024</p> 
                            <!-- Beschrijving -->
                            <p style="margin-top:-5px;max-width:40.7em;">
                                Energieke en klantgerichte cabine-assistente gericht op passagierscomfort en veiligheid aan boord. Vaardig in het omgaan met noodgevallen. Vraag naar mijn ervaringen hiermee.
                            </p>

                            <div class="button-wrapper">
                                <input type="hidden" name="eduid" value="">
                                <button type="button" data-section="edit-study">Wijzigen</button>
                                <button type="button" data-section="trash-study">Verwijderen</button>
                            </div>
                            <div class="account-section-divider"></div>
                        </form>
                    </div>

                    <!-- Vaardigheden -->
                    <button class="accordion">Vaardigheden</button>
                    <div class="panel">
                        <form action="src/resume.src.php" method="post">
                            <div class="tab">        
                                <label for="technical">Technische</label>
                                <input type="text" name="technical" placeholder="Office 365">

                                <label for="language">Talen</label>
                                <input type="text" name="language" placeholder="Swedish">
                                
                                <label for="interest">Interesses</label>
                                <input type="text" name="interest" placeholder="Theatre">
                            </div>
                            <div class="button-wrapper">
                                <input type="hidden" name="techid" value="">
                                <input type="hidden" name="langid" value="">
                                <input type="hidden" name="intid" value="">
                                <button type="button" name="editSkill">Wijzigen</button>
                                <button type="button">Verwijderen</button>
                            </div>
                            <div class="account-section-divider"></div>
                        </form>
                    </div>

                    <!-- Overige -->
                    <button class="accordion">Motivatiebrief</button>
                    <div class="panel">
                        <p>Motivatie</p>
                        <form action="src/resume.src.php" method="post">
                            <textarea name="letter" rows="4" placeholder="Schrijf hier jouw motivatie..."></textarea>
                            <div class="button-wrapper">
                                <input type="hidden" name="motid" value="">
                                <button name="editMot">Wijzigen</button>
                                <button name="delMot">Verwijderen</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="ads"></div>
            </div>
        </section>
        
        <section id="user" class="<?= serverAccount() ?>">
            <div>
                <div class="form-window">
                    <h2>Account</h2>
                    <!-- <button class="avatar">Profiel Foto</button> -->
                    <form action="src/account.src.php" method="post">
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
                                <label for="country">Nationaliteit</label>
                                <input type="text" name="country" placeholder="Nationaliteit" disabled> 
                            </div>
                            <div>
                                <label for="phone">Telefoon</label>
                                <input type="text" name="phone" placeholder="Mobile Number" disabled> 
                            </div>
                            <div class="date-options">
                                <label for="day-select">Geboortedatum</label>
                                <select class="day-select" name="day" required>
                                    <option value="" selected disabled>--</option>
                                    <!-- Populated with JS -->
                                </select>
                                <select class="month-select" name="month" required>
                                    <option value="" selected disabled>--</option>
                                    <!-- Populated with JS -->
                                </select>
                                <select class="year-select" name="year" required>
                                    <option value="" selected disabled>----</option>
                                    <!-- Populated with JS -->
                                </select>
                            </div>
                            <input type="hidden" name="uid"> 
                            <button type="submit" name="savePersonal">Wijzigen</button>
                        </div>
                        <div class="account-section-divider"></div>
                    </form>
                    <form action="src/account.src.php" method="post">
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
                            <div>
                                <label for="pwdR">Herhaal Wachtwoord</label>
                                <input type="password" id="pwdR" name="pwdR" placeholder="Wachtwoord" disabled>
                            </div>
                        </div>

                        <!-- Hidden field is needed since js submit() instantly sends, ignoring form modifications -->
                        <input type="hidden" name="uid">
                        <button type="submit" name="saveAccount">Wijzigen</button>
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

        <section id="logout" class="<?= logoutRequest(); ?>">
            <h2>Weet je zeker dat je wilt uitloggen?</h2>
            <form action="" method="post">
                <button type="submit" name="logout">Uitloggen</button>
            </form> 
        </section>

        <section id="create-res" class="hidden">
            <div class="form-window">
                <form action="src/resume.src.php" method="post">
                    <button class="return" data-section="home">Terug</button>
                    <h2>Nieuw CV Maken</h2>
                    <label for="cvname">Titel</label>
                    <input type="text" id="cvname" name="cvname" placeholder="Geef het een naam." required>
                    <button type="submit" name="creResume">Opslaan</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>

        <section id="select-res" class="hidden">
            <div class="form-window">
                <form action="src/resume.src.php" method="post">
                    <button class="return" data-section="home">Terug</button>
                    <h2>Downloaden</h2>
                    <p>Kies een template</p>
                    <button type="submit" name="default">Standaard</button>
                    <button type="submit" name="business">Professioneel</button>
                    <button type="submit" name="careertiger">Carrièretijger</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>

        <section id="delete-res" class="hidden">
            <div class="form-window">
                <form action="src/resume.src.php" method="post">
                    <button class="return" data-section="home">Terug</button>
                    <h2>Verwijderen</h2>
                    <label for="cvname">Welk cv wil je verwijderen?</label>
                    <select id="cvname" name="cvname">
                        <option selected disabled hidden>(None selected)</option>
                        <?php if (!empty($resumeData)) { ?>
                        <?php foreach ($resumeData as $resume): ?>
                            <option><?= htmlspecialchars($resume['resumetitle']); ?></option>
                        <?php endforeach; ?> <?php } ?>
                    </select>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>

                    <!-- Hidden field is needed since js submit() instantly sends, ignoring form modifications -->
                    <input type="hidden" name="resid">
                    <button class="Del" type="submit" name="delResume">Verwijderen</button>
                </form>
            </div>
        </section>

        <section id="edit-work" class="hidden">
            <div class="form-window">
                <form action="src/resume.src.php" method="post">
                    <button class="return" data-section="home">Terug</button>
                    <h2>Werkervaring Wijzigen</h2>
                    <div class="workinfo">
                        <div>
                            <label for="worktitle">Functie</label>
                            <input type="text" id="worktitle" name="worktitle" placeholder="Mijn functie" required>
                        </div>
                        <div>
                            <label for="company">Bedrijf</label>
                            <input type="text" id="company" name="company" placeholder="Mijn werkgever" required>
                        </div>
                        <div class="date-options">
                            <label for="day-select">In dienst</label>
                            <select class="day-select" name="join_day" required>
                                <option value="" selected disabled>--</option>
                                <!-- Populated with JS -->
                            </select>
                            <select class="month-select" name="join_month" required>
                                <option value="" selected disabled>--</option>
                                <!-- Populated with JS -->
                            </select>
                            <select class="year-select" name="join_year" required>
                                <option value="" selected disabled>----</option>
                                <!-- Populated with JS -->
                            </select>
                        </div> 
                        <div class="date-options">
                            <label for="day-select">Uit dienst</label>
                            <select class="day-select" name="leave_day" required>
                                <option value="" selected disabled>--</option>
                                <!-- Populated with JS -->
                            </select>
                            <select class="month-select" name="leave_month" required>
                                <option value="" selected disabled>--</option>
                                <!-- Populated with JS -->
                            </select>
                            <select class="year-select" name="leave_year" required>
                                <option value="" selected disabled>----</option>
                                <!-- Populated with JS -->
                            </select>
                        </div> 
                        <label for="workdesc">Beschrijving</label>
                        <textarea id="workdesc" rows="4" placeholder="Write your job description here..."></textarea>
                    </div>
                    <input type="hidden" name="workid" value="">
                    <button type="submit" name="saveExperience">Opslaan</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>


        <section id="trash-work" class="hidden">
            <div class="form-window">
                <form action="src/resume.src.php" method="post">
                    <button class="return" data-section="home">Terug</button>
                    <h2>Weet je het zeker?</h2>
                    <div class="workinfo">
                        <div>
                            <label for="worktitle">Functie</label>
                            <input type="text" id="worktitle" name="worktitle" placeholder="Mijn functie" disabled>
                        </div>
                        <div>
                            <label for="company">Bedrijf</label>
                            <input type="text" id="company" name="company" placeholder="Mijn werkgever" disabled>
                        </div>
                                    <div>
                            <label for="joined">In dienst</label>
                            <input type="text" id="joined" value="06/12/2016" disabled>
                        </div>
                        <div>
                            <label for="left">Uit dienst</label>
                            <input type="text" id="left" value="06/12/2016" disabled>
                        </div> 
                        <label for="workdesc">Beschrijving</label>
                        <textarea id="workdesc" rows="4" placeholder="Write your job description here..." disabled></textarea>
                    </div>
                    <input type="hidden" name="workid" value="">
                    <button type="submit" name="trashExperience">Verwijderen</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>

        <section id="edit-study" class="hidden">
            <div class="form-window">
                <form action="src/resume.src.php" method="post">
                    <button class="return" data-section="home">Terug</button>
                    <h2>Werkervaring Wijzigen</h2>
                    <div class="workinfo">
                        <div>
                            <label for="edutitle">Functie</label>
                            <input type="text" id="edutitle" name="edutitle" placeholder="Mijn Opleiding" required>
                        </div>
                        <div>
                            <label for="company">Bedrijf</label>
                            <input type="text" id="company" name="company" placeholder="Mijn School" required>
                        </div>
                        <div class="date-options">
                            <label for="day-select">Start</label>
                            <select class="day-select" name="join_day" required>
                                <option value="" selected disabled>--</option>
                                <!-- Populated with JS -->
                            </select>
                            <select class="month-select" name="join_month" required>
                                <option value="" selected disabled>--</option>
                                <!-- Populated with JS -->
                            </select>
                            <select class="year-select" name="join_year" required>
                                <option value="" selected disabled>----</option>
                                <!-- Populated with JS -->
                            </select>
                        </div> 
                        <div class="date-options">
                            <label for="day-select">Uitschrijving</label>
                            <select class="day-select" name="leave_day" required>
                                <option value="" selected disabled>--</option>
                                <!-- Populated with JS -->
                            </select>
                            <select class="month-select" name="leave_month" required>
                                <option value="" selected disabled>--</option>
                                <!-- Populated with JS -->
                            </select>
                            <select class="year-select" name="leave_year" required>
                                <option value="" selected disabled>----</option>
                                <!-- Populated with JS -->
                            </select>
                        </div> 
                        <label for="edudesc">Beschrijving</label>
                        <textarea id="edudesc" rows="4" placeholder="Write your study description here..."></textarea>
                    </div>
                    <input type="hidden" name="eduid" value="">
                    <button type="submit" name="saveEducation">Opslaan</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>

        <section id="trash-study" class="hidden">
            <div class="form-window">
                <form action="src/resume.src.php" method="post">
                    <button class="return" data-section="home">Terug</button>
                    <h2>Weet je het zeker?</h2>
                    <div class="workinfo">
                        <div>
                            <label for="edutitle">Functie</label>
                            <input type="text" id="edutitle" name="edutitle" placeholder="Mijn Opleiding" disabled>
                        </div>
                        <div>
                            <label for="company">Bedrijf</label>
                            <input type="text" id="company" name="company" placeholder="Mijn School" disabled>
                        </div>
                                    <div>
                            <label for="joined">Start</label>
                            <input type="text" id="joined" value="06/12/2016" disabled>
                        </div>
                        <div>
                            <label for="left">Uitschrijving</label>
                            <input type="text" id="left" value="06/12/2016" disabled>
                        </div> 
                        <label for="edudesc">Beschrijving</label>
                        <textarea id="edudesc" rows="4" placeholder="Write your job description here..." disabled></textarea>
                    </div>
                    <input type="hidden" name="eduid" value="">
                    <button type="submit" name="trashEducation">Verwijderen</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>
        
        <section id="close" class="hidden">
            <div class="form-window">
                <form action="src/account.src.php" method="post">
                    <button class="return" data-section="home">Terug</button>
                    <h2>Wat jammer dat je vertrekt.</h2>
                    <p>Let op: Hiermee worden al jouw gegevens verwijderd. <br>Wil je echt jouw account verwijderen?</p>
                    <label for="pwd">Wachtwoord</label>
                    <input type="password" id="pwd" name="pwd" placeholder="Vul nog 1 keer je wachtwoord in" required>

                    <!-- Hidden field is needed since js submit() instantly sends, ignoring form modifications -->
                    <input type="hidden" name="username" value=""> 
                    <input type="hidden" name="uid" value=""> 
                    <button type="submit" name="delUser">Verwijder mij</button>
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