<?php if(isset($_SESSION['success'])) { ?>
    <section id="ready_user" class="<?= ViewBook::isVisible('success'); ?>">
        <h1 class="title is-size-2 has-text-dark has-text-centered animate__animated animate__fadeInDown" style="margin-top: 3rem;">
            ✨ Your account has been conjured successfully ✨
        </h1>

        <p class="subtitle is-size-5 has-text-centered has-text-dark animate__animated animate__fadeIn animate__delay-1s" style="max-width: 36rem; margin: 1rem auto 2.5rem auto; line-height: 1.8;">
            Welcome to PaperWitch, a resume editor born to life from a successful college project.
            Log in to the circle and make yourself at home. Your parchment awaits ✨
        </p>

        <div class="form-window animate__animated animate__fadeInRightBig animate__delay-1s">
            <h2 class="title is-size-3">Login</h2>
            <form id="login_form" action="src/action_handler.src.php" method="post">    
                <label for="email">E-mailadres</label>
                <input class="input" type="email" id="email" name="email" placeholder="Email" required/>     

                <label for="pwd">Wachtwoord</label>
                <input class="input" type="password" id="pwd" name="pwd" placeholder="Password" required/>

                <button class="button is-success is-fullwidth" type="submit" id="loginBtn">Inloggen</button>
                <input type="hidden" name="action" value="login">
            </form>
        </div>
    </section>
<?php } ?>