<?php 
  // Start a session for handling data and error messages.
  require_once 'src/session_manager.src.php'; 
  Unauthorized_Access(); // Verify access
  sessionRegenTimer(); // Regenerate the session periodically
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
    <link rel="stylesheet" href="assets/css/3d_illustration.css">
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
            <a href="#" data-section="user"><?= isset($_SESSION['user_name']) ? $_SESSION['user_name'] : $_SESSION['firstname']; ?></a>
            <a href="#" data-section="home">Mijn CV</a>
            <a href="#" data-section="guide">Onze Gids</a>
            <a href="#" data-section="logout">Uitloggen</a>
        </nav>
    </header>
    <div class="skew"></div>

    <main>
        <section id="home" class="current">

        </section>
        
        <section id="user" class="hidden">

        </section>

        <section id="guide" class="hidden">

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