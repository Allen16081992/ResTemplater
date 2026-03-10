<?php // Load PHP Files
    require_once '../validator.conf.php';
    require_once '../classes/educationn_class.php';

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
            $institute = trim((string)($this->postData['institute'] ?? ''));
            $msg = ValidGrimoire::validateName($institute, true);
            if ($msg !== null) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = ['institute' => $msg];
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