<?php
    class educationControl {
        public function __construct(private array $postData) {}

        public function handle(): void {
            // DB querry (only after validation)
            $pdo = Database::Connect();
            $model = new educationCodex($pdo); 

            if (isset($this->postData['delete'])) {
                // DB querry (only after validation)
                $trash = $model->deleteEducation($this->postData['edu_id'], $this->postData['resume_id']);
                if (!$trash) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'Failed to delete education.';
                    ViewBook::revert($this->postData['action'] ?? '');
                    return;
                }

                $_SESSION['success'] = 'Education entry removed.';
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

            // Validate program
            $program = trim((string)($this->postData['program'] ?? ''));
            if ($msg = ValidGrimoire::validateName($program, true)) {
                $errors['program'] = $msg;
            }

            // Validate school
            $school = trim((string)($this->postData['school'] ?? ''));
            if ($msg = ValidGrimoire::validateName($school, true)) {
                $errors['school'] = $msg;
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

            // Verify educationn id
            if (empty($this->postData['edu_id'])) {
                $course = $model->createEducation($this->postData);
            } else {
                $course = $model->updateEducation($this->postData);
            }

            if ($course <= 0) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = 'Failed to save education.';
                ViewBook::revert($this->postData['action'] ?? ''); 
                return;
            }

            $_SESSION['success'] = 'Education saved.';
            ViewBook::revert($this->postData['action'] ?? '');
            return;
        }

    }