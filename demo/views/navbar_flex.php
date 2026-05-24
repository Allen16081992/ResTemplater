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
                ['label' => 'Logout',  'href' => 'config/logout_user.conf.php', 'attr' => '']
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
</header>