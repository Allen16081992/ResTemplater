<?php require_once '../validator.conf.php';

    class wizardControl {
        public function __construct(private array $postData) {}

        private function oldForm(): void {
            $oldForm = [
                'fullname' => $this->postData['fullname'] ?? '',
                'email' => $this->postData['email'] ?? '',
                'city' => $this->postData['city'] ?? '',
                'country' => $this->postData['country'] ?? '',
                'social' => $this->postData['social'] ?? '',
                'phone' => $this->postData['phone'] ?? '',
                'country' => $this->postData['country'] ?? '',
                'experience' => [],
                'education' => []
            ];
            $exp = $this->postData['experience'] ?? [];
            if (!is_array($exp)) $exp = [];

            foreach ($exp as $i => $row) {
                if (!is_array($row)) continue;

                $oldForm['experience'][] = [
                    'id'      => $row['id'] ?? null,           // optional if editing
                    'job'     => $row['job'] ?? '',
                    'company' => $row['company'] ?? '',
                    'start'   => $row['start'] ?? '',
                    'end'     => $row['end'] ?? '',
                    'desc'    => $row['desc'] ?? '',
                ];
            }
            $edu = $this->postData['education'] ?? [];
            if (!is_array($edu)) $edu = [];

            foreach ($edu as $e => $row) {
                if (!is_array($row)) continue;

                $oldForm['education'][] = [
                    'id'      => $row['id'] ?? null,           // optional if editing
                    'job'     => $row['job'] ?? '',
                    'company' => $row['company'] ?? '',
                    'start'   => $row['start'] ?? '',
                    'end'     => $row['end'] ?? '',
                    'desc'    => $row['desc'] ?? '',
                ];
            }
            ViewBook::flashForm($oldForm);
        }

        public function handle(): void {
            // Validate for missing value
            $errors = ValidGrimoire::emptyField($this->postData);
            if (!empty($errors)) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = $errors;
                $this->oldForm($this->postData);
                ViewBook::revert($this->postData['action'] ?? '');
                return;
            }

            // Validate email format
            $email = trim((string)($this->postData['email'] ?? ''));
            $msg = ValidGrimoire::validateEmail($email);
            if ($msg !== null) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = ['email' => $msg];
                $this->oldForm($this->postData);
                ViewBook::revert($this->postData['action'] ?? ''); 
                return;
            }

            // Validate fullname
            $name = trim((string)($this->postData['fullname'] ?? ''));
            $msg = ValidGrimoire::validateName($name, true);
            if ($msg !== null) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = ['fullname' => $msg];
                $this->oldForm($this->postData);
                ViewBook::revert($this->postData['action'] ?? '');
                return;
            }

            // Validate city
            $city = trim((string)($this->postData['city'] ?? ''));
            $msg = ValidGrimoire::validateName($city, true);
            if ($msg !== null) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = ['city' => $msg];
                $this->oldForm($this->postData);
                ViewBook::revert($this->postData['action'] ?? '');
                return;
            }

            // Validate country
            $country = trim((string)($this->postData['country'] ?? ''));
            $msg = ValidGrimoire::validateName($country, true);
            if ($msg !== null) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = ['country' => $msg];
                $this->oldForm($this->postData);
                ViewBook::revert($this->postData['action'] ?? '');
                return;
            }

            // Validate phone number format
            $phone = trim((string)($this->postData['phone'] ?? ''));
            $msg = ValidGrimoire::validatePhone($phone);
            if ($msg !== null) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = ['phone' => $msg];
                $this->oldForm($this->postData);
                ViewBook::revert($this->postData['action'] ?? 'profile'); 
                return;
            }

            $sessionOld = $_SESSION['form_old'] ?? [];
            if (!array_key_exists('experience', $this->postData) && isset($sessionOld['experience']) && is_array($sessionOld['experience'])) {
                $this->postData['experience'] = $sessionOld['experience'];
                $_SESSION['old_form'] = null;
            }

            if (!array_key_exists('education', $this->postData) && isset($sessionOld['education']) && is_array($sessionOld['education'])) {
                $this->postData['education'] = $sessionOld['education'];
                $_SESSION['old_form'] = null;
            }

            // Validate country
            $skills = trim((string)($this->postData['skills'] ?? ''));
            $msg = ValidGrimoire::validateName($skills, false);
            if ($msg !== null) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = ['skills' => $msg];
                $this->oldForm($this->postData);
                ViewBook::revert($this->postData['action'] ?? '');
                return;
            }

            // DB lookup (only after validation)  
            try {
                $pdo = Database::Connect();
                $modelRes = new wizardCodex($pdo);
                $modelRes->setFullResume($this->postData);
                $_SESSION['success'] = "Resume saved successfully.";
                ViewBook::revert($this->postData['action'] ?? '');
                exit;

            } catch (\Throwable $e) {
                $_SESSION['error'] = "Failed to save resume. Please try again.";
                ViewBook::revert($this->postData['action'] ?? '');
                exit;
            }
        }
    }