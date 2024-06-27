<?php
    require_once "validator.control.php";

    class loginControl extends Users {
        // Properties
        use Validators;
        private $formFields;

        // Methods - instead of multiple variables of fields, we handle an array of fields
        public function __construct($formFields) {
            $this->formFields = $formFields;
        }

        public function loginRequest() {
            // Activate security validations
            if ($this->emptyInput()) {
                $serverMsg = $this->emptyInput().' is required.';
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($serverMsg);
                exit();
            }
            elseif ($this->invalidInput() === 'email') {
                // Push the server error
                $serverMsg = 'Please enter a valid email.';
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($serverMsg);
                exit();
            } 
            elseif ($this->invalidInput() === 'password') {
                // Push the server error
                $serverMsg = 'Password must contain:<br>
                    ● Atleast 8 characters in length or more<br>
                    ● Have uppercase and lowercase characters<br>
                    ● Have Numbers and special characters.';
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($serverMsg);
                exit();
            }
            else {
                $this->getUser($this->formFields);
            }
        }
    }