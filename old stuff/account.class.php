<?php
    // Load Database connection
    require_once './src/database/singleton.db.php';

    // Code Convention: PascalCase
    class Account {
        use ErrorHandler;
        
        protected function setAccount($formFields) {

            if (isset($_SESSION['session_data']['user_id'])) {
                // Get the singleton database connection.
                $db = Database::getInstance();
        
                if (!empty($formFields['pwd'])) {
                    // New password provided, update it. 
                    $HashThisNOW = password_hash($formFields['pwd'], PASSWORD_BCRYPT); //(PASSWORD_DEFAULT replaced.)
                    $stmt = $db->connect()->prepare("UPDATE accounts SET username = :username, email = :email, `password` = :passw WHERE userID = :userID;");
                    $stmt->bindParam(":username", $formFields['username']); 
                    $stmt->bindParam(":email", $formFields['email']); 
                    $stmt->bindParam(":passw", $HashThisNOW); 
                    $stmt->bindParam(":userID", $formFields['uid']); 
                } else {
                    // No new password provided, skip the password update.
                    $stmt = $db->connect()->prepare("UPDATE accounts SET username = :username, email = :email WHERE userID = :userID;");
                    $stmt->bindParam(":username", $formFields['username']); 
                    $stmt->bindParam(":email", $formFields['email']); 
                    $stmt->bindParam(":userID", $formFields['uid']); 
                }

                // If this fails, kick back to homepage.
                if (!$stmt->execute()) {
                    unset($stmt, $formFields);
                    $this->handleError('Gegevens bijwerken mislukt.', '../client.php');
                }

                // Clean up variables from memory
                unset($stmt, $formFields);
                $_SESSION['account'] = true;
                $_SESSION['success'] = 'Account Information saved';
                header('location: ../client.php');
                exit();
            }
        } 
        
        protected function setAddress($formFields) {

            if(isset($_SESSION['session_data']['user_id'])) {
                // Get the singleton database connection.
                $db = Database::getInstance();

                // Prepare the SQL statement
                if (!empty($formFields['country'])) {
                    $stmt = $db->connect()->prepare("UPDATE contact SET postalcode = :postal, city = :city, country = :country WHERE userID = :userID;");  
                    $stmt->bindParam(":postal", $formFields['postal']); 
                    $stmt->bindParam(":city", $formFields['city']); 
                    $stmt->bindParam(":country", $formFields['country']);
                    $stmt->bindParam(":userID", $formFields['uid']); 
                } else {
                    $stmt = $db->connect()->prepare("UPDATE contact SET postalcode = :postal, city = :city WHERE userID = :userID;");  
                    $stmt->bindParam(":postal", $formFields['postal']); 
                    $stmt->bindParam(":city", $formFields['city']); 
                    $stmt->bindParam(":userID", $formFields['uid']); 
                }

                // If this fails, kick back to homepage.
                if (!$stmt->execute()) {
                    unset($stmt, $formFields);
                    $this->handleError('Gegevens bijwerken mislukt.', '../client.php');
                }

                // Clean up variables from memory
                unset($stmt, $formFields);
                $_SESSION['account'] = true;
                $_SESSION['success'] = 'Address Information saved';
                header('location: ../client.php');
                exit();
            }
        } 

        protected function setPersonal($formFields) {
            
            if(isset($_SESSION['session_data']['user_id'])) {

                // Get the singleton database connection.
                $db = Database::getInstance();

                // Prepare the SQL statement
                $stmt = $db->connect()->prepare("UPDATE contact SET firstname = :firstname, lastname = :lastname, phone = :phone, birth = :birth WHERE userID = :userID;");  
                $stmt->bindParam(":firstname", $formFields['firstname']); 
                $stmt->bindParam(":lastname", $formFields['lastname']); 
                $stmt->bindParam(":phone", $formFields['phone']);
                $stmt->bindParam(":birth", $formFields['birth']);
                $stmt->bindParam(":userID", $formFields['uid']);

                // If this fails, kick back to homepage.
                if (!$stmt->execute()) {
                    unset($stmt, $formFields);
                    $this->handleError('Gegevens bijwerken mislukt.', '../client.php');
                }

                // Clean up variables from memory
                unset($stmt, $formFields);
                $_SESSION['account'] = true;
                $_SESSION['success'] = 'Address Information saved';
                header('location: ../client.php');
                exit();
            }
        } 

        protected function signupUser($formFields) {   
            // Get the singleton database connection.
            $db = Database::getInstance();

            // Verify if the user already exists
            $stmt = $db->connect()->prepare('SELECT COUNT(*) FROM members WHERE email = :email;');
            $stmt->bindParam(":email", $formFields['email']); 

            // If this fails, kick back to homepage.
            if (!$stmt->execute()) {
                unset($stmt, $formFields);
                $this->handleError('Database aanvraag mislukt.', '../signup.php');
            }

            // If the user already exists.
            if($stmt->fetchColumn() > 0) {
                unset($stmt, $formFields);
                $this->handleError('Gebruiker bestaat al.', '../signup.php');
            }
            
            // Prepare the Argon2 Hashing Algorithm, Password Hashing Competition (PHC) 2015 winner
            $options = [
                'memory_cost' => 1<<17,   // 128 MB memory cost
                'time_cost' => 10,         // 4 iterations
                'threads' => 1            // 4 parallel threads
            ];
            
            $hashThis = password_hash($formFields['pwd'], PASSWORD_ARGON2I, $options);

            // Verify if a username was submitted
            $username = isset($formFields['username']) ? $formFields['username'] : null;

            // Prepare SQL statement and bind parameters
            $stmt = null;
            $stmt = $db->connect()->prepare("INSERT INTO accounts (username, password, email) VALUES (:username, :password, :email);");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashThis);
            $stmt->bindParam(':email', $formFields['email']);

            // If this fails, kick back to homepage.
            if(!$stmt->execute()) {
                unset($stmt, $formFields);
                $this->handleError('Gebruiker aanmaken mislukt.', '../signup.php');
            }

            // Immediately grab the newly generated userID
            $stmt = null;
            $stmt = $db->connect()->prepare('SELECT userID FROM accounts WHERE email = :email;');
            $stmt->bindParam(":email", $formFields['email']);

            if (!$stmt->execute()) {
                unset($stmt, $formFields);
                $this->handleError('Database aanvraag mislukt.', '../signup.php');
            }

            $userID = $stmt->fetchColumn();
            
            // Handle the case where userID is not found
            if (!$userID) {
                unset($stmt, $formFields);
                $this->handleError('Nieuwe Gebruiker niet gevonden.', '../signup.php');
            }

            // Verify if a username was submitted
            $country = isset($formFields['country']) ? $formFields['country'] : null;

            // Insert contact information into the contact table
            $stmt = $db->connect()->prepare('INSERT INTO contact (phone, firstname, lastname, birth, nationality, postalcode, city, userID) VALUES (:phone, :firstname, :lastname, :birth, :country, :postalcode, :city, :userID);');
            $stmt->bindParam(":phone", $formFields['phone']);
            $stmt->bindParam(":firstname", $formFields['firstname']);
            $stmt->bindParam(":lastname", $formFields['lastname']);
            $stmt->bindParam(":birth", $formFields['birth']);
            $stmt->bindParam(":country", $country);
            $stmt->bindParam(":postalcode", $formFields['postal']);
            $stmt->bindParam(":city", $formFields['city']);
            $stmt->bindParam(":userID", $userID);
            
            if (!$stmt->execute()) {
                unset($stmt, $formFields);
                $this->handleError('Database aanvraag mislukt.', '../signup.php');
            }

            // Clean up variables from memory
            unset($stmt, $formFields);
            $_SESSION['success'] = 'Bedankt voor het aanmelden.<br> Welkom bij CV Templater.';
            header('location: ../login.php');
            exit();
        }

        protected function loginUser($formFields) {
            // Get the singleton database connection.
            $db = Database::getInstance();

            // Prepare the SQL statement
            $stmt = $db->connect()->prepare("SELECT userID, username, password FROM accounts WHERE email = :email;");

            // Bind the parameters
            $stmt->bindParam(":email", $formFields['email']);

            // If this fails, kick back to homepage.
            if (!$stmt->execute()) {
                unset($stmt, $formFields);
                $_SESSION['login'] = true;
                $this->handleError('Database aanvraag mislukt.', '../index.php');
            }

            // If we got nothing from the database, do this.
            if($stmt->fetchColumn() == 0) {
                unset($stmt, $formFields);
                $_SESSION['login'] = true;
                $this->handleError('Gebruiker niet gevonden.', '../index.php');
            }

            // Extract the hashed password from the fetched array.
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            // If there was no match from the database, do this.
            if(!$userData) {
                unset($stmt, $userData, $formFields);
                $this->handleError('Gebruiker ophalen mislukt.', '../login.php');
            }

            // Verify passwords
            if (!password_verify($formFields['password'], $userData['password'])) {
                unset($stmt, $formFields, $userData);
                $this->handleError('Wachtwoord is fout.', '../login.php');
            }

            $_SESSION['success'] = 'Hallo, '.htmlspecialchars($userData['firstname']);
            $_SESSION['session_data'] = [
                'user_id' => $userData['userID'],
                'user_name' => isset($userData['username']) ? htmlspecialchars($userData['username']) : htmlspecialchars($userData['firstname'])
            ];

            // Clean up variables from memory
            unset($stmt, $formFields, $userData);
            header('location: ../client.php');
            exit();
        }

        protected function unsetAccount($formFields) {
            // Get the singleton database connection.
            $db = Database::getInstance();
        }
    }