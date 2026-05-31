<?php
    class projectControl {
        public function __construct(private array $postData) {}

        public function handle(): void {
            $pdo = Database::Connect();
            $model = new projectCodex($pdo); 
            $resid = !empty($this->postData['resume_id']) ? (int)$this->postData['resume_id'] : null;

            // 1. Disect the raw string
            $parts = explode('|', $this->postData['action']);
            $rawAction = $parts[0];

            // 1. Extract the Intent (everything after the colon)
            $intent = ltrim(strchr($rawAction, ':'), ':');

            if ($intent === 'delete') {
                $prid = isset($parts[1]) ? (int)$parts[1] : null;

                $result = $model->deleteProject($prid, $resid);
                if ($result <= 0) {
                    $_SESSION['error'] = "Failed to delete project.";
                } else {
                    $_SESSION['success'] = "Project purged from records.";
                }
                ViewBook::revert('builder', $resid);
                return; 

            } elseif ($intent === 'save') {
                $successCount = 0;
                $sourceData = $this->postData['projects'] ?? [];

                foreach ($sourceData as $report) {
                    // Extract and sanitize
                    $prid = !empty($report['id'] ?? null) ? (int)$report['id'] : null;
                    $title= $report['title'];
                    $role = $report['role'];

                    // If it's a new row and everything is blank, ignore it
                    if (empty($prid) && $title === '' && $role === '') {
                        continue;
                    }

                    // Per-row validation (The Blocker)
                    $rowErrors = [];
                    if ($msg = ValidGrimoire::validateName($title, true, 120)) $rowErrors['title'] = $msg;
                    if ($msg = ValidGrimoire::validateName($role, true, 120)) $rowErrors['role'] = $msg;

                    if (!empty($rowErrors)) {
                        $_SESSION['error'] = $rowErrors;
                        ViewBook::revert('builder', $resid);
                        return;
                    }

                    // Prepare payload
                    $payload = [
                        'id'        => $prid,
                        'resume_id' => $resid,
                        'title'     => $title, 
                        'role'      => $role, 
                        'summary'   => trim((string)($report['summary'] ?? ''))
                    ];

                    // Batch execution: Update if ID exists, otherwise Create
                    $result = empty($prid) 
                        ? $model->createProject($payload) 
                        : $model->updateProject($payload);

                    if ($result > 0) $successCount++;
                }

                $_SESSION['success'] = $successCount > 0 
                    ? "Successfully updated $successCount project(s)." 
                    : "Records verified. No changes needed.";
                ViewBook::revert('builder', $resid);
                return;
            }
        }
    }