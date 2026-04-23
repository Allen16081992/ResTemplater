<?php 
    class socialControl {
        public function __construct(private array $postData) {}

        public function handle(): void {
            // DB querry (only after validation)
            $pdo = Database::Connect();
            $model = new socialCodex($pdo); 

            if (isset($this->postData['delete'])) {
                // DB querry (only after validation)
                $clr = $model->deleteSocial($this->postData['delete'], $this->postData['resume_id']);
                if (!$clr) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'Failed to delete social media.';
                    ViewBook::revert($this->postData['action'] ?? '');
                    return;
                }

                $_SESSION['success'] = 'Social media removed.';
                ViewBook::revert('builder');  
                return;
            }

            if (isset($this->postData['social'])) {
                // Validate for missing value
                $errors = ValidGrimoire::emptyField($this->postData['social']);
                if (!empty($errors)) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = $errors;
                    ViewBook::revert($this->postData['action'] ?? '');
                    return;
                }

                // Verify experience id
                $social = $this->postData['social'];
                foreach ($social as $item) {
                    if (empty($item['id'])){
                        $social = $model->createSocial($social);
                    } else {
                        $social = $model->updateSocial($social);
                    }
                }

                if ($social <= 0) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'Failed to save social media.';
                    ViewBook::revert($this->postData['action'] ?? ''); 
                    return;
                }

                $_SESSION['success'] = 'Social media saved.';
                ViewBook::revert($this->postData['action'] ?? '');
                return;
            }
        }
    }