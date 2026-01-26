<?php
    // Handle logout operations
    if (isset($_POST['logout'])) {
        SessionBook::revokeSession();
        header('location: ../index.php');
        exit();
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
                $_SESSION['error'] = '403: Forbidden. Request Denied.';
                header('Location: ../client.php');
                exit;
            }
            if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
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

        public static function revokeSession(): void {
            if(session_status() === PHP_SESSION_ACTIVE) { 
                session_unset(); session_destroy();
            }
        }

        public static function setUserSession($user): void {
            $_SESSION['session_data'] = [
                'user_id' => (int)$user['userID'],
                'username' => (string)$user['username'] ?? '',
                'fullname' => (string)$user['fullname']
            ];
        }

        public static function clearUserSession(): void {
            $keys = ['session_data', 'action', 'login', 'signup', 'old'];
            foreach ($keys as $key) { unset($_SESSION[$key]); }
        }

        public static function clearPublicState(): void {
            unset($_SESSION['error'], $_SESSION['flash'], $_SESSION['action'], $_SESSION['form_old']);
            // optionally unset other UI-only keys
        }

        public static function intrusionGuard(): void {
            if (!isset($_SESSION['session_data']['user_id'])) {
                $_SESSION['error'] = '401: Access denied.';
                header('Location: ../index.php');
                exit;
            }
            session_regenerate_id(true);
            $_SESSION['last_regen'] = time();
        }

        // Rate limiter against brute-force attacks, bot abuse, spamming form submissions
        public static function throttleLogin(int $cooldown = 30): void {
            if (isset($_SESSION['last_login_attempt']) && time() - $_SESSION['last_login_attempt'] < $cooldown) {
                $_SESSION['login'] = true;
                $_SESSION['error'] = "401: You're trying too quickly. Wait $cooldown sec.";
                header('Location: ../index.php');
                exit;
            }
            $_SESSION['last_login_attempt'] = time();
        }
        
        //────────────────────────────────────//
        //             USER LOGIC             //
        //────────────────────────────────────//
        public static function addUsername() {
            // Check for user name or fallback options
            if (isset($_SESSION['session_data']['username'])) {
                return $_SESSION['session_data']['username'];
            } elseif (isset($_SESSION['session_data']['firstname'])) {
                return $_SESSION['session_data']['firstname'];
            } else { return "Profile"; }
        }
    }
    
    //   /##     /##   /##   /##########   /##            /##      /########
    //  / ##    / ##  / ##  / ##______/   / ##           / ##     / ##___  ##
    //  | ##    | ##  |__/  | ##          | ##     /#    | ##     | ##   | ##
    //  | ##    | ##   /##  | ########    | ##    / #    | ##     | ########
    //   \ ##  / ##/  / ##  | ##____/      \ ##  | ###  / ##      | ##___  ##
    //    \ ##  ##/   | ##  | ##            \ ##/ ## ##/ ##       | ##   \ ##
    //     \ ####/    | ##  | ##########     \ #### \ ####        | ########/ 
    //      \___/     |__/  |__________/      \__/   \__/         |________/

    // ┌───┐                                                             ┌───┐
    // └─┬─┘   ViewBook provides view rendering, redirects, and section  └─┬─┘
    //   │     visibility helpers based on session UI state.               │
    // ┌─┴─┐                                                             ┌─┴─┐
    // └───┘                                                             └───┘

    class ViewBook {
        //────────────────────────────────────//
        //          VISIBILITY LOGIC          //
        //────────────────────────────────────//
        public static function setVisibility(string $key, string $default = 'home'): string {
            // Catch UI state and abolish the superglobal
            $action = $_SESSION['action'] ?? $default;
            return ($action === $key) ? 'current' : 'hidden';
        }
   
        public static function render(string $view, array $data = []): void {
            extract($data); // all sorts of data
            require_once './views/'.$view; // file path
        }

        public static function revert(string $view) : void {
            // Read previous UI state from submit button
            $_SESSION['action'] = $view;
            header('Location: ../index.php'); exit();
        }

        //────────────────────────────────────//
        //          MESSAGING LOGIC           //
        //────────────────────────────────────//
        public static function flashForm(array $formData): void {
            $_SESSION['form_old'] = $formData;
        }
    }