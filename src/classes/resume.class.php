<?php
    // Load Database connection
    require_once __DIR__ . '../../database/singleton.db.php'; 
    require_once __DIR__ . '../../session_manager.src.php';
    require_once __DIR__ . '../../controller/validator.control.php';

    // Code Convention: PascalCase
    class Resume {
        use Rebound;

        protected function createResume() {

        }

        protected function readResume() {
            // Get the singleton database connection.
            $db = Database::getInstance();

            // Prepare SQL statement.
            $stmt = $db->connect()->prepare('SELECT resumeID, resumetitle FROM `resume` WHERE userID = :userID AND resID = :resid');
            $stmt->bindParam(":userID", $_SESSION['user_id']);

            if (!$stmt->execute()) {
                unset($stmt);
                $this->handleError('Gegevens ophalen mislukt.', '../client.php');
            }

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $userData = array_map('htmlspecialchars', $user);
            return ['resume' => $userData];
        }

        protected function updateResume() {

        }

        protected function deleteResume() {

        }

        protected function findResumeTitles() {

            // Verify if the user ID exists
            if (isset($_SESSION['user_id'])) {
                $userID = $_SESSION['user_id'];

                // Get the singleton database connection.
                $db = Database::getInstance();

                // Prepare SQL statement.
                $stmt = $db->connect()->prepare('SELECT resumetitle FROM `resume` WHERE userID = ?');
                $stmt->bindParam(":userID", $userID);
            }

        }
    }