<?php // PHP Files
    require_once __DIR__ . '/../mixedGrimoire.conf.php';
    
    class userControl {
        public function __construct(private array $postData) {}
        
        public function handle(): void {
            // DB lookup (only after validation)
            $pdo = Database::Connect();
            $model = new userCodex($pdo); 

            // Account closure
            if (!isset($this->postData['closure'])) {
                $exist = $model->findByEmail($this->postData['email']);
                if (!$exist) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'User not found.';
                    ViewBook::revert($postData['action'] ?? 'profile');
                    return; 
                }

                // If logged in user match, close account
                if ($exist['id'] === (int)$this->postData['id']) {
                    // Verify password
                    $pwd = (string)($this->postData['pwd'] ?? '');
                    if (!mixedGrimoire::checkHash($pwd, $exist['password_hash'])) {
                        // Hold error message + set previous UI state
                        $_SESSION['error'] = ['pwd' => 'Password not right.'];

                        // Set visibility
                        $_SESSION['action'] = 'closure';
                        ViewBook::revert($this->postData['action'] ?? 'closure');
                        return;
                    }
                }

                // Delete user (only after validation)
                $erase = $model->deleteAccount($exist['id']);
                if ($erase <= 0) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'Failed to delete account.';
                    ViewBook::revert($this->postData['action'] ?? 'profile');
                    return;
                }

                // Set success message + redirect
                $_SESSION['action'] = 'home';
                $_SESSION['success'] = 'Account closed. Data erased.';
                ViewBook::revert($this->postData['action'] ?? '');
                return;
            }

            // Validate for missing value
            $errors = ValidGrimoire::emptyField($this->postData);
            if (!empty($errors)) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = $errors;
                ViewBook::revert($this->postData['action'] ?? 'profile');
                return;
            }

            // Account details
            if (isset($this->postData['account'])) {
                // Validate email format
                $email = trim((string)($this->postData['email'] ?? ''));
                if ($msg = ValidGrimoire::validateEmail($email)) {
                    $errors['email'] = $msg;
                }

                // Validate password
                $pwd = trim((string)($this->postData['pwd'] ?? ''));
                if ($msg = ValidGrimoire::validatePwd($pwd)) {
                    $errors['pwd'] = $msg;
                }

                // Final check: if errors exist, send them back together
                if (!empty($errors)) {
                    $_SESSION['error'] = $errors;
                    ViewBook::revert($this->postData['action'] ?? 'profile');
                    return;
                }

                $exist = $model->findByEmail($this->postData['email']);
                if (!$exist) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'Update failed. User not found.';
                    ViewBook::revert($postData['action'] ?? 'profile');
                    return; 
                }
                
                $user = $_SESSION['session_data']['user_id'];
                $update = $model->updateEmail($user, $email);

                // Verify if task was successful
                if ($update <= 0) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'Updating account failed.';
                    ViewBook::revert($this->postData['action'] ?? 'profile');
                    return;
                }

                if (!empty($pwd)) {
                    $passw = $model->updateHash($user, $pwd);
                    if ($passw <= 0) {
                        // Hold error message + set previous UI state
                        $_SESSION['error'] = 'Updating account failed.';
                        ViewBook::revert($this->postData['action'] ?? 'profile');
                        return;
                    }
                }
                $_SESSION['success'] = 'Account updated.';
            } 

            // Personalia - Contacts
            if (!isset($this->postData['contact'])) {
                $errors = [];

                // Collect errors
                $fields = ['fullname', 'city', 'country'];
                foreach ($fields as $field) {
                    $value = trim((string)($this->postData[$field] ?? ''));
                    if ($msg = ValidGrimoire::validateName($value, true)) {
                        $errors[$field] = $msg;
                    }
                }

                // Separate logic for phone
                $phone = trim((string)($this->postData['phone'] ?? ''));
                if ($msg = ValidGrimoire::validatePhone($phone)) {
                    $errors['phone'] = $msg;
                }

                // Final check: if errors exist, send them back together
                if (!empty($errors)) {
                    $_SESSION['error'] = $errors;
                    ViewBook::revert($this->postData['action'] ?? 'profile');
                    return;
                }

                $exist = $model->fetchContact($this->postData['user_id']);
                if (!$exist) {
                    $contact = $model->createContact($this->postData);
                } else {
                    $contact = $model->updateContact($this->postData);
                }

                // Verify if task was successful
                if ($contact <= 0) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'Updating personal failed.';
                    ViewBook::revert($postData['action'] ?? 'profile');
                    return;
                }
                $_SESSION['success'] = 'Profile updated.';
            }
            ViewBook::revert($postData['action'] ?? 'profile');
            exit;
        }
    }