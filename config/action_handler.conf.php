<?php // Start Session
    require_once './session_manager.conf.php';
    SessionBook::invokeSession();

    // Negate non-POST requests
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $_SESSION['error'] = 405;
        header('Location: ../error.php');
        exit;
    }

    // Whitelisted Keys and Routes
    $routes = [
        'login'  => loginControl::class,
        'signup' => signupControl::class,
        'profile'=> profileControl::class,
        'resume' => resumeControl::class
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
    require_once './controller/user_contr.php';
    require_once './controller/resume_contr.php';

    // $pdo = Database::connect();
    $targetClass = $routes[$action];
    $handler = new $targetClass($_POST);
    $handler->handle();
    exit;