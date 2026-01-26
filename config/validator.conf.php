<?php // Code Convention: camelCase

    class validGrimoire {
        public static function emptyField(array $postData): array {
            $errors = [];
            $skip = ['action', 'username', 'date'];

            foreach ($postData as $field => $value) {
                if (in_array($field, $skip, true)) { continue; }
                if (trim((string)$value) === '') {
                    $errors[$field] = 'This field is required.';
                } 
            }
            return $errors;
        }  
        
        public static function validateUsername(string $uid): ?string {
            $uid = trim($uid);

            // Optional field: empty is allowed
            if ($uid === '') { return null; }
            if (strlen($uid) < 5 || strlen($uid) > 32) { return 'Must be between 5 and 32 characters.'; }
            if (!preg_match('/^[a-zA-Z0-9._-]+$/', $uid)) { 
                return 'Only letters, numbers, dots, dashes and underscores are allowed.'; 
            }
            return null;
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
            return null; 
        }

        public static function validateName(string $value, bool $required = true, int $maxLen = 80): ?string {
            $value = trim($value);
            if ($required && $value === '') { return 'This field is required.'; }
            if ($value === '') { return null; }
            if (mb_strlen($value, 'UTF-8') > $maxLen) { return "Maximum {$maxLen} characters allowed."; }

            // Disallow control characters
            if (preg_match('/[\x00-\x1F\x7F]/u', $value)) {
                return 'Contains invalid characters.';
            }

            // Letters + separators only
            if (!preg_match("/^[\p{L} .'-]+$/u", $value)) {
                return 'Only letters, spaces, dots, hyphens and apostrophes allowed.';
            }

            // Must contain at least one letter
            if (!preg_match('/\p{L}/u', $value)) {
                return 'Must contain at least one letter.';
            }
            return null;
        }

        public static function validatePhoneValue(string $value): ?string {
            $value = trim($value);
            if ($value === '') { return 'Phone number is required.'; }

            // Remove spaces for length check
            $digits = preg_replace('/\D+/', '', $value);
            if (strlen($digits) < 7 || strlen($digits) > 20) { return 'Invalid phone number length.'; }

            // Allow +, digits, spaces, dashes, parentheses
            if (!preg_match('/^[0-9+\-\s()]+$/', $value)) {
                return 'Invalid phone number format.';
            }
            return null;
        }

        public static function validateZipcode(string $value): ?string {
            $value = trim($value);
            if ($value === '') { return 'Postcode is required.'; }
            if (mb_strlen($value, 'UTF-8') > 20) { return 'Postcode too long.'; }

            // Letters, digits, spaces, hyphens only
            if (!preg_match('/^[A-Za-z0-9 -]+$/', $value)) {
                return 'Invalid postcode format.';
            }
            return null;
        }
    }

    trait Validators {

        // Avoid iteration overhead and improve memory efficiency
        private function invalidInput($input) {
            // Validate submitted value
            if (in_array($input, ['firstname', 'lastname', 'country', 'city'])) {
                return preg_match('/[a-zA-ZÀ-ÿ]/', $input);
            }

            // Validate phone
            if (in_array($input, ['phone'])) {
                return preg_match('/^\+?[0-9]{1,3}?[-. ]?\(?[0-9]{1,4}?\)?[-. ]?[0-9]{1,4}[-. ]?[0-9]{1,9}$/', $input);
            }

            // Validate postal
            if (in_array($input, ['postal'])) {
                return preg_match('/[a-zA-ZÀ-ÿ0-9]/', $input);
            }
        }
    }