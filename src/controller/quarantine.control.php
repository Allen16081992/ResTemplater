<?php
    class Validate {
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
    }