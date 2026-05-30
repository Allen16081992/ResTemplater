<?php
    class skillControl {
        public function __construct(private array $postData) {}

        public function handle(): void {
            // 1. Extract the Intent (everything after the colon)
            // ltrim ensures we remove the ':'
            $intent = ltrim(strchr($this->postData['action'], ':'), ':');
            $resid = $this->postData['resume_id'] ?? '';
            $pdo = Database::Connect();
            $model = new skillCodex($pdo); 

            if ($intent === 'delete') {
                $sid = $this->postData['skills:delete'] ?? '';

                if (empty($sid)) {
                    $_SESSION['error'] = 'Failed to verify Skill.';
                    ViewBook::revert('builder', $resid);
                    return;
                }

                $pyre = $model->deleteSkill($sid, $resid);
                if (!$pyre) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'Failed to delete skill.';
                    ViewBook::revert('builder', $resid); 
                    return;
                }

                $_SESSION['success'] = 'Skill purged from the records.';
                ViewBook::revert('builder', $resid);  
                return;

            } elseif ($intent === 'save') {
                $successCount = 0;
                $sourceData = $this->postData['skills'] ?? [];
                
                foreach ($sourceData as $skill) {
                    $name = trim((string)($skill['name'] ?? ''));
                    $category = trim((string)($skill['category'] ?? ''));
                    $sid = !empty($skill['id']) ? (int)$skill['id'] : null;

                    // --- THE SKIP LOGIC ---
                    // If it's a fresh row with no data, just skip it instead of erroring out
                    if ($name === '' && $category === '' && empty($sid)) continue;

                    // Reset per-row errors
                    $rowErrors = [];
                    if ($msg = ValidGrimoire::validateName($name, true, 80)) $rowErrors['name'] = $msg;
                    if ($msg = ValidGrimoire::validateName($category, true, 80)) $rowErrors['category'] = $msg;

                    if (!empty($rowErrors)) {
                        $_SESSION['error'] = $rowErrors;
                        ViewBook::revert('builder', $resid); 
                        return;
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

                    if ($result > 0) $successCount++;
                }

                // --- THE FAIL-SAFE ---
                if ($successCount === 0 && !empty($sourceData)) {
                    $_SESSION['success'] = 'Record verified. No changes were necessary.';
                } else {
                    $_SESSION['success'] = "Successfully updated $successCount skill(s).";
                }
                ViewBook::revert('builder', $resid);
                return;
            }
        }
    }