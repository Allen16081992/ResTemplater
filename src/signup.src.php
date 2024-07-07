<?php
    // These variables are free to use by anything.
    if(isset($_POST['sign_up'])) {

        // Absorb the first part provided data from the registration form.
        $formFields = [
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'phone' => $_POST['phone'],
            'city' => $_POST['city'],
            'postal' => $_POST['postal'],
            'email' => $_POST['email'],
            'password' => $_POST['password']
        ];

        if (isset($_POST['country'])) {
            $formFields['country'] = $_POST['country'];
        } 

        if (isset($_POST['username'])) {
            $formFields['username'] = $_POST['username'];
        }

        if (isset($_POST['day']) && isset($_POST['month']) && isset($_POST['year'])) {
            $formFields['birth'] = $_POST['day'].'/'.$_POST['month'].'/'.$_POST['year'];
        } else {
            // Push the server error
            $serverMsg = 'Geboortedatum is verplicht.';
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($serverMsg);
            exit();
        }

        // Initialise login class
        require_once "database/singleton.db.php"; // (improved) database connection.
        //require_once "Classes/account.class.php";
        //require_once "controller/signup.control.php";

        //$signup = new signupControl($formFields);

        // Error handlers inside
        //$signup->signupRequest();

        // When verification completes, open the client environment MyResume.
        header('Location: ../client.php');
        exit();
    }