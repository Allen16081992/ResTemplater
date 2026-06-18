<?php
    class resumeControl {
        public function __construct(private array $postData) {}

        /*=============================================
        * THE RITUALS (Private Specialists)
        ============================================*/
        private function dataScan(): void {
            // Validate for missing value

            $error = ValidGrimoire::emptyField($this->postData);
            unset($error['headline']);
            if (!empty($error)) {
                // Store the whole array of specific messages
                $_SESSION['errors'] = $error;
                ViewBook::revert('builder'); 
                return;
            }

            // Validate title
            $title = trim((string)($this->postData['title'] ?? ''));
            if ($msg = ValidGrimoire::validateName($title, true)) {
                $errors['title'] = $msg;
            }

            // // Validate headline
            $headline = trim((string)($this->postData['headline'] ?? ''));
            if ($msg = ValidGrimoire::validateName($headline, false)) {
                $errors['headline'] = $msg;
            }

            // // Final check: if errors exist, send them back together
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
            $model = new resumeCodex($pdo);

            // 2. If no delete, scan the data
            if ($intent !== 'delete') {
                // Validate payload
                $this->dataScan();

                $uid      = $_SESSION['session_data']['user_id'] ?? '';
                $resid    = trim((string)($this->postData['resume_id'] ?? ''));
                $title    = trim((string)($this->postData['title'] ?? ''));
                $headline = trim((string)($this->postData['headline'] ?? ''));

                // Use match to pick the method, then execute it
                $result = match ($intent) {
                    'create' => $model->createResume($title, $headline, $uid),
                    'update' => $model->updateResume($title, $headline, $resid, $uid),
                    'clone'  => $model->cloneResume($resid, $uid),
                    default  => -1
                };

                // One shared Logic Gate for the outcome
                if ($result > 0) {
                    $verb = match ($intent) {
                        'create' => 'created',
                        'update' => 'updated',
                        'clone'  => 'cloned',
                        default  => 'processed'
                    };
                    $_SESSION['success'] = "Resume $verb.";
                } elseif ($result == 0) {
                    $_SESSION['success'] = "Saved. No changes made.";
                } else {
                    $_SESSION['error'] = "Resume $intent failed.";
                }

                // If clone, $result (set new ID). 
                // Otherwise, stick with the existing $resid.
                $finalId = ($intent === 'clone' || $intent === 'create') ? $result : $resid;
                ViewBook::revert('builder', $finalId);
                return;

            } elseif($intent === 'delete') {
                $resid = $this->postData['resume_id'] ?? '';
                $uid = $_SESSION['session_data']['user_id'] ?? '';
                $result = $model->deleteResume($resid, $uid);

                if ($result <= 0) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'Resume not found or already deleted.';
                    ViewBook::revert('builder');  
                    return;
                }
                
                $_SESSION['success'] = 'Resume purged from records.';
                ViewBook::revert('builder');  
                return;

            } else { 
                // Hold error message + set previous UI state
                $_SESSION['error'] = 'Unknown action request.';
                ViewBook::revert('builder');  
                return;
            }
        }
    }