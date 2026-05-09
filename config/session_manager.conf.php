<?php
    // ┌───┐                                                                      ┌───┐
    // └─┬─┘  SessionBook handles sessions, security, and application integrity.  └─┬─┘
    //   │    Handles CSRF tokens, throttling, and intrusion control.               │
    // ┌─┴─┐                                                                      ┌─┴─┐
    // └───┘                                                                      └───┘

    class SessionBook {  
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
                    //'httponly' => true, 
                    'samesite' => 'Lax', 
                ]);
                ini_set('session.use_strict_mode', '1');
                session_start(); 
            }
        }

        public static function verifySession(): void {
            if (isset($_SESSION['session_data']['user_id'])) {
                $_SESSION['action'] = 'home';
            } else {
                $_SESSION['action'] = 'wizard';
            }
        }

        public static function intrusionGuard(): void {
            self::invokeSession();
            if (!isset($_SESSION['session_data']['user_id'])) {
                $_SESSION['error'] = '401: Access denied.';
                header('Location: ../index.php');
                exit;
            }
        }

        public static function sessionRegenTimer() {
            // Periodically renew the session ID against session fixation and hijacking
            $lastRegen = $_SESSION['last_regen'] ?? 0;
            $currentTime = time();
            $regenInterval = 900; // Regenerate every 15 minutes

            if ($currentTime - $lastRegen >= $regenInterval) {
                session_regenerate_id(true);
                $_SESSION['last_regen'] = $currentTime;
            }
            unset($lastRegen, $currentTime, $regenInterval);
        }

        public static function logoutUser(): void {
            self::invokeSession();

            // Clear session data
            $_SESSION = [];

            // Delete session cookie
            if (ini_get('session.use_cookies')) {
                $p = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $p['path'],
                    $p['domain'],
                    $p['secure'],
                    $p['httponly']
                );
            }

            // Destroy session
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_destroy();
            }
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
        //            TOKEN LOGIC             //
        //────────────────────────────────────//
        public static function invokeToken() {
            self::invokeSession();
            if (!isset($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
        }

        public static function isValidToken(string $token): bool {
            self::invokeSession();
            return hash_equals($_SESSION['csrf_token'] ?? '', $token);
        }

        public static function enforceToken(): void {
            self::invokeSession();
            $token = $_POST['csrf_token'] ?? '';
            if (!is_string($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
                $_SESSION['error'] = '403: Forbidden. Request Denied.';
                ViewBook::revert('error');
            }
        }

        public static function csrfField(): string {
            self::invokeToken();
            $t = htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8');
            return '<input type="hidden" name="csrf_token" value="'.$t.'">';
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
        private static function sanitize(mixed $data): mixed {
            if (is_array($data)) {
                return array_map([self::class, 'sanitize'], $data);
            }
            return is_string($data) ? htmlspecialchars($data, ENT_QUOTES, 'UTF-8') : $data;
        }

        public static function setVisibility(string $key, string $default = 'home'): string {
            // Catch UI state and abolish the superglobal
            $action = $_SESSION['action'] ?? $default;
            // unset($_SESSION['action']);
            return ($action === $key) ? 'current' : 'hidden';
        }
   
        public static function render(string $view, array $data = []): void {
            $safeData = self::sanitize($data);
            extract($safeData);
            require_once './views/'.$view; // file path
        }

        public static function revert(string $view) : void {
            // Read previous UI state from submit button
            $_SESSION['action'] = $view;
            if (in_array($view, ['profile', 'wizard', 'builder'])) {
                header('Location: ../client.php'); 
            } elseif (in_array($view, ['error'])) {
                header('Location: ../error.php'); 
            } else {
                header('Location: ../index.php'); 
            }
            exit;
        }

        public static function setEditor() {
            $_SESSION['action'] = $_POST['action'] ?? '';
            unset($_POST['action']);  
        }

        //────────────────────────────────────//
        //          MESSAGING LOGIC           //
        //────────────────────────────────────//
        public static function getError(string $field): string {
            $error = $_SESSION['error'][$field] ?? '';
            // Clear it after reading so it doesn't persist forever
            unset($_SESSION['error'][$field]); 
            return htmlspecialchars($error, ENT_QUOTES, 'UTF-8');
        }

        public static function flashForm(array $formData): void {
            $_SESSION['form_old'] = $formData;
        }

        public static function setOldForm(string $key, string $default = ''): string {
            SessionBook::invokeSession();
            $val = $_SESSION['form_old'][$key] ?? $default;
            self::sanitize($val);
            return (string)$val;
        }

        public static function clearOldForm(): void {
            unset($_SESSION['form_old']);
        }

        public static function flashMessage(): void {
            SessionBook::invokeSession();
            $error = $_SESSION['error'] ?? null;
            $success = $_SESSION['success'] ?? null;

            echo '<div id="server-msg">';
            if (is_string($error)) {
                echo '<div class="error animate__animated animate__bounceInDown">'.htmlspecialchars($error, ENT_QUOTES, 'UTF-8').'</div>';
                unset($_SESSION['error']);
            } elseif (is_string($success)) {
                echo '<div class="success animate__animated animate__bounceInDown">'.htmlspecialchars($success, ENT_QUOTES, 'UTF-8').'</div>';
                unset($_SESSION['success']);
            }
            echo '</div>';
        }

        public static function glyphByID(int $id): string {
            $glyphs = ['✨', '📜', '🪄', '🧠', '🧪', '✨', '⚔️', '🛡️', '📖', '🏺', '🔮', '🦇', '🕯️'];
            
            // Use the ID to pick an index from the array
            $index = $id % count($glyphs); 
            return $glyphs[$index];
        }

        public static function glyphByName(string $title): string {
            $map = [
                'frontend' => '🪄',
                'research' => '🧠',
                'intern'   => '🧾',
                'science'  => '🧪',
                'security' => '⚔️',
                'design'   => '🎨',
                'money'    => '💰',
                'data'     => '💾'
            ];

            foreach ($map as $keyword => $emoji) {
                if (stripos($title, $keyword) !== false) {
                    return $emoji;
                }
            }

            return '📜'; // Default "generic" scroll
        }

        public static function getPaperIcon(int $id, string $title): string {
            // 1. Try to find a meaningful match
            $glyph = self::glyphByName($title);
            
            // 2. If it returned the default scroll, use the ID math instead
            if ($glyph === '📜') {
                return self::glyphByID($id);
            }
            
            return $glyph;
        }

        public static function timeAgo(string $date) {
            // Set last updated info
            $updated = new DateTime($date);
            $now = new DateTime();
            $diff = $now->diff($updated);

            if ($diff->d > 0) {
                return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
            } elseif ($diff->h > 0) {
                return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
            } elseif ($diff->i > 0) {
                return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
            } else {
                return 'Just now';
            }
        }
    }