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

        <form id="personal" class="profile-section">
            <h2>Personal Details</h2>
            <div class="field is-grouped">
                <div class="control">
                    <label for="firstname" class="label">First Name</label>
                    <input type="text" id="firstname" name="firstname" class="input" placeholder="..." disabled>
                </div>
                <div class="control">
                    <label for="lastname"class="label">Last Name</label>
                    <input type="text" id="lastname" name="lastname" class="input" placeholder="..." disabled>
                </div>
            </div>

            <div class="field is-grouped">
                <div class="control">
                    <label for="phone" class="label">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="input" placeholder="+31 6 1234 5678" disabled>
                </div>
                <div class="control">
                    <label for="dateOfBirth" class="label">Date of Birth</label>
                    <input type="date" id="dateOfBirth" class="input" disabled>
                </div>
            </div>

            <div class="field is-grouped">
                <div class="control">
                    <label for="city" class="label">City</label>
                    <input type="text" id="city" name="city" class="input" placeholder="Rotterdam" disabled>
                </div>
                <div class="control">
                    <label for="postcode" class="label">Postal Code</label>
                    <input type="text" id="postcode" name="postcode" class="input" placeholder="1234 AB" disabled>
                </div>
            </div>
            <div class="field">
                <label for="country" class="label">Country</label>
                <input type="text" id="country" name="country" class="input" placeholder="Netherlands" disabled>
            </div>
            <div class="buttons mt-4">
                <button type="button" name="action" value="profile" class="button is-light edit-btn">Edit</button>
                <!-- Save dynamically added by JS -->
            </div>
        </form>

        <form id="account" class="profile-section">
            <h2>Account Details</h2>
            <div class="field">
                <label for="email" class="label">Email</label>
                <input type="email" id="email" name="email" class="input" placeholder="you@domain.com" disabled>
            </div>

            <div class="field">
                <label for="password" class="label">Password</label>
                <input type="password" id="password" name="pwd" class="input" placeholder="••••••••" disabled>
            </div>

            <div class="buttons mt-4">
                <button type="button" name="action" value="profile" class="button is-light edit-btn">Edit</button>
                <!-- Save dynamically added by JS -->
            </div>
        </form>

        <div class="profile-section">     
            <button class="button is-dark is-danger" data-section="closure">Remove Account</button>
        </div>
    </article>
</section>