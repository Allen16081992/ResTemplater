<section class="hidden" id="closure">
    <!-- Header Section -->
    <div class="closure-header has-text-centered animate__animated animate__fadeInDown">
        <h1 class="title is-size-2 has-text-grey-light">
            The Final Chapter
        </h1>
        <p class="heading-subtitle is-size-6 has-text-grey">
            ✨ By closing your account, we assume you don't need us anymore ✨
        </p>
    </div>

    <!-- Main Grid Layout -->
    <div class="closure-grid-container">

        <!-- Column 1: The Character Art -->
        <div class="closure-image-wrapper animate__animated animate__fadeIn">
            <div class="closure-image" style="background-image: url('../assets/images/paperwitch_wave.png');"></div>
        </div>

        <!-- Column 2: The Content & Interactive Form Stack -->
        <div class="closure-content">
            
            <!-- Warning Box Component -->
            <div class="closure-warning-box subtitle is-size-5 animate__animated animate__fadeIn animate__delay-1s">
                <p class="has-text-weight-semibold mb-3">Goodbye, and best of luck in your career prospects.</p>
                <ul class="closure-warning-list is-size-6">
                    <li>Your account, resumes, and all information will be erased instantly.</li>
                    <!-- <li>You will lose ownership of your custom domains/data configurations.</li> -->
                    <li>This action cannot be reversed later. ✨</li>
                </ul>
            </div>

            <!-- Interaction Card -->
            <article class="closure-wrap animate__animated animate__fadeIn animate__delay-2s">
                <form action="config/action_handler.conf.php" method="post" id="closureForm">
                    <?= SessionBook::csrfField(); ?>
                    
                    <!-- Safety Acknowledgment Checkbox -->
                    <div class="field mb-5">
                        <label class="checkbox has-text-grey-light is-size-6 select-none">
                            <input type="checkbox" id="confirmGate" class="mr-2">
                            I understand that all my data will be deleted.
                        </label>
                    </div>

                    <!-- Password Verification -->
                    <div class="field">
                        <label for="password" class="label has-text-grey-light">Verify Password</label>
                        <div class="server-field animate__animated animate__shakeX"><?= ViewBook::getError('pwd') ?></div>
                        <div class="control">
                            <input type="password" id="password" name="pwd" class="input dark-input" placeholder="••••••••" required>
                        </div>
                    </div>
                    
                    <!-- Destructive Action Button -->
                    <button type="submit" name="action" value="account:closure" id="submitClosure" class="button is-danger is-dark is-fullwidth animate__animated animate__fadeIn animate__delay-3s" disabled>
                        Erase Profile Permanently
                    </button>
                </form> 
            </article>

        </div>
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const confirmGate = document.getElementById('confirmGate');
    const submitClosure = document.getElementById('submitClosure');

    if (confirmGate && submitClosure) {
        confirmGate.addEventListener('change', (e) => {
            // Button is enabled only if checkbox is checked
            submitClosure.disabled = !e.target.checked;
        });
    }
});
</script>

    <!-- <h1 class="title is-size-2 has-text-grey-light has-text-centered animate__animated animate__fadeInDown" style="padding:0 15px 0; margin-top: 3rem;">
        ✨ By closing your account, we assume you don't need us anymore ✨
    </h1>
        
        <p class="subtitle is-size-5 animate__animated animate__fadeIn animate__delay-1s">
            Goodbye, and best of luck in your career prospects.<br>
            • Your account, resumes and all information will be erased.<br>
            • You won't be able to recover your account later.<br>
            • This action is irreversible.✨
        </p> -->