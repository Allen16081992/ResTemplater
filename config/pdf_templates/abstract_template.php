<?php declare(strict_types=1);
    // Load files & libraries.
    require_once __DIR__ . '/../session_manager.conf.php';
    require_once __DIR__ . '/../../modules/fpdf185/fpdf.php';
    include_once __DIR__ . '/../../modules/phpqrcode/qrlib.php';
 
    abstract class BaseTemplate extends FPDF {
        protected $data = [];

        private function fetchContactHeader(PDO $pdo, int $uid): array {
            // The JOIN ensures we get the system email and the personal contact info in one shot
            // Explicitly define columns to avoid 'id' and 'timestamp' collisions
            $sql = 'SELECT c.fullname, c.phone, c.city, c.country, c.image_url, a.email 
                    FROM contacts c 
                    JOIN accounts a ON c.user_id = a.id 
                    WHERE c.user_id = ?';    
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$uid]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        }
        
        protected function sanitize(mixed $data): mixed {
            // 1. If it's an array, let the function call itself for each element (Recursion)
            if (is_array($data)) {
                return array_map([$this, 'sanitize'], $data);
            }
            // 2. If it's not an array, treat it as a string for FPDF
            $str = trim((string)($data ?? ''));
            return mb_convert_encoding($str, 'ISO-8859-1', 'UTF-8');
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

        protected function printDate(string $value) {
            return date("d/m/Y", strtotime($value));
        }

        public function fetchData(string $resid, int $uid) {
            $pdo = Database::connect();
            $resid = (int)$resid;

            // 1. Use all specialized Codex classes
            $resCodex = new resumeCodex($pdo);
            $expCodex = new experienceCodex($pdo);
            $eduCodex = new educationCodex($pdo);
            $proCodex = new projectCodex($pdo);
            $socCodex = new socialCodex($pdo);
            $skiCodex = new skillCodex($pdo);

            // 3. Assemble the Master Data Array
            $this->data = [
                'master'       => $this->sanitize($resCodex->fetchResume($resid, $uid)),
                'contact'      => $this->sanitize($this->fetchContactHeader($pdo, $uid)), // Your new private helper
                
                // Repeater Data (Sanitized nested arrays)
                'experience'   => $this->sanitize($expCodex->getExperience($resid)),
                'education'    => $this->sanitize($eduCodex->getEducation($resid)),
                'projects'     => $this->sanitize($proCodex->getProject($resid)),
                'socials'       => $this->sanitize($socCodex->getSocial($resid)),
                'skills'       => $this->sanitize($skiCodex->getSkill($resid)),
                
                // Bullets (Crucial for the "Missing Content" fix)
                'experience_bullets'  => $this->sanitize($expCodex->getBulletpoints($resid)),
                'education_bullets'  => $this->sanitize($eduCodex->getBulletpoints($resid)),
                'projects_bullets'  => $this->sanitize($proCodex->getBulletpoints($resid))
            ];
        }

        public function loadPostData(array $post): void {
            // 1. One clean, recursive sweep of the entire POST array
            $clean = $this->sanitize($post);

            // 2. Map it to the same "PaperWitch" structure used in fetchData()
            $this->data = [
                'master' => [
                    'headline' => $clean['headline'] ?? '',
                    'title'    => $clean['resume_title'] ?? 'Untitled'
                ],
                'contact'    => [
                    'fullname' => $clean['fullname'] ?? '',
                    'email'    => $clean['email'] ?? '',
                    'phone'    => $clean['phone'] ?? '',
                    'city'     => $clean['city'] ?? '',
                    'country'  => $clean['country'] ?? ''
                ],
                'experience'         => $clean['experience'] ?? [],
                'education'          => $clean['education'] ?? [],
                'projects'           => $clean['projects'] ?? [],
                'skills'             => $clean['skills'] ?? [],
                'socials'             => $clean['socials'] ?? [],
                
                // Ensure the keys match your new fetchData() exactly!
                'experience_bullets' => $clean['experience_bullets'] ?? [],
                'education_bullets'  => $clean['education_bullets'] ?? [],
                'projects_bullets'   => $clean['projects_bullets'] ?? []
            ];
        }
        
        // Every template MUST have this, but they will each write it differently
        abstract public function generatePDF();
    }