<?php // Load PHP Files
    require_once '../validator.conf.php';
    require_once '../skill_class.php';

    class skillControl {
        public function __construct(private array $postData) {}

        public function handle(): void {
            // DB querry (only after validation)
            $pdo = Database::Connect();
            $model = new skillCodex($pdo); 

            if (isset($this->postData['delete'])) {
                // DB querry (only after validation)
                $trash = $model->deleteSkill($this->postData['delete'], $this->postData['resume_id']);
                if (!$trash) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'Failed to delete skill.';
                    ViewBook::revert($this->postData['action'] ?? '');
                    return;
                }

                $_SESSION['success'] = 'Skill is removed.';
                ViewBook::revert('builder');  
                return;
            }  
            
            // Validate for missing value
            $errors = ValidGrimoire::emptyField($this->postData);
            if (!empty($errors)) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = $errors;
                ViewBook::revert($this->postData['action'] ?? '');
                return;
            }

            $cleanSkills = [];

            // Validate job title
            foreach ($this->postData['skill'] ?? [] as $i => $skill) {
                $name = trim((string) ($skill['name'] ?? ''));
                $category = trim((string) ($skill['category'] ?? ''));
                $id = isset($skill['id']) ? (int) $skill['id'] : null;

                // Skip fully empty rows
                if ($name === '' && $category === '') {
                    continue;
                }

                $msg = ValidGrimoire::validateName($name, true, 80);
                if ($msg !== null) {
                    $_SESSION['error'] = ['name' => $msg];
                    ViewBook::revert($this->postData['action'] ?? '');
                    return;
                }

                $msg = ValidGrimoire::validateName($category, true, 80);
                if ($msg !== null) {
                    $_SESSION['error'] = ['category' => $msg];
                    ViewBook::revert($this->postData['action'] ?? '');
                    return;
                }

                $cleanSkills[] = [
                    'id' => $id,
                    'name' => $name,
                    'category' => $category,
                    'resume_id' => $this->postData['resume_id']
                ];
            }

            // Verify experience id
            foreach ($cleanSkills as $item) {
                if (empty($item['id'])){
                    $skill = $model->createSkill($cleanSkills);
                } else {
                    $skill = $model->updateSkill($cleanSkills);
                }
            }

            if ($skill <= 0) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = 'Failed to save skills.';
                ViewBook::revert($this->postData['action'] ?? ''); 
                return;
            }

            $_SESSION['success'] = 'Skills saved.';
            ViewBook::revert($this->postData['action'] ?? '');
            return;
        }

    }