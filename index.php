<?php 
    // Load PHP files
    require_once "./src/session_manager.src.php"; 
    SessionBook::invokeSession();
    SessionBook::clearUserSession();
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
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <title>Jouw Mobiele CV Editor | PaperTiger</title>
    <!-- Javascript -->
    <script defer src="assets/js/section-handler.js"></script>
    <script defer src="assets/js/3d_dummy_lines.js"></script>
    <script defer src="assets/js/multi_step_rotator.js"></script>
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

    <?php if ($error = SessionBook::flash('error')): ?>
        <div class="serV serV-error"><?= $error ?></div>
    <?php endif; ?>
    
    <main>
        <?php ViewBook::render('section_home.php'); ?>
        <section id="author" class="hidden"></section>
        <?php ViewBook::render('section_policy.html'); ?>
        <section id="contact" class="hidden"></section>
        <?php ViewBook::render('section_login.php'); ?>
        <?php ViewBook::render('section_signup.php'); ?>
        <?php ViewBook::render('section_success.php'); ?>
    </main>

    <noscript><!-- Als Javascript is uitgeschakeld -->
        <p>Het lijkt erop dat JavaScript is uitgeschakeld in uw browser. Hierdoor werkt de site niet meer.</p>
    </noscript>

    <footer style="background:black; margin-top:6rem;">
        <p>CV Templater © 2023 - 2024</p>
    </footer>
</body>
</html>