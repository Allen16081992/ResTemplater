<?php
    // ┌───┐                                                               ┌───┐
    // └─┬─┘  SessionBook organizes session integrity and intrusion logic. └─┬─┘
    //   │      Housing functions that strike down unauthorized access.      │
    // ┌─┴─┐                                                               ┌─┴─┐
    // └───┘                                                               └───┘

    // Handle logout operations
    if (isset($_POST['logout'])) {
        session_unset(); session_destroy();
        header('location: ../index.php');
        exit();
    }

    class SessionBook {
        //────────────────────────────────────//
        //            TOKEN LOGIC             //
        //────────────────────────────────────//
        public static function invokeToken() {
            if (!isset($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
        }

        public static function isValidToken(string $token): bool {
            return hash_equals($_SESSION['csrf_token'] ?? '', $token);
        }

        public static function enforceToken() {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $_SESSION['error'] = 'CSRF token mismatch';
                header('Location: ../client.php');
                exit;
            }
        }
        
        //────────────────────────────────────//
        //           SESSION LOGIC            //
        //────────────────────────────────────//
        public static function invokeSession(): void {
            if(session_status() === PHP_SESSION_NONE) { session_start(); }
            self::invokeToken();
        }

        public static function revokeSession(): void {
            if(session_status() === PHP_SESSION_ACTIVE) { 
                session_unset(); session_destroy();
            }
        }

        // Periodically renew the session ID against session fixation and hijacking
        public static function sessionRegenTimer() {
            $lastRegen = $_SESSION['last_regen'] ?? 0;
            $currentTime = time();
            $regenInterval = 900; // Regenerate every 15 minutes

            if ($currentTime - $lastRegen >= $regenInterval) {
                session_regenerate_id(true);
                $_SESSION['last_regen'] = $currentTime;
            }
            unset($lastRegen, $currentTime, $regenInterval);
        }      

        //────────────────────────────────────//
        //             USER LOGIC             //
        //────────────────────────────────────//
        public static function addUsername() {
            // Check for user name or fallback options
            if (isset($_SESSION['session_data']['user_name'])) {
                return $_SESSION['session_data']['user_name'];
            } elseif (isset($_SESSION['session_data']['firstname'])) {
                return $_SESSION['session_data']['firstname'];
            } else { return "My PaperWitch"; }
        }

        public static function clearUserSession(): void {
            if (isset($_SESSION['session_data']['user_id'])) {
                unset($_SESSION['session_data']['user_id']);
                unset($_SESSION['session_data']['user_name']);
            }
        }

        // rate limiter against brute-force attacks, bot abuse, spamming form submissions
        public static function throttleLogin(int $cooldown = 30): void {
            if (isset($_SESSION['last_login_attempt']) && time() - $_SESSION['last_login_attempt'] < $cooldown) {
                $_SESSION['login'] = true;
                $_SESSION['error'] = "401: You're trying too quickly. Wait $cooldown sec.";
                header('Location: ../index.php');
                die;
            }
            $_SESSION['last_login_attempt'] = time();
        }  

        public static function intrusionGuard(): void {
            if (!isset($_SESSION['session_data']['user_id'])) {
                $_SESSION['error'] = '401: Access denied.';
                header('Location: ../index.php');
                die;
            }
            session_regenerate_id(true);
            $_SESSION['last_regen'] = time();
        }
    }

    // ┌───┐                                                       ┌───┐
    // └─┬─┘      UIBook organizes visual visibility logic.        └─┬─┘
    //   │    Housing functions that can enforce a specific page.    │
    // ┌─┴─┐                                                       ┌─┴─┐
    // └───┘                                                       └───┘
    
    class UIBook {
        //────────────────────────────────────//
        //          VISIBILITY LOGIC          //
        //────────────────────────────────────//

        // Page (section) visibility enforcers.
        public static function Homepage() {
            $class = "current";
            if (isset($_SESSION['login']) || isset($_SESSION['signup'])) {
                $class = "hidden";
            } 
            return $class;
        }
    
        public static function isVisible(string $key): string {
            if (isset($_SESSION[$key])) {
                unset($_SESSION[$key]);
                return 'current';
            }
            return 'hidden';
        }
    }