<?php
  // Essential PHP files
  require_once __DIR__ . '/config/session_manager.php'; 
  // Miscellaneous PHP Files
  include_once __DIR__ . '/config/mixedGrimoire.php';
  SessionBook::invokeSession();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta http-equiv="Content-Security-Policy" content="default-src 'self'; upgrade-insecure-requests;"> -->
    <meta name="description" content="PaperWitch is a lightweight resume generator from Dutch soil where juniors and students can forge impressively minimalist job scrolls.">
    <!-- Facebook Meta Tags -->
    <meta property="og:url" content="https://paperwitch.eu/">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Your Job Hunting Familiar">
    <meta property="og:description" content="When a magic potion and witch sorcerer collide with papers.. You get... A Witch! Who is fed up of insane job requirements and fake paper templates that claim to boost your chances!">
    <meta property="og:image" content="https://www.paperwitch.eu/assets/images/og-banner.webp">
    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="paperwitch.eu">
    <meta property="twitter:url" content="https://paperwitch.eu/">
    <meta name="twitter:title" content="Your Job Hunting Familiar">
    <meta name="twitter:description" content="When a magic potion and witch sorcerer collide with papers.. You get... A Witch! Who is fed up of insane job requirements and fake paper templates that claim to boost your chances!">
    <meta name="twitter:image" content="https://www.paperwitch.eu/assets/images/og-banner.webp">
    <link rel="canonical" href="https://www.paperwitch.eu/">
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/Favicon-180x180.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/Favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/Favicon-16x16.png">
    <!-- CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Unna:wght@400;700&family=Inter:wght@400;600;800&display=swap">
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/export_page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <title>Your Job Hunting Familiar - Free Resume Builder</title>
    <!-- Javascript -->
    <script defer src="assets/js/section-handler.js"></script>
    <script defer src="assets/js/auth_page-handler.js"></script>
    <script defer src="assets/js/cta-padding-mobile.js"></script><!-- CSS refinement for mobile -->
    <script defer src="assets/js/sparks_effect.js"></script>
    <script type="application/ld+json"> {
        "@context": "https://schema.org",
        "@type": "SoftwareApplication",
        "name": "PaperWitch",
        "operatingSystem": "All",
        "applicationCategory": "BusinessApplication",
        "browserRequirements": "Requires JavaScript. Requires HTML5.",
        "url": "https://www.paperwitch.eu/",
        "description": "A lightweight automated resume generator enabling students and juniors to craft clean, ATS-friendly job scrolls instantly.",
        "offers": {
            "@type": "Offer",
            "price": "0.00",
            "priceCurrency": "EUR"
        }
    } </script>
</head>
<body>
    <?php ViewBook::render('navbar_flex.php'); ?>
    
    <main>
        <?php ViewBook::render('section_home.php'); ?>  
        <?php ViewBook::render('section_login.php'); ?>
        <?php ViewBook::render('section_signup.php'); ?>  
        <?php ViewBook::render('section_success.php'); ?>
        <?php ViewBook::render('section_policy.html'); ?>
        <section id="contact" class="hidden"></section>
    </main>

    <noscript><!-- Als Javascript is uitgeschakeld -->
        <p>It seems like you have Javascript disabled. This site should work but less streamlined. Expect more page refreshes.</p>
    </noscript>
    
    <?php ViewBook::render('footer_sitemap.html'); ?>
</body>
</html>

<!-- Cleanup required to prevent page persistence shenanigans-->
<?php unset($_SESSION['action']); ?>