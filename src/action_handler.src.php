<?php // Load PHP files
    require_once './session_manager.src.php';
    SessionBook::invokeSession();

    // Negate non-POST requests
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        $_SESSION['error'] = '405: Method Not Allowed.';
        header('Location: ../index.php');
        exit;
    }

    // Whitelist and Route map!
    //$allowed = ['login', 'signup', 'savePersonal', 'saveAccount', 'addResume', 'saveCv', 'saveWork', 'saveEdu', 'generatePdf'];
    $routes = [
        'signup' => ['Model/class_master.php', 'controller/control_modules.php', 'signupControl', 'signupUser'],
        'login' => ['Model/class_master.php', 'controller/control_modules.php', 'loginControl', 'loginUser'],
        // ... Add more routes here
    ];

    // User submits a form
    $action = $_POST['action'] ?? '';

    // Verify if submitted action is permitted
    if (!isset($routes[$action])) {
        http_response_code(403);
        $_SESSION['error'] = '403: Forbidden. Unknown action.';
        header('Location: ../index.php');
        exit;
    }

    // Destructure and execute
    [$classFile, $controlFile, $classTarget, $method] = $routes[$action];

    // Initialise login class
    require_once './database/singleton.db.php';
    require_once $classFile;
    require_once $controlFile;

    $handler = new $classTarget($_POST);
    $handler->$method();

    // Dismiss to homepage, Only for signup new user.
    if ($action === 'signup') {
        $_SESSION['success'] = 'Your account has been conjured succesfully. You may log in.';
        header('Location: ../index.php');
        exit;
    }