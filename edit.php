<?php
  // Load PHP files
  require_once './src/session_manager.src.php';

  // Start session prerequisites
  //Unauthorized_Access(); // Verify access
  //sessionRegenTimer(); // Regenerate the session periodically
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta http-equiv="Content-Security-Policy" content="default-src 'self'; upgrade-insecure-requests;"> -->
    <meta name="description" content="Editor page of the client.">
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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>"Op til zijn" | CV Templater</title>
    <!-- Javascript -->
    <script defer src="assets/js/section-handler.js"></script>
</head>
<body>
    <header>
        <div class="logo">
            <a href="portal.php" id="logo"><img src="assets/images/falcon250x.webp" alt="CV Templater Logo"></a>
        </div>
        <nav>
            <a href="client.php">Back</a>
        </nav>
    </header>
    <div class="skew"></div>

    <main>
        <section class="current">
            <div class="form-window">
                <form action="src/resume.src.php" method="post">
                    <h2>Wijzigen</h2>
                    <div class="workinfo">
                        <?php if (isset($_POST['skill'])) { ?>
                            <label for="skill">Proficiency</label>
                            <input type="text" id="skill" name="skill" value="Mijn Opleiding">

                            <label for="language">Language</label>
                            <input type="text" id="language" name="language" value="Mijn School">

                            <label for="interest">Interests</label>
                            <input type="text" id="interest" name="interest" value="06/12/2016">
                            
                            <input type="hidden" name="entryid" value="<?= $_POST['entry_id']; ?>"> 
                            <input type="hidden" name="form" value="<?= $_POST['entry_form']; ?>"> 
                            <button type="submit" name="saveEntry">Opslaan</button>
                        <?php } else { ?> 
                            <div>
                                <label for="title">Functie</label>
                                <input type="text" id="title" name="title" placeholder="Mijn functie" required>
                            </div>
                            <div>
                                <label for="company">Bedrijf</label>
                                <input type="text" id="company" name="company" placeholder="Mijn werk" required>
                            </div>
                            <div class="date-options">
                                <label for="day-select">In dienst</label>
                                <select class="day-select" name="join_day" required>
                                    <option selected disabled>--</option>
                                </select>
                                <select class="month-select" name="join_month" required>
                                    <option selected disabled>--</option>
                                </select>
                                <select class="year-select" name="join_year" required>
                                    <option selected disabled>----</option>
                                </select>
                            </div> 
                            <div class="date-options">
                                <label for="day-select">Uit dienst</label>
                                <select class="day-select" name="leave_day" required>
                                    <option selected disabled>--</option>
                                </select>
                                <select class="month-select" name="leave_month" required>
                                    <option selected disabled>--</option>
                                </select>
                                <select class="year-select" name="leave_year" required>
                                    <option selected disabled>----</option>
                                </select>
                            </div> 
                            <label for="desc">Beschrijving</label>
                            <textarea id="desc" rows="4" placeholder="Write your description here..."></textarea>
                            <input type="hidden" name="entryid" value="<?= $_POST['entry_id']; ?>"> 
                            <input type="hidden" name="form" value="<?= $_POST['entry_form']; ?>"> 
                            <button type="submit" name="saveEntry">Opslaan</button>
                        <?php } ?>   
                    </div>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <p>CV Templater © 2023 - 2024</p>
    </footer>
</body>
</html>