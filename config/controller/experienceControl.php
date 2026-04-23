<?php
    class experienceControl {
        public function __construct(private array $postData) {}

        public function handle(): void {
            // DB querry (only after validation)
            $pdo = Database::Connect();
            $model = new experienceCodex($pdo); 
            
            if (isset($this->postData['delete'])) {
                // DB querry (only after validation)
                $trash = $model->deleteExperience($this->postData['exp_id'], $this->postData['resume_id']);
                if (!$trash) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'Failed to delete experience.';
                    ViewBook::revert($this->postData['action'] ?? '');
                    return;
                }

                $_SESSION['success'] = 'Experience entry removed.';
                ViewBook::revert('builder');  
                return;
            }

            // Validate for missing value
            $errors = ValidGrimoire::emptyField($this->postData);
            if (!empty($errors)) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = $errors;
                ViewBook::revert($this->postData['action'] ?? '');
                return;
            }
            
            // Validate job title
            $title = trim((string)($this->postData['title'] ?? ''));
            $msg = ValidGrimoire::validateName($title, true);
            if ($msg !== null) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = ['title' => $msg];
                ViewBook::revert($this->postData['action'] ?? ''); 
                return;
            }

            // Validate company / employer
            $company = trim((string)($this->postData['company'] ?? ''));
            $msg = ValidGrimoire::validateName($company, true);
            if ($msg !== null) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = ['company' => $msg];
                ViewBook::revert($this->postData['action'] ?? ''); 
                return;
            }

            $date = validGrimoire::validateAndFormatDate($this->postData['start_date']);
            if ($date['error']) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = $date['error']; // show validation message
                ViewBook::revert($this->postData['action'] ?? ''); 
                return;
            } 
            $this->postData['start_date'] = $date['date'];

            $date = validGrimoire::validateAndFormatDate($this->postData['end_date']);
            if ($date['error']) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = $date['error']; // show validation message
                ViewBook::revert($this->postData['action'] ?? ''); 
                return;
            }
            $this->postData['end_date'] = $date['date'];

            // Verify experience id
            if (empty($this->postData['exp_id'])) {
                $work = $model->createExperience($this->postData);
            } else {
                $work = $model->updateExperience($this->postData);
            }

            if ($work <= 0) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = 'Failed to save work experience.';
                ViewBook::revert($this->postData['action'] ?? ''); 
                return;
            }

            $_SESSION['success'] = 'Work experience saved.';
            ViewBook::revert($this->postData['action'] ?? '');
            return;
        }
    }