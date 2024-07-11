<?php // Set this at the top to ensure all outputs are treated as JSON
    header('Content-Type: application/json'); 

    // Code Convention: camelCase
    trait Validators {

        private function emptyInput() {
            foreach ($this->formFields as $fieldName => $fieldValue) {
                if (empty($fieldValue)) {
                    $this->response['errors'][$fieldName] = 'Dit veld is verplicht';
                } 
            }
        }
        
        private function invalidInput() {
            // Validate firstname
            if (isset($this->formFields['firstname'])) {
                if (!preg_match('/[a-zA-ZÀ-ÿ]/', $this->formFields['firstname'])) {
                    $this->response['errors']['firstname'] = 'Dit bevat ongeldige tekens.';
                }
            }

            // Validate lastname
            if (isset($this->formFields['lastname'])) {
                if (!preg_match('/[a-zA-ZÀ-ÿ]/', $this->formFields['lastname'])) {
                    $this->response['errors']['lastname'] = 'Dit bevat ongeldige tekens.';
                }
            }

            // Validate country
            if (isset($this->formFields['country'])) {
                if (!preg_match('/[a-zA-ZÀ-ÿ]/', $this->formFields['country'])) {
                    $this->response['errors']['country'] = 'Dit bevat ongeldige tekens.';
                }
            }

            // Validate username
            if (isset($this->formFields['username'])) {
                if (!preg_match('/^[a-zA-ZÀ-ÿ0-9_-]{3,20}$/', $this->formFields['username'])) {
                    $this->response['errors']['username'] = 'Gebruikersnaam moet tussen 3 en 20 tekens lang zijn en mag alleen letters, cijfers, underscores en streepjes bevatten.';
                }
            }

            // Combine day, month, and year into birth and remove the original fields
            if (isset($this->formFields['day']) && isset($this->formFields['month']) && isset($this->formFields['year'])) {
                $this->formFields['birth'] = $this->formFields['day'] . '/' . $this->formFields['month'] . '/' . $this->formFields['year'];
                unset($this->formFields['day'], $this->formFields['month'], $this->formFields['year']);
            } else {
                $this->response['errors']['year'] = 'Geboortedatum is vereist.';
            }

            // Validate phone
            if (isset($this->formFields['phone'])) {
                $phone = $this->formFields['phone'];
                if (!preg_match('/^\+?[0-9]{1,3}?[-. ]?\(?[0-9]{1,4}?\)?[-. ]?[0-9]{1,4}[-. ]?[0-9]{1,9}$/', $phone)) {
                    $this->response['errors']['phone'] = 'Vul een geldig telefoonnummer in.';
                }
            }

            // Validate city
            if (isset($this->formFields['city'])) {
                if (!preg_match('/[a-zA-ZÀ-ÿ]/', $this->formFields['city'])) {
                    $this->response['errors']['city'] = 'Dit bevat ongeldige tekens.';
                }
            }

            // Validate postal
            if (isset($this->formFields['postal'])) {
                if (!preg_match('/[a-zA-ZÀ-ÿ0-9]/', $this->formFields['postal'])) {
                    $this->response['errors']['postal'] = 'Dit bevat ongeldige tekens.';
                }
            }

            // Verify email element
            if (isset($this->formFields['email'])) {
                // Start validating...
                if (!filter_var($this->formFields['email'], FILTER_VALIDATE_EMAIL)) {
                    $this->response['errors']['email'] = 'Vul een geldig e-mailadres in';
                }
            }

            // Avoid iteration overhead and improve memory efficiency
            // Verify password element
            if (isset($this->formFields['pwd'])) {
                // Start validating...
                if (strlen($this->formFields['pwd']) < 8) {
                    $this->response['errors']['pwd'] = 'Wachtwoord moet minstens 8 tekens lang zijn.';
                }
                elseif (!preg_match('/[a-z]/', $this->formFields['pwd'])) {
                    $this->response['errors']['pwd'] = 'Wachtwoord moet minstens één kleine letter hebben.';
                }
                elseif (!preg_match('/[A-Z]/', $this->formFields['pwd'])) {
                    $this->response['errors']['pwd'] = 'Wachtwoord moet minstens één hoofdletter hebben.'; 
                }
                elseif (!preg_match('/[0-9]/', $this->formFields['pwd'])) {
                    $this->response['errors']['pwd'] = 'Wachtwoord moet minstens één getal hebben.'; 
                }
                elseif (!preg_match('/[!@#$%^&*()_+=[\]{};:\'",<.>\/?\\|~-]/', $this->formFields['pwd'])) {
                    $this->response['errors']['pwd'] = 'Wachtwoord moet minstens één speciale teken hebben.'; 
                }
            }
        }
    }