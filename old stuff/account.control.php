<?php // Load PHP files      
    require_once './validator.control.php';

    class AccountControl extends Account {
        use ErrorHandler;
        use Validators;

        private $formFields;

        public function __construct($formFields) {
           $this->formFields = $formFields;
        }

        protected function pageInvoker() {
            switch (true) {
                case isset($this->formFields['loginBtn']):
                    $_SESSION['login'] = true;
                    header('location: ../index.php'); exit();
                    break;
            
                case isset($this->formFields['signupBtn']):
                    $_SESSION['signup'] = true;
                    header('location: ../index.php'); exit();
                    break;
            
                default:
                    // Optionally handle the case where none of the buttons are set.
                    $_SESSION['account'] = true;
                    header('location: ../client.php'); exit();
                    break;
            } 
        }

        public function verifyData() {
            // Loop over each field
            if(isset($this->formFields)) {
                foreach ($this->formFields as $fieldName => $fieldValue) {
                    // Define an array of optional field names
                    $optional = ['country', 'username'];

                    // Skip checking if the field is optional
                    if (in_array($fieldName, $optional)) {
                        continue;
                    }

                    if ($this->emptyInput($fieldValue)) {
                        $_SESSION['error'] = $fieldName.' veld is verplicht';
                    } 
                }
                $this->pageInvoker();
            }

            // Validate firstname
            if (isset($this->formFields['firstname'])) {
                if ($this->invalidInput($this->formFields['firstname'])) {
                    $_SESSION['error'] = 'Voornaam mag alleen letters bevatten.';
                    $this->pageInvoker();
                }
            }

            // Validate lastname
            elseif (isset($this->formFields['lastname'])) {
                if ($this->invalidInput($this->formFields['lastname'])) {
                    $_SESSION['error'] = 'Achternaam mag alleen letters bevatten.';
                    $this->pageInvoker();
                }
            }

            // Validate username
            elseif (isset($this->formFields['username'])) {
                if ($this->invalidInput($this->formFields['username'])) {
                    $_SESSION['error'] = 'Gebruikersnaam moet:<br>
                    //• Tussen 3 en 20 tekens lang zijn<br>
                    //• Alleen letters, cijfers, underscores_ en streepjes- bevatten.';
                    $this->pageInvoker();
                }
            }

            // Validate phone
            elseif (isset($this->formFields['phone'])) {
                if ($this->invalidInput($this->formFields['phone'])) {
                    $_SESSION['error'] = 'Vul een geldig telefoonnummer in.';
                    $this->pageInvoker();
                }
            }

            // Validate city
            elseif (isset($this->formFields['city'])) {
                if ($this->invalidInput($this->formFields['city'])) {
                    $_SESSION['error'] = 'Woonplaats mag alleen letters bevatten.';
                    $this->pageInvoker();
                }
            }

            // Validate postal
            elseif (isset($this->formFields['postal'])) {
                if ($this->invalidInput($this->formFields['postal'])) {
                    $_SESSION['error'] = 'De postcode mag alleen letters en cijfers bevatten.';
                    $this->pageInvoker();
                }
            }

            // Validate email
            elseif (isset($this->formFields['email'])) {
                if ($this->invalidInput($this->formFields['email'])) {
                    $_SESSION['error'] = 'Vul een geldig e-mailadres in';
                    $this->pageInvoker();
                }
            }

            // Validate password
            elseif (isset($this->formFields['pwd'])) {
                if (strlen($this->formFields['pwd']) < 8) {
                    $_SESSION['error'] = 'Wachtwoord moet minstens 8 tekens lang zijn.';
                    $this->pageInvoker();
                }
                elseif (!preg_match('/[a-z]/', $this->formFields['pwd'])) {
                    $_SESSION['error'] = 'Wachtwoord moet minstens één kleine letter hebben.';
                    $this->pageInvoker();
                }
                elseif (!preg_match('/[A-Z]/', $this->formFields['pwd'])) {
                    $_SESSION['error'] = 'Wachtwoord moet minstens één hoofdletter hebben.'; 
                    $this->pageInvoker();
                }
                elseif (!preg_match('/[0-9]/', $this->formFields['pwd'])) {
                    $_SESSION['error'] = 'Wachtwoord moet minstens één getal hebben.'; 
                    $this->pageInvoker();
                }
                elseif (!preg_match('/[!@#$%^&*()_+=[\]{};:\'",<.>\/?\\|~-]/', $this->formFields['pwd'])) {
                    $_SESSION['error'] = 'Wachtwoord moet minstens één speciale teken hebben.'; 
                    $this->pageInvoker();
                }
            }

            // Validate Dates
            else {
                if (!isset($this->formFields['day']) || !isset($this->formFields['month']) || !isset($this->formFields['year'])) {
                    unset($this->formFields['day'], $this->formFields['month'], $this->formFields['year']);
                    $_SESSION['error'] = 'Geboortedatum is vereist.';
                    $this->pageInvoker();
                } else {
                    $this->formFields['birth'] = $this->formFields['day'].'/'.$this->formFields['month'].'/'.$this->formFields['year'];
                    unset($this->formFields['day'], $this->formFields['month'], $this->formFields['year']);
                }
            }

            // Continue processing
            if (isset($formFields['loginBtn'])) {
                $this->loginUser($this->formFields);

            } elseif (isset($formFields['signupBtn'])) {
                $this->signupUser($this->formFields);

            } elseif (isset($formFields['savePersonal'])) {
                $this->setPersonal($this->formFields);

            } elseif (isset($formFields['saveAccount'])) {
                $this->setAccount($this->formFields);
                $this->setAddress($this->formFields);

            } elseif (isset($formFields['trashAccount'])) {
                $this->unsetAccount($this->formFields);

            }
        }
    }