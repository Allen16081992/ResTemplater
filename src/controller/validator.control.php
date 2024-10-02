<?php // Code Convention: camelCase
    
    trait ErrorHandler {
        private function handleError($msg, $path) {
            $_SESSION['error'] = $msg;
            header('location: ' . $path);
            exit();
        }
    }

    trait Validators {

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