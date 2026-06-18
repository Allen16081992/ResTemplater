<?php // PHP Files
    require_once __DIR__ . '/autoloader.php'; 
    require_once __DIR__ . '/session_manager.php';
    require_once __DIR__ . '/validGrimoire.php';

    // 1. Call Session Mechanics
    SessionBook::invokeSession();
    SessionBook::enforceToken();

    // 2. Negate non-POST Requests
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $_SESSION = [];
        $_SESSION['error'] = 405;
        header('Location: ../error.php');
        exit;
    }

    /*=============================================
    * SNAPSHOT HANDLER (The Action Handler)
    * Format: "module:intent" (e.g. "resume:create")
    * Snapshot: Everything before the colon.
    ============================================*/

    // Sanitize
    $action = trim((string)($_POST['action'] ?? ''));

    // 3. Take a Snapshot before ':'
    $snapshot = strstr($action, ':', true) ?: $action;

    // 4. Routing Table (Whitelist)
    $routes = [
        'login'     => 'loginControl',
        'sign_up'   => 'signupControl',
        'account'   => 'userControl',
        'contact'   => 'userControl',
        'resume'    => 'resumeControl',
        'template'  => 'templateControl',
        'experience'=> 'experienceControl',
        'education' => 'educationControl',
        'projects'  => 'projectControl',
        'skills'     => 'skillControl',
        'socials'    => 'socialControl',
        'wizard'    => 'wizardControl'
    ];

    if (isset($routes[$snapshot])) {
        $targetClass = $routes[$snapshot];

        // 5. Requires are handled by our "Aggressive Autoloader"
        if (class_exists($targetClass)) {
            $ritual = new $targetClass($_POST);
            $ritual->handle();
            exit;

        } else { // file/class missing.
            $_SESSION['error'] = "Unable to find [$targetClass].";
        }
    } else { // Error: Module not in whitelist.
        $_SESSION['error'] = "Forbidden: The pendulum cannot swing to [$snapshot].";
    }

    // If we reach this point, something failed.
    header('Location: ../client.php');
    exit;

    // Testing Variables
    // echo "<pre>POST Token: "; 
    // var_dump($_POST['csrf_token'] ?? 'NOT SET'); 
    // echo "<br>SESSION Token: "; 
    // var_dump($_SESSION['csrf_token'] ?? 'NOT SET'); 
    // echo "</pre>";
    // die("Stopping here to see tokens.");