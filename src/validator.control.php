<?php // Code Convention: camelCase
    
    trait ErrorHandler {
        private function handleError($msg, $path) {
            $_SESSION['error'] = $msg;
            header('location: ' . $path);
            exit();
        }
    }

    trait Validators {
        public static function emptyAgent($field) {
            // Treat any value as text and trim white-space down
            if (trim((string)$field) === '') {
                throw new Exception("Field is required.");
            }
        }

        public static function inputAgent($field) {
            // Verify all possible fields
            if (in_array($field, ['email'])) {
                if (!filter_var($field, FILTER_VALIDATE_EMAIL)) {
                    // throw new Exception("Invalid email address.");
                }
            }
            elseif (in_array($field, ['firstname', 'lastname', 'country', 'city'])) {
                if (!preg_match('/^[a-zA-ZÀ-ÿ\s\'\-]+$/u', $field) ) {
                    // throw new Exception("Please use only letters, spaces, hyphens (-), or apostrophes (').");
                }
            }
            elseif (in_array($field, ['postal'])) {
                if (!preg_match('/^[a-zA-ZÀ-ÿ0-9]+$/', $field)) {
                    // throw new Exception("Name contains invalid characters.");
                }
            }
            elseif (in_array($field, ['phone'])) {
                if (!preg_match('/^\+?[0-9\s\-\(\)]+$/', $field)) {
                    // throw new Exception("Name contains invalid characters.");
                }
            }
        }

        public static function passwordAgent($pwd) {

            if (strlen($pwd) < 8) {
                // = 'Wachtwoord moet minstens 8 tekens lang zijn';
            }
            elseif (!preg_match('/[a-z]/', $pwd)) {
                // = 'Wachtwoord moet minstens één kleine letter hebben';
            }
            elseif (!preg_match('/[A-Z]/', $pwd)) {
                // = 'Wachtwoord moet minstens één hoofdletter hebben'; 
            }
            elseif (!preg_match('/[0-9]/', $pwd)) {
                // = 'Wachtwoord moet minstens één getal hebben'; 
            }
            elseif (!preg_match('/[!@#$%^&*()_+=[\]{};:\'",<.>\/?\\|~-]/', $pwd)) {
                // = 'Wachtwoord moet minstens één speciale teken hebben'; 
            }
        }
        
        public function emptyInput($input) {
            return trim($input) === '';
        }

        // Avoid iteration overhead and improve memory efficiency
        private function invalidInput($input) {
            // Validate submitted value
            if (in_array($input, ['firstname', 'lastname', 'country', 'city'])) {
                return preg_match('/[a-zA-ZÀ-ÿ]/', $input);
            }

            // Validate username
            if (in_array($input, ['username'])) {
                return preg_match('/^[a-zA-ZÀ-ÿ0-9_-]{3,20}$/', $input);
            }

            // Validate phone
            if (in_array($input, ['phone'])) {
                return preg_match('/^\+?[0-9]{1,3}?[-. ]?\(?[0-9]{1,4}?\)?[-. ]?[0-9]{1,4}[-. ]?[0-9]{1,9}$/', $input);
            }

            // Validate postal
            if (in_array($input, ['postal'])) {
                return preg_match('/[a-zA-ZÀ-ÿ0-9]/', $input);
            }

            // Validate email
            if (in_array($input, ['email'])) {
                return filter_var($input, FILTER_VALIDATE_EMAIL);
            }
        }
    }