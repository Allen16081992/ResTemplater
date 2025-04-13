<?php
    // Deny non-POST requests
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        $_SESSION['error'] = '405: Method Not Allowed.';
        header('Location: ../index.php');
        exit;
    }

    // Whitelist and Route map!
    //$allowed = ['login', 'signup', 'savePersonal', 'saveAccount', 'addResume', 'saveCv', 'saveWork', 'saveEdu', 'generatePdf'];
    $allowed = [
        'signup' => ['Classes/signup.class.php', 'controller/signup.control.php', 'signupControl', 'signupUser'],
        'login' => ['Classes/login.class.php', 'controller/login.control.php', 'loginControl', 'verifyUser'],
        // ... Add more routes here
    ];

    // User submits a form
    $action = $_POST['action'] ?? '';

    // Verify if submitted action is permitted
    if (!isset($allowed[$action])) {
        http_response_code(403);
        $_SESSION['error'] = '403: Forbidden. Unknown action.';
        header('Location: ../index.php');
        exit;
    }

    // Destructure and execute
    [$file, $file2, $class, $method] = $allowed[$action];

    // Initialise login class
    require_once "./database/singleton.db.php";
    require_once $file;
    require_once $file2;

    $handler = new $class($_POST);
    $handler->$method();

    // Dismiss to homepage, Only for signup new user.
    if ($action === 'signup') {
        header('Location: ../client.php');
        exit;
    }