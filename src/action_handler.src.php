<?php
    // Negate non-POST requests
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        require_once "session_manager.src.php";
        SessionBook::invokeSession();

        http_response_code(405);
        $_SESSION['error'] = '405: Method Not Allowed.';
        header('Location: ../index.php');
        exit;
    }

    // Whitelist and Route map!
    //$allowed = ['login', 'signup', 'savePersonal', 'saveAccount', 'addResume', 'saveCv', 'saveWork', 'saveEdu', 'generatePdf'];
    $routes = [
        'signup' => ['Classes/signup.class.php', 'controller/signup.control.php', 'signupControl', 'signupUser'],
        'login' => ['Classes/login.class.php', 'controller/login.control.php', 'loginControl', 'loginUser'],
        // ... Add more routes here
    ];

    // User submits a form
    $action = $_POST['action'] ?? '';

    // Verify if submitted action is permitted
    if (!isset($routes[$action])) {
        require_once "session_manager.src.php";
        SessionBook::invokeSession();

        http_response_code(403);
        $_SESSION['error'] = '403: Forbidden. Unknown action.';
        header('Location: ../index.php');
        exit;
    }

    // Destructure and execute
    [$classFile, $controlFile, $classTarget, $method] = $routes[$action];

    // Initialise login class
    require_once "./database/singleton.db.php";
    require_once $classFile;
    require_once $controlFile;

    $handler = new $classTarget($_POST);
    $handler->$method();

    // Dismiss to homepage, Only for signup new user.
    if ($action === 'signup') {
        require_once "session_manager.src.php";
        SessionBook::invokeSession();

        $_SESSION['success'] = 'Your account has been conjured succesfully. You may log in.';
        header('Location: ../index.php');
        exit;
    }

    // echo "Yes, I found my way into helper file.";
    // exit();