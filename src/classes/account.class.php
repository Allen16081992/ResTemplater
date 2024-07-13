<?php
    // Load Database connection

    // For some unknown strange reason, any 'require_once' placed outside the class, (which is normal practice),
    // causes Javascript to break and presistently report 'not valid JSON'. 
    // So now our db file is moved into the functions which seems to solve the issue.

    // Code Convention: PascalCase
    class Account {

        // Register
        protected function signupUser($formFields) {
            // Load Database connection
            require_once '../database/singleton.db.php';       

            // Get the singleton database connection.
            $db = Database::getInstance();

            // Verify if the user already exists
            $stmt = $db->connect()->prepare('SELECT COUNT(*) FROM members WHERE username = :username OR email = :email;');

            // Bind values to prepared statements
            if (isset($formFields['username'])) {
                $stmt->bindParam(":username", $formFields['username']);
            } 
            $stmt->bindParam(":email", $formFields['email']);

            // If this fails, kick back to homepage.
            if (!$stmt->execute()) {
                $stmt = null;
                header('Content-Type: application/json'); 
                $response['errors']['email'] = 'Request to database has failed.';
                return $response;
            }
            
            // Extract the submitted values from the formFields array.
            if (isset($formFields['country'])) {
                $stmt->bindParam(":country", $formFields['country']);
            }

            $email = $formFields['email'];
        }

        // Fetch Info
        protected function Read_User($formFields) {
            // Load Database connection
            require_once '../database/singleton.db.php';
        } 
        
        protected function Update_User($formFields) {
            // Load Database connection
            require_once '../database/singleton.db.php';
        } 
        
        protected function Delete_User($formFields) {
            // Load Database connection
            require_once '../database/singleton.db.php';
        } 

        // Login
        protected function loginUser($formFields) {
             // Load Database connection
            require_once '../database/singleton.db.php';
            
            // Get the singleton database connection.
            $db = Database::getInstance();

            $stmt = $db->connect()->prepare("SELECT userID, username, 'password' FROM accounts WHERE email = :email;");
            $stmt->bindParam(":email", $formFields['email']);
            $stmt->bindParam(":password", $formFields['password']);

            // If this fails, kick back to homepage.
            if (!$stmt->execute()) {
                $stmt = null;
                header('Content-Type: application/json'); 
                $response['errors']['email'] = 'Request to database has failed.';
                return $response;
            }

            // If we got nothing from the database, do this.
            if($stmt->rowCount() == 0 ) {
                $stmt = null;
                header('Content-Type: application/json'); 
                $response['errors']['email'] = 'Unable to find User.';
                return $response;
            }

            // Extract the hashed password from the fetched array.
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify passwords
            if (!password_verify($formFields['password'], $userData['password'])) {
                header('Content-Type: application/json');
                $response['errors']['email'] = 'Incorrect wachtwoord.';
                return $response;
            }

            $_SESSION['user_id'] = $userData['userID'];
            $_SESSION['success'] = "Hallo, ".htmlspecialchars($userData['username']);

            // Verify if the user made a username
            if (isset($userData['username'])) { 
                $_SESSION['user_name'] = htmlspecialchars($userData['username']);
            } else { 
                $_SESSION['user_name'] = htmlspecialchars($userData['firstname']); 
            }

            // Clean up variables from memory
            unset($userData, $stmt, $db);
            header('location: ../client.php?');
            exit();
        }
    }