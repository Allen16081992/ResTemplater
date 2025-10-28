<?php 
    // Load PHP files
    require_once "./src/session_manager.src.php"; 
    include_once "./src/phrases.src.php"
    // SessionBook::invokeSession();
    // SessionBook::clearUserSession();
?>
<!DOCTYPE html>
<html lang="eng">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Let's craft your paper for those Blasphemous companies out there.">
  <!-- Social Sharing -->
  <meta property="og:title" content="Paperwitch - Thy Job Scroll Familiar">
  <meta property="og:description" content="PaperWitch is a lightweight resume tooling kit of dutch origin where juniors and students can craft those boring job scrolls.">
  <meta property="og:image" content="assets/images/falcon250.webp">
  <meta property="og:locale" content="eng_ENG">
  <meta property="og:type" content="website">
  <!-- <meta property="og:url" content="https://www.paperwitch.com"> -->
  <!-- Favicon -->
  <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
  <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon.png">
  <!-- CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Unna:wght@400;700&family=Inter:wght@400;600;800&display=swap">
  <link rel="stylesheet" href="assets/css/paperwitch.css">
  <link rel="stylesheet" href="assets/css/auth_windows.css">
  <title>Thy Student’s Scroll</title>
  <!-- Javascript -->
  <script defer src="assets/js/section-handler.js"></script>
</head>
<body>
  <?php 
  ViewBook::render('navbar_top.php');
  ViewBook::render('landing_main.php');
  ViewBook::render('section_policy.html'); 

  ViewBook::render('section_loginWitch.php'); 
  ViewBook::render('section_signupWitch.php'); 
  ?>
  <script>
    const form = document.getElementById('signup_form');
    const visual = document.getElementById('signupVisual');
    const passwordInput = document.getElementById('password');
    const togglePwd = document.getElementById('togglePwd');
    const signupBtn = document.getElementById('signupBtn');

    const calmImg = 'url("assets/images/paperwitch_bold.png")';
    const activeImg = 'url("assets/images/paperwitch_resentful.png")';
    const burstImg = 'url("assets/images/paperwitch_spilled.png")';

    // initial background
    visual.style.backgroundImage = calmImg;

    // change image based on form interaction
    form.addEventListener('input', () => {
      const allFilled = [...form.querySelectorAll('input[required]')].every(input => input.value.trim() !== '');
      if (allFilled) {
        visual.style.backgroundImage = activeImg;
      } else {
        visual.style.backgroundImage = calmImg;
      }
    });

    // On hover over Sign Up button (only if all fields valid)
    signupBtn.addEventListener('mouseenter', () => {
      const allFilled = [...form.querySelectorAll('input[required]')].every(input => input.value.trim() !== '');
      if (allFilled) {
        visual.style.backgroundImage = burstImg;
        visual.style.filter = 'brightness(1.2)';
      }
    });
    signupBtn.addEventListener('mouseleave', () => {
      visual.style.filter = 'brightness(1)';
      const allFilled = [...form.querySelectorAll('input[required]')].every(input => input.value.trim() !== '');
      visual.style.backgroundImage = allFilled ? activeImg : calmImg;
    });

    // toggle password visibility
    togglePwd.addEventListener('click', () => {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
    });
  </script>
</body>
</html>