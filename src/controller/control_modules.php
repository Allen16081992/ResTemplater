<?php // Load PHP files

    // Login Class
    class loginControl extends Account {
        private $formFields;

        public function __construct(array $postData) {
            $this->formFields = $postData;
        }

        public function loginUser() {
            // Grimoire of Validators — arcane sequence
            if (validGrimoire::emptyField($this->formFields['email'])) {
                $_SESSION['error']['email'] = 'This field is required.';
            } elseif (validGrimoire::checkEmail($this->formFields['email'])) {
                $_SESSION['error']['email'] = 'Invalid email address';
            }
        
            if (validGrimoire::emptyField($this->formFields['pwd'])) {
                $_SESSION['error']['pwd'] = 'This field is required.';
            } elseif (($error = validGrimoire::checkPwd($this->formFields['pwd'])) !== null) {
                $_SESSION['error']['pwd'] = $error;
            }

            if(isset($_SESSION['error'])) {
                // Hold onto filled fields and redirect
                $_SESSION['form_old'] = $this->formFields;
                ViewBook::breakRide(null, $this->formFields);
            }

            // Proceed with login the user
            $this->fetchUser($this->formFields);
            ViewBook::breakRide('client', null);
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