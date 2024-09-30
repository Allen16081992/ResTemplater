<?php // Load PHP files      
    require_once './validator.control.php';

    class AccountControl extends Account {
        use ErrorHandler;
        use Validators;

        private $formFields;

        public function __construct($formFields) {
           $this->formFields = $formFields;
        }

        public function verifyData() {
            // Perform validation
            $this->emptyInput($this->formFields);

            // Check if there are any errors stored in the session
            if (!empty($_SESSION['error'])) {
                $_SESSION['login'] = true;
                header('location: ../index.php');
                exit();
            }

            // Perform validation
            $this->invalidInput($this->formFields);

            // Check if there are any errors stored in the session
            if (!empty($_SESSION['error'])) {
                $_SESSION['login'] = true;
                header('location: ../index.php');
                exit();
            }

            if (isset($formFields['loginBtn'])) {
                $this->loginUser($this->formFields);
            } elseif (isset($formFields['signupBtn'])) {
                $this->signupUser($this->formFields);
            } elseif (isset($formFields['saveInfo'])) {
                $this->setAccount($this->formFields);
            } elseif (isset($formFields['saveAccount'])) {
                $this->setAddress($this->formFields);
            } elseif (isset($formFields['saveAccount'])) {
                $this->setPersonal($this->formFields);
            } elseif (isset($formFields['trashAccount'])) {
                //$this->deleteUser($this->formFields);
            }
        }
    }