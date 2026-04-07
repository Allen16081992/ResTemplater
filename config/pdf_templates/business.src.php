<?php declare(strict_types=1);
    // // Start a session for handling data and error messages.
    // require_once '../session_manager.src.php';
    // SessionBook::sessionRegenTimer(); 

    // // Invoke the (improved) database connection and FPDF library.
    require_once __DIR__ . '/../../modules/fpdf185/fpdf.php';
    include_once __DIR__ . '/../../modules/phpqrcode/qrlib.php';

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

        private function alignSubtitle(string $title, float $contentX, float &$startY): void {
            $startY = $this->GetY();
            $hasSocials = !empty($this->data['social']);
            $align = $hasSocials ? 'L' : 'C';

            $this->SetX($contentX);
            $this->SetFont('Times', 'B', 14);
            
            // Draw the Line
            $lineStartX = $hasSocials ? $contentX : 10;
            $this->Line($lineStartX, $startY, 200, $startY);
            
            // Print the Title
            $this->Cell(0, 7, $this->sanitize($title), 0, 1, $align);
            $this->Ln(2);
        }

        private function sanitize(mixed $value = null): string {
            $str = trim((string)($value ?? ''));
            // Convert UTF-8 from the form/DB to the ISO-8859-1 FPDF expects
            return mb_convert_encoding($str, 'ISO-8859-1', 'UTF-8');
        }

        public function fetchData(int $resumeID, int $userID) {
            $result = [];
            $tables = ['resumes', 'accounts', 'contacts', 'experience', 'experience_bullets', 'education', 'education_bullets', 'skills', 'social'];
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
                    'title'      => $this->sanitize($item['title']),
                    'employer'   => $this->sanitize($item['employer']),
                    'start_date' => $this->sanitize($item['start_date']),
                    'end_date'   => $this->sanitize($item['end_date']),
                    'summary'    => $this->sanitize($item['summary'])
                ];
            }

            $education = [];
            foreach (($result['education'] ?? []) as $item) {
                $education[] = [
                    'title'      => $this->sanitize($item['title']),
                    'institute'  => $this->sanitize($item['institute']),
                    'start_date' => $this->sanitize($item['start_date']),
                    'end_date'   => $this->sanitize($item['end_date']),
                    'summary'    => $this->sanitize($item['summary'])
                ];
            }

            $exp_bullets = [];
            foreach (($result['experience_bullets'] ?? []) as $item) {
                $exp_bullets[] = [
                    'desc'    => $this->sanitize($item['desc']),
                    'work_id' => $item['work_id']
                ];
            }

            $edu_bullets = [];
            foreach (($result['education_bullets'] ?? []) as $item) {
                $edu_bullets[] = [
                    'desc'   => $this->sanitize($item['desc']),
                    'edu_id' => $item['edu_id']
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
                    'name'      => $this->sanitize($item['name']),
                    'media_url' => $item['media_url'] // Keep raw for QR generator
                ];
            }

            $this->data = [
                'resume_title' => $this->sanitize($result['resumes']['resume_title'] ?? 'Untitled'),
                'email'        => $email,
                'contact'      => $contact,
                'skills'       => $skills,
                'experience'   => $experience,
                'education'    => $education,
                'exp_bullets'  => $exp_bullets,
                'edu_bullets'  => $edu_bullets,
                'social'       => $social
            ];
        }

        public function loadPostData(array $post): void {
            $experience = [];
            foreach ($post['experience'] ?? [] as $item) {
                if (!is_array($item)) continue;

                $job      = $this->sanitize($item['job']);
                $employer = $this->sanitize($item['employer']);
                $desc     = $this->sanitize($item['desc']);

                if ($job === '' && $employer === '' && $desc === '') continue;

                $experience[] = [
                    'job'        => $job,
                    'employer'   => $employer,
                    'start_date' => $this->sanitize($item['start_date']),
                    'end_date'   => $this->sanitize($item['end_date']),
                    'desc'       => $desc
                ];
            }

            $education = [];
            foreach ($post['education'] ?? [] as $item) {
                if (!is_array($item)) continue;

                $program = $this->sanitize($item['program']);
                $school  = $this->sanitize($item['school']);
                $start   = $this->sanitize($item['start_date']);
                $end     = $this->sanitize($item['end_date']);
                $desc    = $this->sanitize($item['desc']);

                if ($program === '' && $school === '' && $desc === '') continue;

                $education[] = [
                    'program'    => $program,
                    'school'     => $school,
                    'start_date' => $start,
                    'end_date'   => $end,
                    'desc'       => $desc
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

        function Header() {
            if ($this->PageNo() == 1) {
                //////////////////// INITIALS ///////////////////
                $imagePath = '../../assets/images/MyInitials.png';
                $initials = $this->abbrFullname($this->data['fullname'], 'short');

                // Set Trademark
                $this->Image($imagePath, 20, 10, 30); // Adjust positioning and dimensions

                // Set Initials
                $this->SetXY(24, 24); // Set the position for text
                $this->SetFont('Times', 'I', 14);
                $this->SetFontSize(41); // Set the font size to 16
                $this->SetTextColor(255,255,255); // Set font color to red (RGB values)
                $this->Cell(0, 10, $initials, 0, 0, 'L');

                // Set Name
                $this->SetXY(75, 25); // Set the position for text
                $this->SetTextColor(0,0,0); // Set font color to red (RGB values)
                $this->Cell(0, 10, $this->data['fullname'], 0, 1, '');
                $this->Ln(15);
            }
        }
        
        function Footer() {
            if ($this->PageNo() > 1) {
                // Page Number
                $this->SetY(-15);
                $this->SetFont('Arial','I',8);
                $this->Cell(0, 10, 'Page '.$this->PageNo(), 0, 0, 'C');
            }
        }

        public function generatePDF() {
            $this->AliasNbPages();
            $this->AddPage();
            $this->SetFont('Times', '', 12);
            // $this->SetMargins(5, 5, 5); // sets page margins by mm

            //////////////////// TRADEMARK ///////////////////
            //$building = '../../assets/images/icons/buildings-24.png'; 
            //$envelope = '../../assets/images/icons/envelope-24.png';
            //$mobile = '../../assets/images/icons/phone-24.png'; 
            //$world = '../../assets/images/icons/world-24.png';

            // Sanitize data using htmlspecialchars
            // Resume title becomes Filename
            if (isset($this->data['resume'][0]['resume_title'])) {
                $doc = htmlspecialchars($this->data['resume'][0]['resume_title']); 
                $this->SetTitle($doc.' '.$this->printDate('today'));
            } else { 
                // Wizard
                $this->SetTitle('My Scroll - Standard');
            }


            //////////////////// HEADLINE ///////////////////
            $this->SetDrawColor(0, 80, 180); 
            if ($this->data['headline'] !== '') {
                $this->SetFont('Times', '', 11);
                $this->SetX(77);
                $this->MultiCell(0, 4, $this->data['headline'], 0, 'L'); // Increased line height to 4
                
                // Use GetY() to draw the line safely below the headline
                $currentY = $this->GetY() + 2; 
                $this->Line(10, $currentY, 200, $currentY);
                
                // Set the cursor for the next section (Contact)
                $this->SetY($currentY + 10); 
            }


            //////////////////// CONTACT //////////////////
            $this->SetFont('Times', '', 10);
            $this->Cell(63, 5, '//////', 0, 0, 'L'); 
            $this->Cell(63, 5, '3011 MC', 0, 0, 'C'); 
            $this->Cell(64, 5, $this->data['city'], 0, 1, 'R'); 

            // Draw the middle line based on current Y
            $lineY = $this->GetY() + 1;
            $this->Line(10, $lineY, 200, $lineY);

            // Move down relative to the line
            $this->SetY($lineY + 2);

            $this->Cell(63, 5, $this->data['phone'], 0, 0, 'L'); 
            $this->Cell(63, 5, $this->data['email'], 0, 0, 'C'); 
            $this->Cell(64, 5, $this->data['country'], 0, 1, 'R');

            // Add spacing before the Social/Work section starts
            $this->Ln(10);


            //////////////////// LAYOUT CALCULATIONS //////////////////
            // --- 2. Temporary Test Data ---
            // $this->data['social'] = [
            //     ['media_url' => 'https://www.coolblue.nl/?srsltid=AfmBOopkfD1YKbhGqKxnDIxlV504riquRmxvE-hURF2eKwDjoxdqqed3'],
            //     ['media_url' => 'https://www.youtube.com/'],
            //     ['media_url' => 'https://store.ubisoft.com/eu/home?lang=en-SK']
            // ];
            $topOfColumnsY = $this->GetY(); 
            $hasSocials = !empty($this->data['social']);

            // Define widths and positions based on whether socials exist
            if ($hasSocials) {
                $contentX = 60;      // Start further right
                $contentWidth = 140; // Narrower (200 - 60)
            } else {
                $contentX = 10;      // Start at the left margin
                $contentWidth = 190; // Full width (Standard A4 content area)
            }

            ///////////////////////// (TWO COLUMN) ///////////////////////////
            //////////////////// LEFT COLUMN: SOCIAL MEDIA //////////////////
            if ($hasSocials) {
                $currentSocialY = $topOfColumnsY; 
                $maxSocials = 3;
                $counter = 0;

                foreach ($this->data['social'] as $social) {
                    if ($counter >= $maxSocials) break;
                    
                    $mediaPath = $social['media_url'] ?? 'https://google.com';
                    $mediaLabel = $social['name'] ?? 'Media'; 
                    
                    // --- THE FIX: ADD $counter TO THE FILENAME ---
                    $tempFile = __DIR__ . '/../../assets/images/temp/test_qr_' . $counter . '.png';

                    // Verify for HTTPS Protocol
                    if (!preg_match("~^(?:f|ht)tps?://~i", $mediaPath)) {
                        $mediaPath = "https://" . $mediaPath;
                    }
                    
                    // Create the unique physical file
                    QRcode::png($mediaPath, $tempFile, 'M', 4, 2);

                    if (file_exists($tempFile)) {
                        $this->Line(10, $currentSocialY, 50, $currentSocialY);
                        $this->Line(50, $currentSocialY, 50, $currentSocialY + 50); 

                        $this->SetY($currentSocialY + 2);
                        $this->SetX(10);
                        $this->SetFont('Times', 'B', 11);
                        $this->Cell(40, 5, $mediaLabel, 0, 1, 'C');

                        // Point FPDF to the unique file
                        $this->Image($tempFile, 12.5, $this->GetY() + 2, 35);

                        // Delete it right now on the pyre!
                        if (file_exists($tempFile)) {
                            unlink($tempFile);
                        }
                        
                        $currentSocialY += 58; 
                        $counter++;
                    }
                }
            }


            //////////////////// RIGHT COLUMN: WORK EXPERIENCE //////////////////
            // This now uses $contentX and $contentWidth variables
            $this->SetY($topOfColumnsY); 
            $this->SetX($contentX); 

            // --- 3. Experience Data Loop ---
            if (!empty($this->data['experience'])) {
                // --- 1. Aesthetic Lines (Adjusted to new width) ---
                // ONLY draw the vertical "L-bracket" line if we are in two-column mode
                if ($hasSocials) {
                    // The vertical line at the far right margin (200mm)
                    $this->Line(200, $topOfColumnsY, 200, $topOfColumnsY + 50);
                }

                // --- 2. Section Title ---
                $this->alignSubtitle('Experience', $contentX, $topOfColumnsY);
                foreach ($this->data['experience'] as $exp) {
                    $this->SetX($contentX); // Ensure each line respects the current X
                    
                    // JOB TITLE
                    $this->SetFont('Times', 'B', 11);
                    $this->Cell(0, 6, $exp['job'], 0, 1, 'L');

                    // EMPLOYER & DATES
                    $this->SetX($contentX);
                    $this->SetFont('Times', 'I', 10);
                    $dateRange = $exp['start_date'] . ' - ' . ($exp['end_date'] ?: 'Present');
                    $this->Cell(0, 5, $exp['employer'] . ' | ' . $dateRange, 0, 1, 'L');

                    // SUMMARY (Uses $contentWidth - 5 for a small margin)
                    if (!empty($exp['summary'])) {
                        $this->SetX($contentX);
                        $this->SetFont('Times', '', 9);
                        $this->SetTextColor(50, 50, 50);
                        
                        // The 135 here becomes dynamic:
                        $currentWidth = $contentWidth - 5; 
                        $this->MultiCell($currentWidth, 4, $exp['summary'], 0, 'L');
                        
                        $this->SetTextColor(0, 0, 0);
                    }
                    $this->Ln(5);
                }
                $this->Ln(1);
            }
            

            //////////////////// EDUCATION SECTION //////////////////
            $this->SetX($contentX);

            if (!empty($this->data['education'])) {
                // 1. Capture the Y position before the Title/Line
                $eduLineY = $this->GetY(); 

                // ONLY draw the vertical "L-bracket" line if we are in two-column mode
                if (!empty($this->data['social'])) {
                    // Use $this->GetY() as the end point (maybe -2 or -3 to clean up the tail)
                    $this->Line(200, $eduLineY, 200, $this->GetY() + 50);
                }

                // 2. Section Title & Horizontal Line
                $this->alignSubtitle('Education', $contentX, $eduLineY);
                foreach ($this->data['education'] as $edu) {
                    $this->SetX($contentX);
                    
                    // DEGREE / COURSE
                    $this->SetFont('Times', 'B', 11);
                    $this->Cell(0, 6, $edu['program'], 0, 1, 'L');

                    // SCHOOL & DATE
                    $this->SetX($contentX);
                    $this->SetFont('Times', 'I', 10);
                    $this->Cell(0, 5, $edu['school'] . ' | ' . $edu['start_date'], 0, 1, 'L');           
                    $this->Ln(3);
                }
                $this->Ln(5);
            }


            //////////////////// SKILLS SECTION //////////////////
            $this->SetX($contentX);
            $skillY = $this->GetY();

            $this->alignSubtitle('Education', $contentX, $skillY);
            if (!empty($this->data['skills'])) {
                $colWidth = (200 - $contentX) / 2; // Divide available space by 2
                $count = 0;

                foreach ($this->data['skills'] as $skill) {
                    $this->SetX($contentX + ($count % 2 * $colWidth));            
                    $this->SetFont('Times', 'B', 10);
                    $this->Cell($colWidth, 5, chr(149) . ' ' . $skill['name'], 0, ($count % 2 == 1 ? 1 : 0), 'L');
                    $count++; // chr(149) is a bulletpoint!
                }
                
                // Add a newline if we ended on an odd number
                if ($count % 2 != 0) $this->Ln(5);

                // Draw the Adaptive Vertical Line (Same logic as Education)
                if (!empty($this->data['social'])) {
                    $this->Line(200, $skillY, 200, $this->GetY());
                }
            }

            // Add a line break
            $this->Ln(8);

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
        
    } elseif (isset($_POST['action']) && $_POST['action'] === 'business') {
        $resumePDF = new ResumePDF();
        $resumePDF->loadPostData($_POST);
        $resumePDF->generatePDF();

    } else {
        echo '<pre>';
        print_r($_POST);
        echo '</pre>';
        
        // No goal. Try again later.
        // header('location: ../../error.php');          
        // exit;
    }