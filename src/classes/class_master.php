<?php

    class Account {
        protected function fetchUser($formFields) {
            // Invoke the singleton DB and prepare SQL
            $db = Database::getInstance();
            $stmt = $db->connect()->prepare('SELECT userID, username, password FROM accounts WHERE username = :username OR email = :email;');

            // If this fails, abort.
            if(!$stmt->execute(array($formFields['email'], $formFields['pwd']))) {
                $stmt = null;
                $_SESSION['error'] = 'User verification failed!';
                ViewBook::breakRide(null, $formFields['action']);
            }

            // If we find nothing, abort.
            if($stmt->rowCount() == 0 ) {
                $stmt = null;
                $_SESSION['error'] = 'Unable to find User!';
                ViewBook::breakRide(null, $formFields['action']);
            }

            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            // If there was no match from the database, do this.
            if(!$userData) {
                $_SESSION['error'] = 'Unable to find User!';
                ViewBook::breakRide(null, $formFields['action']);
            }

            $passHash = $userData['password'];   

            // Compare hashed passwords
            if (!password_verify($formFields['pwd'], $passHash)) {
                $_SESSION['error'] = "Incorrect password.";
                ViewBook::breakRide(null, $formFields['action']);
            }

            $_SESSION['session_data']['user_id'] = $userData['userID'];
            $_SESSION['session_data']['user_name'] = !empty($userData['username']) ? ViewBook::e($userData['username']) : ViewBook::e($userData['firstname']);
            unset($stmt, $userData, $formFields, $passHash);
        }  

        protected function verifyUser($formFields) {
            // Invoke the singleton DB and prepare SQL
            $db = Database::getInstance();
            $stmt = $db->connect()->prepare('SELECT userID FROM accounts WHERE username = :username OR email = :email;');

            // If this fails, abort.
            if(!$stmt->execute(array($formFields['email'], $formFields['uid']))) {
                $stmt = null;
                $_SESSION['error'] = 'User verification failed!';
                ViewBook::breakRide(null, $formFields['action']);
            }
        }
    }