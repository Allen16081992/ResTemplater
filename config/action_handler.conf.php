<?php // Start Session
    require_once './session_manager.conf.php';
    SessionBook::invokeSession();

    // Negate non-POST requests
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $_SESSION['error'] = 405;
        header('Location: ../error.php');
        exit;
    }

    // CSRF-Authentication
    SessionBook::enforceToken();
    
    // Whitelisted Keys and Routes
    $routes = [
        'login'  => loginControl::class,  // DONE
        'signup' => signupControl::class, // DONE
        'account'=> accountControl::class,// DONE
        'closure'=> accountControl::class,// DONE
        'resume' => resumeControl::class, // DONE
        // Resume parts
        // 'experience' => experienceControl::class,
        // 'education' => educationControl::class,
        // 'skills' => skillsControl::class,
        // 'social' => socialControl::class,
        // ... Add more routes here
        'wizard' => wizardControl::class  // DONE
    ];

    // User submits a form
    $action = $_POST['action'] ?? '';
    if (!is_string($action)) $action = '';
    $action = trim($action);

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
    require_once './controller/wizard_contr.php';

    // Initialise Class
    $targetClass = $routes[$action];
    $handler = new $targetClass($_POST);
    $handler->handle();
    exit;