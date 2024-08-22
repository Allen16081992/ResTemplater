<?php
    // Start a new session if its not active
    if(!isset($_SESSION)) { 
        session_start(); 
    }

    // Handle logout operations
    if (isset($_POST['logout'])) {
        // Wipe everything session related.
        session_unset(); session_destroy();
        header('location: ./index.html');
        exit();
    }

    // Negate session fixation attempts and periodically regenerating the session ID for security.
    function Unauthorized_Access() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = '401: Toegang geweigerd.';
            header('Location: ../login.php');
            exit;
        } else {
            // Regenerate the session ID
            session_regenerate_id(true);
            $_SESSION['last_regen'] = time();
        }
    }

    // Periodically regenerate the session ID to tackle session fixation and session hijacking
    function sessionRegenTimer() {
        $lastRegen = $_SESSION['last_regen'] ?? 0;
        $currentTime = time();
        $regenInterval = 900; // Regenerate every 15 minutes

        if ($currentTime - $lastRegen >= $regenInterval) {
            session_regenerate_id(true);
            $_SESSION['last_regen'] = time();
        }

        // Clean up variables from memory
        unset($lastRegen, $currentTime, $regenInterval);
    }

    // Show the username
    function addUsername() {
        // Check for user name or fallback options
        if (isset($_SESSION['user_name'])) {
            return $_SESSION['user_name'];
        } elseif (isset($_SESSION['firstname'])) {
            return $_SESSION['firstname'];
        } else {
            return "Gebruiker"; // Default fallback
        }
    }

    // Handle page (section) visibility
    function serverHome() {
        $class = "current";
        if (isset($_SESSION['login']) || isset($_SESSION['signup'])) {
            $class = "hidden";
        } 
        return $class;
    }

    function serverLogin() {
        $class = "hidden";
        if (isset($_SESSION['login'])) {
            $class = "current"; unset($_SESSION['login']);
        } 
        return $class;
    }

    function serverSignup() {
        $class = "hidden";
        if (isset($_SESSION['signup'])) {
            $class = "current"; unset($_SESSION['login']);
        } 
        return $class;
    }

    function logoutRequest() {
        // Determine the basename of the current script
        $file = basename($_SERVER['PHP_SELF']);

        // Check if we are on the homepage (index.php)
        if ($file === 'index.php') {
            // Check if the session data has a logged-in state
            if (isset($_SESSION['session_data']) || isset($_SESSION['user_id']) || isset($_SESSION['user_name'])) {
                // Set the logout session & redirect the user
                $_SESSION['logout'] = true;
                header("Location: ./client.php");
                exit(); 
            }
            unset($file);
        } else {
            // Default class for visibility is hidden
            $class = "hidden";
            
            // If the logout session state is set, change class to current
            if (isset($_SESSION['logout'])) {
                $class = "current"; unset($_SESSION['logout']);
            }
            return $class;
        }
    }