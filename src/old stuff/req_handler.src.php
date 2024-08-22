<?php // Code Convention: camelCase
    class FormHandler {
        use ErrorHandler;
        use Validators;

        // Properties
        private $formFields = [];
        private $response = [
            'errors' => []
        ];
        private $lazy;

        public function __construct(array $postData) {
            $this->formFields = $postData;
        }

        public function validateForm() {
            if ($this->emptyInput()) {
                // Determine the right page to redirect
                if (isset($this->formFields['login'])) {
                    $path = '../login.php';
                } 
                if (isset($this->formFields['sign_up'])) {
                    $path = '../signup.php';
                } 
                if (isset($this->formFields['editCv']) || isset($this->formFields['editJob']) 
                        || isset($this->formFields['editEdu']) || isset($this->formFields['editSkill'])
                        || isset($this->formFields['editMot'])) {
                    $path = '../client.php'; 
                }

                // Handle the error response, e.g., redirect or display errors
                $this->handleError($this->response, $path);
            }
            if (!$this->invalidInput()) {
                //error
            }
            $lazy = new AccountControl($this->formFields);
            $lazy->verifySubmit();
        }
    }

    if(isset($_POST['submit'])) {
        // Initialise traits & class files
        require_once __DIR__ . '../../src/controller/validator.control.php';
        require_once __DIR__ . '../../src/controller/account.control.php';

        $formHandler = new FormHandler($_POST);
        $formHandler->validateForm();
    } else {
        header('location: ../woops.html');
        exit();
    }