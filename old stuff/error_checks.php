<?php

    class AssWipe {

        public static function invalidCheck(mixed $value, string $rule): bool {
            switch ($rule) {
                case 'email':
                    return !filter_var((string)$value, FILTER_VALIDATE_EMAIL);

                case 'pwd_length':
                    return strlen((string)$value) < 8;

                case 'pwd_lower':
                    return !preg_match('/[a-z]/', (string)$value);

                case 'pwd_upper':
                    return !preg_match('/[A-Z]/', (string)$value);

                case 'pwd_number':
                    return !preg_match('/[0-9]/', (string)$value);

                case 'pwd_special':
                    return !preg_match('/[!@#$%^&*()_+=[\]{};:\'",<.>\/?\\|~-]/', (string)$value);

                default:
                    throw new InvalidArgumentException("Unknown rule: $rule");
            }
        }
        
        public function lol() {
            $email = 'email';
            if (AssWipe::invalidCheck($email, 'email')) {
                    $rule=null;
                $msg = [
                    // Rules
                    'required'    => 'This field is required.',
                    'email'       => 'Email is invalid.',
                    'pwd_length'  => 'Wachtwoord moet minstens 8 tekens lang zijn.',
                    'pwd_lower'   => 'Wachtwoord moet minstens één kleine letter bevatten.',
                    'pwd_upper'   => 'Wachtwoord moet minstens één hoofdletter bevatten.',
                    'pwd_number'  => 'Wachtwoord moet minstens één getal bevatten.',
                    'pwd_special' => 'Wachtwoord moet minstens één speciaal teken bevatten.',
                    // Bulletpoints
                    'names_rule'  => "Names can only contain:
                        ● Letters and spaces<br>
                        ● Punctuation marks.",
                    'usern_rule'  => "Usernames may contain:
                        ● Letters ● Numbers ● Underscores
                        ● Hyphens ● Periods.",
                    'pwd_rule'    => "Password must be:
                        ● Atleast 10 characters in length or more
                        ● Have Uppercase and lowercase letters
                        ● Have Numbers and special characters.",
                    // more sets here....
                ];

                $_SESSION['error'] = $msg[$rule] ?? 'Ongeldige invoer.';

            }
        }

        public static function emptyField($field) {
            return trim((string)$field) === '';
        }

        public static function checkAlpha(string $alpha): bool {
            return preg_match('/[a-zA-ZÀ-ÿ]/', $alpha);
        }

        public static function checkPwd(string $pwd): ?string {
            if (strlen($pwd) < 8) {
                return 'Wachtwoord moet minstens 8 tekens lang zijn.';
            }
            if (!preg_match('/[a-z]/', $pwd)) {
                return 'Wachtwoord moet minstens één kleine letter bevatten.';
            }
            if (!preg_match('/[A-Z]/', $pwd)) {
                return 'Wachtwoord moet minstens één hoofdletter bevatten.';
            }
            if (!preg_match('/[0-9]/', $pwd)) {
                return 'Wachtwoord moet minstens één getal bevatten.';
            }
            if (!preg_match('/[!@#$%^&*()_+=[\]{};:\'",<.>\/?\\|~-]/', $pwd)) {
                return 'Wachtwoord moet minstens één speciaal teken bevatten.';
            }
            return null; // Password passed all checks
        } 
        
        public static function checkDate(string $day, string $month, string $year): array {
            // Validate that all inputs are numeric
            if (!ctype_digit($day) || !ctype_digit($month) || !ctype_digit($year)) {
                return [
                    'valid' => false,
                    'error' => 'Date fields must contain digits only.'
                ];
            }
        
            // Validate converted dates
            if (!checkdate((int)$month, (int)$day, (int)$year)) {
                return [
                    'valid' => false,
                    'error' => 'The provided date is invalid.'
                ];
            }
        
            // Calculate age using DateTime for precision
            $birthDate = new DateTime("$year-$month-$day");
            $today = new DateTime();
            $age = $today->diff($birthDate)->y;
        
            // Check if age is within the acceptable range
            if ($age < 16) {
                return [
                    'valid' => false,
                    'error' => 'You must be at least 16 years old.'
                ];
            }
        
            // Return the date in DD-MM-YYYY format
            return [
                'valid' => true,
                'date' => sprintf('%02d-%02d-%04d', $day, $month, $year)
            ];
        }
    }