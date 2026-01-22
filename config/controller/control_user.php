<?php
    require_once '././validator.conf.php';

    class LoginControl {
        // add in all main Classes(Database querry) construct: private PDO $pdo
        public function __construct(private array $postData) {}

        public function handle(): void {
            // Hold submitted data
            $oldForm = $this->postData['email'] ?? '';
            ViewBook::flashForm($oldForm);

            // Validate logic
            $errors = ValidGrimoire::emptyField($this->postData);
            if (!empty($errors)) {
                // Hold error messages for previous UI state
                $_SESSION['error'] = $errors;
                ViewBook::revert($this->postData['action'] ?? 'login');
                return;
            }

            $msg = ValidGrimoire::validateEmail($this->postData['email'] ?? '');
            if ($msg !== null) {
                // Hold error message for previous UI state
                $_SESSION['error'] = ['email' => $msg];
                ViewBook::revert($this->postData['action'] ?? 'login'); 
                return;
            }

            // $this->getUser($this->postData);
            return;
        }
    }

    class SignupControl {
        public function __construct(private array $postData) {}
        // Birthday, Username, Email, Password.

        private function oldForm(string $d, string $m, string $y): void {
            $oldForm = [
                'email' => $this->postData['email'] ?? '',
                'username' => $this->postData['username'] ?? '',
                'day' => $d,
                'month' => $m,
                'year' => $y
            ];
            ViewBook::flashForm($oldForm);
        }

        public function handle(): void {
            // Verify if a date boxes were submitted
            $day   = trim((string)($this->postData['day'] ?? ''));
            $month = trim((string)($this->postData['month'] ?? ''));
            $year  = trim((string)($this->postData['year'] ?? ''));

            // Verify for appropriate values
            $hasAll = ($day !== '' && $month !== '' && $year !== '');

            if (!$hasAll) {
                $_SESSION['error'] = ['date' => 'Please add your full date of birth.'];
                $this->oldForm($day, $month, $year);
                ViewBook::revert($this->postData['action'] ?? 'signup');
                return;
            }

            if (!checkdate((int)$month, (int)$day, (int)$year)) {
                $_SESSION['error'] = ['date' => 'Please enter a valid date.'];
                $this->oldForm($day, $month, $year);
                ViewBook::revert($this->postData['action'] ?? 'signup');
                return;
            }

            $this->postData['date'] = sprintf('%04d-%02d-%02d', (int)$year, (int)$month, (int)$day);

            // Validate logic
            $errors = ValidGrimoire::emptyField($this->postData);
            if (!empty($errors)) {
                // Hold submitted data
                $this->oldForm($day, $month, $year);

                // Hold error messages for previous UI state
                $_SESSION['error'] = $errors;
                ViewBook::revert($this->postData['action'] ?? 'signup');
                return;
            }

            $msg = ValidGrimoire::validateEmail($this->postData['email'] ?? '');
            if ($msg !== null) {
                // Hold submitted data
                $this->oldForm($day, $month, $year);

                // Hold error message for previous UI state
                $_SESSION['error'] = ['email' => $msg];
                ViewBook::revert($this->postData['action'] ?? 'signup'); 
                return;
            }

            $msg = ValidGrimoire::validatePwd($this->postData['pwd'] ?? '');
            if ($msg !== null) {
                // Hold submitted data
                $this->oldForm($day, $month, $year);

                // Hold error message for previous UI state
                $_SESSION['error'] = ['pwd' => $msg];
                ViewBook::revert($this->postData['action'] ?? 'signup'); 
                return;
            }

            // If all good, Refactor date and discard
            unset($this->postData['year'], $this->postData['month'], $this->postData['day']);

            // $this->setUser($this->postData);
            return;
        }
    }