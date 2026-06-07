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

                // Master error tracker to hold issues across all rows
                $allErrors = [];

                // Transaction boundary start - either everything saves or nothing saves
                $pdo->beginTransaction();

                foreach ($sourceData as $index => $report) {
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

                    // If a row has errors, stash them in unique index
                    if (!empty($rowErrors)) {
                        $allErrors[$index] = $rowErrors;
                        continue; // Skip database handling for this row
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

                    if ($result > 0) { 
                        $successCount++; 
                    } else {
                        // If the model returned -1 due to an internal execution error
                        $allErrors[$index]['database'] = "Internal database execution error.";                       
                    }
                }

                // 3. THE FINAL EVALUATION GATE
                if (!empty($allErrors)) {
                    // An error occurred somewhere. Roll back all database adjustments instantly
                    $pdo->rollBack();
                    $_SESSION['error'] = $allErrors; // Your frontend can now pinpoint exactly which row broke
                    ViewBook::revert('builder', $resid);
                    return;
                }

                // Everything across all batches compiled beautifully—commit to storage
                $pdo->commit();

                $_SESSION['success'] = $successCount > 0 
                    ? "Successfully updated $successCount project(s)." 
                    : "Records verified. No changes needed.";
                    
                ViewBook::revert('builder', $resid);
                return;
            }
        }
    }