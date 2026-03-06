<section id="profile" class="<?= ViewBook::setVisibility('profile'); ?>">
    <article class="profile-wrap">
        <header class="profile-header">
            <label for="upload" class="profile-avatar">             
                <!-- <img src=""> -->
            </label>
            <input type="file" id="upload" name="upload"> 
            <div>
                <h1>Joanna Ross</h1>
                <p>Your job-hunting familiar</p>
                <label class="include-toggle">
                    <input id="av" type="checkbox">
                    <span>Include avatar on resume</span>
                </label>
            </div>
        </header>

        <form id="personal" class="profile-section" action="./config/action_handler.conf.php" method="post">
            <h2>Personal Details</h2>
            <div class="field">
                <div class="control">
                    <label for="fullname" class="label">Full Name</label>
                    <input type="text" id="fullname" name="fullname" class="input" placeholder="..." value="<?= htmlspecialchars($profile['contact']['fullname'] ?? '') ?>" disabled>
                </div>
            </div>

            <div class="field is-grouped">
                <div class="control">
                    <label for="phone" class="label">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="input" placeholder="+31 6 1234 5678" value="<?= htmlspecialchars($profile['contact']['phone'] ?? '') ?>" disabled>
                </div>
                <div class="control">
                    <label for="dateOfBirth" class="label">Date of Birth</label>
                    <input type="date" id="dateOfBirth" class="input" value="<?= htmlspecialchars($profile['account']['birth_date'] ?? '') ?>" disabled>
                </div>
            </div>

            <div class="field is-grouped">
                <div class="control">
                    <label for="city" class="label">City</label>
                    <input type="text" id="city" name="city" class="input" placeholder="Rotterdam" value="<?= htmlspecialchars($profile['contact']['city'] ?? '') ?>" disabled>
                </div>
                <div class="control">
                    <label for="country" class="label">Country</label>
                    <input type="text" id="country" name="country" class="input" placeholder="Netherlands" value="<?= htmlspecialchars($profile['contact']['country'] ?? '') ?>" disabled>
                </div>
            </div>
            <div class="buttons mt-4">
                <button type="button" class="button is-light edit-btn">Edit</button>
                <!-- Save dynamically added by JS -->
            </div>
        </form>

        <form id="account" class="profile-section" action="./config/action_handler.conf.php" method="post">
            <h2>Account Details</h2>
            <div class="field">
                <label for="email" class="label">Email</label>
                <input type="email" id="email" name="email" class="input" placeholder="you@domain.com" value="<?= htmlspecialchars($profile['account']['email'] ?? '') ?>" disabled>
            </div>

            <div class="field">
                <label for="password" class="label">Password</label>
                <input type="password" id="password" name="pwd" class="input" placeholder="••••••••" disabled>
            </div>

            <div class="buttons mt-4">
                <button type="button" class="button is-light edit-btn">Edit</button>
                <!-- Save dynamically added by JS -->
            </div>
        </form>

        <div class="profile-section">     
            <button class="button is-dark is-danger" data-section="closure">Remove Account</button>
        </div>
    </article>
</section>