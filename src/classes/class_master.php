<?php

    class ClassMaster {

        protected function fetchUser($formFields) {
            // Invoke the singleton DB and prepare SQL
            $db = Database::getInstance();
            $stmt = $db->connect()->prepare('SELECT userID, username, password FROM accounts WHERE username = :username OR email = :email;');

            // If this fails, abort.
            if(!$stmt->execute(array($formFields['email'], $formFields['pwd']))) {
                $_SESSION['error'] = 'User verification failed!';
                $this->breakRide($formFields);
            }

            // If we find nothing, abort.
            if($stmt->rowCount() == 0 ) {
                $_SESSION['error'] = 'Unable to find User!';
                $this->breakRide($formFields);
            }

            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            // If there was no match from the database, do this.
            if(!$userData) {
                $_SESSION['error'] = 'Unable to find User!';
                $this->breakRide($formFields);
            }

            $passHash = $userData['password'];   

            // Compare hashed passwords
            if (!password_verify($formFields['pwd'], $passHash)) {
                $_SESSION['error'] = "Incorrect password.";
                $this->breakRide($formFields);
            }

            $_SESSION['session_data']['user_id'] = $userData['userID'];
            $_SESSION['session_data']['user_name'] = !empty($userData['username']) ? ViewBook::e($userData['username']) : ViewBook::e($userData['firstname']);
            unset($stmt, $userData, $formFields, $passHash, $_POST);
        }

        protected function breakRide($field) {
            if ($field['action'] === 'login') {
                $_SESSION['login'] = true;
            }
            if ($field['action'] === 'signup') {
                $_SESSION['signup'] = true;
            }
            unset($stmt, $db, $field, $formFields, $_POST);
            header('location: ../../index.php');
            exit;
        }

        protected function XfetchUser($formFields) {
            // Invoke the singleton DB and prepare SQL
            $db = Database::getInstance();
            $stmt = $db->connect()->prepare('SELECT userID FROM accounts WHERE email = :email;');

            // If this fails, abort.
            if(!$stmt->execute(array($formFields['email']))) {
                $_SESSION['error'] = 'User verification failed!';
                $this->breakRide($formFields);
            }

            $userID = $stmt->fetchColumn();

            // If there was no match from the database, do this.
            if(!$userID) {
                $_SESSION['error'] = 'Unable to find User!';
                $this->breakRide($formFields);
            }


        }
        
    }