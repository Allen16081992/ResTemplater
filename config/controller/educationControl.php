<?php
    class educationControl {
        public function __construct(private array $postData) {}

        public function handle(): void {
            $pdo = Database::Connect();
            $model = new educationCodex($pdo); 
            $resid = !empty($this->postData['resume_id']) ? (int)$this->postData['resume_id'] : null;

            // 1. Disect the raw string
            $parts = explode('|', $this->postData['action']);
            $rawAction = $parts[0];

            // 1. Extract the Intent (everything after the colon)
            $intent = ltrim(strchr($rawAction, ':'), ':');

            if ($intent === 'delete') {
                $edid = isset($parts[1]) ? (int)$parts[1] : null;

                $result = $model->deleteEducation($edid, $resid);
                if ($result <= 0) {
                    $_SESSION['error'] = "Failed to delete education.";
                } else {
                    $_SESSION['success'] = "Education purged from records.";
                }
                ViewBook::revert('builder', $resid);
                return; 

            } elseif ($intent === 'save') {
                $successCount = 0;
                $sourceData = $this->postData['education'] ?? [];

                foreach ($sourceData as $course) {
                    // Sanitize these first for any loop iteration
                    $formattedStart = null;
                    $formattedEnd = null;

                    // Extract and sanitize
                    $eid = !empty($course['id'] ?? null) ? (int)$course['id'] : null;
                    $program= $course['program'];
                    $school = $course['school'];

                    // If it's a new row and everything is blank, ignore it
                    if (empty($eid) && $program === '' && $school === '' && empty($course['start_date'])) {
                        continue;
                    }

                    // Per-row validation (The Blocker)
                    $rowErrors = [];
                    if ($msg = ValidGrimoire::validateName($program, true, 120)) $rowErrors['program'] = $msg;
                    if ($msg = ValidGrimoire::validateName($school, true, 120)) $rowErrors['school'] = $msg;

                    // 1. Validate Start Date
                    $startDate = ValidGrimoire::validateAndFormatMonth($course['start_date'], false);
                    if ($startDate['error']) {
                        $rowErrors['start_date'] = $startDate['error'];
                    } else {
                        $formattedStart = $startDate['date']; // This is your Y-m-01
                    }

                    // 2. Validate End Date
                    $endDate = ValidGrimoire::validateAndFormatMonth($course['end_date'], true);
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
                        'program'     => $program, // Changed from 'title'
                        'school'      => $school,  // Changed from 'employer'
                        'start_date'  => $formattedStart,
                        'end_date'    => $formattedEnd,
                        'summary'     => trim((string)($course['summary'] ?? ''))
                    ];

                    // Batch execution: Update if ID exists, otherwise Create
                    $result = empty($eid) 
                        ? $model->createEducation($payload) 
                        : $model->updateEducation($payload);

                    if ($result > 0) $successCount++;
                }

                $_SESSION['success'] = $successCount > 0 
                    ? "Successfully updated $successCount education(s)." 
                    : "Records verified. No changes needed.";
                ViewBook::revert('builder', $resid);
                return;
            }
        }
    }