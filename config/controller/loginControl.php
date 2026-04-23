<?php // PHP Files
    require_once __DIR__ . '/../mixedGrimoire.conf.php';

    class loginControl {
        public function __construct(private array $postData) {}

        public function handle(): void {
            SessionBook::throttleLogin(3); // example cooldown seconds

            // Hold submitted data
            ViewBook::flashForm(['email' => $this->postData['email'] ?? '']);

            // Validate for missing value
            $errors = ValidGrimoire::emptyField($this->postData);
            if (!empty($errors)) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = $errors;
                ViewBook::revert($this->postData['action'] ?? 'login');
                return;
            }

            // Validate email format
            $email = trim((string)($this->postData['email'] ?? ''));
            $msg = ValidGrimoire::validateEmail($email);
            if ($msg !== null) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = ['email' => $msg];
                ViewBook::revert($this->postData['action'] ?? 'login'); 
                return;
            }

            // DB lookup (only after validation)
            $pdo = Database::Connect();
            $model = new userCodex($pdo);  

            // Fetch user data
            $user = $model->findByEmail($email);
            if ($user === false) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = ['email' => 'Invalid email or password.'];
                ViewBook::revert($this->postData['action'] ?? 'login');
                return;
            }

            // Verify password
            $pwd = (string)($this->postData['pwd'] ?? '');
            if (!mixedGrimoire::checkHash($pwd, $user['password_hash'])) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = ['pwd' => 'Invalid email or password.'];
                ViewBook::revert($this->postData['action'] ?? 'login');
                return;
            }

            // Auth success: reset session
            session_regenerate_id(true);
            $_SESSION['last_regen'] = time();

            // Auth success: set user
            $_SESSION['session_data'] = [
                'user_id' => (int)$user['id']
            ];

            // Perform redirect
            ViewBook::revert('profile');
            exit;
        }
    }