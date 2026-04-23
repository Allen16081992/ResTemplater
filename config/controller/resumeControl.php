<?php
    class resumeControl {
        public function __construct(private array $postData) {}

        public function handle(): void {
            // DB querry (only after validation)
            $pdo = Database::Connect();
            $model = new resumeCodex($pdo); 

            // Verify required action
            if (isset($this->postData['create'])) {
                // Validate for missing value
                $error = ValidGrimoire::emptyField($this->postData['title']);
                if (!empty($error)) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = ['title' => $error];
                    ViewBook::revert('builder'); 
                    return;
                }

                // Validate resume name
                $title = trim((string)($this->postData['title'] ?? ''));
                $msg = ValidGrimoire::validateName($title, true);
                if ($msg !== null) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = ['title' => $msg];
                    ViewBook::revert('builder'); 
                    return;
                }

                // Validate summary
                $summary = trim((string)($this->postData['summary'] ?? ''));
                $msg = ValidGrimoire::validateName($summary, false);
                if ($msg !== null) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = ['summary' => $msg];
                    ViewBook::revert('builder'); 
                    return;
                }
                $uid = $this->postData['user_id'];

                // DB querry (only after validation)
                $new = $model->createResume($title, $summary, $uid);

                if ($new <= 0) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'Resume creation failed.';
                    ViewBook::revert('builder');  
                    return;
                }

                $_SESSION['success'] = 'Resume created.';
                ViewBook::revert('builder');  
                return;

            } // elseif(isset($this->postData['read'])) {
            //     This is a file named loadResumeData_conf.php
            // }
            elseif(isset($this->postData['update'])) {
                // Validate for missing value
                $error = ValidGrimoire::emptyField($this->postData['title']);
                if (!empty($error)) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = ['title' => $error];
                    ViewBook::revert('builder');  
                    return;
                }

                // Validate resume name
                $title = trim((string)($this->postData['title'] ?? ''));
                $msg = ValidGrimoire::validateName($title, true);
                if ($msg !== null) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = ['title' => $msg];
                    ViewBook::revert('builder');  
                    return;
                }

                // Validate summary
                $summary = trim((string)($this->postData['summary'] ?? ''));
                $msg = ValidGrimoire::validateName($summary, false);
                if ($msg !== null) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = ['summary' => $msg];
                    ViewBook::revert('builder');  
                    return;
                }
                $uid = $this->postData['user_id'];

                // DB querry (only after validation)
                $new = $model->updateResume($title, $summary, $uid);

                if ($new <= 0) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'Updating resume failed.';
                    ViewBook::revert('builder');  
                    return;
                } 

                $_SESSION['success'] = 'Resume saved.';
                ViewBook::revert('builder');  
                return;

            } elseif(isset($this->postData['delete'])) {
                $resumeID = $this->postData['resume_id'];
                $uid = $this->postData['user_id'];

                // DB querry (only after validation)
                $wipe = $model->deleteResume($resumeID, $uid);

                if (!$wipe) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'Resume not found or already deleted.';
                    ViewBook::revert('builder');  
                    return;
                }

                $_SESSION['success'] = 'Resume deleted.';
                ViewBook::revert('builder');  
                return;

            } else {
                // Hold error message + set previous UI state
                $_SESSION['error'] = 'Invalid action request.';
                ViewBook::revert('builder');  
                return;
            }
            exit;
        }
    }