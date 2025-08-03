<a href="index.php" id="logo"><img src="assets/images/falcon250x.webp" alt="Brand logo"></a>
<nav aria-label="main navigation">
    <!-- If on Homepage -->
    <?php if (in_array(basename($_SERVER['PHP_SELF']), ['index.php'])) { ?>
        <a href="#" data-section="home">Home</a><!-- For Mobile only -->
        <a href="#" data-section="policy">Privacy</a>

        <!-- Logged in -->
        <?php if (isset($_SESSION['session_data']['user_name'])) { ?>
            <a href="client.php">Mijn CV (<?= SessionBook::addUsername(); ?>)</a>
            <a href="#" data-section="logout">Logout</a>   
            <!-- TODO: Create the Logout Section in 'index.php' as well -->        
        <?php } else { ?>
            <a href="#" data-section="login">Log in</a>
            <a href="#" data-section="sign_up">Sign Up</a>   
            <!-- <a href="#" data-section="contact">Contact</a> -->   
            <!-- <a href="#" data-section="author">Tips</a> -->  
        <?php } ?>

    <!-- If in User Environment -->
    <?php } elseif (in_array(basename($_SERVER['PHP_SELF']), ['client.php'])) { ?>
        <a href="#" data-section="user"><?= SessionBook::addUsername(); ?></a>
        <a href="#" data-section="home">Mijn CV</a>
        <a href="#" data-section="guide">Onze gids</a>
        <a href="#" data-section="logout">Log out</a>
    <?php } ?>
</nav>