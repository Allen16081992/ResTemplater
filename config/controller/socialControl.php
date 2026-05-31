<?php 
    class socialControl {
        public function __construct(private array $postData) {}

        public function handle(): void {
            $pdo = Database::Connect();
            $model = new socialCodex($pdo); 
            $resid = !empty($this->postData['resume_id']) ? (int)$this->postData['resume_id'] : null;

            // 1. Disect the raw string
            $parts = explode('|', $this->postData['action']);
            $rawAction = $parts[0];

            // 1. Extract the Intent (everything after the colon)
            $intent = ltrim(strchr($rawAction, ':'), ':');

            if ($intent === 'delete') {
                $soid = isset($parts[1]) ? (int)$parts[1] : null;

                if (empty($soid)) {
                    $_SESSION['error'] = 'Failed to verify Social link.';
                    ViewBook::revert('builder', $resid);
                    return;
                }

                $result = $model->deleteSocial($soid, $resid);
                if ($result <= 0) {
                    $_SESSION['error'] = 'Failed to delete Social link';
                } else {
                    $_SESSION['success'] = 'Social link purged from records.';
                }
                ViewBook::revert('builder', $resid); 
                return;

            } elseif ($intent === 'save') {
                $successCount = 0;
                $sourceData = $this->postData['socials'] ?? [];

                foreach ($sourceData as $social) {
                    // Extract and sanitize
                    $sid = !empty($social['id'] ?? null) ? (int)$social['id'] : null;
                    $url = $social['media_url'] ?? '';

                    // --- THE SKIP LOGIC ---
                    // If it's a fresh row with no data, just skip it instead of erroring out    
                    if ($url === '' && empty($sid)) { continue; }              

                    $payload = [
                        'id'        => $sid,
                        'media_url' => $url,
                        'resume_id' => $resid
                    ];

                    $result = empty($sid) 
                        ? $model->createSocial($payload) 
                        : $model->updateSocial($payload);

                    if ($result > 0) $successCount++;
                }

                $_SESSION['success'] = $successCount > 0 
                    ? "Successfully updated $successCount social(s)." 
                    : "Records verified. No changes needed.";
                ViewBook::revert('builder', $resid);
                return;
            }
        }
    }