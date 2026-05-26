<?php
    class experienceControl {
        public function __construct(private array $postData) {}

        private function dataScan(): void {
            // Validate for missing value
            $errors = ValidGrimoire::emptyField($this->postData);
            if (!empty($errors)) {
                // Store the whole array of specific messages
                $_SESSION['error'] = $errors;
                ViewBook::revert('builder');
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

            // Validate & reformat date
            $startDate = ValidGrimoire::validateAndFormatMonth($this->postData['start_date']);
            if ($startDate['error']) {
                $errors['start_date'] = $startDate['error'];
            } else {
                $this->postData['start_date'] = $startDate['date'];
            }

            // Validate & reformat date
            $endDate = ValidGrimoire::validateAndFormatMonth($this->postData['end_date']);
            if ($endDate['error']) {
                $errors['end_date'] = $endDate['error'];
            } else {
                $this->postData['end_date'] = $endDate['date'];
            }

            // Final check: if errors exist, send them back together
            if (!empty($errors)) {
                $_SESSION['error'] = $errors;
                ViewBook::revert('builder');
                return;
            }
        }

        public function handle(): void {
            // 1. Extract the Intent (everything after the colon)
            // ltrim ensures we remove the ':'
            $intent = ltrim(strchr($this->postData['action'], ':'), ':');
            $pdo = Database::Connect();
            $model = new experienceCodex($pdo); 
            $expid = $this->postData['experience_id'] ?? ''; 
            $resid = $this->postData['resume_id'] ?? '';

            if ($intent === 'delete') {
                // DB querry (only after validation)
                $trash = $model->deleteExperience($expid, $resid);
                if ($trash <= 0) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'Experience not found or already deleted.';
                    ViewBook::revert('builder');
                    return;
                }

                $_SESSION['success'] = 'Experience deleted.';
                ViewBook::revert('builder');  
                return;

            } elseif ($intent === 'save') {
                // Validate payload
                $this->dataScan();

                // Verify if experience came with its own id
                if (is_numeric($expid) && (int)$expid > 0) {
                    $result = $model->updateExperience($this->postData);
                } else {
                    $result = $model->createExperience($this->postData);
                }

                if ($result <= 0) { 
                    $_SESSION['error'] = 'Failed to save experience.';
                    ViewBook::revert('builder');
                    return;
                }
    
                $_SESSION['success'] = 'Experience saved!';
                ViewBook::revert('builder');

            } else { 
                // Hold error message + set previous UI state
                $_SESSION['error'] = 'Unknown action request.';
                ViewBook::revert('builder');  
                return;
            }
        }
    }