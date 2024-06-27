<?php

    trait Validators {

        private function emptyInput() {
            foreach ($this->formFields as $fieldName => $fieldValue) {
                if (empty($fieldValue)) {
                    return $fieldName; // Returns the specified empty fields
                } 
            }
        }
        
        private function invalidInput() {
            foreach ($this->formFields as $fieldName => $fieldValue) {
                if ($fieldName === 'firstname' || $fieldName === 'lastname') {
                    // Check for username requirements
                    if (!preg_match("/^[a-zA-ZÀ-ÿ\s'.]*$/", $fieldValue)) {
                        return $fieldName;  
                    }
                }
                elseif ($fieldName === 'username') {
                    // Check for username requirements
                    if (!preg_match("/^[a-zA-Z0-9_\-\.]*$/", $fieldValue)) {
                        return "Usernames may contain:<br> 
                        ● Letters ● Numbers ● Underscores<br>
                        ● Hyphens ● Periods.";
                    }
                }
                elseif ($fieldName === 'email') {
                    // Check if the field name is 'pwdR' and if it matches exactly with 'pwd'
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
            }
        }
    }