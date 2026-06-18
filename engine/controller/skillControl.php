<?php
    class skillControl {
        public function __construct(private array $postData) {}

        public function handle(): void {
            $pdo = Database::Connect();
            $model = new skillCodex($pdo); 
            $resid = !empty($this->postData['resume_id']) ? (int)$this->postData['resume_id'] : null;

            // 1. Dissect the raw string
            $parts = explode('|', $this->postData['action']);
            $rawAction = $parts[0];

            // 2. Extract the Intent (everything after the colon)
            $intent = ltrim(strchr($rawAction, ':'), ':');

            if ($intent === 'delete') {
                $sid = isset($parts[1]) ? (int)$parts[1] : null;

                if (empty($sid)) {
                    $_SESSION['error'] = 'Failed to verify Skill.';
                    ViewBook::revert('builder', $resid);
                    return;
                }

                $result = $model->deleteSkill($sid, $resid);
                if ($result <= 0) {
                    $_SESSION['error'] = 'Failed to delete skill.';
                } else {
                    $_SESSION['success'] = 'Skill purged from records.';
                }
                ViewBook::revert('builder', $resid); 
                return;

            } elseif ($intent === 'save') {
                $successCount = 0;
                $sourceData = $this->postData['skills'] ?? [];
                
                // Master error tracker to hold issues across all rows
                $allErrors = [];

                // Transaction boundary start - atomic batch saving safety
                $pdo->beginTransaction();

                foreach ($sourceData as $index => $skill) {
                    // Extract and sanitize
                    $sid = !empty($skill['id'] ?? null) ? (int)$skill['id'] : null;
                    $name = $skill['name'] ?? '';
                    $category = $skill['category'] ?? '';

                    // --- THE SKIP LOGIC ---
                    if ($name === '' && ($category === '' || $category === 'Other') && empty($sid)) { 
                        continue; 
                    }

                    // Whitelisted categories validation fallback
                    $validCategories = ['tool', 'language', 'technical', 'certificate', 'soft-skill', 'hard-skill', 'Other'];
                    if (!in_array($category, $validCategories)) {
                        $category = 'Other'; 
                    }

                    // Reset per-row errors
                    $rowErrors = [];
                    if ($msg = ValidGrimoire::validateName($name, true, 80)) $rowErrors['name'] = $msg;
                    
                    if (!empty($rowErrors)) {
                        $allErrors[$index] = $rowErrors;
                        continue; // Skip database handling for this specific row, continue evaluating others
                    }                

                    $payload = [
                        'id'        => $sid,
                        'name'      => $name,
                        'category'  => $category,
                        'resume_id' => $resid
                    ];

                    $result = empty($sid) 
                        ? $model->createSkill($payload) 
                        : $model->updateSkill($payload);

                    // Check for >= 0 because an unchanged row update returns 0 rowCount, which is a success state
                    if ($result >= 0) {
                        $successCount++;
                    } else {
                        $allErrors[$index]['database'] = "Internal database execution error.";
                    }
                }

                // --- 3. THE FINAL EVALUATION GATE (OUTSIDE THE LOOP) ---
                if (!empty($allErrors)) {
                    // If any row failed validation or processing, undo everything completely
                    $pdo->rollBack();
                    $_SESSION['error'] = $allErrors; 
                    ViewBook::revert('builder', $resid);
                    return;
                }

                // All rows vetted and staged without issues—commit batch cleanly
                $pdo->commit();

                $_SESSION['success'] = $successCount > 0 
                    ? "Successfully updated $successCount skill(s)." 
                    : "Records verified. No changes needed.";
                
                ViewBook::revert('builder', $resid);
                return;
            }
        }
    }