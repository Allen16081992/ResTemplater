<?php // Code Convention: camelCase

    class validGrimoire {
        public static function emptyField(array $postData): array {
            $errors = [];
            $skip = ['action', 'username'];

            foreach ($postData as $field => $value) {
                if (in_array($field, $skip, true)) { continue; }
                if (trim((string)$value) === '') {
                    $errors[$field] = 'This field is required.';
                } 
            }
            return $errors;
        }   

        public static function validateEmail(string $email): ?string {
            return filter_var($email, FILTER_VALIDATE_EMAIL) ? null : 'Invalid email address.';
        }

        public static function validatePwd(string $password): ?string {
            $len = mb_strlen($password, 'UTF-8');

            // Length rules
            if ($len < 12) { return 'Password must be at least 12 characters long.'; }
            if ($len > 128) { return 'Password must be no more than 128 characters long.'; }

            // Disallow control characters (newlines, null bytes, etc.)
            if (preg_match('/[\x00-\x1F\x7F]/u', $password)) { return 'Password contains invalid control characters.'; }

            // Must contain at least one non-space character
            if (!preg_match('/\S/u', $password)) { return 'Password must contain at least one non-space character.'; }

            return null; // valid
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