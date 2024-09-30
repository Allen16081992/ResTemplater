<?php  
    // Verify which form was sumitted
    if (isset($_POST['loginBtn']) || isset($_POST['signupBtn'])) {

        // Load PHP files
        require_once './session_manager.src.php';
        require_once './classes/account.class.php';
        require_once './controller/account.control.php';

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
                'signupBtn' => $_POST['signupBtn']
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

        // Initialise classes 
        $parse = new AccountControl($formFields);
        $parse->verifyData();

    } elseif (isset($_POST['creResume']) || isset($_POST['saveCv']) || isset($_POST['saveAv'])) {

        // Absorb form data
        if (isset($_POST['creResume'])) {
            $formFields['cvname'] = $_POST['cvname'];

        } elseif (isset($_POST['saveCv'])) {
            $formFields = [
                'resid' => $_POST['resid'],
                'resumetitle' => $_POST['resumetitle'],
                'saveCv' => $_POST['saveCv']
            ];
            
        } elseif(isset($_POST['saveAv'])) {
            $formFields = [
                'file-upload' => $_FILES['file-upload'],
                'resumeID' => $_POST['resumeID'],
                'userID' => $_POST['userID']
            ];

        } elseif(isset($_POST['saveWork'])) {
            $formFields = [
                'worktitle' => $_POST['worktitle'],
                'company' => $_POST['company'],
                'join_day' => $_POST['join_day'],
                'join_month' => $_POST['join_month'],
                'join_year' => $_POST['join_year'],
                'leave_day' => $_POST['leave_day'],
                'leave_month' => $_POST['leave_month'],
                'leave_year' => $_POST['leave_year'],
                'workdesc' => $_POST['workdesc'],
                'workid' => $_POST['workid'],
                'resumeID' => $_POST['resumeID'],
                'userID' => $_POST['userID']
            ];
            
        } elseif(isset($_POST['saveEdu'])) {
            $formFields = [
                'edutitle' => $_POST['edutitle'],
                'company' => $_POST['company'],
                'join_day' => $_POST['join_day'],
                'join_month' => $_POST['join_month'],
                'join_year' => $_POST['join_year'],
                'leave_day' => $_POST['leave_day'],
                'leave_month' => $_POST['leave_month'],
                'leave_year' => $_POST['leave_year'],
                'edudesc' => $_POST['edudesc'],
                'eduid' => $_POST['eduid'],
                'resumeID' => $_POST['resumeID'],
                'userID' => $_POST['userID']
            ];
            
        } elseif(isset($_POST['default'])) { 
            unset($_POST['default']);
            header('location: ./download.src.php');
            exit();
        } elseif(isset($_POST['business'])) {
            unset($_POST['business']);
            header('location: ./download2.src.php');
            exit();
        } elseif(isset($_POST['careertiger'])) {
            unset($_POST['careertiger']);
            header('location: ./download3.src.php');
            exit();
        }

    }