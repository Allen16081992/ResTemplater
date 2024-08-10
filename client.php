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
    <title>Client | CV Templater</title>
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
                    <h2>Mijn CV</h2>
                    <form action="">
                        <div class="tab"> 
                            <div>
                            <label for="cvname">CV Ophalen:</label>
                            <select name="cvname" id="">
                                <option value="default">---------</option>
                            </select>
                            </div>           

                            <button data-section="create-res">Nieuwe CV</button>
                            <button data-section="delete-res">Verwijder CV</button>
                        </div>
                    </form>
                </div>
                <div id="accordion">
                    <button class="accordion">Curriculum Vitae</button>
                    <div class="panel">
                        <div class="form-window">
                            <form action="">   
                                <input type="hidden" value="resid">   
                                <label for="cvname">Naam CV</label>
                                <input type="text" name="cvname" placeholder="Welke naam geef je het?" value="">
                            
                                <label for="intro">Intro</label>
                                <textarea style="width:100%;" name="intro" id="intro" placeholder="Korte beschrijving over jezelf..."></textarea>
                                
                                <button type="submit" name="saveResume">Opslaan</button>
                            </form>
                        </div>
                    </div>
                    <button class="accordion">Contactgegevens</button>
                    <div class="panel">
                        <p>Content for Section 2.</p>
                    </div>
                    <button class="accordion">Werkervaring / Stages</button>
                    <div class="panel">
                        <p>Content for Section 3.</p>
                    </div>
                    <button class="accordion">Opleiding / Cursussen</button>
                    <div class="panel">
                        <p>Content for Section 4.</p>
                    </div>
                    <button class="accordion">Vaardigheden</button>
                    <div class="panel">
                        <p>Content for Section 5.</p>
                    </div>
                    <button class="accordion">Overige</button>
                    <div class="panel">
                        <h2>Foto</h2>
                        <form action="">
                            <label for="file-upload"></label>
                            <input type="file" class="avatar" name="file-upload">
                            <div>
                                <button>Wijzigen</button>
                                <button>Verwijderen</button>
                            </div>
                        </form>
                        
                        <div class="account-section-divider"></div>
                        <p>Motivatiebrief</p>
                    </div>
                </div>
                <div class="form-window">
                    <strong style="margin-bottom:1rem;">Downloaden</strong>
                    <form action="">
                        <button>Standaard</button>
                        <button>Professioneel</button>
                        <button>Carrièretijger</button>
                    </form>
                </div>
            </div>

        </section>
        
        <section id="user" class="hidden">
            <div class="profile">
                <div class="form-window">
                    <h2>Mijn Account</h2>
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
                        <h3>Account</h3>
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
                                <select id="day-select" name="day" required style="width:4rem;">
                                    <option value="" selected disabled>--</option>
                                    <!-- Populated with JS -->
                                </select>
                                <select id="month-select" name="month" required style="width:4rem;">
                                    <option value="" selected disabled>--</option>
                                    <!-- Populated with JS -->
                                </select>
                                <select id="year-select" name="year" required style="width:5rem;">
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
                    <p>Deze acties kunnen niet ongedaan worden.</p>
                    <button type="submit" data-section="close">Account Sluiten</button>     
                </div>
            </div>
        </section>

        <section id="guide" class="hidden">
            <h2>Onze gids</h2>
            <div class="sheet">
                <img src="assets/images/tutorials/tutorial1.png" style="height:100px;" alt="Resume Name">
                <div class="text-field">
                    <h3></h3>
                    <p></p>
                </div>
            </div>
        </section>

        <section id="logout" class="hidden">
            <h2>Weet je zeker dat je wilt uitloggen?</h2>
            <form action="" method="post">
                <button type="submit" name="logout">Uitloggen</button>
            </form> 
        </section>

        <section id="create-res" class="hidden">

            <div class="form-window">
                <button style="display:flex; position:absolute; margin-top:1.4rem; margin-right:25rem;" data-section="home">Terug</button>
                <h2>Nieuw CV Maken</h2>
                <form id="login_form" action="src/req_handler.src.php" method="post">
                    <label for="cvname">Titel</label>
                    <input type="text" id="cvname" name="cvname" placeholder="Geef het een naam." required>
                    <button type="submit" name="creResume">Maak mijn cv</button>

                    <!-- Hidden field is needed since js submit() instantly sends, ignoring form modifications -->
                    <input type="hidden" name="creResume">
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>

        </section>

        <section id="delete-res" class="hidden">

            <div class="form-window">
                <button style="display:flex; position:absolute; margin-top:1.4rem; margin-right:25rem;" data-section="home">Terug</button>
                <h2>Verwijderen</h2>
                <form id="login_form" action="src/req_handler.src.php" method="post">
                    <label for="selectCv">Welk cv wil je verwijderen?</label>
                    <select style="width:100%;" name="selectCv">
                        <option value="">(None selected)</option><!-- the value="" is needed for javascript -->
                        <?php if (!empty($resumeData)) { ?>
                        <?php foreach ($resumeData as $resume): ?>
                        <option><?php echo htmlspecialchars($resume['resumetitle']); ?></option>
                        <?php endforeach; ?> <?php } ?>
                    </select>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>

                    <!-- Hidden field is needed since js submit() instantly sends, ignoring form modifications -->
                    <input type="hidden" name="delResume">
                    <button class="Del" type="submit" name="delResume">Delete</button>
                </form>
            </div>

        </section>
        
        <section id="close" class="hidden">
            <div class="form-window">
                <button style="display:flex; position:absolute; margin-top:1.4rem; margin-right:21rem;" data-section="home">Terug</button>
                <h2>Wat jammer dat je gaat...</h2>
                <form id="login_form" action="src/req_handler.src.php" method="post">
                    <p>Let op: Hiermee worden al jouw gegevens verwijderd. <br>Wil je echt jouw account verwijderen?</p>
                    <label for="pwd">Wachtwoord</label>
                    <input type="password" id="pwd" name="pwd" placeholder="Vul nog 1 keer je wachtwoord in" required>
                    <button type="submit" class="Del" name="deleteMe">Verwijder mij</button>

                    <!-- Hidden field is needed since js submit() instantly sends, ignoring form modifications -->
                    <input type="hidden" name="username" value=""> 
                    <input type="hidden" name="uid" value=""> 
                    <input type="hidden" name="deleteMe">
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