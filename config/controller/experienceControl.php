<?php
    class experienceControl {
        public function __construct(private array $postData) {}

        public function handle(): void {
            $pdo = Database::Connect();
            $model = new experienceCodex($pdo); 
            $resid = !empty($this->postData['resume_id']) ? (int)$this->postData['resume_id'] : null;

            // 1. Disect the raw string
            $parts = explode('|', $this->postData['action']);
            $rawAction = $parts[0];

            // 1. Extract the Intent (everything after the colon)
            $intent = ltrim(strchr($rawAction, ':'), ':');
            
            if ($intent === 'delete') {
                $exid = isset($parts[1]) ? (int)$parts[1] : null;

                $result = $model->deleteExperience($exid, $resid);
                if ($result <= 0) {
                    $_SESSION['error'] = "Failed to delete experience.";
                } else {
                    $_SESSION['success'] = "Experience purged from records.";
                }
                ViewBook::revert('builder', $resid);
                return; 

            } elseif ($intent === 'save') {
                $successCount = 0;
                $sourceData = $this->postData['experience'] ?? [];
                
                // Master error tracker to hold issues across all rows
                $allErrors = [];

                // Transaction boundary start - either everything saves or nothing saves
                $pdo->beginTransaction();

                foreach ($sourceData as $index => $job) {
                    $formattedStart = null;
                    $formattedEnd = null;

                    $eid = !empty($job['id'] ?? null) ? (int)$job['id'] : null;
                    $title = $job['title'] ?? '';
                    $employer = $job['employer'] ?? '';

                    // Cleanly ignore blank ghost rows without throwing errors
                    if (empty($eid) && $title === '' && $employer === '' && empty($job['start_date'])) {
                        continue;
                    }

                    // Per-row validation error tracking
                    $rowErrors = [];
                    if ($msg = ValidGrimoire::validateName($title, true, 120)) $rowErrors['title'] = $msg;
                    if ($msg = ValidGrimoire::validateName($employer, true, 120)) $rowErrors['employer'] = $msg;

                    $startDate = ValidGrimoire::validateAndFormatMonth($job['start_date'] ?? '', false);
                    if ($startDate['error']) {
                        $rowErrors['start_date'] = $startDate['error'];
                    } else {
                        $formattedStart = $startDate['date'];
                    }

                    $endDate = ValidGrimoire::validateAndFormatMonth($job['end_date'] ?? '', true);
                    if ($endDate['error']) {
                        $rowErrors['end_date'] = $endDate['error'];
                    } else {
                        $formattedEnd = $endDate['date'];
                    }

                    // If this specific row has errors, stash them under its unique frontend index
                    if (!empty($rowErrors)) {
                        $allErrors[$index] = $rowErrors;
                        continue; // Skip database handling for this row, check the remaining rows
                    }

                    if ($formattedEnd === 'Present') { 
                        $formattedEnd = null; 
                    }

                    $payload = [
                        'id'          => $eid,
                        'resume_id'   => $resid,
                        'title'       => $title,
                        'employer'    => $employer,
                        'start_date'  => $formattedStart,
                        'end_date'    => $formattedEnd,
                        'summary'     => trim((string)($job['summary'] ?? ''))
                    ];

                    // Execute row processing
                    $result = empty($eid) 
                        ? $model->createExperience($payload) 
                        : $model->updateExperience($payload);

                    if ($result >= 0) { // Using >= 0 because an unchanged row update returns 0, which is still a success
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
                    ? "Successfully updated $successCount experience(s)." 
                    : "Records verified. No changes needed.";
                
                ViewBook::revert('builder', $resid);
                return;
            }
        }
    }