<?php
    class wizardControl {
        public function __construct(private array $postData) {}

        private function oldForm(): void {
            $oldForm = [
                'fullname' => $this->postData['fullname'] ?? '',
                'email' => $this->postData['email'] ?? '',
                'city' => $this->postData['city'] ?? '',
                'country' => $this->postData['country'] ?? '',
                'phone' => $this->postData['phone'] ?? '',
                'experience' => [],
                'education' => [],
                'skills' => []
            ];

            $exp = $this->postData['experience'] ?? [];
            if (!is_array($exp)) $exp = [];
            foreach ($exp as $i => $row) {
                if (!is_array($row)) continue;

                $oldForm['experience'][] = [
                    'id'        => $row['id'] ?? null,           // optional if editing
                    'job'       => $row['job'] ?? '',
                    'company'   => $row['company'] ?? '',
                    'start_date'=> $row['start_date'] ?? '',
                    'end_date'  => $row['end_date'] ?? '',
                    'desc'      => $row['desc'] ?? ''
                ];
            }

            $edu = $this->postData['education'] ?? [];
            if (!is_array($edu)) $edu = [];
            foreach ($edu as $e => $row) {
                if (!is_array($row)) continue;

                $oldForm['education'][] = [
                    'id'        => $row['id'] ?? null,           // optional if editing
                    'program'   => $row['program'] ?? '',
                    'school'    => $row['school'] ?? '',
                    'start_date'=> $row['start_date'] ?? '',
                    'end_date'  => $row['end_date'] ?? '',
                    'desc'      => $row['desc'] ?? ''
                ];
            }

            $ski = $this->postData['skills'] ?? [];
            if (!is_array($ski)) $ski = [];
            foreach ($ski as $y => $row) {
                if (!is_array($row)) continue;

                $oldForm['skills'][] = [
                    'id'      => $row['id'] ?? null,           // optional if editing
                    'name'     => $row['name'] ?? '',
                    'category' => $row['category'] ?? '',
                    'level'   => $row['level'] ?? null
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
                $this->oldForm();
                ViewBook::revert($this->postData['action'] ?? '');
                return;
            }

            // Validate email format
            $email = trim((string)($this->postData['email'] ?? ''));
            if ($msg = ValidGrimoire::validateEmail($email)) {
                $errors['email'] = $msg;
            }

            // Validate fullname
            $name = trim((string)($this->postData['fullname'] ?? ''));
            if ($msg = ValidGrimoire::validateName($name, true)) {
                $errors['fullname'] = $msg;
            }

            // Validate city
            $city = trim((string)($this->postData['city'] ?? ''));
            if ($msg = ValidGrimoire::validateName($city, true)) {
                $errors['city'] = $msg;
            }

            // Validate country
            $country = trim((string)($this->postData['country'] ?? ''));
            if ($msg = ValidGrimoire::validateName($country, true)) {
                $errors['country'] = $msg;
            }

            // Validate phone number format
            $phone = trim((string)($this->postData['phone'] ?? ''));
            if ($msg = ValidGrimoire::validatePhone($phone)) {
                $errors['phone'] = $msg;
            }

            // Final check: if errors exist, send them back together
            if (!empty($errors)) {
                $this->oldForm();
                $_SESSION['error'] = $errors;
                $_SESSION['error']['global'] = 'Some fields in the Wizard require your attention.';
                ViewBook::revert($this->postData['action'] ?? '');
                return;
            }

            $sessionOld = $_SESSION['form_old'] ?? [];
            if (!array_key_exists('experience', $this->postData) && isset($sessionOld['experience']) && is_array($sessionOld['experience'])) {
                $this->postData['experience'] = $sessionOld['experience'];
            }

            if (!array_key_exists('education', $this->postData) && isset($sessionOld['education']) && is_array($sessionOld['education'])) {
                $this->postData['education'] = $sessionOld['education'];
            }

            if (!array_key_exists('skills', $this->postData) && isset($sessionOld['skills']) && is_array($sessionOld['skills'])) {
                $this->postData['skills'] = $sessionOld['skills'];         
            }

            // DB lookup (only after validation)  
            try {
                $pdo = Database::Connect();
                $modelRes = new wizardCodex($pdo);
                $modelRes->setFullResume($this->postData);
            } catch (\Throwable $e) {
                $_SESSION['error'] = "Saving failed. Please try again.";
            }

            ViewBook::revert($this->postData['action'] ?? '');
            exit;
        }
    } // 157