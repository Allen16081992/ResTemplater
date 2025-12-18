<?php 
    $thisPage = basename($_SERVER['SCRIPT_NAME']); 
    if ($thisPage  === 'client.php') {
?>

<a href="index.php" id="logo"><img src="assets/images/witch_logo2.png" alt="Brand logo"></a>
<nav aria-label="main navigation">
    <a href="#" data-section="profile">Profile</a> 
    <a href="#" data-section="home">Resume</a>
    <a href="index.php">Logout</a>    
    <!-- <a href="#" data-section="contact">Contact</a> -->    
</nav>

<?php } else { ?>

<a href="index.php" id="logo"><img src="assets/images/witch_logo2.png" alt="Brand logo"></a>
<nav aria-label="main navigation">
    <a href="#" data-section="policy">Privacy</a>  
    <a href="#" data-section="login">Log in</a>
    <a href="#" id="signUp" data-section="sign_up">Sign Up</a>   
    <!-- <a href="#" data-section="contact">Contact</a> -->    
</nav>

<?php } ?>