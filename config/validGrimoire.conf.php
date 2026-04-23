<?php // Code Convention: camelCase
    class validGrimoire {
        public static function emptyField(array $postData): array {
            $errors = [];
            $skip = ['action', 'username', 'date', 'month'];

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

        public static function validatePhone(string $value): ?string {
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

        public static function validateAndFormatDate(string $input): array {
            $input = trim(preg_replace('/\s+/', ' ', $input));

            if ($input === 'end_date' && $input === '') {
                return ['error' => null, 'date' => 'Present'];
            }

            if ($input === '') {
                return ['error' => 'Date is required.', 'date' => null];
            }

            $lower = strtolower($input);

            try {
                // Handle exact natural phrases
                if ($lower === 'today') {
                    $d = new DateTime('today');
                } elseif ($lower === 'tomorrow') {
                    $d = new DateTime('tomorrow');
                } elseif ($lower === 'yesterday') {
                    $d = new DateTime('yesterday');
                } else {
                    // Parse numeric or word-based full date
                    $d = new DateTime($input);
                }
            } catch (Exception $e) {
                return ['error' => 'Invalid date.', 'date' => null];
            }

            // Ensure calendar date is valid
            if (!checkdate((int)$d->format('m'), (int)$d->format('d'), (int)$d->format('Y'))) {
                return ['error' => 'Invalid date.', 'date' => null];
            }

            // Return formatted date for MariaDB
            return ['error' => null, 'date' => $d->format('Y-m-d')];
        }
    }