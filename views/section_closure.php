<section class="hidden" id="closure">
    <h1 class="title is-size-2 has-text-grey-light has-text-centered animate__animated animate__fadeInDown" style="margin-top: 3rem;">
        ✨ By closing your account, we assume you don't need us anymore ✨
    </h1>

    <p class="subtitle is-size-5 animate__animated animate__fadeIn animate__delay-1s" style="max-width: 36rem; margin: 1rem auto 2.5rem auto; line-height: 1.8;">
        Goodbye, and best of luck in your career prospects.<br>
        • Your account, resumes and all information will be erased.<br>
        • You won't be able to recover your account later.<br>
        • This action is irreversible.✨
    </p>

    <article class="profile-wrap animate__animated animate__fadeIn animate__delay-2s">
        <form action="./config/action_handler.conf.php" method="post">
            <div class="field">
                <label for="password" class="label">Password</label>
                <input type="password" id="password" name="pwd" class="input" placeholder="••••••••">
            </div>
            <button type="submit" name="action" value="closure" class="button is-dark is-danger animate__animated animate__fadeIn animate__delay-3s">Erase me permanently</button>
            <input type="hidden" name="id" value="<?= $_SESSION['session_data']['user_id'] ?? '' ?>">
        </form> 
    </article>
</section>