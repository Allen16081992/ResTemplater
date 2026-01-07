<?php // Start Session
    require_once './session_manager.conf.php';
    SessionBook::invokeSession();

    // Negate non-POST requests
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $_SESSION['error'] = 405;
        header('Location: ../error.php');
        exit;
    }

    // Whitelisted Keys/Routes
    $routes = [
        'login'  => LoginControl::class,
        'signup' => SignupAction::class
        // ... Add more routes here
    ];

    // User submits a form
    $action = $_POST['action'] ?? '';

    // Verify if submitted action is permitted
    if (!isset($routes[$action])) {
        $_SESSION['error'] = 403;
        header('Location: ../error.php');
        exit;
    }

    // Load PHP files
    require_once './database/v2_db.php';
    // require_once './Model/class_master.php';
    require_once './controller/control_modules.php';

    $pdo = Database::connect();
    $targetClass = $routes[$action];
    $handler = new $targetClass($pdo, $_POST);
    $handler->handle();

    // Dismiss to homepage, Only for signup new user.
    // if ($action === 'signup') {
    //     $_SESSION['success'] = 'Your account has been conjured succesfully. You may log in.';
    //     header('Location: ../views/signup_success.php');
    //     exit;
    // }