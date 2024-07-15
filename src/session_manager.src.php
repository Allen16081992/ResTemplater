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