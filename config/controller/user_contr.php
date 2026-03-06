<?php // Load PHP Files
    require_once '../validator.conf.php';
    require_once '../classes/user_class.php';

    class loginControl {
        public function __construct(private array $postData) {}
        use Auxiliary;

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
                $_SESSION['error'] = 'Invalid email or password.';
                ViewBook::revert($this->postData['action'] ?? 'login');
                return;
            }

            // Verify password
            $pwd = (string)($this->postData['pwd'] ?? '');
            if (!$this->verifyHash($pwd, $user['password_hash'])) {
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
            ViewBook::revert('wizard');
            exit;
        }
    }

    class signupControl {
        public function __construct(private array $postData) {}
        use Auxiliary;

        private function oldForm(string $d, string $m, string $y): void {
            $oldForm = [
                'email' => $this->postData['email'] ?? '',
                'day' => $d,
                'month' => $m,
                'year' => $y
            ];
            ViewBook::flashForm($oldForm);
        }

        public function handle(): void {
            // Verify submitted date boxes
            $day   = trim((string)($this->postData['day'] ?? ''));
            $month = trim((string)($this->postData['month'] ?? ''));
            $year  = trim((string)($this->postData['year'] ?? ''));

            $hasAll = ($day !== '' && $month !== '' && $year !== '');
            if (!$hasAll) {
                $_SESSION['error'] = ['date' => 'Please add your full date of birth.'];
                $this->oldForm($day, $month, $year);
                ViewBook::revert($this->postData['action'] ?? 'sign_up');
                return;
            }

            // Validate date format
            if (!checkdate((int)$month, (int)$day, (int)$year)) {
                $_SESSION['error'] = ['date' => 'Please enter a valid date.'];
                $this->oldForm($day, $month, $year);
                ViewBook::revert($this->postData['action'] ?? 'sign_up');
                return;
            }

            // Calculate age using DateTime for precision
            $birthDate = new DateTime("$year-$month-$day");
            $today = new DateTime();
            $age = $today->diff($birthDate)->y;
        
            // Check if age is within the acceptable range
            if ($age < 16) {
                $_SESSION['error'] = ['date' => 'You must be at least 16 years old.'];
                $this->oldForm($day, $month, $year);
                ViewBook::revert($this->postData['action'] ?? 'sign_up');
                return;
            }
        
            // Return the date in DD-MM-YYYY format
            $this->postData['date'] = sprintf('%04d-%02d-%02d', (int)$year, (int)$month, (int)$day);

            // Validate for missing value
            $errors = ValidGrimoire::emptyField($this->postData);
            if (!empty($errors)) {
                // Hold submitted data
                $this->oldForm($day, $month, $year);

                // Hold error messages for previous UI state
                $_SESSION['error'] = $errors;
                ViewBook::revert($this->postData['action'] ?? 'sign_up');
                return;
            }

            // Validate email format
            $email = trim((string)($this->postData['email'] ?? ''));
            $msg = ValidGrimoire::validateEmail($email);
            if ($msg !== null) {
                // Hold submitted data
                $this->oldForm($day, $month, $year);

                // Hold error message for previous UI state
                $_SESSION['error'] = ['email' => $msg];
                ViewBook::revert($this->postData['action'] ?? 'sign_up'); 
                return;
            }

            // Validate password format
            $pwd = trim((string)($this->postData['pwd'] ?? ''));
            $msg = ValidGrimoire::validatePwd($pwd);
            if ($msg !== null) {
                // Hold submitted data
                $this->oldForm($day, $month, $year);

                // Hold error message for previous UI state
                $_SESSION['error'] = ['pwd' => $msg];
                ViewBook::revert($this->postData['action'] ?? 'sign_up'); 
                return;
            }

            // If all good, Refactor date and cleanup
            unset($this->postData['year'], $year, $this->postData['month'], $month, $this->postData['day'], $day);

            // DB lookup (only after validation)
            $pdo = Database::Connect();
            $model = new userCodex($pdo);  
            $exist = $model->emailExists($email);

            if ($exist) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = 'Email address already in use.';
                ViewBook::revert($this->postData['action'] ?? 'sign_up');
                return;
            }

            // Set verified entries
            $this->postData['email'] = $email;
            $this->postData['pwd'] = $pwd;

            // Set new user
            $newUser = $model->createAccount($this->postData);
            if ($newUser <= 0) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = 'Sign Up failed.';
                ViewBook::revert($this->postData['action'] ?? 'sign_up');
                return;
            }

            // Registration done: redirect
            $_SESSION['action'] = 'success';
            ViewBook::revert($this->postData['action'] ?? 'success');
            exit;
        }
    }

    class accountControl {
        public function __construct(private array $postData) {}
        use Auxiliary;
        
        public function handle(): void {
            // Validate for missing value
            $errors = ValidGrimoire::emptyField($this->postData);
            if (!empty($errors)) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = $errors;
                ViewBook::revert($this->postData['action'] ?? 'profile');
                return;
            }

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
                    if (!$this->verifyHash($pwd, $exist['password_hash'])) {
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

                // DB lookup (only after validation)
                // $pdo = Database::Connect();
                // $model = new userCodex($pdo); 
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
                    $passw = $model->updatePasswordHash($user, $pwd);
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

                // DB lookup (only after validation)
                // $pdo = Database::Connect();
                // $model = new userCodex($pdo); 
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