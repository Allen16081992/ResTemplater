<?php   
    // These variables are free to use by anything.
    if(isset($_POST['submit'])) {

        // Absorb the first part provided data from the registration form.
        $formFields = [
            'uid' => $_POST['username'],
            'password' => $_POST['pwd']
        ];

        // Initialise login class
        require_once "database/singleton.db.php"; // (improved) database connection.
        //require_once "Classes/login.class.config.php";
        require_once "controller/login.control.php";

        $login = new loginControl($formFields);

        // Error handlers inside
        $login->loginRequest();

        // When verification completes, open the client environment MyResume.
        header('Location: ../client.php');
        exit();
    }