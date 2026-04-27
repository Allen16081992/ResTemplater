<?php // PHP Files
    require_once __DIR__ . '/autoloader.conf.php'; 
    require_once __DIR__ . '/session_manager.conf.php';
    require_once __DIR__ . '/validGrimoire.conf.php';

    // 1. Session Mechanics
    SessionBook::invokeSession();
    SessionBook::enforceToken();

    // 2. Negate non-POST Requests
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $_SESSION['error'] = 405;
        header('Location: ../error.php');
        exit;
    }

    // 3. Routing Table
    $routes = [
        'login'    => 'loginControl',
        'signup'   => 'signupControl',
        'account'  => 'userControl',
        'contact'  => 'userControl',
        'resume'   => 'resumeControl',
        'template' => 'templateControl',
        'experience'=> 'experienceControl',
        'education' => 'educationControl',
        'projects'  => 'projectControl',
        'skill'     => 'skillControl',
        'social'    => 'socialControl',
        'wizard'    => 'wizardControl'
    ];

    $action = $_POST['action'] ?? '';
    if (!is_string($action)) $action = '';
    $action = trim($action);
    
    if (!isset($routes[$action])) {
        $_SESSION['error'] = 403;
        header('Location: ../error.php');
        exit;
    }
    
    // 4. Requires are now handled by our autoloader
    $targetClass = $routes[$action];

    // 5. Invoke Class & Function
    $handler = new $targetClass($_POST);
    $handler->handle();
    exit;