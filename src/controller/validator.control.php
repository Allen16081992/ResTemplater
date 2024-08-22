<?php // Code Convention: camelCase
    require_once __DIR__ . '../../session_manager.src.php';
    
    trait ErrorHandler {
        private function handleError($msg, $path) {
            $_SESSION['error'] = $msg;
            header('location: ' . $path);
            exit();
        }
    }

    trait Validators {
        private function emptyInput() {
            // Define an array of optional field names
            $optional = ['country', 'username'];

            foreach ($this->formFields as $fieldName => $fieldValue) {
                // Skip checking if the field is optional
                if (in_array($fieldName, $optional)) {
                    continue;
                }

                if (empty($fieldValue)) {
                    //$this->response['errors'][$fieldName] = 'Dit veld is verplicht';
                } 
            }
        }
        
        private function invalidInput() {
            // Validate firstname
            if (isset($this->formFields['firstname'])) {
                if (!preg_match('/[a-zA-ZÀ-ÿ]/', $this->formFields['firstname'])) {
                    //$this->response['errors']['firstname'] = 'Voornaam mag alleen letters bevatten.';
                }
            }

            // Validate lastname
            if (isset($this->formFields['lastname'])) {
                if (!preg_match('/[a-zA-ZÀ-ÿ]/', $this->formFields['lastname'])) {
                    //$this->response['errors']['lastname'] = 'Achternaam mag alleen letters bevatten.';
                }
            }

            // Validate country
            if (!empty($this->formFields['country'])) {
                if (!preg_match('/[a-zA-ZÀ-ÿ]/', $this->formFields['country'])) {
                    //$this->response['errors']['country'] = 'Nationaliteit mag alleen letters bevatten.';
                }
            }

            // Validate username
            if (!empty($this->formFields['username'])) {
                if (!preg_match('/^[a-zA-ZÀ-ÿ0-9_-]{3,20}$/', $this->formFields['username'])) {
                    //$this->response['errors']['username'] = 'Gebruikersnaam moet:<br>
                    //• Tussen 3 en 20 tekens lang zijn<br>
                    //• Alleen letters, cijfers, underscores_ en streepjes- bevatten.';
                }
            }

            // Combine day, month, and year into birth and remove the original fields
            if (!isset($this->formFields['day']) || !isset($this->formFields['month']) || !isset($this->formFields['year'])) {
                unset($this->formFields['day'], $this->formFields['month'], $this->formFields['year']);
                //$this->response['errors']['year'] = 'Geboortedatum is vereist.';
            } else {
                $this->formFields['birth'] = $this->formFields['day'] . '/' . $this->formFields['month'] . '/' . $this->formFields['year'];
                unset($this->formFields['day'], $this->formFields['month'], $this->formFields['year']);
            }

            // Validate phone
            if (isset($this->formFields['phone'])) {
                if (!preg_match('/^\+?[0-9]{1,3}?[-. ]?\(?[0-9]{1,4}?\)?[-. ]?[0-9]{1,4}[-. ]?[0-9]{1,9}$/', $this->formFields['phone'])) {
                    //$this->response['errors']['phone'] = 'Vul een geldig telefoonnummer in.';
                }
            }

            // Validate city
            if (isset($this->formFields['city'])) {
                if (!preg_match('/[a-zA-ZÀ-ÿ]/', $this->formFields['city'])) {
                    //$this->response['errors']['city'] = 'Woonplaats mag alleen letters bevatten.';
                }
            }

            // Validate postal
            if (isset($this->formFields['postal'])) {
                if (!preg_match('/[a-zA-ZÀ-ÿ0-9]/', $this->formFields['postal'])) {
                    //$this->response['errors']['postal'] = 'De postcode mag alleen letters en cijfers bevatten.';
                }
            }

            // Verify email element
            if (isset($this->formFields['email'])) {
                // Start validating...
                if (!filter_var($this->formFields['email'], FILTER_VALIDATE_EMAIL)) {
                    //$this->response['errors']['email'] = 'Vul een geldig e-mailadres in';
                }
            }

            // Avoid iteration overhead and improve memory efficiency
            // Verify password element
            if (isset($this->formFields['pwd'])) {
                // Start validating...
                if (strlen($this->formFields['pwd']) < 8) {
                    //$this->response['errors']['pwd'] = 'Wachtwoord moet minstens 8 tekens lang zijn.';
                }
                elseif (!preg_match('/[a-z]/', $this->formFields['pwd'])) {
                    //$this->response['errors']['pwd'] = 'Wachtwoord moet minstens één kleine letter hebben.';
                }
                elseif (!preg_match('/[A-Z]/', $this->formFields['pwd'])) {
                    //$this->response['errors']['pwd'] = 'Wachtwoord moet minstens één hoofdletter hebben.'; 
                }
                elseif (!preg_match('/[0-9]/', $this->formFields['pwd'])) {
                    //$this->response['errors']['pwd'] = 'Wachtwoord moet minstens één getal hebben.'; 
                }
                elseif (!preg_match('/[!@#$%^&*()_+=[\]{};:\'",<.>\/?\\|~-]/', $this->formFields['pwd'])) {
                    //$this->response['errors']['pwd'] = 'Wachtwoord moet minstens één speciale teken hebben.'; 
                }
            }
        }
    }