<?php
    class resumeControl {
        public function __construct(private array $postData) {}

        /*=============================================
        * THE RITUALS (Private Specialists)
        ============================================*/
        private function dataScan(): void {
            // Validate for missing value
            $error = ValidGrimoire::emptyField($this->postData);
            if (!empty($error)) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = ['title' => $error];
                ViewBook::revert('builder'); 
                return;
            }

            // Validate title
            $title = trim((string)($this->postData['title'] ?? ''));
            if ($msg = ValidGrimoire::validateName($title, true)) {
                $errors['title'] = $msg;
            }

            // Validate headline
            $headline = trim((string)($this->postData['headline'] ?? ''));
            if ($msg = ValidGrimoire::validateName($headline, false)) {
                $errors['headline'] = $msg;
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

            // 2. If no delete, scan the data
            if ($intent !== 'delete') {
                // Validate payload
                $this->dataScan();

                $uid      = $this->postData['user_id'] ?? 0;
                $title    = trim((string)($this->postData['title'] ?? ''));
                $headline = trim((string)($this->postData['headline'] ?? ''));

                // DB querry (only after validation)
                $pdo = Database::Connect();
                $model = new resumeCodex($pdo);

                // Use match to pick the method, then execute it
                $result = match ($intent) {
                    'create' => $model->createResume($title, $headline, $uid),
                    'update' => $model->updateResume($title, $headline, $uid),
                    default  => 0
                };

                // One shared Logic Gate for the outcome
                if ($result > 0) {
                    $_SESSION['success'] = "Resume " . ($intent === 'create' ? 'created' : 'updated') . ".";
                } else {
                    $_SESSION['error'] = "Resume $intent failed.";
                }

                ViewBook::revert('builder');
                return;
            } 
            elseif($intent === 'delete') {
                $resid = $this->postData['resume_id'] ?? '';
                $uid = $this->postData['user_id'] ?? '';

                // DB querry (only after validation)
                $pdo = Database::Connect();
                $model = new resumeCodex($pdo);
                $burn = $model->deleteResume($resid, $uid);

                if (!$burn) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'Resume not found or already deleted.';
                    ViewBook::revert('builder');  
                    return;
                }

                $_SESSION['success'] = 'Resume deleted.';
                ViewBook::revert('builder');  
                return;

            } 
            else { 
                // Hold error message + set previous UI state
                $_SESSION['error'] = 'Unknown action request.';
                ViewBook::revert('builder');  
                return;
            }
        }
    }