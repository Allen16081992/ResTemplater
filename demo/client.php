<?php 
  header('Cache-Control: private, no-store, no-cache, must-revalidate'); // per-user, don’t cache
  header('Pragma: no-cache');   // legacy HTTP/1.0
  header('Expires: 0');         // expire immediately

  // Essential PHP files
  require_once __DIR__ . '/config/session_manager.php'; 
  SessionBook::invokeSession();
  SessionBook::sessionRegenTimer();
  SessionBook::invokeToken();
  //SessionBook::verifySession();

  // require_once __DIR__ . '/config/loadResumeData.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta http-equiv="Content-Security-Policy" content="default-src 'self'; upgrade-insecure-requests;"> -->
    <meta name="description" content="PaperWitch is a lightweight resume generator from Dutch soil where juniors and students can forge impressively minimalist job scrolls.">
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/Favicon-180x180.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/Favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/Favicon-16x16.png">
    <!-- CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Unna:wght@400;700&family=Inter:wght@400;600;800&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
    <link rel="stylesheet" href="assets/css/paperwitch.css">
    <link rel="stylesheet" href="assets/css/profile.css">
    <link rel="stylesheet" href="assets/css/ui_wizard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <title>"A Lightweight Toolkit" | CV Templater</title>
    <!-- Javascript -->
    <script defer src="assets/js/section-handler.js"></script>
    <!-- <script defer src="assets/js/ui_editor-handler.js"></script> -->
    <script defer src="assets/js/ui_wizard-handler.js"></script>
    <!-- <script defer src="assets/js/profile-handler.js"></script> -->
</head>
<body>
    <?php ViewBook::render('navbar_flex.php'); ?>
    <?php ViewBook::flashMessage(); ?>
    
    <main>
        <?php // ViewBook::render('section_profile.php', $data ?? []); ?>
        <?php // ViewBook::render('section_builder_select.php', $data ?? []); ?>
        <?php //ViewBook::render('section_ui_default.php', $data ?? []); ?>
        <?php ViewBook::render('section_ui_wizard.php'); ?>     
        <?php // ViewBook::render('section_closure.php'); ?>
    </main>

    <noscript><!-- Als Javascript is uitgeschakeld -->
        <p>Het lijkt erop dat JavaScript is uitgeschakeld in uw browser. Hierdoor werkt de site niet meer.</p>
    </noscript>

    <footer>
        <div class="container">
            <div class="columns">
                <div class="column">
                    <strong>PaperWitch</strong><span class="has-text-grey-light"> © 2023 - 2024</span>
                    <p class="has-text-grey is-size-6">Resumes with Attitude.</p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>

<!-- Cleanup required to prevent page persistence shenanigans-->
<?php unset($_SESSION['action']); ?>