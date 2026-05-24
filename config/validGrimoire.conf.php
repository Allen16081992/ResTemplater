<?php // Code Convention: camelCase
    class validGrimoire {
        public static function emptyField(array $postData): array {
            $errors = [];
            $skip = ['action', 'headline', 'date', 'month'];

            foreach ($postData as $field => $value) {
                if (in_array($field, $skip, true)) { continue; }

                // Check if the value is an array (like 'skills')
                if (is_array($value)) {
                    // If the array is empty, it's an error
                    if (empty($value)) {
                        $errors[$field] = 'This field is required.';
                    }
                    // Optional: You could loop through $value here if you 
                    // need to validate nested fields specifically.
                } else {
                    // Standard string validation
                    if (trim((string)$value) === '') {
                        $errors[$field] = 'This field is required.';
                    }
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

        public static function validateAndFormatMonth(string $input, bool $isEndDate = false): array {
            // 1. Clean whitespace
            $input = trim(preg_replace('/\s+/', ' ', $input));

            // 2. Handle empty End Date fields as 'Present' (ongoing positions)
            if ($isEndDate && $input === '') {
                return ['error' => null, 'date' => 'Present'];
            }

            // 3. Handle normal required field validation
            if ($input === '') {
                return ['error' => 'Date is required.', 'date' => null];
            }

            $lower = strtolower($input);

            try {
                // 4. Intercept conversational natural text phrases
                if ($lower === 'today') {
                    $d = new DateTime('today');
                } elseif ($lower === 'tomorrow') {
                    $d = new DateTime('tomorrow');
                } elseif ($lower === 'yesterday') {
                    $d = new DateTime('yesterday');
                } else {
                    // 5. If it's a Chrome standard YYYY-MM, append the day so DateTime can parse it.
                    // Otherwise, let PHP try to parse the Firefox custom input string.
                    $parseInput = preg_match('/^\d{4}-\d{2}$/', $input) ? $input . '-01' : $input;
                    $d = new DateTime($parseInput);
                }
            } catch (Exception $e) {
                return ['error' => 'Invalid date format.', 'date' => null];
            }

            // 6. Double-check calendar authenticity (checks leap years, valid months, etc.)
            if (!checkdate((int)$d->format('m'), (int)$d->format('d'), (int)$d->format('Y'))) {
                return ['error' => 'Invalid calendar date.', 'date' => null];
            }

            // 7. Lock down output format to the 1st of the month for MariaDB DATE storage
            return ['error' => null, 'date' => $d->format('Y-m-01')];
        }
    }