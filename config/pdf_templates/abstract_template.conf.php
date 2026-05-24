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

        public function fetchData(string $resid, int $uid) {
            $result = [];
            $tables = [
                'resumes', 'accounts', 'contacts', 
                'experience', 'education', 'projects', 'skills', 'socials',
                'experience_bullets', 'education_bullets', 'projects_bullets',
            ];
            $pdo = Database::connect();

            // Loop through each table and fetch data
            foreach ($tables as $table) {
                if ($table === 'resumes') {
                    $stmt = $pdo->prepare("SELECT title, headline FROM `$table` WHERE id = :resume_id");
                    $stmt->execute([':resume_id' => $resid]);
                    $result[$table] = $stmt->fetch(PDO::FETCH_ASSOC);
                } elseif ($table === 'accounts') {
                    $stmt = $pdo->prepare("SELECT email FROM `$table` WHERE id = :user_id");
                    $stmt->execute([':user_id' => $uid]);
                    $result[$table] = $stmt->fetch(PDO::FETCH_ASSOC);
                } elseif ($table === 'contacts') {
                    // FIX 3: fetch() in plaats van fetchAll() om de data direct plat te slaan
                    $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE user_id = :user_id");
                    $stmt->execute([':user_id' => $uid]);
                    $result[$table] = $stmt->fetch(PDO::FETCH_ASSOC);
                } elseif (str_ends_with($table, '_bullets')) {

                    $parentTable = str_replace('_bullets', '', $table);
                    $foreignKey = $parentTable . '_id';
                    
                    $parentIDs = [];
                    if (!empty($result[$parentTable])) {
                        foreach ($result[$parentTable] as $parentRow) {
                            $parentIDs[] = $parentRow['id'];
                        }
                    }

                    if (empty($parentIDs)) {
                        $result[$table] = [];
                        continue;
                    }

                    $placeholders = implode(',', array_fill(0, count($parentIDs), '?'));
                    $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE `$foreignKey` IN ($placeholders)");
                    $stmt->execute($parentIDs);
                    $result[$table] = $stmt->fetchAll(PDO::FETCH_ASSOC);

                } else {
                    $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE resume_id = ?");
                    $stmt->execute([$resid]);
                    $result[$table] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
            }

            $parchment = $this->sanitize($result['resumes']['title'] ?? 'Untitled');
            $email = $this->sanitize($result['accounts']['email'] ?? '');

            // Directly map contacts and skip loop
            $contactRow = $result['contacts'] ?: [];
            $contact = [
                'fullname'  => $this->sanitize($contactRow['fullname'] ?? ''),
                'phone'     => $this->sanitize($contactRow['phone'] ?? ''),
                'city'      => $this->sanitize($contactRow['city'] ?? ''),
                'country'   => $this->sanitize($contactRow['country'] ?? ''),
                'image_url' => $contactRow['image_url'] ?? ''
            ];

            $experience = [];
            foreach (($result['experience'] ?? []) as $item) {
                $experience[] = [
                    'id'        => $item['id'], // FIX 2: Essentieel voor de bullets koppeling straks!
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
                    'id'        => $item['id'], // FIX 2: Essentieel voor de bullets koppeling straks!
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
                    'id'        => $item['id'], // FIX 2: Essentieel voor de bullets koppeling straks!
                    'title'     => $this->sanitize($item['title']),
                    'role'      => $this->sanitize($item['role']),
                    'summary'   => $this->sanitize($item['summary'])
                ];
            }

            $exp_bullets = [];
            foreach (($result['experience_bullets'] ?? []) as $item) {
                $exp_bullets[] = [
                    'summary'       => $this->sanitize($item['summary']),
                    'experience_id' => $item['experience_id']
                ];
            }

            $edu_bullets = [];
            foreach (($result['education_bullets'] ?? []) as $item) {
                $edu_bullets[] = [
                    'summary'      => $this->sanitize($item['summary']),
                    'education_id' => $item['education_id']
                ];
            }

            $pro_bullets = [];
            foreach (($result['projects_bullets'] ?? []) as $item) {
                $pro_bullets[] = [
                    'summary'     => $this->sanitize($item['summary']),
                    'projects_id' => $item['projects_id']
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
            foreach (($result['socials'] ?? []) as $item) {
                $social[] = [
                    'name'      => $this->sanitize($item['name']),
                    'media_url' => $item['media_url'] 
                ];
            }

            // FIX 1: Check de headline uit $result['resumes'], want $this->data bestaat nog niet
            if (empty($experience) && empty($education) && empty($projects)) {
                $headline = 'This parchment is empty. Add some ingredients, silly!';
            } else {
                $headline = $result['resumes']['headline'] ?? '';
            }

            $this->data = [
                'resume_title' => $parchment,
                'headline'     => $headline,
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
            // Directly map contacts and skip loop
            $headline = $this->sanitize($post['headline']);
            $contact = [
                'fullname' => $this->sanitize($post['fullname']),
                'email'    => $this->sanitize($post['email']),
                'phone'    => $this->sanitize($post['phone']),
                'city'     => $this->sanitize($post['city']),
                'country'  => $this->sanitize($post['country'])
            ];

            // $experience = [];
            // foreach ($post['experience'] ?? [] as $item) {
            //     if (!is_array($item)) continue;

            //     $title      = $this->sanitize($item['title']);
            //     $employer = $this->sanitize($item['employer']);
            //     $summary     = $this->sanitize($item['summary'] ?? '');

            //     if ($title === '' && $employer === '' && $summary === '') continue;

            //     $experience[] = [
            //         'title'      => $title,
            //         'employer'   => $employer,
            //         'start_date' => $this->sanitize($item['start_date']),
            //         'end_date'   => $this->sanitize($item['end_date']),
            //         'summary'    => $summary
            //     ];
            // }
            $experience = [];
            $counter = 1;
            foreach ($post['experience'] ?? [] as $item) {
                $experience[] = [
                    'id'        => $item['id'] ?? $counter, 
                    'title'     => $this->sanitize($item['title']),
                    'employer'  => $this->sanitize($item['employer']),
                    'start_date'=> $this->sanitize($item['start_date']),
                    'end_date'  => $this->sanitize($item['end_date']),
                    'summary'   => $this->sanitize($item['summary'])
                ];
                $counter++;
            }

            // $education = [];
            // foreach ($post['education'] ?? [] as $item) {
            //     if (!is_array($item)) continue;

            //     $program = $this->sanitize($item['program']);
            //     $school  = $this->sanitize($item['school']);
            //     $start   = $this->sanitize($item['start_date']);
            //     $end     = $this->sanitize($item['end_date']);
            //     $summary    = $this->sanitize($item['summary'] ?? '');

            //     if ($program === '' && $school === '' && $summary === '') continue;

            //     $education[] = [
            //         'program'    => $program,
            //         'school'     => $school,
            //         'start_date' => $start,
            //         'end_date'   => $end,
            //         'summary'    => $summary
            //     ];
            // }
            $education = [];
            $counter = 1;
            foreach ($post['education'] ?? [] as $item) {
                $education[] = [
                    'id'        => $item['id'] ?? $counter, 
                    'program'   => $this->sanitize($item['program']),
                    'school'    => $this->sanitize($item['school']),
                    'start_date'=> $this->sanitize($item['start_date']),
                    'end_date'  => $this->sanitize($item['end_date']),
                    'summary'   => $this->sanitize($item['summary'])
                ];
                $counter++;
            }

            $skills = [];
            foreach ($post['skills'] ?? [] as $item) {
                $skills[] = [
                    'name'     => $this->sanitize($item['name']),
                    'category' => $this->sanitize($item['category'])
                ];
            }

            $social = [];
            foreach (($post['social'] ?? []) as $item) {
                // Alleen toevoegen als er ook echt een naam of url is ingevuld
                if (!empty($item['name']) || !empty($item['media_url'])) {
                    $social[] = [
                        'name'      => $this->sanitize($item['name'] ?? 'Media'),
                        'media_url' => $this->sanitize($item['media_url'] ?? '') 
                    ];
                }
            }        

            $this->data = [
                'headline'  => $headline,
                'contact'   => $contact,
                'experience'=> $experience,
                'education' => $education,
                'skills'    => $skills,
                'social'   => $social,
            ];
        }
        
        // Every template MUST have this, but they will each write it differently
        abstract public function generatePDF();
    }