<?php
    // Load Database connection
    require_once __DIR__ . '../../database/singleton.db.php'; 
    require_once __DIR__ . '../../session_manager.src.php';   

    // Code Convention: PascalCase
    class Account {

        protected function signupUser($formFields) {   
            // Get the singleton database connection.
            $db = Database::getInstance();

            // Verify if the user already exists
            $stmt = $db->connect()->prepare('SELECT COUNT(*) FROM members WHERE email = :email;');

            // Bind values to prepared statements
            $stmt->bindParam(":email", $formFields['email']); 

            // If this fails, kick back to homepage.
            if (!$stmt->execute()) {
                unset($stmt, $formFields);
                $_SESSION['error'] = 'Request to database has failed.';
                header('location: ../signup.php'); 
                exit();
            }

            // If the user already exists.
            if(!$stmt->rowCount() == 0 ) {
                unset($stmt, $formFields);
                $_SESSION['error'] = 'Gebruiker bestaat al';
                header('location: ../login.php');
                exit();
            }
            $stmt = null;
            
            // Prepare the Argon2 Hashing Algorithm, Password Hashing Competition (PHC) 2015 winner
            $options = [
                'memory_cost' => 1<<17,   // 128 MB memory cost
                'time_cost' => 10,         // 4 iterations
                'threads' => 1            // 4 parallel threads
            ];
            
            $hashThis = password_hash($formFields['pwd'], PASSWORD_ARGON2I, $options);

            // Prepare SQL statement and bind parameters
            $stmt = $db->connect()->prepare("INSERT INTO accounts (username, password, email) VALUES (?, ?, ?);");

            // Verify if they submitted a username
            if (isset($formFields['username'])) {
                $stmt->bindParam(":username", $formFields['username']);
            } else {
                $name = "";
                $stmt->bindParam(":username", $name);
            }
            $stmt->bindParam(":hashThis", $hashThis);
            $stmt->bindParam(":email", $formFields['email']);

            // If this fails, kick back to homepage.
            if(!$stmt->execute()) {
                unset($stmt, $formFields);
                $_SESSION['error'] = 'Aanmelden mislukt.';
                header('location: ../signup.php'); 
                exit();
            }
            $stmt = null;

            // Immediately grab the newly generated userID
            $stmt = $db->connect()->prepare('SELECT userID FROM accounts WHERE email = :email;');
            $stmt->bindParam(":email", $formFields['email']);

            if (!$stmt->execute()) {
                unset($stmt, $formFields);
                $_SESSION['error'] = 'Request to database has failed.';
                header('location: ../signup.php'); 
                exit();
            }

            $userID = $stmt->fetchColumn();
            
            // Handle the case where userID is not found
            if (!$userID) {
                unset($stmt, $formFields);
                $_SESSION['error'] = 'Failed to retrieve userID.';
                header('location: ../index.php');
                exit();
            }

            // Insert contact information into the contact table
            $stmt = $db->connect()->prepare('INSERT INTO contact (phone, firstname, lastname, birth, nationality, postalcode, city, userID) VALUES (?, ?, ?, ?, ?, ?, ?, ?);');
            $stmt->bindParam(":phone", $formFields['phone']);
            $stmt->bindParam(":firstname", $formFields['firstname']);
            $stmt->bindParam(":lastname", $formFields['lastname']);
            $stmt->bindParam(":birth", $formFields['birth']);
            
            if (isset($formFields['country'])) {
                $stmt->bindParam(":country", $formFields['country']);
            } else {
                $country = "";
                $stmt->bindParam(":country", $country);
            }
            
            $stmt->bindParam(":postal", $formFields['postal']);
            $stmt->bindParam(":city", $formFields['city']);
            $stmt->bindParam(":uid", $userID);
            
            if (!$stmt->execute()) {
                unset($stmt, $formFields);
                $_SESSION['error'] = 'Request to database has failed.';
                header('location: ../signup.php'); 
                exit();
            }

            // Clean up variables from memory
            unset($stmt, $formFields);
            $_SESSION['success'] = 'Bedankt voor het aanmelden.<br> Welkom bij CV Templater.';
            header('location: ../login.php');
            exit();
        }

        // Fetch Info
        protected function Read_User($formFields) {

        } 
        
        protected function Update_User($formFields) {

        } 
        
        protected function Delete_User($formFields) {

        } 

        protected function loginUser($formFields) {
            // Get the singleton database connection.
            $db = Database::getInstance();

            // Prepare the SQL statement
            $stmt = $db->connect()->prepare("SELECT userID, username, password FROM accounts WHERE email = :email;");

            // Bind the parameters
            $stmt->bindParam(":email", $formFields['email']);
            //$stmt->bindParam(":password", $formFields['password']);

            // If this fails, kick back to homepage.
            if (!$stmt->execute()) {
                unset($stmt, $formFields);
                $_SESSION['error'] = 'Request to database has failed.';
                header('location: ../login.php'); 
                exit();
            }

            // If we got nothing from the database, do this.
            if(!$stmt->rowCount() == 0 ) {
                unset($stmt, $formFields);
                $_SESSION['error'] = 'Gebruiker niet gevonden';
                header('location: ../login.php');
                exit();
            }

            // Extract the hashed password from the fetched array.
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            // If there was no match from the database, do this.
            if(!$userData) {
                $_SESSION['error'] = 'Gebruiker laden mislukt';
                header('location: ../login.php');
                exit();
            }

            // Verify passwords
            if (!password_verify($formFields['password'], $userData['password'])) {
                unset($stmt, $formFields, $userData);
                $_SESSION['error'] = "Incorrect wachtwoord.";
                header('location: ../login.php');
                exit();
            }

            $_SESSION['user_id'] = $userData['userID'];
            $_SESSION['success'] = "Hallo, ".htmlspecialchars($userData['firstname']);

            // Verify if the user made a username
            if (isset($userData['username'])) { 
                $_SESSION['user_name'] = htmlspecialchars($userData['username']);
            } else { 
                $_SESSION['user_name'] = htmlspecialchars($userData['firstname']);         
            }

            // Clean up variables from memory
            unset($stmt, $formFields, $userData);
            header('location: ../client.php');
            exit();
        }
    }