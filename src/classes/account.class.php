<?php
    // Load Database connection
    require_once __DIR__ . '../../database/singleton.db.php'; 
    require_once __DIR__ . '../../session_manager.src.php';
    require_once __DIR__ . '../../controller/validator.control.php';

    // Code Convention: PascalCase
    class Account {
        use Rebound;

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

        // Fetch Info
        protected function readUser() {
            // Get the singleton database connection.
            $db = Database::getInstance();

            // Prepare SQL statement.
            $stmt = $db->connect()->prepare('SELECT username, email FROM accounts WHERE userID = :userID');
            $stmt->bindParam(":userID", $_SESSION['user_id']);

            if (!$stmt->execute()) {
                unset($stmt);
                $this->handleError('Database aanvraag mislukt.', '../client.php');
            }

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $account = array_map('htmlspecialchars', $user);
            return ['account' => $account];
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

            // If this fails, kick back to homepage.
            if (!$stmt->execute()) {
                unset($stmt, $formFields);
                $this->handleError('Database aanvraag mislukt.', '../login.php');
            }

            // If we got nothing from the database, do this.
            if($stmt->fetchColumn() == 0) {
                unset($stmt, $formFields);
                $this->handleError('Gebruiker niet gevonden.', '../login.php');
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

            $_SESSION['user_id'] = $userData['userID'];
            $_SESSION['success'] = "Hallo, ".htmlspecialchars($userData['firstname']);

            // Verify if the user made a username
            $_SESSION['user_name'] = isset($userData['username']) ? htmlspecialchars($userData['username']) : htmlspecialchars($userData['firstname']);

            // Clean up variables from memory
            unset($stmt, $formFields, $userData);
            header('location: ../client.php');
            exit();
        }
    }