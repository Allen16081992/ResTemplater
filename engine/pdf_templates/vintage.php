<?php declare(strict_types=1);
    // Load files
    require_once __DIR__ . '/abstract_template.php';

    class VintageTemplate extends BaseTemplate {
        function Header() {
            if ($this->PageNo() == 1) {
                $this->SetFont('Courier', 'B', 16);
                $this->Cell(0, 10, 'Curriculum Vitae', 0, 0, 'C');          
                $this->Ln(10);
            }
        }

        function Footer() {
            if ($this->PageNo() > 1) {
                $this->SetY(-15);
                $this->SetFont('Courier','I',8);
                $this->Cell(0, 10, 'Page '.$this->PageNo(), 0, 0, 'C');
            }
        }

        public function generatePDF() {
            // error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
            // ob_start();
            // TEMPORARY DEBUG: Dump the cauldron to check array keys
            //echo '<pre>'; print_r($this->data); echo '</pre>'; die();

            $this->AliasNbPages();
            $this->AddPage();
            $this->SetFont('Courier', '', 12);

            // 1. Correct Variable Assignments
            $resumeTitle = $this->data['master']['title'] ?? 'My Scroll';
            $fullname    = $this->data['contact']['fullname'] ?? 'Stranger';
            $headline    = $this->data['master']['headline'] ?? '';
            $today = $this->printDate('today');
            $this->SetTitle($this->abbrFullname($fullname) . " " . $today);


            //////////////////// HEADLINE ///////////////////
            if ($headline !== '') {
                $this->SetFont('Courier', '', 8);
                $this->SetX(77);
                $this->MultiCell(0, 2, $headline, 0, 'L');
            }
            $this->Ln(4);
            

            //////////////////// PERSONAL //////////////////
            $this->SetFont('Courier', 'BU', 11);
            $this->Cell(0, 10, 'Personal', 0, 1, 'L');
            $this->SetFont('Courier', '', 10);

            // 2. Personal Section (Two-Column Typewriter Style)
            $labelX = 10;
            $valueX = 76;
            $rowH   = 4;
            $y = $this->GetY();
            $rows = [
                'Name'    => $fullname ?? '',
                'Initial' => $this->abbrFullname($fullname ?? '', 'dotted'), // The 1990s touch!
                'City'    => $this->data['contact']['city'] ?? '',
                'Country' => $this->data['contact']['country'] ?? '',
                'Phone'   => $this->data['contact']['phone'] ?? '',
                'Email'   => $this->data['contact']['email'] ?? '', 
            ];

            foreach ($rows as $label => $value) {
                $this->SetXY($labelX, $y);
                $this->Cell(40, $rowH, $label, 0, 0, 'L');
                $this->SetXY($valueX, $y);
                $this->Cell(0, $rowH, ': ' . $value, 0, 1, 'L');
                $y += $rowH;
            }
            $this->Ln($rowH);


            //////////////////// RIGHT COLUMN: ADAPTIVE STACK //////////////////            
            // 1. Determine the Order (Your Teleportation Logic)
            $order = empty($this->data['experience']) ? ['projects', 'education'] : ['experience', 'education', 'projects'];

            // 2. The Vintage Master Loop
            foreach ($order as $section) {
                if (empty($this->data[$section])) continue;

                // --- SECTION HEADER (Underlined Courier) ---
                $this->SetFont('Courier', 'BU', 11);
                $this->Cell(0, 10, ucfirst($section), 0, 1, 'L');

                foreach (array_slice($this->data[$section], 0, 6) as $item) {
                    $this->SetFont('Courier', '', 10);
                    // SetX(76); was here originally

                    // --- LEFT COLUMN (Dates or Project Title) ---
                    if ($section === 'projects') {
                        $leftText = $item['title'] ?? 'Project';
                    } else {
                        $leftText = trim(($item['start_date'] ?? '') . ' - ' . ($item['end_date'] ?? 'Present'), ' -');
                    }
                    $this->SetX(10); //test v2
                    $this->Cell(66, 6, $leftText, 0, 0, 'L');

                    // --- THE SPINE (The Colon at X=76) ---
                    $this->SetX(76);
                    
                    // Dynamic Value based on Section
                    if ($section === 'experience') {
                        $mainVal = ($item['title'] ?? '') . ' (' . ($item['employer'] ?? '') . ')';
                    } elseif ($section === 'education') {
                        $mainVal = ($item['program'] ?? '') . ' (' . ($item['school'] ?? '') . ')';
                    } else { // Projects
                        $mainVal = !empty($item['role']) ? $item['role'] : 'View Project';
                    }

                    $this->Cell(0, 6, ': ' . $mainVal, 0, 1, 'L');

                    // --- THE CONTENT (Summary or Bullets) ---
                    $this->SetFont('Courier', '', 8);
                    
                    if (!empty($item['summary'])) {
                        $this->SetX(78);
                        $this->MultiCell(0, 4, $item['summary'], 0, 'L');
                    } elseif (!empty($item['bullets'])) {
                        // Find bullets by id
                        $bulletKey = ($section === 'experience') ? 'exp_bullets' : (($section === 'education') ? 'edu_bullets' : 'pro_bullets');
                        $foreignKey = ($section === 'experience') ? 'experience_id' : (($section === 'education') ? 'education_id' : 'projects_id');
                        foreach (($this->data[$bulletKey] ?? []) as $bullet) {
                            if ($bullet[$foreignKey] == $item['id']) {
                                $this->SetX(78);
                                $this->Cell(4, 4, chr(149), 0, 0);
                                $this->MultiCell(0, 4, $bullet['summary'], 0, 'L');
                            }
                        }
                    }
                    $this->Ln(2); // Vintage spacing between entries
                }
            }


            /////////////////////// SKILLS ////////////////////////
            $skills = $this->data['skills'] ?? [];
            if (!empty($skills)) {
                $this->SetFont('Courier', 'BU', 11);
                $this->Cell(0, 10, 'Skills', 0, 1, 'L');
                $this->SetFont('Courier', '', 10);

                foreach (array_slice($skills, 0, 10) as $item) {
                    $name     = $item['name'] ?? '';
                    $category = explode(' ', $item['category'] ?? 'General')[0];

                    $this->SetX(10);
                    // chr(149) is the bullet point
                    $this->Cell(5, 6, chr(149), 0, 0);
                    $this->Cell(0, 6, '[' . strtoupper($category) . '] ' . $name, 0, 1, 'L');
                }
            }


            ///////////////////////// (TWO COLUMN) ///////////////////////////
            //////////////////// QR CODES (BOTTOM CENTERED) //////////////////
            if (class_exists('QRcode') && !empty($this->data['socials'])) {
                $maxSocials = 4;
                $socials = array_slice($this->data['socials'], 0, $maxSocials);
                $count = count($socials);

                if ($count > 0) {
                    // 1. Calculate Positioning
                    $qrSize = 25;           // Size of the square
                    $spacing = 30;          // Gap between QRs
                    $totalWidth = ($count * $qrSize) + (($count - 1) * $spacing);
                    $startX = (210 - $totalWidth) / 2; // Center on A4 (210mm)
                    
                    // 2. Go to the Bottom (approx 45mm from bottom edge)
                    $this->SetY(-45); 

                    // 3. Draw the Divider Line
                    $this->SetDrawColor(0, 0, 0);
                    $this->Line($startX, $this->GetY(), $startX + $totalWidth, $this->GetY());
                    $this->Ln(2);

                    // 4. Loop and Render
                    $currentX = $startX;
                    foreach ($socials as $index => $social) {
                        $mediaPath = $social['media_url'] ?? 'https://google.com';
                        $mediaLabel = $social['name'] ?? 'Media';
                        $mediaLabel = (strlen($mediaLabel) > 16) ? substr($mediaLabel, 0, 12) . '...' : $mediaLabel;
                        
                        // Clean URL
                        if (!preg_match("~^(?:f|ht)tps?://~i", $mediaPath)) {
                            $mediaPath = "https://" . $mediaPath;
                        }

                        // Generate Temp File
                        $tempFile = __DIR__ . '/../../assets/images/temp/vntg_qr_' . $index . '.png';
                        QRcode::png($mediaPath, $tempFile, 'M', 4, 2);

                        if (file_exists($tempFile)) {
                            // Label (Courier 7pt for that "Property Of" typewriter look)
                            $this->SetXY($currentX, $this->GetY());
                            $this->SetFont('Courier', 'I', 7);
                            $this->Cell($qrSize, 4, $mediaLabel, 0, 0, 'C');

                            // The QR Image (Placed below the label)
                            $this->Image($tempFile, $currentX, $this->GetY() + 4, $qrSize);
                            unlink($tempFile); // Clean up   
                        }
                        
                        // Move X for the next one
                        $currentX += $qrSize + $spacing;
                    }
                }
            }

            // Add a line break
            $this->Ln(8);
            
            $this->Output();
        }
    }

    // RIGHT UNDER generatePDF()
    // error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
    // ob_start();
    // // --- SHOVE TEST DATA HERE ---
    // $this->data['social'] = [
    //     ['name' => 'Coolblue', 'media_url' => 'https://www.coolblue.nl/'],
    //     ['name' => 'YouTube',  'media_url' => 'https://www.youtube.com/'],
    //     ['name' => 'UbiSoft',  'media_url' => 'https://store.ubisoft.com/']
    // ];

    // $this->data['projects'] = [
    //     [
    //         'title'   => 'ResTemplater Engine',
    //         'role'    => 'Backend Architect',
    //         'summary' => 'A PHP-based engine designed to automate resume generation.'
    //     ],
    //     [
    //         'title'   => 'Cloud Dashboard',
    //         'role'    => 'Frontend Developer',
    //         'summary' => 'A React dashboard for monitoring real-time server metrics.'
    //     ]
    // ];

    // --- 2. Temporary Test Data ---
    // $this->data['social'] = [
    //     [
    //         'name'      => 'Coolblue',
    //         'media_url' => 'https://www.coolblue.nl/?srsltid=AfmBOopkfD1YKbhGqKxnDIxlV504riquRmxvE-hURF2eKwDjoxdqqed3'
    //     ],
    //     [
    //         'name'      => 'YouTube',
    //         'media_url' => 'https://www.youtube.com/'
    //     ],
    //     [
    //         'name'      => 'UbiSoft',
    //         'media_url' => 'https://store.ubisoft.com/eu/home?lang=en-SK'
    //     ]
    // ];
    // $this->data['projects'] = [
    //     [
    //         'title'   => 'ResTemplater Engine',
    //         'role'    => 'Backend Architect',
    //         'summary' => 'A PHP-based engine designed to automate resume generation.'
    //     ],
    //     [
    //         'title'   => 'Cloud Dashboard',
    //         'role'    => 'Frontend Developer',
    //         'summary' => 'A React dashboard for monitoring real-time server metrics.'
    //     ]
    // ];