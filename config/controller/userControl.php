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
                $msg = ValidGrimoire::validateEmail($email);
                if ($msg !== null) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = ['email' => $msg];
                    ViewBook::revert($this->postData['action'] ?? 'profile'); 
                    return;
                }

                // Validate password
                $pwd = trim((string)($this->postData['pwd'] ?? ''));
                if (!empty($pwd)) {
                    $msg = ValidGrimoire::validatePwd($pwd);
                    if ($msg !== null) {
                        // Hold error message for previous UI state
                        $_SESSION['error'] = ['pwd' => $msg];
                        ViewBook::revert($this->postData['action'] ?? 'sign_up'); 
                        return;
                    }
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
                // Validate firstname
                $fullname = trim((string)($this->postData['fullname'] ?? ''));
                $msg = ValidGrimoire::validateName($fullname, true);
                if ($msg !== null) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = ['fullname' => $msg];
                    ViewBook::revert($this->postData['action'] ?? 'profile'); 
                    return;
                }

                // Validate city
                $city = trim((string)($this->postData['city'] ?? ''));
                $msg = ValidGrimoire::validateName($city, true);
                if ($msg !== null) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = ['city' => $msg];
                    ViewBook::revert($this->postData['action'] ?? 'profile'); 
                    return;
                }

                // Validate nation
                $country = trim((string)($this->postData['country'] ?? ''));
                $msg = ValidGrimoire::validateName($country, true);
                if ($msg !== null) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = ['country' => $msg];
                    ViewBook::revert($this->postData['action'] ?? 'profile'); 
                    return;
                }

                // Validate phone number format
                $phone = trim((string)($this->postData['phone'] ?? ''));
                $msg = ValidGrimoire::validatePhone($phone);
                if ($msg !== null) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = ['phone' => $msg];
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