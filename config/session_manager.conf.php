<?php
    // Handle logout operations
    if (isset($_POST['logout'])) {
        SessionBook::revokeSession();
        header('location: ../index.php');
        exit();
    }
    
    // ┌───┐                                                                      ┌───┐
    // └─┬─┘  SessionBook handles sessions, security, and application integrity.  └─┬─┘
    //   │    Handles CSRF tokens, throttling, and intrusion control.               │
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
                ViewBook::revert(''); 
            }
            if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
                $_SESSION['error'] = '403: Forbidden. Request Denied.';
                ViewBook::revert('');
            }
        }

        public static function csrfField(): string {
            self::invokeToken();
            $t = htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8');
            return '<input type="hidden" name="csrf_token" value="'.$t.'">';
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
                    'samesite' => 'Lax', 
                ]);
                ini_set('session.use_strict_mode', '1');
                session_start(); 
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
                'user_id' => (int)$user['id'],
                'firstname' => (string)$user['firstname']
            ];
        }

        public static function clearUserSession(): void {
            $keys = ['session_data', 'action', 'login', 'signup', 'old'];
            foreach ($keys as $key) { unset($_SESSION[$key]); }
        }

        public static function clearPublicState(): void {
            unset($_SESSION['error'], $_SESSION['action'], $_SESSION['form_old']);
            // optionally unset other UI-only keys
        }

        public static function intrusionGuard(): void {
            if (!isset($_SESSION['session_data']['user_id'])) {
                $_SESSION['error'] = '401: Access denied.';
                header('Location: ../index.php');
                exit;
            }
        }

        public static function onLoginSuccess(): void {
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
    }
    
    //   /##     /##   /##   /##########   /##            /##      /########     /#######     /#######    /##   /##
    //  / ##    / ##  / ##  / ##______/   / ##           / ##     / ##___  ##   /##___  ##   /##___  ##  / ##  /##
    //  | ##    | ##  |__/  | ##          | ##     /#    | ##     | ##   | ##  | ##   \ ##  | ##   | ##  | ## /##
    //  | ##    | ##   /##  | ########    | ##    / #    | ##     | ########   | ##   | ##  | ##   | ##  | #####
    //   \ ##  / ##/  / ##  | ##____/      \ ##  | ###  / ##      | ##___  ##  | ##   | ##  | ##   | ##  | ##_ ##
    //    \ ##  ##/   | ##  | ##            \ ##/ ## ##/ ##       | ##   \ ##  | ##   | ##  | ##   | ##  | ## \ ##
    //     \ ####/    | ##  | ##########     \ #### \ ####        | ########/  |  #######/  |  #######/  | ##  \ ##
    //      \___/     |__/  |__________/      \__/   \__/         |________/   \________/   \________/   |__/   \_/

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
            if ($view == 'profile' || $view == 'resume') {
                header('Location: ../client.php'); exit();
            } else {
                header('Location: ../index.php'); exit();
            }
        }

        public static function flashMessage() {
            echo '<div id="server-msg">';
            if (isset($_SESSION['error'])) {
                echo '<div class="error animate__animated animate__bounceInDown">'.$_SESSION['error'].'</div>';
            } elseif (isset($_SESSION['success'])) {
                echo '<div class="success animate__animated animate__bounceInDown">'.$_SESSION['success'].'</div>';
            }
            echo '</div>';
        }

        //────────────────────────────────────//
        //          MESSAGING LOGIC           //
        //────────────────────────────────────//
        public static function flashForm(array $formData): void {
            $_SESSION['form_old'] = $formData;
        }

        //────────────────────────────────────//
        //             USER LOGIC             //
        //────────────────────────────────────//
        public static function addUsername() {
            // Check for user name or fallback options
            if (isset($_SESSION['session_data']['firstname'])) {
                return $_SESSION['session_data']['firstname'];
            } else { return "Profile"; }
        }
    }