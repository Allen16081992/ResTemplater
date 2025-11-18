<?php 
    header('Cache-Control: private, no-store, no-cache, must-revalidate'); // per-user, don’t cache
    header('Pragma: no-cache');   // legacy HTTP/1.0
    header('Expires: 0');         // expire immediately
    
    // Essential PHP files
    require_once "./src/session_manager.src.php"; 

    // Miscellaneous PHP Files
    include_once "./src/phrases.src.php"
    // SessionBook::invokeSession();
    // SessionBook::clearUserSession();
    
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta http-equiv="Content-Security-Policy" content="default-src 'self'; upgrade-insecure-requests;"> -->
    <meta name="description" content="PaperWitch is a lightweight resume toolkit of dutch origin where juniors and students can craft those boring job scrolls.">
    <!-- Open Graph meta tags for social sharing -->
    <meta property="og:title" content="Paperwitch - Thy Job Scroll Familiar">
    <meta property="og:description" content="Het Curriculum Vitae Ombouw Kistje van Nederlandse Bodem.">
    <meta property="og:image" content="assets/images/falcon250.webp">
    <meta property="og:locale" content="nl_NL">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.donkereheiligdom.nl">
    <!-- Favicon -->
    <?php include_once "./views/head_favicon.html" ?>
    <!-- Styling Sheets -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Unna:wght@400;700&family=Inter:wght@400;600;800&display=swap">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <title>The Job Scroll Cauldron</title>
    <!-- Javascript -->
    <script defer src="assets/js/section-handler.js"></script>
    <script defer src="assets/js/sparks_effect.js"></script>
    <script defer src="assets/js/cta-padding-mobile.js"></script>
    <script defer src="assets/js/signup_page-handler.js"></script>
    <!-- <script defer src="assets/js/multi_step_rotator.js"></script> -->
</head>
<body>
    <header> 
        <?php ViewBook::render('navbar_flex.php'); ?>
    </header>
    
    <main>
        <?php ViewBook::render('section_home.php'); ?>   
        <?php ViewBook::render('section_policy.html'); ?>
        <?php ViewBook::render('section_login.php'); ?>
        <?php ViewBook::render('section_signup.php'); ?>
        <?php ViewBook::render('section_success.php'); ?>
        <section id="author" class="hidden"></section>
        <section id="contact" class="hidden"></section>
    </main>

    <noscript><!-- Als Javascript is uitgeschakeld -->
        <p>Het lijkt erop dat JavaScript is uitgeschakeld in uw browser. Hierdoor werkt de site niet meer.</p>
    </noscript>

    <?php ViewBook::render('footer_sitemap.html'); ?>
</body>
</html>