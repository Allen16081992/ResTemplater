<section id="login" class="<?= ViewBook::isVisible('login'); ?>">
    <div class="form-window">
        <h2 class="title is-size-3">Login</h2>
        <form id="login_form" action="src/action_handler.src.php" method="post">    
            <label for="email">E-mailadres</label>
            <input class="input" type="email" id="email" name="email" value="<?= ViewBook::e(ViewBook::old('email')) ?>" placeholder="Email" required/>     
            
            <label for="pwd">Wachtwoord</label>
            <input class="input" type="password" id="pwd" name="pwd" placeholder="Password" required/>

            <button class="button is-success is-fullwidth" type="submit" id="loginBtn">Inloggen</button>
            <input type="hidden" name="action" value="login">
        </form>
    </div>
</section>