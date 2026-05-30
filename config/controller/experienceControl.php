<?php
    class experienceControl {
        public function __construct(private array $postData) {}

        public function handle(): void {
            $pdo = Database::Connect();
            $model = new experienceCodex($pdo); 
            $resid = $this->postData['resume_id'] ?? '';

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
                    $_SESSION['success'] = "Experience deleted.";
                }
                ViewBook::revert('builder', $resid);
                return; 

            } elseif ($intent === 'save') {
                $successCount = 0;
                $experiences = $this->postData['experience'] ?? [];

                foreach ($experiences as $job) {
                    // Sanitize these first for any loop iteration
                    $formattedStart = null;
                    $formattedEnd = null;

                    // Extract and sanitize
                    $eid      = !empty($job['id']) ? (int)$job['id'] : null;
                    $title    = $job['title'];
                    $employer = $job['employer'];

                    // If it's a new row and everything is blank, ignore it
                    if (empty($eid) && $title === '' && $employer === '' && empty($job['start_date'])) {
                        continue;
                    }

                    // Per-row validation (The Blocker)
                    $rowErrors = [];
                    if ($msg = ValidGrimoire::validateName($title, true, 120)) $rowErrors['title'] = $msg;
                    if ($msg = ValidGrimoire::validateName($employer, true, 120)) $rowErrors['employer'] = $msg;

                    // 1. Validate Start Date
                    $startDate = ValidGrimoire::validateAndFormatMonth($job['start_date'], false);
                    if ($startDate['error']) {
                        $rowErrors['start_date'] = $startDate['error'];
                    } else {
                        $formattedStart = $startDate['date']; // This is your Y-m-01
                    }

                    // 2. Validate End Date
                    $endDate = ValidGrimoire::validateAndFormatMonth($job['end_date'], true);
                    if ($endDate['error']) {
                        $rowErrors['end_date'] = $endDate['error'];
                    } else {
                        $formattedEnd = $endDate['date']; // Could be Y-m-01 or 'Present'
                    }

                    if (!empty($rowErrors)) {
                        $_SESSION['error'] = $rowErrors;
                        ViewBook::revert('builder', $resid);
                        return;
                    }

                    // Translate 'Present' to DB-friendly NULL
                    if ($formattedEnd === 'Present') { 
                        $formattedEnd = null; 
                    }

                    // Prepare payload
                    $payload = [
                        'id'          => $eid,
                        'resume_id'   => $resid,
                        'title'       => $title,
                        'employer'    => $employer,
                        'start_date'  => $formattedStart,
                        'end_date'    => $formattedEnd,
                        'summary'     => trim((string)($job['summary'] ?? ''))
                    ];

                    // Batch execution: Update if ID exists, otherwise Create
                    $result = empty($eid) 
                        ? $model->createExperience($payload) 
                        : $model->updateExperience($payload);

                    if ($result) $successCount++;
                }

                $_SESSION['success'] = $successCount > 0 
                    ? "Successfully updated $successCount experience(s)." 
                    : "Records verified. No changes needed.";
                ViewBook::revert('builder', $resid);
                return;
            }
        }
    }