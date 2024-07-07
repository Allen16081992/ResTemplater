<?php   
    // These variables are free to use by anything.
    if(isset($_POST['sign_in'])) {

        // Absorb the first part provided data from the registration form.
        $formFields = [
            'email' => $_POST['email'],
            'password' => $_POST['pwd']
        ];

        // Initialise login class
        require_once "database/singleton.db.php"; // (improved) database connection.
        require_once "Classes/account.class.php";
        require_once "controller/login.control.php";

        $login = new loginControl($formFields);

        // Error handlers inside
        $login->loginRequest();

        // When verification completes, open the client environment MyResume.
        header('Location: ../client.php');
        exit();
    }