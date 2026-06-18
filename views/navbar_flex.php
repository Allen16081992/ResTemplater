<header> <!-- Site navigation bar -->
    <a href="index.php" id="logo"><img src="assets/images/webp/witch_logo2.webp" alt="Brand logo"></a>
    <?php
        // 1. Setup contextual state
        $isLoggedIn = isset($_SESSION['session_data']['user_id']);
        $currentPage = basename($_SERVER['SCRIPT_NAME']);
        $isClientPage = ($currentPage === 'client.php');

        // 2. Define your menu structure
        $links = [];

        if ($isLoggedIn && $isClientPage) {
            $links = [
                ['label' => 'Profile', 'href' => '#', 'attr' => 'data-section="profile"'],
                ['label' => 'Resume',  'href' => '#', 'attr' => 'data-section="home"'],
                ['label' => 'Logout',  'href' => 'engine/logout_user.php', 'attr' => '']
            ];
        } elseif (!$isLoggedIn && $isClientPage) {
            // This handles the "Back" button requirement
            $links = [
                ['label' => 'Back', 'href' => 'index.php', 'attr' => '']
            ];
        } else {
            // Default/Homepage navigation
            $links = [
                ['label' => 'Privacy', 'href' => '#', 'attr' => 'data-section="policy"'],
                ['label' => 'Login',  'href' => '#', 'attr' => 'id="signIn" data-section="login"'],
                ['label' => 'Sign Up', 'href' => '#', 'attr' => 'id="signUp" data-section="sign_up"']
            ];
        }
    ?>

    <nav aria-label="main navigation">
        <?php foreach ($links as $link): ?>
            <a href="<?= htmlspecialchars($link['href']) ?>" <?= $link['attr'] ?>>
                <?= htmlspecialchars($link['label']) ?>
            </a>
        <?php endforeach; ?>
    </nav>
    <!-- <nav aria-label="main navigation"> -->
        <!-- V1 -->
        <?php //$page = basename($_SERVER['SCRIPT_NAME']); if ($page  === 'client.php') { ?>
            <!-- <a href="#" data-section="profile">Profile</a>
            <a href="#" data-section="home">Resume</a> -->
            <?php //if (!isset($_SESSION['session_data']['user_id'])) { ?>
                <!-- <a href="index.php">Back</a>  -->
            <?php //} else { ?>
                <!-- <a href="../engine/logout_user.php">Logout</a>   -->
            <?php //} ?>
        <?php //} else { ?>  
            <!-- <a href="#" data-section="policy">Privacy</a>  
            <a href="#" id="signIn" data-section="login">Log in</a>
            <a href="#" id="signUp" data-section="sign_up">Sign Up</a>  -->
        <?php //} ?>
    <!-- </nav> -->
</header>