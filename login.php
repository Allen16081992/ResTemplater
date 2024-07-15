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
    <link rel="stylesheet" href="assets/css/main.css">
    <!-- <link rel="stylesheet" href="assets/css/3d_illustration.css"> -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Aanmelden | CV Templater</title>
    <!-- Javascript -->
    <script defer src="assets/js/multi_step_rotator.js"></script>
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.html"><img src="assets/images/falcon250x.webp" alt="CV Templater Logo"></a>
        </div>
        <nav>
            <a href="index.html" data-section="login">Terug</a>
        </nav>
    </header>
    <div class="skew"></div>

    <main>
        <section id="login" class="current">
            <div class="grid-container">
                <div class="form-window">
                    <h2>Aanmelden</h2>
                    <form id="login_form" action="src/req_handler.src.php" method="post">
                        <label for="email">E-mailadres</label>
                        <input type="email" id="email" name="email" placeholder="Email" required>
                        <label for="pwd">Wachtwoord</label>
                        <input type="password" id="pwd" name="pwd" placeholder="Wachtwoord" required>
                        <!-- Hidden field is needed since js submit() instantly sends, ignoring form modifications -->
                        <input type="hidden" name="login">
                        <button type="submit" id="loginBtn">Log in</button>
                        <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                    </form>
                </div>
                <div class="art-window"></div>
            </div>
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