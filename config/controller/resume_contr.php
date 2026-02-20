<?php require_once '../validator.conf.php';

    class resumeControl {
        public function __construct(private array $postData) {}

        public function handle(): void {

            // Verify required action
            if (isset($this->postData['create'])) {
                // Validate for missing value
                $error = ValidGrimoire::emptyField($this->postData['resume_title']);
                if (!empty($error)) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = ['resume_title' => $error];
                    header('Location: ../client.php');
                }

                // Validate resume name
                $title = trim((string)($this->postData['resume_title'] ?? ''));
                $msg = ValidGrimoire::validateName($title, true);
                if ($msg !== null) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = ['resume_title' => $msg];
                    header('Location: ../client.php');
                }

                // Validate summary
                $summary = trim((string)($this->postData['summary'] ?? ''));
                $msg = ValidGrimoire::validateName($summary, false);
                if ($msg !== null) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = ['summary' => $msg];
                    header('Location: ../client.php');
                }
                $uid = $this->postData['user_id'];

                // DB querry (only after validation)
                $pdo = Database::Connect();
                $model = new resumeCodex($pdo); 
                $new = $model->createResume($title, $summary, $uid);

                if ($new <= 0) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'Resume creation failed.';
                    header('Location: ../client.php');
                }

                $_SESSION['success'] = 'Your new resume is ready.';
                header('Location: ../client.php');

            } // (Read) is loadResumes_conf.php
            elseif(isset($this->postData['update'])) {
                // Validate for missing value
                $error = ValidGrimoire::emptyField($this->postData['resume_title']);
                if (!empty($error)) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = ['resume_title' => $error];
                    header('Location: ../client.php');
                }

                // Validate resume name
                $title = trim((string)($this->postData['resume_title'] ?? ''));
                $msg = ValidGrimoire::validateName($title, true);
                if ($msg !== null) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = ['resume_title' => $msg];
                    header('Location: ../client.php');
                }

                // Validate summary
                $summary = trim((string)($this->postData['summary'] ?? ''));
                $msg = ValidGrimoire::validateName($summary, false);
                if ($msg !== null) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = ['summary' => $msg];
                    header('Location: ../client.php');
                }
                $uid = $this->postData['user_id'];

                // DB querry (only after validation)
                $pdo = Database::Connect();
                $model = new resumeCodex($pdo);
                $new = $model->updateResume($title, $summary, $uid);

                if ($new <= 0) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'Updating resume failed.';
                    header('Location: ../client.php');
                } 

                $_SESSION['success'] = 'Resume saved.';
                header('Location: ../client.php');

            } elseif(isset($this->postData['delete'])) {
                $resumeID = $this->postData['resume_id'];
                $uid = $this->postData['user_id'];

                // DB querry (only after validation)
                $pdo = Database::Connect();
                $model = new resumeCodex($pdo); 
                $wipe = $model->deleteResume($resumeID, $uid);

                if (!$wipe) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'Resume not found or already deleted.';
                    header('Location: ../client.php');
                }

                $_SESSION['success'] = 'Resume removed.';
                header('Location: ../client.php');

            } else {
                // Hold error message + set previous UI state
                $_SESSION['error'] = 'Invalid action request.';
                header('Location: ../client.php');
            }
            exit;
        }
    }