<?php
    // Handle logout operations
    if (isset($_POST['logout'])) {
        SessionBook::revokeSession();
        header('location: ../index.php');
        exit();
    }
    // in a controller, just shove data in session.
    function flashForm(array $formData): void {
        $_SESSION['form_old'] = $formData;
    }
    
    // ┌───┐                                                                      ┌───┐
    // └─┬─┘  SessionBook handles sessions, security, and application integrity.  └─┬─┘
    //   │    Handles CSRF tokens, flash data, throttling, and intrusion control.   │
    // ┌─┴─┐                                                                      ┌─┴─┐
    // └───┘                                                                      └───┘

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

        public static function enforceToken(): void {
            if (!isset($_POST['csrf_token'])) {
                // Token missing
                $_SESSION['error'] = '403: Forbidden. Request Denied.';
                header('Location: ../client.php');
                exit;
            }
        
            if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
                // Token mismatch
                $_SESSION['error'] = 'Something went wrong. It might just need a kick. Please try again.';
                header('Location: ../client.php');
                exit;
            }
        }
        
        //────────────────────────────────────//
        //           SESSION LOGIC            //
        //────────────────────────────────────//
        public static function invokeSession(): void {
            if(session_status() === PHP_SESSION_NONE) { 
                $flag = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
                session_set_cookie_params([
                    'lifetime' => 0,
                    'path' => '/',
                    'domain' => '',
                    'secure' => $flag, // Dynamically enforce
                    'httponly' => true, 
                    'samesite' => 'Strict', 
                ]);
                session_start(); 
            }

            // Only call token when needed
            if (in_array(basename($_SERVER['PHP_SELF']), ['index.php', 'client.php'])) {
                self::invokeToken();
            }
        }

        public static function clearUserSession(): void {
            $keys = ['session_data', 'login', 'account', 'signup'];
            foreach ($keys as $key) { unset($_SESSION[$key]); }
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
        //          MESSAGING LOGIC           //
        //────────────────────────────────────//
        public static function flash(string $key): ?string {
            if (isset($_SESSION[$key])) {
                $msg = $_SESSION[$key];
                unset($_SESSION[$key]);
                return $msg;
            }
            return null;
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
            } else { return "Profile"; }
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
    
    // ┌───┐                                                            ┌───┐
    // └─┬─┘   ViewBook handles view rendering and section visibility.  └─┬─┘
    //   │     Load views and control what the user sees per session.     │
    // ┌─┴─┐                                                            ┌─┴─┐
    // └───┘                                                            └───┘

    class ViewBook {
        //────────────────────────────────────//
        //          VISIBILITY LOGIC          //
        //────────────────────────────────────//

        public static function Homepage(): string {
            if (isset($_SESSION['login']) || isset($_SESSION['signup']) || isset($_SESSION['success'])) {
                return 'hidden';
            }
            return 'current';
        }        
    
        public static function setView_Error(string $key): string {
            //return isset($_SESSION[$key]) ? 'current' : 'hidden';
            if (isset($_SESSION[$key])) {
                unset($_SESSION[$key]);
                return 'current';
            }
            return 'hidden';
        }
   
        public static function render(string $view, array $data = []): void {
            extract($data); // all sorts of data
            require_once './views/'.$view; // file path
        }
    }