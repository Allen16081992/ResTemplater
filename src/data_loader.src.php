<?php
    // Load PHP files
    require_once 'database/singleton.db.php';

    class LoadUserData {

        // Properties
        private $db;

        // Methods
        public function __construct() {
            // Get the singleton database connection.
            $this->db = Database::getInstance();
        }

        // Centralized method to verify if a query executes successfully
        private function verifyQuery($stmt) {
            if (!$stmt->execute()) {
                $_SESSION['error']['503'] = '503: Service unavailable.';
                header('location: client.php');
                exit();
            }
        }

        public function LoadUserdata() {
            // Verify if the user ID exists
            if (isset($_SESSION['session_data']['user_id'])) {
                $userID = $_SESSION['session_data']['user_id'];

                try {
                    //===== USER DATA COLLECTION =====//

                    // Prepare SQL statements
                    $stmtA = $this->db->connect()->prepare('SELECT username, email FROM `accounts` WHERE userID = :userID');
                    $stmtB = $this->db->connect()->prepare('SELECT firstname, lastname, phone, birth, country, postalcode, city FROM `contact` WHERE userID = :userID');

                    // Bind parameters
                    $stmtA->bindParam(":userID", $userID);
                    $stmtB->bindParam(":userID", $userID);

                    // Verify query execution
                    $this->verifyQuery($stmtA);
                    $this->verifyQuery($stmtB);

                    // Fetch account and contact data
                    $account = $stmtA->fetch(PDO::FETCH_ASSOC);
                    $contact = $stmtB->fetch(PDO::FETCH_ASSOC);

                    // Cleanup statement objects after use
                    unset($stmtA, $stmtB);

                    // Handle fetch failures
                    if (!$account || !$contact) {
                        unset($account, $contact, $userID);
                        $_SESSION['error']['503'] = 'User information not found';
                        header('location: client.php');
                        exit();
                    }

                    //===== RESUME DATA COLLECTION =====//

                    // Prepare SQL statements, bind parameters and verify execution
                    $stmt = $this->db->connect()->prepare('SELECT resumeID, resumetitle FROM `resume` WHERE userID = :userID');
                    $stmt->bindParam(":userID", $userID);
                    $this->verifyQuery($stmt);

                    // Fetch resume data and check for errors
                    $resume = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Cleanup after successful fetch
                    unset($stmt);

                    // Handle fetch failures
                    if (!$resume) {
                        unset($resume, $userID);
                        $_SESSION['error']['503'] = 'User information not found';
                        header('location: client.php');
                        exit();
                    }

                    // Return the combined user, contact, and resume data
                    return ['account' => $account, 'contact' => $contact, 'resume' => $resume];

                } catch (PDOException $e) {
                    // Handle unexpected PDO exceptions
                    error_log('Database error during LoadUserData: ' . $e->getMessage());
                    $_SESSION['error']['503'] = '503: Service unavailable.';
                    header('location: client.php');
                    exit();
                }
            }

            // Set error and return an empty array if user ID is not set
            $_SESSION['error']['503'] = 'User ID is missing.';
            header('location: client.php');
            exit();
        }
    }

    class LoadResumeData {
        // Properties
        private $db;

        // Methods
        public function __construct() {
            // Get the singleton database connection.
            $this->db = Database::getInstance();
        }

        // Centralized method to verify if a query executes successfully
        private function verifyQuery($stmt) {
            if (!$stmt->execute()) {
                $_SESSION['error']['503'] = '503: Service unavailable.';
                header('location: client.php');
                exit();
            }
        } 
        
        public function LoadResumeData($resid, $userID) {

            try {
                // Prepare SQL statements
                $stmtA = $this->db->connect()->prepare('SELECT profileID, profiledesc, filePath, `fileName` FROM `profile` WHERE resumeID = :resumeID AND userID = :userID');
                $stmtB = $this->db->connect()->prepare('SELECT workID, worktitle, workdesc, company, firstDate, lastDate FROM `experience` WHERE resumeID = :resumeID AND userID = :userID');
                $stmtC = $this->db->connect()->prepare('SELECT eduID, edutitle, edudesc, company, firstDate, lastDate FROM `education` WHERE resumeID = :resumeID AND userID = :userID');
                $stmtD = $this->db->connect()->prepare('SELECT techID, techtitle, `language`, interest FROM `techskill` WHERE resumeID = :resumeID AND userID = :userID');
                $stmtE = $this->db->connect()->prepare('SELECT motID, letter FROM `motivation` WHERE resumeID = :resumeID AND userID = :userID');

                // Bind parameters
                $stmtA->bindParam(":resumeID", $resid);
                $stmtA->bindParam(":userID", $userID);

                $stmtB->bindParam(":resumeID", $resid);
                $stmtB->bindParam(":userID", $userID);

                $stmtC->bindParam(":resumeID", $resid);
                $stmtC->bindParam(":userID", $userID);

                $stmtD->bindParam(":resumeID", $resid);
                $stmtD->bindParam(":userID", $userID);

                $stmtE->bindParam(":resumeID", $resid);
                $stmtE->bindParam(":userID", $userID);

                // Verify query execution
                $this->verifyQuery($stmtA);
                $this->verifyQuery($stmtB);
                $this->verifyQuery($stmtC);
                $this->verifyQuery($stmtD);
                $this->verifyQuery($stmtE);

                // Fetch all resume data
                $data['profile'] = $stmtA->fetchAll(PDO::FETCH_ASSOC);
                $data['experience'] = $stmtB->fetchAll(PDO::FETCH_ASSOC);
                $data['education'] = $stmtC->fetchAll(PDO::FETCH_ASSOC);
                $data['techskill'] = $stmtD->fetchAll(PDO::FETCH_ASSOC);
                $data['motivation'] = $stmtE->fetchAll(PDO::FETCH_ASSOC);

                // Cleanup statement objects after use
                unset($stmtA, $stmtB, $stmtC, $stmtD, $stmtE, $resid, $userID);

                // Handle fetch failures
                if (empty($data)) {
                    unset($data);
                    $_SESSION['error']['503'] = 'Resume data unavailable.';
                    header('location: client.php');
                    exit();
                }

                // Return the combined resume information
                return $data;

            } catch (PDOException $e) {
                // Handle unexpected PDO exceptions
                error_log('Database error during LoadResumeData: ' . $e->getMessage());
                $_SESSION['error']['503'] = '503: Service unavailable.';
                header('location: client.php');
                exit();
            }
        }
    }

    // Instantiate LoadUserData if no session error 503 exists
    if (!isset($_SESSION['error']['503'])) {
        $user = new LoadUserData();
        $data = $user->LoadUserdata();

        // Additional step: Gather Resume info
        if (isset($_POST['cvname'])) {
            // Absorb form data
            $resid = $_POST['cvname'];
            $userID = $_SESSION['session_data']['user_id'];

            $resume = new LoadResumeData();
            $resumeData = $resume->LoadResumeData($resid, $userID);
            $data = array_merge($data, $resumeData);
        }
    }