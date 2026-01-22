<?php if(isset($_SESSION['success'])) { ?>
    <section id="ready_user" class="<?= ViewBook::setVisibility('success'); //ViewBook::setView_Error('success'); ?>">
        <h1 class="title is-size-2 has-text-dark has-text-centered animate__animated animate__fadeInDown" style="margin-top: 3rem;">
            ✨ Your account has been casted successfully ✨
        </h1>

        <p class="subtitle is-size-5 has-text-centered has-text-dark animate__animated animate__fadeIn animate__delay-1s" style="max-width: 36rem; margin: 1rem auto 2.5rem auto; line-height: 1.8;">
            Welcome to PaperWitch, a resume editor born to life from a successfull college project.
            Log in and make yourself at home. Your job scroll awaits ✨
        </p>
    </section>
<?php } ?>