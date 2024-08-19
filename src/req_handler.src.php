<?php
    require_once __DIR__ . '../../src/account.class.php';

    class FormHandler extends Account {
        use Validators;

        // Properties
        private $formFields = [];

        public function __construct(array $postData) {
            $this->formFields = $postData;
        }

        public function validateForm() {
            $this->emptyInput();
            if (!$this->invalidInput()) {
                // Verify the form
                if (isset($this->formFields['login'])) {
                    $this->loginUser($this->formFields);
                } elseif (isset($this->formFields['sign_up'])) {
                    $this->signupUser($this->formFields);
                } else {
                    header('location: ../woops.html');
                    exit();
                }
            }
        }
    }

    $formHandler = new FormHandler($_POST);
    $formHandler->validateForm();