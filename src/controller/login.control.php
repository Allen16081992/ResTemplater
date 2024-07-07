<?php
    require_once "validator.control.php";

    class loginControl extends Account {
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
                // Empty input, no values given for account.
                $serverMsg = 'No '.$this->emptyInput().' provided.';
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($serverMsg);
                exit();
            } else {
                //$this->getUser($this->formFields);
            }
        }
    }