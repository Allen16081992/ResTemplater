<?php

    class validGrimoire {
        public static function emptyField($field) {
            return trim((string)$field);
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
    }