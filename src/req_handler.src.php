<?php
    require_once 'controller/req_verify.control.php';
    require_once 'controller/validator.control.php';

    class FormHandler extends Separator {
        use Validators;

        // Properties
        private $formFields = [];
        private $response = [
            'success' => false,
            'errors' => []
        ];

        public function __construct(array $postData) {
            $this->formFields = $postData;
            $this->validateForm();
        }

        private function validateForm() {
            $this->emptyInput();
            $this->invalidInput();
        }

        public function getResponse() {
            if (empty($this->response['errors'])) {
                //$this->response['success'] = true;
                $this->verifyForm($this->formFields);
            } else {
                $this->response['success'] = false;
                unset($_POST, $postData, $this->formFields);
            }
            return $this->response;
        }
    }

    $formHandler = new FormHandler($_POST);
    $response = $formHandler->getResponse();
    echo json_encode($response);