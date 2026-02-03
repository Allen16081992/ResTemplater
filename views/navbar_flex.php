<header> <!-- Site navigation bar -->
    <a href="index.php" id="logo"><img src="assets/images/witch_logo2.png" alt="Brand logo"></a>
    <nav aria-label="main navigation">
        <?php $page = basename($_SERVER['SCRIPT_NAME']); if ($page  === 'client.php') { ?>
            <a href="#" data-section="profile"><?= ViewBook::addUsername(); ?></a>
            <a href="#" data-section="home">Resume</a>
            <?php if (!isset($_SESSION['session_data']['user_id'])) { ?>
                <a href="index.php">Back</a> 
            <?php } else { ?>
                <a href="index.php">Logout</a>  
            <?php } ?>
        <?php } else { ?>  
            <a href="#" data-section="policy">Privacy</a>  
            <a href="#" data-section="login">Log in</a>
            <a href="#" id="signUp" data-section="sign_up">Sign Up</a> 
        <?php } ?>
    </nav>
</header>