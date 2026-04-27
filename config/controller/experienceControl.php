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
            if ($msg = ValidGrimoire::validateName($title, true)) {
                $errors['title'] = $msg;
            }

            // Validate employer
            $employer = trim((string)($this->postData['employer'] ?? ''));
            if ($msg = ValidGrimoire::validateName($employer, true)) {
                $errors['employer'] = $msg;
            }

            $startDate = ValidGrimoire::validateAndFormatDate($this->postData['start_date']);
            if ($startDate['error']) {
                $errors['start_date'] = $startDate['error'];
            } else {
                $this->postData['start_date'] = $startDate['date'];
            }

            $endDate = ValidGrimoire::validateAndFormatDate($this->postData['end_date']);
            if ($endDate['error']) {
                $errors['end_date'] = $endDate['error'];
            } else {
                $this->postData['end_date'] = $endDate['date'];
            }

            // Final check: if errors exist, send them back together
            if (!empty($errors)) {
                $_SESSION['error'] = $errors;
                ViewBook::revert($this->postData['action'] ?? 'profile');
                return;
            }

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