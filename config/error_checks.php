<?php

    class validGrimoire {
        public static function emptyField($field) {
            return trim((string)$field) === '';
        }

        public static function checkEmail(string $email): bool {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
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