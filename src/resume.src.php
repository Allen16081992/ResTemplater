<?php
    // Verify which form was sumitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

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

        // Load PHP files
        require_once 'session_manager.src.php';
        //require_once 'classes/resume.class.php';
        require_once 'controller/resume.control.php';

        // Initialise classes
        $rs = new ResumeControl($formFields);
        $rs->verifyInfo();
    }