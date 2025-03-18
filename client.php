<?php 
  // Load PHP files
  require_once './src/session_manager.src.php'; 
  require_once './src/data_loader.src.php';

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- <link rel="stylesheet" href="assets/css/3d_illustration.css"> -->
    <title>"A Lightweight Toolkit" | CV Templater</title>
    <!-- Javascript -->
    <script defer src="assets/js/section-handler.js"></script>
</head>
<body>
    <header>
        <div class="logo">
            <a href="portal.php" id="logo"><img src="assets/images/falcon250x.webp" alt="CV Templater Logo"></a>
        </div>
        <nav>
            <a href="#" data-section="user"><?= addUsername(); ?></a>
            <a href="#" data-section="home">Mijn CV</a>
            <a href="#" data-section="guide">Onze gids</a>
            <a href="#" data-section="logout">Logout</a>
        </nav>
    </header>
    <div class="skew"></div>

    <main>
        <section id="home" class="<?= Homepage(); ?>">
            <div class="tabination">
                <!-- Tab Navigation -->
                <div class="tab-buttons">
                    <button data-tab="profile" class="active">Profile</button>
                    <button data-tab="experience">Experience</button>
                    <button data-tab="education">Education</button>
                    <button data-tab="skills">Skills</button>
                    <button data-tab="preview">Preview</button>
                </div>

                <!-- Tabs Content (Inside Resume Builder) -->
                <div class="tab-content">
                    <div id="profile" class="tab-section current">
                        <h2>Profile</h2>
                        <p>Profile Info Content</p>
                    </div>

                    <div id="experience" class="tab-section hidden">
                        <h2 class="title is-size-4 has-text-dark">Experience</h2>
                        <?php if (isset($_SESSION['session_data']['user_ID']) && !empty($data['experience'])) { 
                            foreach ($data['experience'] as $experience): 
                                echo '<form class="form-window" action="edit.php" method="post">
                                        <div class="items">
                                            <h3 class="has-text-weight-bold">'.htmlspecialchars($experience['worktitle']).'</h3>
                                            <h3 class="has-text-info">'.htmlspecialchars($experience['company']).'</h3>
                                            <span>'.htmlspecialchars($experience['firstDate']).'-'.htmlspecialchars($experience['lastDate']).'</span> 
                                            <p class="subtitle is-size-6">'.htmlspecialchars($experience['workdesc']).'</p>
                                        </div>
                                        <div class="button-wrapper">
                                            <input type="hidden" name="entryid" value="">
                                            <button type="button" class="trash" name="trash-work" data-section="trash-work"><i class="bx bxs-trash-alt"></i></button>
                                            <button type="button" class="edit" name="edit-work" data-section="edit-work"><i class="bx bxs-pencil"></i></button>
                                        </div>
                                    </form>';
                            endforeach; ?>
                        <?php } else { ?>
                            <form class="form-window" action="edit.php" method="post">
                                <div class="items">
                                    <h3 class="has-text-weight-bold"><?= "Cabin Attendant"; ?></h3>
                                    <h3 class="has-text-info">Turkish Airlines</h3>
                                    <span>06.12.2016 - 21.06.2024</span> 
                                    <p class="subtitle is-size-6">
                                        Energieke en klantgerichte cabine-assistente gericht op passagierscomfort en veiligheid aan boord. Vaardig in het omgaan met noodgevallen. Vraag naar mijn ervaringen hiermee.
                                    </p>
                                </div> 

                                <div class="button-wrapper">
                                    <input type="hidden" name="entryid" value="">
                                    <button type="button" class="trash" name="trash-work" data-section="trash-work"><i class='bx bxs-trash-alt'></i></button>
                                    <button type="button" class="edit" name="edit-work" data-section="edit-work"><i class='bx bxs-pencil'></i></button>
                                </div>
                            </form>
                            <button class="button is-primary is-dark">Add</button>
                        <?php } ?>
                    </div>
                    <div id="education" class="tab-section hidden">
                        <h2>Education</h2>
                        <p>Education Content</p>
                        <div class="button-wrapper">
                            <input type="hidden" name="entryid" value="">
                            <button type="button" class="trash" name="trash-work" data-section="trash-work"><i class='bx bxs-trash-alt'></i></button>
                            <button type="button" class="edit" name="edit-work" data-section="edit-work"><i class='bx bxs-pencil'></i></button>
                        </div>
                    </div>
                    <div id="skills" class="tab-section hidden">
                        <h2>Skills</h2>
                        Technical Skill Content
                    </div>
                    <div id="preview" class="tab-section hidden">
                        <h2>Preview</h2>
                        Resume Preview
                    </div>
                </div>

                <!-- <div class="sheet"> -->
                    <!-- <h2>Mijn Curriculum Vitae</h2> -->
                    <!-- <div class="accordion-head">
                    <form action="src/resume.src.php" method="post">
                        <button type="submit" data-section="create-res">New Resume</button> 
                        <button type="submit" style="background:#4f46e5; color:#fff;" data-section="trash-cv">Delete Resume</button> 

                        <label for="selectCv"></label>
                        <select id="selectCv" name="cvname">
                            <option selected disabled hidden>Select Resume:</option>
                            <?php // Check if there is resume data to display
                            if (!empty($resumeData['resume'])): 
                                // Loop through each resume and create an option element
                                foreach ($resumeData['resume'] as $resume): ?>
                                    <option value="<?= htmlspecialchars($resume['resumeID']) ?>">
                                        <?= htmlspecialchars($resume['resumetitle']) ?>
                                    </option>
                                <?php endforeach; 
                            endif; ?>
                        </select>
                    </form>   
                    </div>   -->

                    <!-- Curriculum Vitae -->
                    <!-- <button class="accordion">Curriculum Profiel</button>
                    <div class="panel">    
                        <form class="workinfo">
                            <div class="tab">
                                <div>
                                    <label for="title">Titel</label>
                                    <input type="text" id="title" name="resumetitle" placeholder="Professional Dredger" disabled>
                                </div>
                                <input type="hidden" name="resumeID" value=""> 
                                <input type="hidden" name="userID" value=""> 
                                <button type="button" class="trash" data-section="trash-cv"><i class='bx bxs-trash-alt'></i></button>
                                <button type="button" class="edit" data-section="edit-cv"><i class='bx bxs-pencil'></i></button>
                                <button type="button" data-section="save-res">Save to PDF</button> 
                            </div>
                        </form>
                        <div class="account-section-divider"></div>
                        <h2>Foto</h2>
                        <form>
                            <label for="file-upload"></label>
                            <input type="file" class="avatar" name="file-upload">
                            <p>Tip: Gebruik geen foto, dan zetten wij jouw initialen erin.</p>
                            <input type="hidden" name="resumeID" value=""> 
                            <input type="hidden" name="userID" value=""> 
                            <div class="button-wrapper">
                                <button name="delImg" class="trash"><i class='bx bxs-trash-alt'></i></button>
                                <button name="saveImg" class="edit"><i class='bx bxs-pencil'></i></button>
                            </div>
                        </form>
                    </div> -->

                    <!-- Werkervaring / Stages -->
                    <!-- <button class="accordion">Werkervaring / Stages</button>
                    <div class="panel">
                        <form class="workinfo" action="edit.php" method="post">
                 
                            <strong>Cabin Attendant</strong>
                      
                            <strong>Turkish Airlines</strong>
                 
                            <span>06/12/2016</span>
                     
                            <span>21/06/2024</span> 
                    
                            <p>
                                Energieke en klantgerichte cabine-assistente gericht op passagierscomfort en veiligheid aan boord. Vaardig in het omgaan met noodgevallen. Vraag naar mijn ervaringen hiermee.
                            </p>
                            <div class="button-wrapper">
                                <input type="hidden" name="entryid" value="">
                                <button type="button" class="trash" name="trash-work" data-section="trash-work"><i class='bx bxs-trash-alt'></i></button>
                                <button type="button" class="edit" name="edit-work" data-section="edit-work"><i class='bx bxs-pencil'></i></button>
                            </div>
                        </form>
                    </div> -->

                    <!-- Opleiding / Cursussen -->
                    <!-- <button class="accordion">Opleiding / Cursussen</button>
                    <div class="panel">
                        <form class="workinfo" action="edit.php" method="post">
                
                            <strong>Mediavormgever</strong>
                 
                            <strong>Grafisch Lyceum</strong>
                   
                            <span>06/12/2016</span>
                       
                            <span>21/06/2024</span> 
                      
                            <p>
                                Energieke en klantgerichte cabine-assistente gericht op passagierscomfort en veiligheid aan boord. Vaardig in het omgaan met noodgevallen. Vraag naar mijn ervaringen hiermee.
                            </p> 
                            <div class="button-wrapper">
                                <input type="hidden" name="eduid" value="">
                                <button type="button" class="trash" data-section="trash-study"><i class='bx bxs-trash-alt'></i></button>
                                <button type="button" class="edit" data-section="edit-study"><i class='bx bxs-pencil'></i></button>
                            </div>  
                        </form>
                    </div> -->

                    <!-- Vaardigheden -->
                    <!-- <button class="accordion">Vaardigheden</button>
                    <div class="panel">
                        <form action="edit.php" method="post">
                            <div class="tab">  
                                <div>
                                    <strong>Proficiencies</strong>      
                                    <p>Office 365</p>
                                </div>

                                <div> 
                                    <strong>Language</strong> 
                                    <p>Swedish</p>
                                </div>

                                <div> 
                                    <strong>Interests</strong> 
                                    <p>Theatre</p>
                                </div>
                            </div>
                            <div class="button-wrapper">
                                <input type="hidden" name="techid" value="">
                                <input type="hidden" name="langid" value="">
                                <input type="hidden" name="intid" value="">
                                <button type="button" class="trash" data-section="trash-skill"><i class='bx bxs-trash-alt'></i></button>
                                <button type="button" class="edit" data-section="edit-skill"><i class='bx bxs-pencil'></i></button>
                            </div>
                        </form>
                    </div> -->

                    <!-- Overige -->
                    <!-- <button class="accordion">Motivatiebrief</button>
                    <div class="panel">
                        <form action="src/resume.src.php" method="post">
                            <p>Schrijf hier jouw motivatie...</p>
                            <div class="button-wrapper">
                                <input type="hidden" name="motid" value="">
                                <button type="button" class="trash" data-section="trash-mot"><i class='bx bxs-trash-alt'></i></button>
                                <button type="button" class="edit" data-section="edit-mot"><i class='bx bxs-pencil'></i></button>
                            </div>
                            <div class="account-section-divider"></div>
                        </form>
                    </div> -->
                </div>

            </div>
        </section>
        
        <section id="user" class="<?= AccountPage() ?>">
            <div class="form-window">
                
                <!-- <button class="avatar">Profiel Foto</button> -->
                <form action="src/account.src.php" method="post">
                    <h3>Persoonlijk</h3>
                    <div class="tab">
                        <div>
                            <label for="firstname">Voornaam</label>
                            <input type="text" name="firstname" placeholder="Zara" disabled>
                        </div>
                        <div>
                            <label for="lastname">Achternaam</label>
                            <input type="text" name="lastname" placeholder="Arkmenedih" disabled> 
                        </div>
                        <div > 
                            <label for="postalcode">Postcode</label>
                            <input type="text" name="postalcode" placeholder="Postcode" disabled>             
                        </div> 
                        <div> 
                            <label for="city">Woonplaats</label>
                            <input type="text" name="city" placeholder="Woonplaats" disabled>          
                        </div> 
                        <div>
                            <label for="country">Nationaliteit</label>
                            <input type="text" name="country" placeholder="Nationaliteit" disabled> 
                        </div>
                        <div>
                            <label for="phone">Telefoon</label>
                            <input type="text" name="phone" placeholder="Mobile Number" disabled> 
                        </div>
                        <div class="date-options">
                            <label for="day-select">Geboortedatum</label>
                            <select class="day-select" name="day" required>
                                <option value="" selected disabled>--</option>
                                <!-- Populated with JS -->
                            </select>
                            <select class="month-select" name="month" required>
                                <option value="" selected disabled>--</option>
                                <!-- Populated with JS -->
                            </select>
                            <select class="year-select" name="year" required>
                                <option value="" selected disabled>----</option>
                                <!-- Populated with JS -->
                            </select>
                        </div>
                        <input type="hidden" name="uid"> 
                        <button type="submit" name="savePersonal">Wijzigen</button>
                    </div>
                    <div class="account-section-divider"></div>
                </form>
                <form action="src/account.src.php" method="post">
                    <h3>Account</h3>
                    <div class="tab">
                        <div>
                            <label for="username">Gebruikersnaam</label>
                            <input type="text" id="username" name="username" placeholder="(Optioneel)" disabled>
                        </div>
                        <div>
                            <label for="email">E-mailadres</label>
                            <input type="email" id="email" name="email" placeholder="Email" disabled>
                        </div>
                        <div>
                            <label for="pwd">Wachtwoord</label>
                            <input type="password" id="pwd" name="pwd" placeholder="Wachtwoord" disabled>
                        </div>
                        <div>
                            <label for="pwdR">Herhaal Wachtwoord</label>
                            <input type="password" id="pwdR" name="pwdR" placeholder="Wachtwoord" disabled>
                        </div>
                    </div>

                    <!-- Hidden field is needed since js submit() instantly sends, ignoring form modifications -->
                    <input type="hidden" name="uid">
                    <button type="submit" name="saveAccount">Wijzigen</button>
                    <div class="account-section-divider"></div>
                </form>
                <p>Deze actie kan niet worden teruggedraaid.</p>
                <button type="submit" data-section="close-account">Account Sluiten</button>     
            </div>
        </section>

        <section id="guide" class="hidden">
            <h2>Onze gids</h2>
        </section>

        <section id="logout" class="<?= logoutRequest(); ?>">
            <h2>Weet je zeker dat je wilt uitloggen?</h2>
            <form action="" method="post">
                <button type="submit" name="logout">Uitloggen</button>
            </form> 
        </section>

        <section id="create-res" class="hidden">
            <div class="form-window">
                <form action="src/resume.src.php" method="post">
                    <button class="return" data-section="home">Terug</button>
                    <h2>Nieuw CV Maken</h2>
                    <label for="cvname">Titel</label>
                    <input type="text" id="cvname" name="cvname" placeholder="Geef het een naam." required>
                    <button type="submit" name="creResume">Opslaan</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>

        <section id="save-res" class="hidden">
            <div class="form-window">
                <form action="src/resume.src.php" method="post">
                    <button class="return" data-section="home">Terug</button>
                    <h2>Downloaden</h2>
                    <p>Kies een template</p>
                    <button type="submit" name="default">Standaard</button>
                    <button type="submit" name="business">Professioneel</button>
                    <button type="submit" name="careertiger">Carrièretijger</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>

        <section id="trash-cv" class="hidden">
            <div class="form-window">
                <form action="src/resume.src.php" method="post">
                    <button class="return" data-section="home">Terug</button>
                    <h2>Verwijderen</h2>
                    <label for="cvname">Welk cv wil je verwijderen?</label>
                    <select id="cvname" name="cvname">
                        <option selected disabled hidden>(None selected)</option>
                        <?php if (!empty($resumeData)) { ?>
                        <?php foreach ($resumeData as $resume): ?>
                            <option><?= htmlspecialchars($resume['resumetitle']); ?></option>
                        <?php endforeach; ?> <?php } ?>
                    </select>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>

                    <!-- Hidden field is needed since js submit() instantly sends, ignoring form modifications -->
                    <input type="hidden" name="resid">
                    <button class="Del" type="submit" name="delResume">Verwijderen</button>
                </form>
            </div>
        </section>

        <section id="edit-work" class="hidden">
            <div class="form-window">
                <form action="src/resume.src.php" method="post">
                    <button class="return" data-section="home">Terug</button>
                    <h2>Wijzigen</h2>
                    <div class="workinfo">
                        <input type="hidden" name="workid" value="">
                        <div>
                            <label for="worktitle">Functie</label>
                            <input type="text" id="worktitle" name="worktitle" placeholder="Mijn functie" required>
                        </div>
                        <div>
                            <label for="company">Bedrijf</label>
                            <input type="text" id="company" name="company" placeholder="Mijn werkgever" required>
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
                        <label for="workdesc">Beschrijving</label>
                        <textarea id="workdesc" rows="4" placeholder="Write your job description here..."></textarea>
                        <button type="submit" name="saveExperience">Opslaan</button>
                    </div>
                </form>
            </div>
        </section>

        <section id="trash-work" class="hidden">
            <div class="form-window">
                <form action="src/resume.src.php" method="post">
                    <button class="return" data-section="home">Terug</button>
                    <h2>Weet je het zeker?</h2>
                    <div class="workinfo">
                        <div>
                            <label for="worktitle">Functie</label>
                            <input type="text" id="worktitle" name="worktitle" placeholder="Mijn functie" disabled>
                        </div>
                        <div>
                            <label for="company">Bedrijf</label>
                            <input type="text" id="company" name="company" placeholder="Mijn werkgever" disabled>
                        </div>
                        <div>
                            <label for="joined">In dienst</label>
                            <input type="text" id="joined" value="06/12/2016" disabled>
                        </div>
                        <div>
                            <label for="left">Uit dienst</label>
                            <input type="text" id="left" value="06/12/2016" disabled>
                        </div> 
                        <label for="workdesc">Beschrijving</label>
                        <textarea id="workdesc" rows="4" placeholder="Write your job description here..." disabled></textarea>
                    </div>
                    <input type="hidden" name="workid" value="">
                    <button type="submit" name="trashExperience">Verwijderen</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>

        <section id="edit-study" class="hidden">
            <div class="form-window">
                <form action="src/resume.src.php" method="post">
                    <button class="return" data-section="home">Terug</button>
                    <h2>Wijzigen</h2>
                    <div class="workinfo">
                        <div>
                            <label for="edutitle">Functie</label>
                            <input type="text" id="edutitle" name="edutitle" placeholder="Mijn Opleiding" required>
                        </div>
                        <div>
                            <label for="company">Bedrijf</label>
                            <input type="text" id="company" name="company" placeholder="Mijn School" required>
                        </div>
                        <div class="date-options">
                            <label for="day-select">Start</label>
                            <select class="day-select" name="join_day" required>
                                <option value="" selected disabled>--</option>
                            </select>
                            <select class="month-select" name="join_month" required>
                                <option value="" selected disabled>--</option>
                            </select>
                            <select class="year-select" name="join_year" required>
                                <option value="" selected disabled>----</option>
                            </select>
                        </div> 
                        <div class="date-options">
                            <label for="day-select">Uitschrijving</label>
                            <select class="day-select" name="leave_day" required>
                                <option value="" selected disabled>--</option>
                            </select>
                            <select class="month-select" name="leave_month" required>
                                <option value="" selected disabled>--</option>
                            </select>
                            <select class="year-select" name="leave_year" required>
                                <option value="" selected disabled>----</option>
                            </select>
                        </div> 
                        <label for="edudesc">Beschrijving</label>
                        <textarea id="edudesc" rows="4" placeholder="Write your study description here..."></textarea>
                    </div>
                    <input type="hidden" name="eduid" value="">
                    <button type="submit" name="saveEducation">Opslaan</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>

        <section id="trash-study" class="hidden">
            <div class="form-window">
                <form action="src/resume.src.php" method="post">
                    <button class="return" data-section="home">Terug</button>
                    <h2>Weet je het zeker?</h2>
                    <div class="workinfo">
                        <div>
                            <label for="edutitle">Functie</label>
                            <input type="text" id="edutitle" name="edutitle" placeholder="Mijn Opleiding" disabled>
                        </div>
                        <div>
                            <label for="company">Bedrijf</label>
                            <input type="text" id="company" name="company" placeholder="Mijn School" disabled>
                        </div>
                        <div>
                            <label for="joined">Start</label>
                            <input type="text" id="joined" value="06/12/2016" disabled>
                        </div>
                        <div>
                            <label for="left">Uitschrijving</label>
                            <input type="text" id="left" value="06/12/2016" disabled>
                        </div> 
                        <label for="edudesc">Beschrijving</label>
                        <textarea id="edudesc" rows="4" placeholder="Write your job description here..." disabled></textarea>
                    </div>
                    <input type="hidden" name="eduid" value="">
                    <button type="submit" name="trashEducation">Verwijderen</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>

        <section id="edit-skill" class="hidden">
            <div class="form-window">
                <form action="src/resume.src.php" method="post">
                    <button class="return" data-section="home">Terug</button>
                    <h2>Wijzigen</h2>
                    
                    <label for="skill">Proficiency</label>
                    <input type="text" id="skill" name="skill" value="Mijn Opleiding">

                    <label for="language">Language</label>
                    <input type="text" id="language" name="language" value="Mijn School">

                    <label for="interest">Interests</label>
                    <input type="text" id="interest" name="interest" value="06/12/2016">
                    
                    <input type="hidden" name="techid" value="">
                    <button type="submit" name="saveSkill">Opslaan</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>

        <section id="trash-skill" class="hidden">
            <div class="form-window">
                <form action="src/resume.src.php" method="post">
                    <button class="return" data-section="home">Terug</button>
                    <h2>Weet je het zeker?</h2>
                    
                    <label for="skill">Proficiency</label>
                    <input type="text" id="skill" name="skill" value="Mijn Opleiding" disabled>

                    <label for="language">Language</label>
                    <input type="text" id="language" name="language" value="Mijn School" disabled>

                    <label for="interest">Interests</label>
                    <input type="text" id="interest" name="interest" value="06/12/2016" disabled>
                    
                    <input type="hidden" name="techid" value="">
                    <button type="submit" name="trashSkill">Verwijderen</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>

        <section id="edit-mot" class="hidden">
            <div class="form-window">
                <form action="src/resume.src.php" method="post">
                    <button class="return" data-section="home">Terug</button>   
                    <h2>Wijzigen</h2>               
                    
                    <label for="mot">Motivation</label>
                    <textarea name="letter" rows="4" placeholder="Schrijf hier je motivatie..."></textarea>
                    <input type="hidden" name="motid" value="">
                    
                    <button type="submit" name="saveMotivation">Opslaan</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>

        <section id="trash-mot" class="hidden">
            <div class="form-window">
                <form action="src/resume.src.php" method="post">
                    <button class="return" data-section="home">Terug</button>
                    <h2>Weet je het zeker?</h2>                
                    
                    <label for="mot">Motivation</label>
                    <textarea name="letter" rows="4" placeholder="Schrijf hier je motivatie..." disabled></textarea>
                    <input type="hidden" name="motid" value="">
                    
                    <button type="submit" name="trashMotivation">Verwijderen</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>
        
        <section id="close-account" class="hidden">
            <div class="form-window">
                <form action="src/account.src.php" method="post">
                    <button class="return" data-section="home">Terug</button>
                    <h2>Wat jammer dat je vertrekt.</h2>
                    <p>Let op: Hiermee worden al jouw gegevens verwijderd. <br>Wil je echt jouw account verwijderen?</p>
                    <label for="pwd">Wachtwoord</label>
                    <input type="password" id="pwd" name="pwd" placeholder="Vul nog 1 keer je wachtwoord in" required>

                    <!-- Hidden field is needed since js submit() instantly sends, ignoring form modifications -->
                    <input type="hidden" name="username" value=""> 
                    <input type="hidden" name="uid" value=""> 
                    <button type="submit" name="delUser">Verwijder mij</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
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