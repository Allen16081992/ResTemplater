<section id="profile" class="<?= ViewBook::setView_Error('profile'); ?>">
    <article class="profile-wrap">
        <header class="profile-header">
            <div class="profile-avatar">
                <!-- <img src=""> -->
            </div>
            <div>
                <h1>Joanna Ross</h1>
                <p>Your job-hunting familiar</p>
                <label class="include-toggle">
                    <input type="checkbox" checked>
                    <span>Include avatar on resume</span>
                </label>
            </div>
        </header>

        <section class="profile-section">
            <h2>Personal Details</h2>
            <div class="field is-grouped">
                <div class="control">
                    <label class="label">First Name</label>
                    <input class="input" type="text" placeholder="..." disabled>
                </div>
                <div class="control">
                    <label class="label">Last Name</label>
                    <input class="input" type="text" placeholder="..." disabled>
                </div>
            </div>

            <div class="field is-grouped">
                <div class="control">
                    <label class="label">Phone Number</label>
                    <input class="input" type="tel" placeholder="+31 6 1234 5678" disabled>
                </div>
                <div class="control">
                    <label class="label">Date of Birth</label>
                    <input id="dob" class="input" type="date" disabled>
                </div>
            </div>

            <div class="field is-grouped">
                <div class="control">
                    <label class="label">City</label>
                    <input class="input" type="text" placeholder="Rotterdam" disabled>
                </div>
                <div class="control">
                    <label class="label">Postal Code</label>
                    <input class="input" type="text" placeholder="1234 AB" disabled>
                </div>
            </div>
            <div class="field">
                <label class="label">Country</label>
                <input class="input" type="text" placeholder="Netherlands" disabled>
            </div>
            <div class="buttons mt-4">
                <button class="button is-light edit-btn">Edit</button>
                <!-- <button class="button btn-cta">Save Changes</button>
                <button class="button is-light">Cancel</button> -->
            </div>
            </section>

            <section class="profile-section">
            <h2>Account Access</h2>
            <div class="field">
                <label class="label">Username (optional)</label>
                <input class="input" type="text" placeholder="..." disabled>
            </div>

            <div class="field">
                <label class="label">Email</label>
                <input class="input" type="email" placeholder="you@domain.com" disabled>
            </div>

            <div class="field">
                <label class="label">Password</label>
                <input class="input" type="password" placeholder="••••••••" disabled>
            </div>

            <div class="buttons mt-4">
                <button class="button is-light edit-btn">Edit</button>
                <!-- <button class="button btn-cta">Save Changes</button>
                <button class="button is-light">Cancel</button> -->
            </div>
        </section>
    </article>
</section>