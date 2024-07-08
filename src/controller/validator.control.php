<?php
    // Code Convention: camelCase
    // trait Rebound {
    //     private function jsonRebound() {
    //         $serverMsg = ' is required.';
    //         header('Content-Type: application/json; charset=utf-8');
    //         echo json_encode($serverMsg);
    //         exit();
    //     }
    // }
    trait Validators {

        private function emptyInput() {
            foreach ($this->formFields as $fieldName => $fieldValue) {
                if (empty($fieldValue)) {
                    return $response['errors'][$fieldName] = 'Dit veld is verplicht';
                } 
            }
        }
        
        private function invalidInput() {
            // Verify email element
            if (isset($this->formFields['email'])) {
                // Start validating...
                if (!filter_var($this->formFields['email'], FILTER_VALIDATE_EMAIL)) {
                    return $response['errors']['email'] = 'Vul een geldig e-mailadres in';
                }
            }

            // Avoid iteration overhead and improve memory efficiency
            // Verify password element
            if (isset($this->formFields['pwd'])) {
                // Start validating...
                if (strlen($this->formFields['pwd']) < 8) {
                    return $response['errors']['pwd'] = 'Wachtwoord moet minstens 8 tekens lang zijn.';
                }
                elseif (!preg_match('/[a-z]/', $this->formFields['pwd'])) {
                    return $response['errors']['pwd'] = 'Wachtwoord moet minstens één kleine letter hebben.';
                }
                elseif (!preg_match('/[A-Z]/', $this->formFields['pwd'])) {
                    return $response['errors']['pwd'] = 'Wachtwoord moet minstens één hoofdletter hebben.'; 
                }
                elseif (!preg_match('/[0-9]/', $this->formFields['pwd'])) {
                    return $response['errors']['pwd'] = 'Wachtwoord moet minstens één getal hebben.'; 
                }
                elseif (!preg_match('/[!@#$%^&*()_+=[\]{};:\'",<.>\/?\\|~-]/', $this->formFields['pwd'])) {
                    return $response['errors']['pwd'] = 'Wachtwoord moet minstens één speciale teken hebben.'; 
                }
            }
        }

        private function uidTakenVerify() {
            // Make sure the submitted values aren't in use.
            //return $this->verifyUser($this->formFields);
        }
    }