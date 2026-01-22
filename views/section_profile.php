<section id="profile" class="<?= ViewBook::setVisibility('profile'); //ViewBook::setView_Error('profile'); ?>">
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
                    <input type="checkbox" checked>
                    <span>Include avatar on resume</span>
                </label>
            </div>
        </header>

        <form id="personal" class="profile-section">
            <h2>Personal Details</h2>
            <div class="field is-grouped">
                <div class="control">
                    <label for="firstname" class="label">First Name</label>
                    <input type="text" id="firstname" class="input" placeholder="..." disabled>
                </div>
                <div class="control">
                    <label for="lastname"class="label">Last Name</label>
                    <input type="text" id="lastname" class="input" placeholder="..." disabled>
                </div>
            </div>

            <div class="field is-grouped">
                <div class="control">
                    <label for="phone" class="label">Phone Number</label>
                    <input type="tel" id="phone" class="input" placeholder="+31 6 1234 5678" disabled>
                </div>
                <div class="control">
                    <label for="dateOfBirth" class="label">Date of Birth</label>
                    <input type="date" id="dateOfBirth" class="input" disabled>
                </div>
            </div>

            <div class="field is-grouped">
                <div class="control">
                    <label for="city" class="label">City</label>
                    <input type="text" id="city" class="input" placeholder="Rotterdam" disabled>
                </div>
                <div class="control">
                    <label for="postcode" class="label">Postal Code</label>
                    <input type="text" id="postcode" class="input" placeholder="1234 AB" disabled>
                </div>
            </div>
            <div class="field">
                <label for="country" class="label">Country</label>
                <input type="text" for="country" class="input" placeholder="Netherlands" disabled>
            </div>
            <div class="buttons mt-4">
                <button type="button" class="button is-light edit-btn">Edit</button>
                <!-- Save dynamically added by JS -->
            </div>
        </form>

        <form id="account" class="profile-section">
            <h2>Account Details</h2>
            <div class="field">
                <label for="username" class="label">Username (optional)</label>
                <input type="text" id="username" class="input" placeholder="..." disabled>
            </div>

            <div class="field">
                <label for="email" class="label">Email</label>
                <input type="email" id="email" class="input" placeholder="you@domain.com" disabled>
            </div>

            <div class="field">
                <label for="password" class="label">Password</label>
                <input type="password" id="password" class="input" placeholder="••••••••" disabled>
            </div>

            <div class="buttons mt-4">
                <button type="button" class="button is-light edit-btn">Edit</button>
                <!-- Save dynamically added by JS -->
            </div>
        </form>
    </article>
</section>