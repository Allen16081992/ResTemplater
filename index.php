<?php 
    header('Cache-Control: private, no-cache, must-revalidate'); // per-user, don’t cache
    header('Pragma: no-cache');   // legacy HTTP/1.0
    header('Expires: 0');         // expire immediately
    
    // Essential PHP files
    require_once "./config/session_manager.conf.php"; 

    // Miscellaneous PHP Files
    include_once "./config/phrases.conf.php";
    // SessionBook::invokeSession();
    // SessionBook::clearUserSession(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta http-equiv="Content-Security-Policy" content="default-src 'self'; upgrade-insecure-requests;"> -->
    <meta name="description" content="PaperWitch, a lightweight resume generator from Dutch soil where juniors and students alike can craft boring job scrolls.">
    <!-- Social Sharing -->
    <meta property="og:title" content="PaperWitch - Thy Job Scroll Familiar">
    <meta property="og:description" content="An online resume generator from Royal Dutch soil.">
    <meta property="og:image" content="https://www.../assets/images/falcon250.webp">
    <meta property="og:locale" content="en_GB">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.../">
    <meta property="og:site_name" content="PaperWitch">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="PaperWitch - Thy Job Scroll Familiar">
    <meta name="description" content="PaperWitch is a lightweight resume generator from Dutch soil, where juniors and students can craft those boring job scrolls.">
    <meta name="twitter:image" content="https://www.../assets/images/falcon250.webp">
    <link rel="canonical" href="https://www.../">
    <!-- Favicon -->
    <?php include_once "./views/head_favicon.html" ?>
    <!-- CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Unna:wght@400;700&family=Inter:wght@400;600;800&display=swap">
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/export_page.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/> -->
    <title>Thy Job Scroll Cauldron</title>
    <!-- Javascript -->
    <script defer src="assets/js/section-handler.js"></script>
    <script defer src="assets/js/sparks_effect.js"></script>
    <script defer src="assets/js/cta-padding-mobile.js"></script><!-- CSS refinement for mobile -->
    <script defer src="assets/js/signup_page-handler.js"></script><!-- Control image manipulation -->
</head>
<body>
    <header> 
        <?php ViewBook::render('navbar_flex.php'); ?>
    </header>
    
    <main>
        <?php ViewBook::render('section_home.php'); ?>  
        <?php ViewBook::render('section_login.php'); ?>
        <?php ViewBook::render('section_signup.php'); ?>  
        <?php ViewBook::render('section_success.php'); ?>
        <?php ViewBook::render('section_policy.html'); ?>
        <?php ViewBook::render('section_export.html'); ?>
        <section id="author" class="hidden"></section>
        <section id="contact" class="hidden"></section>
    </main>

    <noscript><!-- Als Javascript is uitgeschakeld -->
        <p>It seems like you have Javascript disabled. This site should work but less streamlined. Expect more page refreshes.</p>
    </noscript>

    <?php ViewBook::render('footer_sitemap.html'); ?>
</body>
</html>