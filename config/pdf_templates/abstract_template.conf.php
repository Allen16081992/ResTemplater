<?php declare(strict_types=1);
    // Load files & libraries.
    require_once __DIR__ . '/../session_manager.conf.php';
    require_once __DIR__ . '/../../modules/fpdf185/fpdf.php';
    include_once __DIR__ . '/../../modules/phpqrcode/qrlib.php';
 
    abstract class BaseTemplate extends FPDF {
        protected $data = [];

        // All your shared logic goes here
        protected function sanitize(mixed $value = null): string {
            $str = trim((string)($value ?? ''));
            // Convert UTF-8 from the form/DB to the ISO-8859-1 FPDF expects
            return mb_convert_encoding($str, 'ISO-8859-1', 'UTF-8');
        }

        protected function printDate(string $value) {
            return date("d/m/Y", strtotime($value));
        }

        protected function abbrFullname(string $fullName, string $mode = 'initial') {
            // 1. Clean up and split
            $words = explode(' ', trim($fullName));
            
            // 2. Safety check: If only one word, just return it capitalized
            if (count($words) <= 1) {
                return strtoupper(substr($words[0], 0, 1)); // Or return $words[0]
            }

            $first = ucfirst($words[0]);
            $last  = ucfirst(end($words));

            // Mode logic
            if ($mode === 'short') {
                // Option 2: Just Initials (e.g., AD)
                return strtoupper(substr($first, 0, 1) . substr($last, 0, 1));
            }

            if ($mode === 'dotted') {
                // The 1990s Special: e.g., A.D.
                return strtoupper(substr($first, 0, 1) . '.' . substr($last, 0, 1) . '.');
            }

            // Default: Firstname + Last Initial (e.g., Andreas D.)
            return $first . ' ' . strtoupper(substr($last, 0, 1)) . '.';
        }

        public function fetchData(int $resumeID, int $userID) {
            $result = [];
            $tables = ['resumes', 'accounts', 'contacts', 'experience', 'experience_bullets', 'education', 'education_bullets', 'projects', 'project_bullets', 'skills', 'socials'];
            $pdo = Database::connect();

            // Loop through each table and fetch data
            foreach ($tables as $table) {
                if ($table === 'resumes') {
                    $stmt = $pdo->prepare("SELECT resume_title FROM `$table` WHERE resumeID = ?");
                    $stmt->execute([$resumeID]);
                    $result[$table] = $stmt->fetch(PDO::FETCH_ASSOC);
                } elseif ($table === 'accounts') {
                    $stmt = $pdo->prepare("SELECT email FROM `$table` WHERE userID = ?");
                    $stmt->execute([$userID]);
                    $result[$table] = $stmt->fetch(PDO::FETCH_ASSOC);
                } elseif ($table === 'contacts') {
                    $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE userID = ?");
                    $stmt->execute([$userID]);
                    $result[$table] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE resumeID = ?");
                    $stmt->execute([$resumeID]);
                    $result[$table] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
            }

            if (empty($result['resumes'])) {
                $_SESSION['error'] = 'No data found.';
                header('location: ../client.php');          
                exit;
            }

            $email = $this->sanitize($result['accounts']['email'] ?? '');

            $contact = [];
            foreach ($result['contacts'] ?: [] as $item) {
                $contact[] = [
                    'fullname'  => $this->sanitize($item['fullname']),
                    'phone'     => $this->sanitize($item['phone']),
                    'city'      => $this->sanitize($item['city']),
                    'country'   => $this->sanitize($item['country']),
                    'image_url' => $item['image_url'] ?? ''
                ];
            }

            $experience = [];
            foreach (($result['experience'] ?? []) as $item) {
                $experience[] = [
                    'title'     => $this->sanitize($item['title']),
                    'employer'  => $this->sanitize($item['employer']),
                    'start_date'=> $this->sanitize($item['start_date']),
                    'end_date'  => $this->sanitize($item['end_date']),
                    'summary'   => $this->sanitize($item['summary'])
                ];
            }

            $education = [];
            foreach (($result['education'] ?? []) as $item) {
                $education[] = [
                    'program'   => $this->sanitize($item['program']),
                    'school'    => $this->sanitize($item['school']),
                    'start_date'=> $this->sanitize($item['start_date']),
                    'end_date'  => $this->sanitize($item['end_date']),
                    'summary'   => $this->sanitize($item['summary'])
                ];
            }

            $projects = [];
            foreach (($result['projects'] ?? []) as $item) {
                $projects[] = [
                    'title'    => $this->sanitize($item['title']),
                    'role'     => $this->sanitize($item['role']),
                    'summary'  => $this->sanitize($item['summary'])
                ];
            }

            $exp_bullets = [];
            foreach (($result['experience_bullets'] ?? []) as $item) {
                $exp_bullets[] = [
                    'summary'  => $this->sanitize($item['summary']),
                    'work_id'  => $item['work_id']
                ];
            }

            $edu_bullets = [];
            foreach (($result['education_bullets'] ?? []) as $item) {
                $edu_bullets[] = [
                    'summary'  => $this->sanitize($item['summary']),
                    'edu_id'   => $item['edu_id']
                ];
            }

            $pro_bullets = [];
            foreach (($result['project_bullets'] ?? []) as $item) {
                $pro_bullets[] = [
                    'summary'   => $this->sanitize($item['summary']),
                    'project_id'=> $item['project_id']
                ];
            }

            $skills = [];
            foreach (($result['skills'] ?? []) as $item) {
                $skills[] = [
                    'name'     => $this->sanitize($item['name']),
                    'category' => $this->sanitize($item['category'])
                ];
            }

            $social = [];
            foreach (($result['social'] ?? []) as $item) {
                $social[] = [
                    'name'     => $this->sanitize($item['name']),
                    'media_url'=> $item['media_url'] // Keep raw for QR generator
                ];
            }

            $this->data = [
                'resume_title' => $this->sanitize($result['resumes']['resume_title'] ?? 'Untitled'),
                'email'        => $email,
                'contact'      => $contact,
                'skills'       => $skills,
                'experience'   => $experience,
                'education'    => $education,
                'projects'     => $projects,
                'exp_bullets'  => $exp_bullets,
                'edu_bullets'  => $edu_bullets,
                'pro_bullets'  => $pro_bullets,
                'social'       => $social
            ];
        }

        public function loadPostData(array $post): void {
            $experience = [];
            foreach ($post['experience'] ?? [] as $item) {
                if (!is_array($item)) continue;

                $title      = $this->sanitize($item['title']);
                $employer = $this->sanitize($item['employer']);
                $summary     = $this->sanitize($item['summary'] ?? '');

                if ($title === '' && $employer === '' && $summary === '') continue;

                $experience[] = [
                    'title'      => $title,
                    'employer'   => $employer,
                    'start_date' => $this->sanitize($item['start_date']),
                    'end_date'   => $this->sanitize($item['end_date']),
                    'summary'    => $summary
                ];
            }

            $education = [];
            foreach ($post['education'] ?? [] as $item) {
                if (!is_array($item)) continue;

                $program = $this->sanitize($item['program']);
                $school  = $this->sanitize($item['school']);
                $start   = $this->sanitize($item['start_date']);
                $end     = $this->sanitize($item['end_date']);
                $summary    = $this->sanitize($item['summary'] ?? '');

                if ($program === '' && $school === '' && $summary === '') continue;

                $education[] = [
                    'program'    => $program,
                    'school'     => $school,
                    'start_date' => $start,
                    'end_date'   => $end,
                    'summary'    => $summary
                ];
            }

            $skills = [];
            foreach ($post['skills'] ?? [] as $item) {
                if (!is_array($item)) continue;

                $name     = $this->sanitize($item['name']);
                $category = $this->sanitize($item['category']);

                if ($name === '') continue;

                $skills[] = [
                    'name' => $name,
                    'category' => $category
                ];
            }

            $social = [];
            foreach ($post['social'] ?? [] as $item) {
                if (!is_array($item)) continue;

                $media = trim((string) ($item['name'] ?? ''));
                if ($media === '') continue;

                $social[] = [
                    'media_url' => $media
                ];
            }         

            $this->data = [
                'fullname' => trim((string) ($post['fullname'] ?? '')),
                'headline' => trim((string) ($post['headline'] ?? '')),
                'email' => trim((string) ($post['email'] ?? '')),
                'city' => trim((string) ($post['city'] ?? '')),
                'country' => trim((string) ($post['country'] ?? '')),
                'phone' => trim((string) ($post['phone'] ?? '')),
                'social' => trim((string) ($post['social'] ?? '')),
                'experience' => $experience,
                'education' => $education,
                'skills' => $skills
            ];
        }
        
        // Every template MUST have this, but they will each write it differently
        abstract public function generatePDF();
    }