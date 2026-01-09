<?php 
  header('Cache-Control: private, no-store, no-cache, must-revalidate'); // per-user, don’t cache
  header('Pragma: no-cache');   // legacy HTTP/1.0
  header('Expires: 0');         // expire immediately

  // Essential PHP files
  require_once "./config/session_manager.conf.php"; 
  
  // SessionBook::invokeSession();
  // SessionBook::intrusionGuard();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta http-equiv="Content-Security-Policy" content="default-src 'self'; upgrade-insecure-requests;"> -->
    <meta name="description" content="PaperWitch is a lightweight resume toolkit of dutch origin where juniors and students can craft those boring job scrolls.">
    <!-- Social Sharing -->
    <meta property="og:title" content="Paperwitch - Thy Job Scroll Familiar">
    <meta property="og:description" content="PaperWitch is a lightweight resume tooling kit of dutch origin where juniors and students can craft those boring job scrolls.">
    <meta property="og:image" content="assets/images/falcon250.webp">
    <meta property="og:locale" content="eng_ENG">
    <meta property="og:type" content="website">
    <!-- Favicon -->
    <?php include_once "./views/head_favicon.html" ?>
    <!-- CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Unna:wght@400;700&family=Inter:wght@400;600;800&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
    <link rel="stylesheet" href="assets/css/paperwitch.css">
    <link rel="stylesheet" href="assets/css/profile.css">
    <link rel="stylesheet" href="assets/css/editor.css">
    <!-- <link rel="stylesheet" href="assets/css/export.css"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/> -->
    <title>"A Lightweight Toolkit" | CV Templater</title>
    <!-- Javascript -->
    <script defer src="assets/js/section-handler.js"></script>
    <script defer src="assets/js/banal-editor.js"></script>
    <script defer src="assets/js/form-handler.js"></script>
</head>
<body>
    <header> 
        <?php ViewBook::render('navbar_flex.php'); ?>
    </header>

    <main>
        <?php ViewBook::render('section_profile.php'); ?>
        <?php ViewBook::render('section_resume.php'); ?>  

        <section id="export" class="<?= ViewBook::setView_Error('export'); ?>"> 
            <?php ViewBook::render('section_export.html'); ?>
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