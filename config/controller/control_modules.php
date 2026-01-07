<?php // Load PHP files
    require_once '../error_checks.conf.php';

    final class LoginControl {
        public function __construct(private PDO $pdo, private array $postData) {}

        private function revert(): void {
            // Hold onto filled fields and redirect
            $_SESSION['old']['email'] = $email;
            $_SESSION['login'] = true;
            header('location: ../index.php');          
            exit();
        }

        public function handle(): void {
            $email = $this->postData['email'] ?? '';
            $pwd   = $this->postData['pwd'] ?? '';

            if (validGrimoire::emptyField($email)) {
                $_SESSION['error']['email'] = 'This field is required.'; 
                $this->revert();
            } 
            if (!validGrimoire::checkEmail($email)) {
                $_SESSION['error']['email'] = 'Invalid email address';
                $this->revert();
            }
            if (validGrimoire::emptyField($pwd)) {
                $_SESSION['error']['pwd'] = 'This field is required.'; 
                $this->revert();
            } 
            if (validGrimoire::isEmpty($pwd)) {
                $_SESSION['error']['pwd'] = 'Password is required.';
                $this->revert();
            }
            $this->authenticate($email, $pwd);
        }
    }

    // Signup Class
    class signupControl extends Account {
        private $formFields;

        public function __construct(array $formFields) {
            $this->formFields = $formFields;
        }

        public function signupUser() {
            // Grimoire of Validators — arcane sequence
            $skip = ['username', 'action', 'csrf_token'];

            foreach ($this->formFields as $field => $value) {
                if (in_array($field, $skip)) {
                    continue;
                }
                if (validGrimoire::emptyField($value)) {
                    $_SESSION['error'][$field] = 'This field is required.';
                }
            }

            if (validGrimoire::checkAlpha($this->formFields['firstname'])) {
                $_SESSION['error']['firstname'] = 'Only alphabetical characters.';
            }
            if (validGrimoire::checkAlpha($this->formFields['lastname'])) {
                $_SESSION['error']['lastname'] = 'Only alphabetical characters.';
            }
            if (validGrimoire::checkEmail($this->formFields['email'])) {
                $_SESSION['error']['email'] = 'Invalid email address';
            }
            if (($error = validGrimoire::checkPwd($this->formFields['pwd'])) !== null) {
                $_SESSION['error']['pwd'] = $error;
            }

            // Handle invalid dates
            $result = validGrimoire::checkDate($this->formFields['day'], $this->formFields['month'], $this->formFields['year']);
            if (!$result['valid']) {
                $_SESSION['error']['birth'] = $result['error'];
            }

            if(isset($_SESSION['error'])) {
                // Hold onto filled fields and redirect
                $_SESSION['form_old'] = $this->formFields;
                ViewBook::breakRide(null, null);
            }

            // Combine into date format
            $this->formFields['birth'] = $result['date'];
            unset($this->formFields['day'], $this->formFields['month'], $this->formFields['year']);

            // Proceed with signing up
            $this->verifyUser($this->formFields);
            ViewBook::breakRide(null, null);
        }
    }