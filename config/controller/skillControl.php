<?php
    class skillControl {
        public function __construct(private array $postData) {}

        public function handle(): void {
            $pdo = Database::Connect();
            $model = new skillCodex($pdo); 
            $resid = !empty($this->postData['resume_id']) ? (int)$this->postData['resume_id'] : null;

            // 1. Disect the raw string
            $parts = explode('|', $this->postData['action']);
            $rawAction = $parts[0];

            // 1. Extract the Intent (everything after the colon)
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
                
                foreach ($sourceData as $skill) {
                    // Extract and sanitize
                    $sid = !empty($skill['id'] ?? null) ? (int)$skill['id'] : null;
                    $name = $skill['name'];
                    $category = $skill['category'] ?? '';

                    // --- THE SKIP LOGIC ---
                    // If it's a fresh row with no data, just skip it instead of erroring out    
                    if ($name === '' && ($category === '' || $category === 'Other') && empty($sid)) { continue; }

                    // Whitelisted categories
                    $validCategories = ['tool', 'language', 'technical', 'certificate', 'soft-skill', 'hard-skill', 'Other'];
                    if (!in_array($category, $validCategories)) {
                        $category = 'Other'; // Fallback if someone messes with the HTML Inspector
                    }

                    // Reset per-row errors
                    $rowErrors = [];
                    if ($msg = ValidGrimoire::validateName($name, true, 80)) $rowErrors['name'] = $msg;
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

                $_SESSION['success'] = $successCount > 0 
                    ? "Successfully updated $successCount skill(s)." 
                    : "Records verified. No changes needed.";
                ViewBook::revert('builder', $resid);
                return;
            }
        }
    }