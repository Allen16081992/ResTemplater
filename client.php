<?php 
  // Load PHP files
  require_once './src/session_manager.src.php'; 
  // SessionBook::invokeSession();
  // SessionBook::intrusionGuard();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <title>"A Lightweight Toolkit" | CV Templater</title>
    <!-- Javascript -->
    <script defer src="assets/js/section-handler.js"></script>
</head>
<body>
    <header>
        <?php ViewBook::render('navbar_flex.php'); ?>
    </header>
    <div class="skew"></div>

    <?php if ($error = SessionBook::flash('error')): ?>
        <div class="serV serV-error"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($success = SessionBook::flash('success')): ?>
        <div class="serV serV-oke"><?= $success ?></div>
    <?php endif; ?>

    <main>
        <section id="home" class="<?= ViewBook::Homepage(); ?>">
            <div class="flexo">
                <!-- Add Timeline to Landing Page to highlight feautures -->
                <!-- Rotating CTA button text is a good idea -->
            </div>

            <div class="resume-board columns">
                <div class="column sidebar">
                    <button type="submit" class="button is-small is-success" data-section="add-res">New Resume</button>
                    <button type="submit" class="button is-small is-danger" data-section="trash-res">Delete Resume</button>
                    <form action="">
                        <label for="selectCv"></label>
                        <div class="select is-small is-fullwidth" style="margin-bottom:10px;">
                            <select id="selectCv" name="cvname">
                                <option selected disabled hidden>Select a resume:</option>
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
                        </div>
                    </form> 
                    <hr>
                    <ul>
                        <li><a href="">Personal</a></li>
                        <li><a href="">Experience</a></li>
                        <li><a href="">Education</a></li>
                        <li><a href="">Skills</a></li>
                    </ul>
                </div>
                
                <div class="column" style="border:2px dotted red; background:#0f172a; border-radius: 10px;">
                    <h2 class="title is-size-4">Curriculum Vitae</h2>
                    <p class="subtitle is-6">Minimal Bulma starter with repeatable sections, client-side validation, and localStorage autosave.</p>
                    <hr>
                    
                        <!-- <button class="button is-success" type="submit" data-section="add-res">New Resume</button> 
                        <button class="button is-danger is-outlined" type="submit" data-section="trash-res">Delete Resume</button> 
                        <button class="button" data-section="save-res">Download</button>  -->
                        <!-- <label for="selectCv"></label>
                        <div class="select is-fullwidth" style="margin-bottom:10px;">
                            <select id="selectCv" name="cvname">
                                <option selected disabled hidden>Select a resume</option> -->
                                <?php // Check if there is resume data to display
                                // if (!empty($resumeData['resume'])): 
                                //     // Loop through each resume and create an option element
                                //     foreach ($resumeData['resume'] as $resume): ?>
                                        <!-- <option value="< // htmlspecialchars($resume['resumeID']) ?>">
                                            < // htmlspecialchars($resume['resumetitle']) ?>
                                        </option> -->
                                    <?php // endforeach; 
                                // endif; ?>
                            <!-- </select>
                        </div> -->
                    

                    <!-- Tab Navigation -->
                    <!-- <div class="tab-buttons">
                        <button data-tab="profile" class="active">Profile</button>
                        <button data-tab="experience">Work</button>
                        <button data-tab="education">Education</button>
                        <button data-tab="skills">Skills</button>
                        <button data-tab="letter">Motivation</button>
                        <button data-tab="preview">Preview</button>
                    </div> -->
                </div>

            </div>
            
            <!-- Tabs Content (Inside Resume Builder) -->
            <div class="tab-content">
                <div id="profile" class="tab-section current">
                    <div >
                        <h2 class="title is-size-4">Profile</h2>
                        <div style="width:100%; display:flex; justify-content:center;">
                            <label for="file-upload"></label>
                            <input type="file" class="avatar" name="file-upload">
                        </div>
                        <label for="title">Titel</label>
                        <input class="input" type="text" id="title" name="resumetitle" placeholder="Professional Dredger">
                        <div class="button-wrapper">
                            <button class="button" name="delAV">No Image</button>
                            <button class="button is-link" name="addAV">Save</button>
                        </div>
                    </div>
                </div>

                <div id="experience" class="tab-section hidden"> 
                    <div >
                        <h2 class="title is-size-4">Experience</h2>
                        <?php if (isset($_SESSION['session_data']['user_ID']) && !empty($data['experience'])) { 
                            foreach ($data['experience'] as $job): 
                                echo '<form>
                                        <div class="items">
                                            <input type="hidden" name="entryid" value="'.htmlspecialchars($job['workID']).'">
                                            <h3 class="has-text-info">'.htmlspecialchars($job['worktitle']).'</h3>
                                            <h3>'.htmlspecialchars($job['company']).'</h3>
                                            <span>'.htmlspecialchars($job['firstDate']).'-'.htmlspecialchars($job['lastDate']).'</span> 
                                            <p class="subtitle is-size-6">'.htmlspecialchars($job['workdesc']).'</p>
                                        </div>
                                        <div class="button-wrapper">
                                            <input type="hidden" name="entryid" value="">
                                            <button type="button" name="trash-work" data-section="trash-work"><i class="bx bxs-trash-alt"></i></button>
                                            <button type="button" name="edit-work" data-section="edit-work"><i class="bx bxs-pencil"></i></button>
                                        </div>
                                    </form>';
                            endforeach; ?>
                        <?php } else {
                            echo '<form>
                                <button class="button is-primary is-small is-fullwidth" data-tab="add-skill">Add</button>
                            </form>';
                        } ?>
                    </div>
                </div>
                <div id="education" class="tab-section hidden">
                    <div >
                        <h2 class="title is-size-4">Education</h2>
                        <?php if (isset($_SESSION['session_data']['user_ID']) && !empty($data['education'])) { 
                            foreach ($data['education'] as $study): 
                                echo '<form>
                                        <div class="items">
                                            <h3 class="has-text-info">'.htmlspecialchars($study['edutitle']).'</h3>
                                            <h3>'.htmlspecialchars($study['company']).'</h3>
                                            <span>'.htmlspecialchars($study['firstDate']).'-'.htmlspecialchars($study['lastDate']).'</span> 
                                            <p class="subtitle is-size-6">'.htmlspecialchars($study['edudesc']).'</p>
                                        </div>
                                        <div class="button-wrapper">
                                            <input type="hidden" name="entryid" value="">
                                            <button type="button" class="trash" data-section="trash-study"><i class="bx bxs-trash-alt"></i></button>
                                            <button type="button" class="edit" data-section="trash-study"><i class="bx bxs-pencil"></i></button>
                                        </div>
                                    </form>';
                            endforeach; ?>
                        <?php } else {
                            echo '<form>
                                <button class="button is-primary is-small is-fullwidth" data-tab="edit-study">Add</button>
                            </form>'; 
                        } ?>
                    </div>
                </div>
                <div id="skills" class="tab-section hidden">
                    <div >
                        <h2 class="title is-size-4">Technical Skills</h2>
                        <?php if (isset($_SESSION['session_data']['user_ID']) && !empty($data['techskill'])) { 
                            foreach ($data['techskill'] as $skill): 
                                echo '<form>
                                        <div class="items">
                                            <div>
                                                <h3 class="title is-size-6">Skills</h3>
                                                <span class="subtitle is-size-6">'.htmlspecialchars($skill['techtitle']).'</span>
                                            </div>
                                            <div>
                                                <h3 class="title is-size-6">Language</h3>
                                                <span class="subtitle is-size-6">'.htmlspecialchars($skill['language']).'</span>
                                            </div>
                                            <div>
                                                <h3 class="title is-size-6">Free-time</h3>
                                                <span class="subtitle is-size-6">'.htmlspecialchars($skill['interest']).'</span>
                                            </div>
                                        </div>
                                        <div class="button-wrapper">
                                            <input type="hidden" name="entryid" value="'.htmlspecialchars($skill['techID']).'">
                                            <button type="button" class="trash" data-section="trash-study"><i class="bx bxs-trash-alt"></i></button>
                                            <button type="button" class="edit" data-section="trash-study"><i class="bx bxs-pencil"></i></button>
                                        </div>
                                    </form>';
                            endforeach; ?>
                        <?php } else {
                            echo '<form>
                                <button class="button is-primary is-small is-fullwidth" data-tab="add-skill">Add</button>
                            </form>';
                        } ?>
                    </div>
                </div>
                <div id="letter" class="tab-section hidden">
                    <div >
                        <h2 class="title is-size-5">Motivation</h2>
                        <?php if (isset($_SESSION['session_data']['user_ID']) && !empty($data['techskill'])) { 
                                echo '<form>
                                    <p class="subtitle is-size-6">'.htmlspecialchars($data['motivation'][1]).'</p>
                                </form>                              
                                <div class="button-wrapper">
                                    <input type="hidden" name="entryid" value="'.htmlspecialchars($data['motivation'][0]).'">
                                    <button type="button" data-section="trash-mot"><i class="bx bxs-trash-alt"></i></button>
                                    <button type="button" data-section="edit-mot"><i class="bx bxs-pencil"></i></button>
                                </div>
                                '; ?>
                        <?php } else {
                            echo '<form>
                                <button class="button is-primary  is-small is-fullwidth" data-tab="add-skill">Add</button>
                            </form>';
                        } ?>
                    </div>
                </div>
                <div id="preview" class="tab-section hidden">
                    Resume Preview
                </div>
            </div>

            <!-- <div class="sheet"> -->
                <!-- <h2>Mijn Curriculum Vitae</h2> -->
                <!-- <div class="accordion-head">
                <form action="src/resume.src.php" method="post">
                    <button type="submit" data-section="add-res">New Resume</button> 
                    <button type="submit" style="background:#4f46e5; color:#fff;" data-section="trash-res">Delete Resume</button> 
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
                            <button type="button" class="trash" data-section="trash-res"><i class='bx bxs-trash-alt'></i></button>
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
                    <form class="workinfo" method="post">
                
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
                    <form class="workinfo" method="post">
            
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
                    <form method="post">
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
        </section>
        
        <section id="user" class="<?= ViewBook::isVisible('account'); ?>">
            <div class="form-window">
                
                <!-- <button class="avatar">Profiel Foto</button> -->
                <form action="src/account.src.php" method="post">
                    <h3 class="subtitle">Persoonlijk</h3>
                    <div class="tab">
                        <div>
                            <label for="firstname">Voornaam</label>
                            <input class="input" type="text" name="firstname" placeholder="Zara">
                        </div>
                        <div>
                            <label for="lastname">Achternaam</label>
                            <input class="input" type="text" name="lastname" placeholder="Arkmenedih"> 
                        </div>
                        <div > 
                            <label for="postalcode">Postcode</label>
                            <input class="input" type="text" name="postalcode" placeholder="Postcode">             
                        </div> 
                        <div> 
                            <label for="city">Woonplaats</label>
                            <input class="input" type="text" name="city" placeholder="Woonplaats">          
                        </div> 
                        <div>
                            <label for="country">Nationaliteit</label>
                            <input class="input" type="text" name="country" placeholder="Nationaliteit"> 
                        </div>
                        <div>
                            <label for="phone">Telefoon</label>
                            <input class="input" type="text" name="phone" placeholder="Mobile Number"> 
                        </div>
                        <input type="hidden" name="uid" value="<?= $_SESSION['session_data']['user_ID']; ?>"> 
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                    </div>
                    <button class="button is-link" type="submit" name="savePersonal">Save</button>
                    <div class="divider"></div>
                </form>
                <form action="src/account.src.php" method="post">
                    <h3 class="subtitle">Account</h3>
                    <div class="tab">
                        <div>
                            <label for="username">Gebruikersnaam</label>
                            <input class="input" type="text" id="username" name="username" placeholder="(Optioneel)">
                        </div>
                        <div>
                            <label for="email">E-mailadres</label>
                            <input class="input" type="email" id="email" name="email" placeholder="Email">
                        </div>
                        <div>
                            <label for="pwd">Wachtwoord</label>
                            <input class="input" type="password" id="pwd" name="pwd" placeholder="Wachtwoord">
                        </div>
                        <div>
                            <label for="pwdR">Herhaal Wachtwoord</label>
                            <input class="input" type="password" id="pwdR" name="pwdR" placeholder="Wachtwoord">
                        </div>
                    </div>

                    <input type="hidden" name="uid" value="<?= $_SESSION['session_data']['user_ID']; ?>"> 
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                    <button class="button is-link" type="submit" name="saveAccount">Save</button>
                    <div class="account-section-divider"></div>
                </form>
                <p class="subtitle">Deze actie kan niet worden teruggedraaid.</p>
                <button class="button is-danger" type="submit" data-section="close-account">Close Account</button>     
            </div>
        </section>

        <section id="guide" class="hidden">
            <h2>Onze gids</h2>
        </section>

        <section id="logout" class="hidden">
            <div class="form-window">
            <h2 class="title is-size-5">Until next time!</h2>
            <form action="" method="post">
                <button class="button is-fullwidth" type="submit" name="logout">Log out</button>
            </form> 
            </div>
        </section>

        <section id="add-res" class="hidden">
            <div class="form-window">
                <button class="button is-small" data-section="home">Back</button>
                <form action="src/resume.src.php" method="post">
                    <h2 class="title is-size-4">New Resume</h2>
                    <label for="cvname">Title</label>
                    <input class="input" type="text" id="cvname" name="cvname" placeholder="Let's give it a name" required>
                    <input type="hidden" name="uid" value="<?= $_SESSION['session_data']['user_ID']; ?>"> 
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                    <button class="button is-link" type="submit" name="addResume">Save</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>

        <section id="save-res" class="hidden">
            <div class="form-window">
                <button class="button is-small" data-section="home">Back</button>
                <form action="src/resume.src.php" method="post">
                    <h2 class="title is-size-4">Download as PDF</h2>
                    <p>Choose your preferred layout</p>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                    <div class="buttons">
                        <button class="button" type="submit" name="default">Default</button>
                        <button class="button is-info is-inverted" type="submit" name="business">Corporate</button>
                        <button class="button is-success is-inverted" type="submit" name="careertiger">Careertiger</button>
                    </div>
                </form>
            </div>
        </section>

        <section id="trash-res" class="hidden">
            <div class="form-window">
                <button class="button is-small" data-section="home">Back</button>
                <form action="src/resume.src.php" method="post">
                    <h2 class="title is-size-4">Delete a resume</h2>
                    <label for="cvname">Which resume do you like to discard?</label>
                    <div class="select is-fullwidth">
                        <select id="cvname" name="cvname">
                            <option selected disabled hidden>Select a resume</option>
                            <?php if (!empty($resumeData)) { 
                                foreach ($resumeData as $resume): ?>
                                <option><?= htmlspecialchars($resume['resumetitle']); ?></option>
                            <?php endforeach; } ?>
                        </select>
                    </div>       
                    <input type="hidden" name="uid" value="<?= $_SESSION['session_data']['user_ID']; ?>"> 
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                    <button class="button is-danger is-dark" style="margin-top:20px;" type="submit" name="delResume">Delete</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>

        <section id="edit-work" class="hidden">
            <div class="form-window">
                <button class="button is-small" data-section="home">Back</button>
                <form action="src/resume.src.php" method="post">
                    <h2 class="title is-size-5">Edit Experience</h2>        
                    <label for="worktitle">Functie</label>
                    <input class="input" type="text" id="worktitle" name="worktitle" placeholder="Mijn functie" required>

                    <label for="company">Bedrijf</label>
                    <input class="input" type="text" id="company" name="company" placeholder="Mijn werkgever" required>
                    
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
                    <textarea id="workdesc" name="workdesc" rows="4" placeholder="Write your job description here..."></textarea>

                    <input type="hidden" name="workid" value="<?= $_SESSION['session_data']['work_ID'];; ?>">
                    <input type="hidden" name="uid" value="<?= $_SESSION['session_data']['user_ID']; ?>"> 
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                    <button class="button is-link" type="submit" name="saveExp">Save</button> 
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span> 
                </form>
            </div>
        </section>

        <section id="trash-work" class="hidden">
            <div class="form-window">
                <button class="button is-small" data-section="home">Back</button>
                <form action="src/resume.src.php" method="post">
                    <h2 class="title is-size-5">Delete Experience</h2>
                    <p class="subtitle is-size-6">Are you sure?</p>

                    <input type="hidden" name="workid" value="<?= $_SESSION['session_data']['work_ID'];; ?>">
                    <input type="hidden" name="uid" value="<?= $_SESSION['session_data']['user_ID']; ?>"> 
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                    <button class="button is-danger is-inverted" type="submit" name="trashExp">Delete</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>

        <section id="edit-study" class="hidden">
            <div class="form-window">
                <button class="button is-small" data-section="home">Back</button>
                <form action="src/resume.src.php" method="post">
                    <h2 class="title is-size-5">Edit Education</h2>
                    <label for="edutitle">Course</label>
                    <input class="input" type="text" id="edutitle" name="edutitle" placeholder="My course" required>

                    <label for="company">College</label>
                    <input class="input" type="text" id="company" name="company" placeholder="Mijn college" required>
                    
                    <div class="date-options">
                        <label for="day-select">Hired</label>
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
                        <label for="day-select">Leave</label>
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
                    
                    <input type="hidden" name="eduid" value="<?= $_SESSION['session_data']['edu_ID'];; ?>">
                    <input type="hidden" name="uid" value="<?= $_SESSION['session_data']['user_ID']; ?>"> 
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                    <button class="button is-link" type="submit" name="saveEducation">Save</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>

        <section id="trash-study" class="hidden">
            <div class="form-window">
                <button class="button is-small" data-section="home">Back</button>
                <form action="src/resume.src.php" method="post">
                    <h2 class="title is-size-5">Delete Education</h2>
                    <p class="subtitle is-size-6">Are you sure?</p>

                    <input type="hidden" name="eduid" value="<?= $_SESSION['session_data']['edu_ID'];; ?>">
                    <input type="hidden" name="uid" value="<?= $_SESSION['session_data']['user_ID']; ?>"> 
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                    <button class="button is-danger is-inverted" type="submit" name="trashEdu">Delete</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>

        <section id="edit-skill" class="hidden">
            <div class="form-window">
                <button class="button is-small" data-section="home">Back</button>
                <form action="src/resume.src.php" method="post">
                    <h2 class="title is-size-5">Changing Skills</h2>
                    
                    <label for="skill">Skill</label>
                    <input class="input" type="text" id="skill" name="skill" value="Writing">

                    <label for="language">Language</label>
                    <input class="input" type="text" id="language" name="language" value="British">

                    <label for="interest">Interests</label>
                    <input class="input" type="text" id="interest" name="interest" value="Warhammer">
                    
                    <input type="hidden" name="techid" value="<?= $_SESSION['session_data']['tech_ID'];; ?>">
                    <input type="hidden" name="uid" value="<?= $_SESSION['session_data']['user_ID']; ?>"> 
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                    <button class="button is-link" type="submit" name="saveSkill">Save</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>

        <section id="trash-skill" class="hidden">
            <div class="form-window">
                <button class="button is-small" data-section="home">Back</button>
                <form action="src/resume.src.php" method="post">
                    <h2 class="title is-size-5">Delete Skills</h2>
                    <p class="subtitle is-size-6">Are you sure you want to delete this?</p>
                    
                    <input type="hidden" name="techid" value="<?= $_SESSION['session_data']['tech_ID'];; ?>">
                    <input type="hidden" name="uid" value="<?= $_SESSION['session_data']['user_ID']; ?>"> 
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                    <button class="button is-danger is-inverted" type="submit" name="trashSkill">Delete</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>

        <section id="edit-mot" class="hidden">
            <div class="form-window">
                <button class="button is-small" data-section="home">Back</button>
                <form action="src/resume.src.php" method="post">
                    <h2>Wijzigen</h2>                        
                    <label for="mot">Motivation</label>
                    <textarea id="mot" name="mot" rows="4" placeholder="Schrijf hier je motivatie..."></textarea>
                    
                    <input type="hidden" name="motid" value="<?= $_SESSION['session_data']['mot_ID'];; ?>">
                    <input type="hidden" name="uid" value="<?= $_SESSION['session_data']['user_ID']; ?>"> 
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                    <button class="button is-link" type="submit" name="saveMotivation">Opslaan</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>

        <section id="trash-mot" class="hidden">
            <div class="form-window">
                <button class="button is-small" data-section="home">Back</button>
                <form action="src/resume.src.php" method="post">
                    <h2>Weet je het zeker?</h2>                

                    <input type="hidden" name="motid" value="<?= $_SESSION['session_data']['mot_ID'];; ?>">
                    <input type="hidden" name="uid" value="<?= $_SESSION['session_data']['user_ID']; ?>"> 
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                    <button class="button is-danger" type="submit" name="trashMotivation">Verwijderen</button>
                    <span style="opacity:0;">Nog geen account? Maak hier een nieuwe</span>
                </form>
            </div>
        </section>
        
        <section id="close-account" class="hidden">
            <div class="form-window">
                <button class="button is-small" data-section="home">Back</button>
                <form action="src/account.src.php" method="post">
                    <h2>Wat jammer dat je vertrekt.</h2>
                    <p class="subtitle is-size-6">Let op: Hiermee worden al jouw gegevens verwijderd. <br>Wil je echt jouw account verwijderen?</p>
                    <label class="label" for="pwd">Wachtwoord</label>
                    <input class="input" type="password" id="pwd" placeholder="Vul nog 1 keer je wachtwoord in" required>

                    <input type="hidden" name="uid" value="<?= $_SESSION['session_data']['user_ID']; ?>"> 
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                    <button class="button is-danger" type="submit" name="delUser">Delete</button>
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