<a href="index.php" id="logo"><img src="assets/images/witch_logo2.png" alt="Brand logo"></a>
<nav aria-label="main navigation">
    <?php $page = basename($_SERVER['SCRIPT_NAME']); if ($page  === 'client.php') { ?>
        <a href="#" data-section="profile">Profile</a> 
        <a href="#" data-section="home">Resume</a>
        <a href="index.php">Logout</a>  
    <?php } else { ?>  
        <a href="#" data-section="policy">Privacy</a>  
        <a href="#" data-section="login">Log in</a>
        <a href="#" id="signUp" data-section="sign_up">Sign Up</a> 
    <?php } ?>
</nav>