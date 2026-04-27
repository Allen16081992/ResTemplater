<?php
    class projectControl {
        public function __construct(private array $postData) {}

        public function handle(): void {
            // DB querry (only after validation)
            $pdo = Database::Connect();
            $model = new projectCodex($pdo); 

            if (isset($this->postData['delete'])) {
                // DB querry (only after validation)
                $trash = $model->deleteProject($this->postData['project_id'], $this->postData['resume_id']);
                if (!$trash) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'Failed to delete project.';
                    ViewBook::revert($this->postData['action'] ?? '');
                    return;
                }

                $_SESSION['success'] = 'Project removed.';
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

            // Validate project name
            $title = trim((string)($this->postData['title'] ?? ''));
            if ($msg = ValidGrimoire::validateName($title, true)) {
                $errors['title'] = $msg;
            }

            // Validate project role
            $role = trim((string)($this->postData['role'] ?? ''));
            if ($msg = ValidGrimoire::validateName($role, true)) {
                $errors['role'] = $msg;
            }

            // Final check: if errors exist, send them back together
            if (!empty($errors)) {
                $_SESSION['error'] = $errors;
                ViewBook::revert($this->postData['action'] ?? 'profile');
                return;
            }

            // Verify project id
            if (empty($this->postData['project_id'])) {
                $project = $model->createProject($this->postData);
            } else {
                $project = $model->updateProject($this->postData);
            }

            if ($project <= 0) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = 'Failed to save project.';
                ViewBook::revert($this->postData['action'] ?? ''); 
                return;
            }

            $_SESSION['success'] = 'Project saved.';
            ViewBook::revert($this->postData['action'] ?? '');
            return;
        }
    }