<?php
    require_once "validator.control.php";

    class signupControl extends Account {
        // Properties
        use Validators;
        private $formFields;

        // Methods - instead of multiple variables of fields, we handle an array of fields
        public function __construct($formFields) {
            $this->formFields = $formFields;
        }

        public function signupRequest() {
            // Activate security validations
            if ($this->emptyInput()) {
                $serverMsg = $this->emptyInput().' is required.';
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($serverMsg);
                exit();
            }
            // Verder aanvullen
        }
    }