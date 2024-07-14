<?php
    require_once 'classes/account.class.php';
    require_once 'controller/req_verify.control.php';
    require_once 'controller/validator.control.php';

    class FormHandler extends Separator {
        use Validators;

        // Properties
        private $formFields = [];
        // private $response = [
        //     'success' => false,
        //     'errors' => []
        // ];

        protected function __construct(array $postData) {
            $this->formFields = $postData;
            //$this->validateForm();
        }

        public function validateForm() {
            $this->emptyInput();
            if (!$this->invalidInput()) {
                $this->verifyForm($this->formFields);
            }
        }

        // public function getResponse() {
        //     if (empty($this->response['errors'])) {
        //         $this->response['success'] = true;
        //     } else {
        //         $this->response['success'] = false;
        //         unset($_POST, $postData, $this->formFields);
        //     }
        //     return $this->response;
        // }
    }

    $formHandler = new FormHandler($_POST);
    $response = $formHandler->validateForm();
    //echo json_encode($response);