<?php
    // Load Database connection
    require_once '../database/singleton.db.php';

    class Users {

        protected function getUser($formFields) {
            // Get the singleton instance of the Database class to establish a database connection.
            $db = Database::getInstance();

            $stmt = $db->connect()->prepare("SELECT userID, username, 'password' FROM accounts WHERE username = :uid OR email = :uid;");
            $stmt->bindParam(":uid", $formFields['uid']);
            $stmt->bindParam(":password", $formFields['password']);

            // If this fails, kick back to homepage.
            if (!$stmt->execute()) {
                $stmt = null;
                // Push the server error
                $serverMsg = 'Request to database has failed.';
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($serverMsg);
                exit();
            }

            // If we got nothing from the database, do this.
            if($stmt->rowCount() == 0 ) {
                $stmt = null;
                $serverMsg = 'Unable to find User.';
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($serverMsg);
                exit();
            }

            // Extract the hashed password from the fetched array.
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify passwords
            if (!password_verify($formFields['password'], $userData['password'])) {
                $serverMsg = 'Het wachtwoord is fout.';
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($serverMsg);
                exit();
            }

            $_SESSION['user_id'] = $userData['userID'];
            $_SESSION['user_name'] = htmlspecialchars($userData['username']);
            $_SESSION['success'] = "Hallo, ".htmlspecialchars($userData['username']);

            // Clean up variables from memory
            unset($userData, $stmt, $db);
        }
    }