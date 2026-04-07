<?php declare(strict_types=1);
    // // Start a session for handling data and error messages.
    // require_once '../session_manager.src.php';
    // SessionBook::sessionRegenTimer(); 

    // // Invoke the (improved) database connection and FPDF library.
    require_once __DIR__ . '/../../modules/fpdf185/fpdf.php';

    class ResumePDF extends FPDF {
        private array $data = [];

        public function __construct() {
            parent::__construct();
            $this->SetAutoPageBreak(true, 15);
        }

        private function abbrFullname(string $fullName, string $mode = 'initial') {
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

            // Default: Firstname + Last Initial (e.g., Andreas D.)
            return $first . ' ' . strtoupper(substr($last, 0, 1)) . '.';
        }

        private function printDate(string $value) {
            return date("d/m/Y", strtotime($value));
        }

        public function fetchData(int $resumeID, int $userID) {
            $result = [];
            $tables = ['resumes', 'accounts', 'contacts', 'experience', 'experience_bullets', 'education', 'education_bullets', 'skills'];
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
                    $result[$table] = $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE resumeID = ?");
                    $stmt->execute([$resumeID]);
                    $result[$table] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
            }

            $resumetitle = $result['resumes'] ?: [];
            if (empty($resume)) {
                $_SESSION['error'] = 'No data found.';
                header('location: ../client.php');          
                exit;
            }

            $email = $result['accounts'] ?: [];
            $contact = $result['contacts'] ?: [];

            $skills = [];
            foreach (($result['skills'] ?? []) as $item) {
                $name = trim((string)($item['name'] ?? ''));
                if ($name === '') { continue; }

                $skills[] = [
                    'name' => $name,
                    'category' => trim((string)($item['category'] ?? '')),
                ];
            }

            $experience = [];
            foreach (($result['experience'] ?? []) as $item) {
                $experience[] = [
                    'title' => trim((string)($item['title'] ?? '')),
                    'employer' => trim((string)($item['employer'] ?? '')),
                    'start_date' => trim((string)($item['start_date'] ?? '')),
                    'end_date' => trim((string)($item['end_date'] ?? '')),
                    'summary' => trim((string)($item['summary'] ?? ''))
                ];
            }

            $education = [];
            foreach (($result['education'] ?? []) as $item) {
                $education[] = [
                    'title' => trim((string)($item['title'] ?? '')),
                    'institute' => trim((string)($item['institute'] ?? '')),
                    'start_date' => trim((string)($item['start_date'] ?? '')),
                    'end_date' => trim((string)($item['end_date'] ?? '')),
                    'summary' => trim((string)($item['summary'] ?? ''))
                ];
            }

            $exp_bullets = [];
            foreach (($result['experience_bullets'] ?? []) as $item) {
                $exp_bullets[] = [
                    'desc' => trim((string)($item['desc'] ?? '')),
                    'work_id' => trim((string)($item['work_id'] ?? ''))
                ];
            }

            $edu_bullets = [];
            foreach (($result['education_bullets'] ?? []) as $item) {
                $edu_bullets[] = [
                    'desc' => trim((string)($item['desc'] ?? '')),
                    'acad_id' => trim((string)($item['acad_id'] ?? ''))
                ];
            }

            $this->data = [
                'resume_title' => $resumetitle,
                'email' => $email,
                'contact' => $contact,
                'skills' => $skills,
                'experience' => $experience,
                'education' => $education,
                'exp_bullets' => $exp_bullets,
                'edu_bullets' => $edu_bullets
            ];
        }

        public function loadPostData(array $post): void {
            $experience = [];
            $rawExperience = $post['experience'] ?? [];

            if (is_array($rawExperience)) {
                foreach ($rawExperience as $item) {
                    if (!is_array($item)) {
                        continue;
                    }

                    $job = trim((string) ($item['job'] ?? ''));
                    $employer = trim((string) ($item['employer'] ?? ''));
                    $start = trim((string) ($item['start_date'] ?? ''));
                    $end = trim((string) ($item['end_date'] ?? ''));
                    $desc = trim((string) ($item['desc'] ?? ''));

                    if ($job === '' && $employer === '' && $desc === '') {
                        continue;
                    }

                    $experience[] = [
                        'job' => $job,
                        'employer' => $employer,
                        'start_date' => $start,
                        'end_date' => $end,
                        'desc' => $desc
                    ];
                }
            }

            $education = [];
            $rawEducation = $post['education'] ?? [];

            if (is_array($rawEducation)) {
                foreach ($rawEducation as $item) {
                    if (!is_array($item)) {
                        continue;
                    }

                    $program = trim((string) ($item['program'] ?? ''));
                    $school = trim((string) ($item['school'] ?? ''));
                    $start = trim((string) ($item['start_date'] ?? ''));
                    $end = trim((string) ($item['end_date'] ?? ''));
                    $desc = trim((string) ($item['desc'] ?? ''));

                    if ($program === '' && $school === '' && $desc === '') {
                        continue;
                    }

                    $education[] = [
                        'program' => $program,
                        'school' => $school,
                        'start_date' => $start,
                        'end_date' => $end,
                        'desc' => $desc
                    ];
                }
            }

            $skills = [];
            $rawSkills = $post['skills'] ?? [];

            if (is_array($rawSkills)) {
                foreach ($rawSkills as $item) {
                    if (!is_array($item)) {
                        continue;
                    }

                    $name = trim((string) ($item['name'] ?? ''));
                    $category = trim((string) ($item['category'] ?? ''));

                    if ($name === '') {
                        continue;
                    }

                    $skills[] = [
                        'name' => $name,
                        'category' => $category
                    ];
                }
            }

            $this->data = [
                'fullname' => trim((string) ($post['fullname'] ?? '')),
                'headline' => trim((string) ($post['headline'] ?? '')),
                'email' => trim((string) ($post['email'] ?? '')),
                'city' => trim((string) ($post['city'] ?? '')),
                'country' => trim((string) ($post['country'] ?? '')),
                'phone' => trim((string) ($post['phone'] ?? '')),
                'experience' => $experience,
                'education' => $education,
                'skills' => $skills
            ];
        }
        
        function Header() {
            if ($this->PageNo() == 1) {
                //Cell( width, height, text, border, end line, align)
                // Set the font and size
                $this->SetFont('Courier', 'B', 16);
                // Add Custom header
                $this->Cell(0, 10, 'Curriculum Vitae', 0, 0, 'C');          
                // Add a line break
                $this->Ln(10);
            }
        }
        
        function Footer() {
            // Check if more than one page exists
            if ($this->PageNo() > 1) {
                // Select Arial italic 8
                $this->SetFont('Arial','I',8);
                // Set the position of the footer at 15mm from the bottom
                $this->SetY(-15);
                // Page number
                $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
            }
        }

        public function generatePDF() {
            $this->AliasNbPages();
            $this->AddPage();
            $this->SetFont('Courier', '', 12);
            
            //////////////////// TRADEMARK ///////////////////
            //$imagePath = '../../assets/images/witch_logo2.png';
            // $building = '../../assets/images/icons/buildings-24.png'; 
            // $envelope = '../../assets/images/icons/envelope-24.png';
            // $mobile = '../../assets/images/icons/phone-24.png'; 
            // $world = '../../assets/images/icons/world-24.png';

            // Set Trademark
            //$this->Image($imagePath, 10, 10, 30); // Adjust positioning and dimensions

            // Sanitize data using htmlspecialchars
            // Resume title becomes Filename
            if (isset($this->data['contacts']['fullname'])) {
                $today = $this->printDate('today');
                $doc = $this->abbrFullname($this->data['contacts']['fullname']); 
                $this->SetTitle($doc.' '.$today);
            } else { 
                // Wizard
                $this->SetTitle('My Scroll - Standard');
            }


            //////////////////// HEADLINE ///////////////////
            if ($this->data['headline'] !== '') {
                $this->SetFont('Courier', '', 8);
                $this->SetX(77);
                $this->MultiCell(0, 2, $this->data['headline'], 0, 'L');
            }
            $this->Ln(4);
            

            //////////////////// PERSONAL //////////////////
            $this->SetFont('Courier', 'BU', 11);
            $this->Cell(0, 10, 'Personal', 0, 1, 'L');
            $this->SetFont('Courier', '', 10);

            // Two-Column
            $labelX = 10;
            $valueX = 76;
            $rowH   = 4;
            $y = $this->GetY();
            $this->data['birth_date'] = '02/06/1956';
            $rows = [
                'Name'          => $this->data['fullname'] ?? '',
                'City'          => $this->data['city'] ?? '',
                'Country'       => $this->data['country'] ?? '',
                'Date of Birth' => $this->data['birth_date'] ?? '',
                'Email'         => $this->data['email'] ?? '',
                'Phone'         => $this->data['phone'] ?? '',  
            ];
            foreach ($rows as $label => $value) {
                $this->SetXY($labelX, $y);
                $this->Cell(40, $rowH, $label, 0, 0, 'L');

                $this->SetXY($valueX, $y);
                $this->Cell(0, $rowH, ': ' . $value, 0, 1, 'L');

                $y += $rowH;
            }
            $this->Ln($rowH);


            /////////////////////// EDUCATION ////////////////////////
            $this->SetFont('Courier', 'BU', 11);
            $this->Cell(0, 10, 'Education', 0, 1, 'L');

            $educations = $this->data['education'] ?? [];
            foreach (array_slice($educations, 0, 6) as $item) {
                $start    = $item['start_date'] ?? '';
                $end      = $item['end_date'] ?? '';
                $program  = $item['program'] ?? '';
                $school   = $item['school'] ?? '';
                $desc     = $item['summary'] ?? '';

                $dateRange = trim($start . ' - ' . $end, ' -');

                $this->SetFont('Courier', '', 10);
                $this->Cell(60, 6, $dateRange, 0, 0, 'L');
                $this->SetX(76);
                $this->Cell(0, 6, ': '.$program.'  ('.$school.')', 0, 1, 'L');

                if ($desc !== '') {
                    $this->SetFont('Courier', '', 8);
                    $this->SetX(77);
                    $this->MultiCell(0, 2, '  ' . $desc, 0, 'L');
                }
            }
            if (!isset($this->data['education']['summary']) || empty($this->data['education']['summary'])) {
                $edu_bullets = $this->data['education_bullets'] ?? [];
                foreach (array_slice($edu_bullets, 0, 6) as $item) {
                    $desc    = $item['desc'] ?? '';
                    $sort      = $item['sort_order'] ?? '';

                    $this->SetFont('Courier', '', 8);
                    $this->SetX(77);
                    $this->Cell(5, 6, chr(149), 0, 0);
                    $this->MultiCell(0, 2, '  ' . $desc, 0, 'L');
                }
            }
            $this->Ln($rowH);


            /////////////////////// EXPERIENCE ////////////////////////
            $this->SetFont('Courier', 'BU', 11);
            $this->Cell(0, 10, 'Experience', 0, 1, 'L');

            $experiences = $this->data['experience'] ?? [];
            foreach (array_slice($experiences, 0, 6) as $item) {
                $start    = $item['start_date'] ?? '';
                $end      = $item['end_date'] ?? '';
                $job      = $item['job'] ?? '';
                $employer = $item['employer'] ?? '';
                $desc     = $item['summary'] ?? '';

                $dateRange = trim($start . ' - ' . $end, ' -');

                $this->SetFont('Courier', '', 10);
                $this->Cell(60, 6, $dateRange, 0, 0, 'L');
                $this->SetX(76);
                $this->Cell(0, 6, ': '.$job.'  ('.$employer.')', 0, 1, 'L');

                if ($desc !== '') {
                    $this->SetFont('Courier', '', 8);
                    $this->SetX(77);
                    $this->MultiCell(0, 2, '  ' . $desc, 0, 'L');
                }
            }
            if (!isset($this->data['experience']['summary']) || empty($this->data['experience']['summary'])) {
                $exp_bullets = $this->data['experience_bullets'] ?? [];
                foreach (array_slice($exp_bullets, 0, 6) as $item) {
                    $desc    = $item['desc'] ?? '';
                    $sort      = $item['sort_order'] ?? '';

                    $this->SetFont('Courier', '', 8);
                    $this->SetX(77);
                    $this->Cell(5, 6, chr(149), 0, 0);
                    $this->MultiCell(0, 2, '  ' . $desc, 0, 'L');
                }
            }

            $this->Ln($rowH);


            /////////////////////// SKILLS ////////////////////////
            $this->SetFont('Courier', 'BU', 11);
            $this->Cell(0, 10, 'Skills', 0, 1, 'L');

            $skills = $this->data['skills'] ?? [];
            foreach (array_slice($skills, 0, 10) as $item) {
                $name     = $item['name'] ?? '';
                $category = $item['category'] ?? '';

                $this->SetFont('Courier', '', 10);
                // $bullet = iconv('UTF-8', 'windows-1252', '•');
                // $this->Cell(5, 6, $bullet, 0, 0);
                $this->Cell(5, 6, chr(149), 0, 0);
                $first = explode(' ', $category)[0];
                $this->Cell(0, 6, '('.$first.') '.$name, 0, 1, 'L');
            }
            $this->Ln($rowH);

            $this->Output();
        }
    }

    // 1. Guardrail. Only form submissions allowed!
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $_SESSION['error'] = 405;
        header('location: ../error.php');          
        exit;
    }

    // 2. Router. User and Visitor
    if (isset($_SESSION['session_data']['user_id'])) {
        $resid = $_SESSION['session_data']['resumeID'];
        $uid = $_SESSION['session_data']['user_id'];

        $resumePDF = new ResumePDF();
        $resumePDF->fetchData($resid, $usid);
        $resumePDF->generatePDF();
        
    } elseif (isset($_POST['action']) && $_POST['action'] === 'vintage') {
        $resumePDF = new ResumePDF();
        $resumePDF->loadPostData($_POST);
        $resumePDF->generatePDF();

    } else {
        // echo '<pre>';
        // print_r($_POST);
        // echo '</pre>';
        
        // No goal. Try again later.
        header('location: ../../error.php');          
        exit;
    }