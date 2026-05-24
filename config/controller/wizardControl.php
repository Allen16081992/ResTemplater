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
            // Capture everything after the colon ':'
            // $parts = explode(':', $this->postData['action'], 2);
            // $intent = $parts[1] ?? '';

            // Validate for missing value
            $errors = ValidGrimoire::emptyField($this->postData);
            if (!empty($errors)) {
                // Hold error message + set previous UI state
                $_SESSION['error'] = $errors;
                $this->oldForm();
                ViewBook::revert($this->postData['action'] ?? '');
                return;
            }

            // Define the fields we care about
            $fields = ['email', 'fullname', 'city', 'country', 'phone'];

            foreach ($fields as $field) {
                // 1. Get value, cast to string, trim
                $value = trim((string)($this->postData[$field] ?? ''));
                
                // 2. Overwrite the original array immediately
                $this->postData[$field] = $value;
                $msg = null;

                // 3. Validate (using a dynamic switch or specific calls)
                if ($field === 'email') {
                    $msg = ValidGrimoire::validateEmail($value);
                } elseif ($field === 'phone') {
                    $msg = ValidGrimoire::validatePhone($value);
                } elseif ($field !== 'start_date' || $field !== 'end_date') {
                    // Name, City, Country all use the same method
                    $msg = ValidGrimoire::validateName($value, true);
                }

                // Final check: if errors exist, send them back together
                if ($msg) {
                    $_SESSION['error'][$field] = $msg;
                }
            }

            // If more than 1 entries
            if (!empty($_SESSION['error'])) {
                $this->oldForm();
                $_SESSION['error']['global'] = 'Some fields in the Wizard require your attention.';
                ViewBook::revert('wizard');
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

            // ==========================================
            // NEW: Timeline Date Processing Stage
            // ==========================================
            $timelines = ['experience', 'education'];

            foreach ($timelines as $timeline) {
                if (isset($this->postData[$timeline]) && is_array($this->postData[$timeline])) {
                    foreach ($this->postData[$timeline] as $index => $item) {
                        
                        // 1. Validate & Clean Start Date
                        if (isset($item['start_date'])) {
                            $startResult = ValidGrimoire::validateAndFormatMonth($item['start_date'], false);
                            if ($startResult['error']) {
                                $_SESSION['error'][$timeline][$index]['start_date'] = $startResult['error'];
                            } else {
                                $this->postData[$timeline][$index]['start_date'] = $startResult['date'];
                            }
                        }

                        // 2. Validate & Clean End Date (Flags TRUE for blank/Present allowance)
                        if (isset($item['end_date'])) {
                            $endResult = ValidGrimoire::validateAndFormatMonth($item['end_date'], true);
                            if ($endResult['error']) {
                                $_SESSION['error'][$timeline][$index]['end_date'] = $endResult['error'];
                            } else {
                                $this->postData[$timeline][$index]['end_date'] = $endResult['date'];
                            }
                        }
                    }
                }
            }

            // Re-check for newly added timeline validation errors before executing DB commands
            if (!empty($_SESSION['error'])) {
                $this->oldForm();
                $_SESSION['error']['global'] = 'Please review your timeline dates in the Wizard.';
                ViewBook::revert('wizard');
                return;
            }
            // ==========================================

            // DB lookup (only after validation)  
            try {
                $pdo = Database::Connect();
                $modelRes = new wizardCodex($pdo);
                $modelRes->setFullResume($this->postData);
            } catch (\Throwable $e) {
                $_SESSION['error'] = "Failed to save resume. Please try again.";
            }
            ViewBook::revert('wizard');
            exit;
        }
    }