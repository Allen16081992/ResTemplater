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
            <a href="#" data-section="home">Mijn CV</a><!-- Mobile only -->
            <a href="#" data-section="user"><?= isset($_SESSION['user_name']) ? $_SESSION['user_name'] : $_SESSION['firstname']; ?></a>
            <a href="#" data-section="guide">Onze gids</a>
            <a href="#" data-section="logout">Uitloggen</a>
        </nav>
    </header>
    <div class="skew"></div>

    <main>
        <section id="create-res" class="hidden">
            <div class="grid-container">
                <div class="form-window">
                    <button style="display:flex; position:absolute; margin-top:1.4rem;" data-section="home">Terug</button>
                    <h2>Nieuw CV Maken</h2>
                    <form id="login_form" action="src/req_handler.src.php" method="post">
                        <label for="cvname">Titel</label>
                        <input type="text" id="cvname" name="cvname" placeholder="Geef jouw cv een naam" required>
                        <button type="submit" name="creResume">Maak mijn cv</button>

                        <!-- Hidden field is needed since js submit() instantly sends, ignoring form modifications -->
                        <input type="hidden" name="creResume">
                        <span>Nog geen account? <a href="#" data-section="sign_up">Maak hier een nieuwe</a></span>
                    </form>
                </div>
                <!-- <div class="art-window"></div> -->
            </div>
        </section>

        <section id="delete-res" class="hidden">
            <div class="grid-container">
                <div class="form-window">
                    <button style="display:flex; position:absolute; margin-top:1.4rem;" data-section="home">Terug</button>
                    <h2>Verwijderen</h2>
                    <form id="login_form" action="src/req_handler.src.php" method="post">
                        <p>Welk cv wil je verwijderen?</p>
                        <label for="selectCv">Kies een cv</label>
                        <select name="selectCv">
                            <option value="">(None selected)</option><!-- the value="" is needed for javascript -->
                            <?php if (!empty($resumeData)) { ?>
                            <?php foreach ($resumeData as $resume): ?>
                            <option><?php echo htmlspecialchars($resume['resumetitle']); ?></option>
                            <?php endforeach; ?> <?php } ?>
                        </select>
                        <button class="Del" type="submit" name="delResume">Delete</button>

                        <!-- Hidden field is needed since js submit() instantly sends, ignoring form modifications -->
                        <input type="hidden" name="delResume">
                        <span>Nog geen account? <a href="#" data-section="sign_up">Maak hier een nieuwe</a></span>
                    </form>
                </div>
                <!-- <div class="art-window"></div> -->
            </div>
        </section>

        <section id="home" class="current">
            <div class="sheet">
                <h2>Mijn CV</h2>
                <div class="box">
                    <button class="accordion">Document</button>
                    <div class="panel">
                        <p>Content for Section 1.</p>
                        <button data-section="create-res">Nieuw CV</button>
                        <button data-section="delete-res">Verwijderen</button>
                    </div>
                    <button class="accordion">Profiel</button>
                    <div class="panel">
                        <p>Content for Section 2.</p>
                    </div>
                    <button class="accordion">Werkervaring en Stage</button>
                    <div class="panel">
                        <p>Content for Section 3.</p>
                    </div>
                    <button class="accordion">Opleiding en Cursussen</button>
                    <div class="panel">
                        <p>Content for Section 4.</p>
                    </div>
                    <button class="accordion">Vaardigheden</button>
                    <div class="panel">
                        <p>Content for Section 5.</p>
                    </div>
                    <button class="accordion">Motivatie</button>
                    <div class="panel">
                        <p>Content for Section 6.</p>
                    </div>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </div>
            </div>
        </section>
        
        <section id="user" class="hidden">

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
    </main>

    <noscript><!-- Als Javascript is uitgeschakeld -->
        <p>Het lijkt erop dat JavaScript is uitgeschakeld in uw browser. Hierdoor werkt de site niet meer.</p>
    </noscript>

    <footer>
        <p>Donkere Heiligdom © 2011-2024</p>
    </footer>
</body>
</html>