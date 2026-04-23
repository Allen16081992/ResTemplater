<?php
    class signupControl {
        public function __construct(private array $postData) {}

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