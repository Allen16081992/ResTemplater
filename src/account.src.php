<?php  
    // Verify which form was sumitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Absorb form data
        if (isset($_POST['loginBtn'])) {  
            $formFields = [
                'email' => $_POST['email'],
                'pwd' => $_POST['pwd'],
                'loginBtn' => $_POST['loginBtn']
            ];

        } elseif (isset($_POST['signupBtn'])) {
            $formFields = [
                'firstname' => $_POST['firstname'],
                'lastname' => $_POST['lastname'],
                'birth' => $_POST['day'].'/'.$_POST['month'].'/'.$_POST['year'],
                'phone' => $_POST['phone'],
                'city' => $_POST['city'],
                'postal' => $_POST['postal'],
                'email' => $_POST['email'],
                'pwd' => $_POST['pwd'],
                'signupBtn' => $_POST['signupBtn'],
            ];
    
            if (isset($_POST['country'])) {
                $formFields['country'] = $_POST['country'];
            }
            if (isset($_POST['username'])) {
                $formFields['username'] = $_POST['username'];
            }

        } elseif (isset($_POST['saveInfo'])) {
            $formFields = [
                'firstname' => $_POST['firstname'],
                'lastname' => $_POST['lastname'],
                'postal' => $_POST['postal'],
                'city' => $_POST['city'],
                'country' => $_POST['country'],
                'phone' => $_POST['phone'],
                'uid' => $_POST['uid'],
                'saveInfo' => $_POST['saveInfo']
            ];

        } elseif (isset($_POST['saveAccount'])) { 
            $formFields = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'pwd' => $_POST['pwd'],
                'birth' => $_POST['day'].'/'.$_POST['month'].'/'.$_POST['year'],
                'uid' => $_POST['uid'],
                'saveAccount' => $_POST['saveAccount']
            ];

        } elseif (isset($_POST['trashAccount'])) { 
            $formFields = [
                'pwd' => $_POST['pwd'],
                'uid' => $_POST['uid'],
                'saveAccount' => $_POST['saveAccount']
            ];

        }

        // Load PHP files
        require_once 'session_manager.src.php';
        require_once 'classes/account.class.php';
        require_once 'controller/account.control.php';

        // Initialise classes 
        $parse = new AccountControl($formFields);
        $parse->verifyData();
    }