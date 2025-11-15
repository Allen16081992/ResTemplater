<?php 
  // Load PHP files
  require_once './src/session_manager.src.php'; 
  // SessionBook::invokeSession();
  // SessionBook::intrusionGuard();
?>
<!DOCTYPE html>
<html lang="eng">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Let's craft your paper for those Blasphemous companies out there.">
    <!-- Social Sharing -->
    <meta property="og:title" content="Paperwitch - Thy Job Scroll Familiar">
    <meta property="og:description" content="PaperWitch is a lightweight resume tooling kit of dutch origin where juniors and students can craft those boring job scrolls.">
    <meta property="og:image" content="assets/images/falcon250.webp">
    <meta property="og:locale" content="eng_ENG">
    <meta property="og:type" content="website">
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon.png">
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Unna:wght@400;700&family=Inter:wght@400;600;800&display=swap">
    <link rel="stylesheet" href="assets/css/paperwitch.css">
    <link rel="stylesheet" href="assets/css/profile.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/> -->
    <title>"A Lightweight Toolkit" | CV Templater</title>
    <!-- Javascript -->
    <script defer src="assets/js/section-handler.js"></script>
    <style>body{height:100vh; }</style>
</head>
<body>
    <?php ViewBook::render('navbar_top.php'); ?>
    <section class="profile-wrap">
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
                <div class="control is-expanded">
                <label class="label">First Name</label>
                <input class="input" type="text" placeholder="..." disabled>
                </div>
                <div class="control is-expanded">
                <label class="label">Last Name</label>
                <input class="input" type="text" placeholder="..." disabled>
                </div>
            </div>

            <div class="field is-grouped">
                <div class="control is-expanded">
                <label class="label">Phone Number</label>
                <input class="input" type="tel" placeholder="+31 6 1234 5678" disabled>
                </div>
                <div class="control is-expanded">
                <label class="label">Date of Birth</label>
                <input id="dob" class="input" type="date" disabled>
                </div>
            </div>

            <div class="field is-grouped">
                <div class="control is-expanded">
                <label class="label">City</label>
                <input class="input" type="text" placeholder="Rotterdam" disabled>
                </div>
                <div class="control is-expanded">
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
    </section>

    <noscript><!-- Als Javascript is uitgeschakeld -->
        <p>Het lijkt erop dat JavaScript is uitgeschakeld in uw browser. Hierdoor werkt de site niet meer.</p>
    </noscript>

    <footer>
        <p>CV Templater © 2023 - 2024</p>
    </footer>

<script>
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".profile-section").forEach(section => {
    const editBtn = section.querySelector(".edit-btn");

    editBtn.addEventListener("click", () => {
      const isEditing = editBtn.textContent === "Cancel";

      if (!isEditing) {
        // Switch to editing mode
        editBtn.textContent = "Cancel";
        // editBtn.classList.remove("is-light");
        // editBtn.classList.add("cancel-btn");

        // Create Save button dynamically
        const saveBtn = document.createElement("button");
        saveBtn.className = "button btn-cta save-btn";
        saveBtn.textContent = "Save Changes";
        section.querySelector(".buttons").appendChild(saveBtn);

        // enable everything EXCEPT dob
        const toggleables = Array.from(section.querySelectorAll("input")).filter(el => el.id !== "dob");
        toggleables.forEach(input => input.removeAttribute("disabled"));

        // Save button logic
        saveBtn.addEventListener("click", () => {
          console.log("Saving changes for section:", section.querySelector("h2").textContent);
            // disable everything EXCEPT dob (dob stays disabled anyway)
            const toggleables = Array.from(section.querySelectorAll("input")).filter(el => el.id !== "dob");
            toggleables.forEach(input => input.setAttribute("disabled", true));

          editBtn.textContent = "Edit";
          editBtn.classList.remove("cancel-btn");
          editBtn.classList.add("is-light");
          saveBtn.remove();
        });
      } else {
        // Cancel editing
        editBtn.textContent = "Edit";
        editBtn.classList.remove("cancel-btn");
        editBtn.classList.add("is-light");

        const saveBtn = section.querySelector(".save-btn");
        if (saveBtn) saveBtn.remove();

        section.querySelectorAll("input").forEach(input => input.setAttribute("disabled", true));
      }
    });
  });
});
</script>
</body>
</html>