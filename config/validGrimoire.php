<?php // Code Convention: camelCase
    class validGrimoire {
        public static function emptyField(array $postData): array {
            $errors = [];
            $skip = ['action', 'headline', 'date', 'month', 'csrf_token'];

            // Map specific fields to custom human-friendly messages
            $customMessages = [
                'resume_id' => 'Please select a resume first.',
                'fullname'  => 'We need your name for the header.'
            ];

            foreach ($postData as $field => $value) {
                if (in_array($field, $skip, true)) { continue; }

                // Determine if the field is "empty"
                $isEmpty = is_array($value) ? empty($value) : trim((string)$value) === '';

                if ($isEmpty) {
                    // Use custom message if it exists, otherwise use the default
                    $errors[$field] = $customMessages[$field] ?? 'This field is required.';
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
            
            // 1. Structural Checks (Crucial for DB and UI integrity)
            if ($required && $value === '') { return 'This field is required.'; }
            if ($value === '') { return null; }
            if (mb_strlen($value, 'UTF-8') > $maxLen) { return "Maximum {$maxLen} characters allowed."; }

            // 2. Security Check: Disallow hidden ASCII control characters 
            // This stops null bytes (\x00) and line breaks (\x0A/\x0D) from breaking FPDF or DB inserts
            if (preg_match('/[\x00-\x1F\x7F]/u', $value)) {
                return 'Contains invalid system control characters.';
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

        public static function pwHasher(string $pwd): string {
            return password_hash($pwd, PASSWORD_ARGON2ID, [
                'memory_cost' => 65536, // 64 MiB (in KiB)
                'time_cost'   => 2,
                'threads'     => 1,
            ]);
        }

        public static function checkHash(string $pwd, string $hash): bool {
            return $hash !== '' && password_verify($pwd, $hash);
        }
    }