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
                    return $fieldName; // Returns the specified empty fields
                } 
            }
        }
        
        private function invalidInput() {
            if (strlen($this->formFields['password']) < 10) {

            } elseif (!preg_match('/[a-z]/', $this->formFields['password'])) {

            } elseif (!preg_match('/[A-Z]/', $this->formFields['password'])) {

            } elseif (!preg_match('/[0-9]/', $this->formFields['password'])) {

            } elseif (!preg_match('/[0-9]/', $this->formFields['password'])) {

            } elseif (!preg_match('/[!@#$%^&*()_+=[\]{};:\'",<.>/?\\|~-]/', $this->formFields['password'])) {

            }

            foreach ($this->formFields as $fieldName => $fieldValue) {
                if ($fieldName === 'email') {
                    // Verify submitted email
                    if (!filter_var($fieldValue, FILTER_VALIDATE_EMAIL)) {
                        return $fieldName;
                    }
                }
                elseif ($fieldName === 'password') {
                    // Check for password requirements
                    if (strlen($fieldValue) < 10 || 
                        !preg_match('/[a-z]/', $fieldValue) ||  // At least one lowercase letter
                        !preg_match('/[A-Z]/', $fieldValue) ||  // At least one uppercase letter
                        !preg_match('/[0-9]/', $fieldValue) ||  // At least one digit
                        !preg_match('/[!@#$%^&*()_+=[\]{};:\'",<.>/?\\|~-]/', $fieldValue)) {  // At least one special character
                        return $fieldName;
                    }
                } 
                else {
                    if (!preg_match("/^[a-zA-ZÀ-ÿ0-9_\-\.]*$/", $fieldValue)) {
                        return $fieldName;  
                        // return "Usernames may contain:<br> 
                        // ● Letters ● Numbers ● Underscores<br>
                        // ● Hyphens ● Periods.";
                    }
                }
            }
        }

        private function uidTakenVerify() {
            // Make sure the submitted values aren't in use.
            //return $this->verifyUser($this->formFields);
        }
    }