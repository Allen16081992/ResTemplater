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
                // Return JSON response
                header('Content-Type: application/json; charset=utf-8');
                // $serverMsg = $this->emptyInput();
                echo json_encode($this->emptyInput());
                exit();
            } else {
                //$this->getUser($this->formFields);
            }
        }
    }