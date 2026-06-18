<?php declare(strict_types=1);
    // Load files
    require_once __DIR__ . '/abstract_template.php';

    class ContraTemplate extends BaseTemplate {
        // Define our layout profiles cleanly
        const LAYOUT_CONTRA_STANDARD = 2; // Split Header
        const LAYOUT_CONTRA_TIMELINE = 3; // Vertical Spine Axis
        const LAYOUT_CONTRA_EASTER   = 4; // Asymmetric Inverted Grid

        private $activeLayout = self::LAYOUT_CONTRA_STANDARD; // Default system state
        private $lastHistoryY = null; // Used to track vertical timeline continuation

        private function setLayout() {
            $resumeTitle = $this->data['master']['title'] ?? 'My Scroll';
            $normalized = strtoupper(trim($resumeTitle));
            
            if (strpos($normalized, 'INVERT') !== false) {
                $this->activeLayout = self::LAYOUT_CONTRA_EASTER;
            } elseif (strpos($normalized, 'STACK') !== false) {
                $this->activeLayout = self::LAYOUT_CONTRA_TIMELINE;
            } else {
                $this->activeLayout = self::LAYOUT_CONTRA_STANDARD;
            }
        }

        private function renderSectionTitle($title) {
            $this->Ln(4);
            $this->SetFont('Helvetica', 'B', 11);
            $this->SetTextColor(26, 26, 26);
            $this->Cell(0, 6, strtoupper($this->sanitize($title)), 0, 1, 'L');
            $this->Ln(2);
        }

        private function drawQRCode($url, $x, $y, $size, $suffix) {
            if (class_exists('QRcode')) {
                $tempFile = __DIR__ . "/../../assets/images/temp/qr_{$suffix}.png";
                QRcode::png($url, $tempFile, 'M', 4, 1);
                if (file_exists($tempFile)) {
                    $this->Image($tempFile, $x, $y, $size);
                    unlink($tempFile);
                }
            }
        }

        private function drawContraShard() {
            // --- ACCENT 1: Triangle ---
            $this->SetDrawColor(21, 42, 74); // Dark Blue   
            $x_start = 30;  // Move from left
            $y_start = 0;   // Start at top of page edge
            $width = 30;    // Width
            $height = 22;   // Height
            $this->SetLineWidth(0.2); 
            
            for ($i = 0; $i <= $width; $i += 0.1) {
                $this->Line($x_start + $i, $y_start, $x_start + $width, $y_start + $height);
            }

            // --- ACCENT 2: Rectangle ---
            $this->SetFillColor(56, 189, 248); // Sky Blue      
            $rect_x = $x_start + $width; // Attached to triangle
            $rect_y = 0; // Start at top of page edge
            $rect_width = 3; // Width
            $rect_height = 17; // Height
            $this->Rect($rect_x, $rect_y, $rect_width, $rect_height, 'F'); // 'F' stands for fill
        }

        function renderHistoryEntry($title, $company, $dateRange, $location, $description, $qrUrl = "https://github.com") {
            $startX = $this->GetX();
            $blockStartY = $this->GetY();
            $qrSize = 20;
            $textOffset = 25;
            $contentX = $startX + $textOffset;

            switch ($this->activeLayout) {
                
                // ========================================================
                // LAYOUT 2: STANDARD SPLIT HEADER
                // ========================================================
                case self::LAYOUT_CONTRA_STANDARD:
                    $this->drawQRCode($qrUrl, $startX, $blockStartY, $qrSize, 'layout2');

                    // Job Title (Left Axis)
                    $this->SetXY($contentX, $blockStartY);
                    $this->SetFont('Helvetica', 'B', 11);
                    $this->SetTextColor(26, 26, 26);
                    $this->Cell(80, 6, strtoupper($title), 0, 0, 'L'); 

                    // Dates (Right Axis Snap)
                    $this->SetFont('Helvetica', '', 9); 
                    $this->SetTextColor(120, 120, 120);
                    $this->Cell(0, 6, strtoupper($dateRange), 0, 1, 'R'); 

                    // Company Name & Location
                    $this->SetX($contentX);
                    $this->SetFont('Helvetica', '', 10);
                    $this->Cell(0, 5, $company . " — " . $location, 0, 1, 'L');

                    // Wrap Body Paragraph Text
                    $this->SetX($contentX);
                    $this->SetFont('Helvetica', '', 9);
                    $this->SetTextColor(40, 40, 40);
                    $this->MultiCell(0, 4, $description, 0, 'L');

                    $this->SetY(max($this->GetY(), $blockStartY + $qrSize) + 8);
                    $this->SetX($startX);
                    break;

                // ========================================================
                // LAYOUT 3: TIMELINE SPINE AXIS
                // ========================================================
                case self::LAYOUT_CONTRA_TIMELINE:
                    // If a previous timeline entry left a mark, draw the continuous spine connector
                    if ($this->lastHistoryY !== null) {
                        $this->SetDrawColor(220, 220, 220); 
                        $this->SetLineWidth(0.3);
                        $lineX = $startX + ($qrSize / 2);
                        $this->Line($lineX, $this->lastHistoryY, $lineX, $blockStartY);
                    }

                    $this->drawQRCode($qrUrl, $startX, $blockStartY, $qrSize, 'layout3');

                    $this->SetXY($contentX, $blockStartY);
                    $this->SetFont('Helvetica', 'B', 12);
                    $this->SetTextColor(26, 26, 26);
                    $this->Cell(0, 5, $title, 0, 1, 'L');

                    $this->SetX($contentX);
                    $this->SetFont('Helvetica', '', 9);
                    $this->SetTextColor(100, 100, 100);
                    $this->Cell(0, 4, $company . "  |  " . $dateRange . " (" . $location . ")", 0, 1, 'L');
                    $this->Ln(1);

                    $this->SetX($contentX);
                    $this->SetFont('Helvetica', '', 9.5);
                    $this->SetTextColor(30, 30, 30);
                    $this->MultiCell(0, 4.5, $description, 0, 'L');

                    $blockEndY = $this->GetY();
                    
                    // Draw this entry's timeline segment marker
                    $this->SetDrawColor(220, 220, 220); 
                    $this->SetLineWidth(0.3);
                    $lineX = $startX + ($qrSize / 2); 
                    $this->Line($lineX, $blockStartY + $qrSize + 2, $lineX, $blockEndY - 2);

                    // Record this position flag so the next block loop can stitch into it
                    $this->lastHistoryY = $blockEndY;

                    $this->SetY(max($blockEndY, $blockStartY + $qrSize) + 10);
                    $this->SetX($startX);
                    break;

                // ========================================================
                // LAYOUT 4: THE INVERTED EASTER EGG GRID
                // ========================================================
                case self::LAYOUT_CONTRA_EASTER:
                    $col1_X = $startX;          // Dates
                    $col2_X = $startX + 35;     // QR Code Anchor
                    $col3_X = $startX + 59;     // Title / Details Content

                    // Col 1: Date Stamps
                    $this->SetXY($col1_X, $blockStartY + 1);
                    $this->SetFont('Helvetica', 'B', 9);
                    $this->SetTextColor(168, 85, 247); // Purple accent hit for the secret layout
                    $this->Cell(32, 4, strtoupper($dateRange), 0, 1, 'L');

                    $this->SetX($col1_X); 
                    $this->SetFont('Helvetica', '', 8.5);
                    $this->SetTextColor(100, 100, 100);
                    $this->Cell(32, 4, $company, 0, 0, 'L');

                    // Col 2: The QR Element
                    $this->drawQRCode($qrUrl, $col2_X, $blockStartY, $qrSize, 'layout4');

                    // Col 3: Dense Technical Descriptions
                    $this->SetXY($col3_X, $blockStartY);
                    $this->SetFont('Helvetica', 'B', 11.5);
                    $this->SetTextColor(0, 0, 0);
                    $this->Cell(0, 5, $title, 0, 1, 'L');
                    $this->Ln(1);

                    $this->SetX($col3_X); 
                    $this->SetFont('Helvetica', '', 9);
                    $this->MultiCell(0, 4, $description, 0, 'L');

                    $this->SetY(max($this->GetY(), $blockStartY + $qrSize) + 8);
                    $this->SetX($startX);
                    break;
            }
        }

        public function compileCoreHistory() {
            // REQUIREMENT #2: Determine Section Order based on Experience availability
            if (empty($this->data['experience'])) {
                // Experience is dropped entirely; Projects teleports to pole position
                $order = ['projects', 'education'];
            } elseif (empty($this->data['education'])) {
                // Education is dropped entirely; Projects teleports to pole position
                $order = ['projects', 'experience'];
            } else {
                // Standard traditional stack sequence
                $order = ['experience', 'education', 'projects'];
            }

            // Iterate through our dynamically assigned structural sequence
            foreach ($order as $section) {
                switch ($section) {
                    
                    // ========================================================
                    // EXPERIENCE BLOCK LOOP
                    // ========================================================
                    case 'experience':
                        if (empty($this->data['experience'])) break;

                        $this->renderSectionTitle("Experience");
                        
                        // REQUIREMENT #3: Restrict display limit to exactly 5 entries max
                        $experienceSet = array_slice($this->data['experience'], 0, 5);
                        
                        foreach ($experienceSet as $job) {
                            // Extract fields safely with fallback coalescing flags
                            $title       = $job['title'] ?? 'Untitled Role';
                            $company     = $job['company'] ?? 'Unknown Company';
                            $dateRange   = $job['date_range'] ?? '';
                            $location    = $job['location'] ?? '';
                            $qrUrl       = $job['project_url'] ?? 'https://github.com';

                            // REQUIREMENT #4: Handle summary paragraph vs bullet points fallback
                            if (!empty($job['summary'])) {
                                // Pass straight string payload down to the active visual grid coordinate loop
                                $this->renderHistoryEntry($title, $company, $dateRange, $location, $job['summary'], $qrUrl);
                            } elseif (!empty($job['bullets']) && is_array($job['bullets'])) {
                                // Render layout line metadata entry first with empty string description
                                $this->renderHistoryEntry($title, $company, $dateRange, $location, '', $qrUrl);
                                // Follow up by looping your clean canvas indent bullet points directly underneath
                                foreach ($job['bullets'] as $bullet) {
                                    $this->renderBulletPoint($bullet);
                                }
                                $this->Ln(4); // Drop standard baseline padding spacer
                            }
                        }
                        break;

                    // ========================================================
                    // EDUCATION BLOCK LOOP
                    // ========================================================
                    case 'education':
                        if (empty($this->data['education'])) break;

                        $this->renderSectionTitle("Education");
                        
                        // REQUIREMENT #3: Restrict display limit to exactly 5 entries max
                        $educationSet = array_slice($this->data['education'], 0, 5);
                        
                        foreach ($educationSet as $edu) {
                            $degree      = $edu['degree'] ?? 'Degree / Certification';
                            $institution = $edu['institution'] ?? 'Institution';
                            $dateRange   = $edu['date_range'] ?? '';
                            $location    = $edu['location'] ?? '';
                            $qrUrl       = $edu['verification_url'] ?? 'https://github.com';

                            // REQUIREMENT #1: Education strictly utilizes identical active layout metrics
                            if (!empty($edu['summary'])) {
                                $this->renderHistoryEntry($degree, $institution, $dateRange, $location, $edu['summary'], $qrUrl);
                            } elseif (!empty($edu['bullets']) && is_array($edu['bullets'])) {
                                $this->renderHistoryEntry($degree, $institution, $dateRange, $location, '', $qrUrl);
                                foreach ($edu['bullets'] as $bullet) {
                                    $this->renderBulletPoint($bullet);
                                }
                                $this->Ln(4);
                            } else {
                                // If completely empty of summaries or bullets, print basic structural layout node
                                $this->renderHistoryEntry($degree, $institution, $dateRange, $location, '', $qrUrl);
                            }
                        }
                        break;

                    // ========================================================
                    // PROJECTS BLOCK LOOP (Note: Projects contain no dates)
                    // ========================================================
                    case 'projects':
                        if (empty($this->data['projects'])) break;

                        $this->renderSectionTitle("Projects & Artifacts");
                        
                        foreach ($this->data['projects'] as $project) {
                            $projName    = $project['name'] ?? 'Untitled Project';
                            $techStack   = $project['tech_stack'] ?? ''; // e.g. "PHP / Vanilla JS / Bulma"
                            $location    = $project['role_context'] ?? 'Independent Dev'; 
                            $datePlaceholder = ''; // Locked to empty string to honor your custom note
                            $qrUrl       = $project['repo_url'] ?? 'https://github.com';

                            if (!empty($project['summary'])) {
                                $this->renderHistoryEntry($projName, $techStack, $datePlaceholder, $location, $project['summary'], $qrUrl);
                            } elseif (!empty($project['bullets']) && is_array($project['bullets'])) {
                                $this->renderHistoryEntry($projName, $techStack, $datePlaceholder, $location, '', $qrUrl);
                                foreach ($project['bullets'] as $bullet) {
                                    $this->renderBulletPoint($bullet);
                                }
                                $this->Ln(4);
                            }
                        }
                        break;
                }
            }
        }

        function Header() {
            if ($this->PageNo() == 1) {
                $this->drawContraShard(); // Visual stamp

                // Text parameters
                ////////////////////   NAME   ///////////////////
                $this->SetXY(70, 8);                  // Set position
                $this->SetFont('Helvetica', 'I', 14); // Set font
                $this->SetFontSize(41);               // Set size
                $this->Cell(0, 10, $this->data['contact']['fullname'], 0, 1, 'L');

                // Move the cursor to the end of the Header block with a safety padding buffer
                $this->SetY($this->GetY() + 15); 
            }
        }

        function Footer() {
            if ($this->PageNo() > 1) {
                $this->SetY(-15);
                $this->SetFont('Helvetica','I',8);
                $this->Cell(0, 10, 'Page '.$this->PageNo(), 0, 0, 'C');
            }
        }

        public function generatePDF() {
            $this->AliasNbPages();
            
            // Run our layout state detector before drawing anything
            $this->setLayout();

            // Calculate and apply balanced margins safely
            $pageWidth = 210;
            $contentWidth = 150; 
            $margin = ($pageWidth - $contentWidth) / 2;

            $this->SetLeftMargin($margin);
            $this->SetRightMargin($margin);
            
            // Add canvas page context
            $this->AddPage();
            
            // Explicitly declare your layout origin coordinate marker!
            $startX = $margin; 
            $this->SetX($startX); 

            $this->SetFont('Helvetica', '', 10);

            error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
            ob_start();

            // #1: Determine Rendering Order based on dataset availability
            if (empty($this->data['experience'])) {
                $order = ['projects', 'education'];
            } elseif (empty($this->data['education'])) {
                $order = ['projects', 'experience'];
            } else {
                $order = ['experience', 'education', 'projects'];
            }

// The Integrated Master Template Loop
        foreach ($order as $section) {
            if (empty($this->data[$section])) continue;

            // Render Section Header Block
            $headerTitle = ($section === 'projects') ? 'Projects & Artifacts' : ucfirst($section);
            $this->renderSectionTitle($headerTitle);

            // Restrict display limit to exactly 5 entries max for History Blocks
            $loopData = $this->data[$section];
            if ($section === 'experience' || $section === 'education') {
                $loopData = array_slice($loopData, 0, 5);
            }

            foreach ($loopData as $item) {
                $blockStartY = $this->GetY();
                
                // --- ASSIGN DYNAMIC COORDINATE VALUES BY ACTIVE LAYOUT TYPE ---
                $qrSize = 20;
                $textOffset = 25; 
                $contentX = $startX + $textOffset;

                // Adjust layout configurations dynamically if Easter Egg grid is triggered
                if ($this->activeLayout == self::LAYOUT_CONTRA_EASTER) {
                    $col1_X = $startX;          // Dates Column
                    $col2_X = $startX + 35;     // QR Code Anchor
                    $col3_X = $startX + 59;     // Main Content Column
                    $contentX = $col3_X;
                }

                // --- GENERATE AND RENDER QR DATA NODE ---
                $qrUrl = $item['project_url'] ?? $item['verification_url'] ?? $item['repo_url'] ?? 'https://github.com';
                $itemId = $item['id'] ?? uniqid();
                
                if ($this->activeLayout == self::LAYOUT_CONTRA_EASTER) {
                    $this->drawQRCode($qrUrl, $col2_X, $blockStartY, $qrSize, 'easter_' . $itemId);
                } else {
                    $this->drawQRCode($qrUrl, $startX, $blockStartY, $qrSize, $section . '_' . $itemId);
                }

                // --- LAYOUT RENDERING LINE: TEXT ROUTING ENGINE ---
                switch ($this->activeLayout) {

                    case self::LAYOUT_CONTRA_STANDARD: // --- LAYOUT 2: SPLIT HEADER ---
                        $this->SetXY($contentX, $blockStartY);
                        $this->SetFont('Helvetica', 'B', 11);
                        $this->SetTextColor(26, 26, 26);
                        
                        $titleText = $item['title'] ?? $item['program'] ?? $item['name'] ?? '';
                        $this->Cell(80, 6, strtoupper($this->sanitize($titleText)), 0, 0, 'L'); 

                        // Handle dates on the right axis snap
                        $this->SetFont('Helvetica', '', 9); 
                        $this->SetTextColor(120, 120, 120);
                        
                        if ($section === 'projects') {
                            $dateText = ''; 
                        } else {
                            $dateText = isset($item['start_date']) ? ($item['start_date'] . ' - ' . ($item['end_date'] ?: 'Present')) : '';
                        }
                        $this->Cell(0, 6, strtoupper($dateText), 0, 1, 'R'); 

                        // Corporate Label / Sub-info Row
                        $this->SetX($contentX);
                        $this->SetFont('Helvetica', '', 10);
                        $orgText = $item['employer'] ?? $item['school'] ?? $item['tech_stack'] ?? '';
                        $this->Cell(0, 5, $this->sanitize($orgText), 0, 1, 'L');
                        break;

                    case self::LAYOUT_CONTRA_TIMELINE: // --- LAYOUT 3: TIMELINE SPINE ---
                        if ($this->lastHistoryY !== null) {
                            $this->SetDrawColor(220, 220, 220); 
                            $this->SetLineWidth(0.3);
                            $lineX = $startX + ($qrSize / 2);
                            $this->Line($lineX, $this->lastHistoryY, $lineX, $blockStartY);
                        }

                        $this->SetXY($contentX, $blockStartY);
                        $this->SetFont('Helvetica', 'B', 12);
                        $this->SetTextColor(26, 26, 26);
                        $titleText = $item['title'] ?? $item['program'] ?? $item['name'] ?? '';
                        $this->Cell(0, 5, $this->sanitize($titleText), 0, 1, 'L');

                        $this->SetX($contentX);
                        $this->SetFont('Helvetica', '', 9);
                        $this->SetTextColor(100, 100, 100);
                        
                        $orgText = $item['employer'] ?? $item['school'] ?? $item['tech_stack'] ?? '';
                        $dateText = ($section === 'projects') ? '' : (isset($item['start_date']) ? '  |  ' . $item['start_date'] . ' - ' . ($item['end_date'] ?: 'Present') : '');
                        $this->Cell(0, 4, $this->sanitize($orgText) . $dateText, 0, 1, 'L');
                        $this->Ln(1);
                        break;

                    case self::LAYOUT_CONTRA_EASTER: // --- LAYOUT 4: INVERTED GRID ---
                        $this->SetXY($col1_X, $blockStartY + 1);
                        $this->SetFont('Helvetica', 'B', 9);
                        $this->SetTextColor(168, 85, 247); // Core purple trigger accent
                        
                        $dateText = ($section === 'projects') ? 'ARTIFACT' : (isset($item['start_date']) ? $item['start_date'] . ' - ' . ($item['end_date'] ?: 'PRES') : 'INFO');
                        $this->Cell(32, 4, strtoupper($dateText), 0, 1, 'L');

                        $this->SetX($col1_X); 
                        $this->SetFont('Helvetica', '', 8.5);
                        $this->SetTextColor(100, 100, 100);
                        $orgText = $item['employer'] ?? $item['school'] ?? $item['tech_stack'] ?? '';
                        $this->Cell(32, 4, $this->sanitize(substr($orgText, 0, 18)), 0, 0, 'L');

                        // Column 3: Content Target Area
                        $this->SetXY($col3_X, $blockStartY);
                        $this->SetFont('Helvetica', 'B', 11.5);
                        $this->SetTextColor(0, 0, 0);
                        $titleText = $item['title'] ?? $item['program'] ?? $item['name'] ?? '';
                        $this->Cell(0, 5, $this->sanitize($titleText), 0, 1, 'L');
                        $this->Ln(1);
                        break;
                }

                // --- CONTENT OUTPUT LAYER: SUMMARY VS RELATIONAL BULLETS ---
                $this->SetTextColor(40, 40, 40);
                
                // If standard summary string property exists, prioritize rendering it natively
                if (!empty($item['summary'])) {
                    $this->SetX($contentX);
                    $this->SetFont('Helvetica', '', 9.5);
                    $this->MultiCell($contentWidth - $textOffset, 4.2, $this->sanitize($item['summary']), 0, 'L');
                } else {
                    // Relational schema lookup strategy maps key strings safely
                    $bulletKey = ($section === 'experience') ? 'exp_bullets' : (($section === 'education') ? 'edu_bullets' : 'pro_bullets');
                    $foreignKey = ($section === 'experience') ? 'experience_id' : (($section === 'education') ? 'education_id' : 'projects_id');
                    
                    if (!empty($this->data[$bulletKey])) {
                        foreach ($this->data[$bulletKey] as $bullet) {
                            if (isset($bullet[$foreignKey]) && $bullet[$foreignKey] == $itemId && !empty($bullet['summary'])) {
                                $this->SetX($contentX + 3); // Indent spacer track
                                $this->SetFont('Helvetica', '', 9);
                                
                                $this->Cell(4, 4, chr(149), 0, 0); // Draws mid-dot bullet marker
                                $this->MultiCell($contentWidth - ($textOffset + 7), 4, $this->sanitize($bullet['summary']), 0, 'L');
                            }
                        }
                    }
                }

                // Calculate final block end coordinates safely
                $blockEndY = $this->GetY();

                // Complete Timeline Axis segment drawing logic if layout 3 is running
                if ($this->activeLayout == self::LAYOUT_CONTRA_TIMELINE) {
                    $this->SetDrawColor(220, 220, 220); 
                    $this->SetLineWidth(0.3);
                    $lineX = $startX + ($qrSize / 2); 
                    $this->Line($lineX, $blockStartY + $qrSize + 2, $lineX, $blockEndY - 2);
                    $this->lastHistoryY = $blockEndY; // Store trace indicator
                }

                // Cleanly reset bounding cursor limits below the tallest element printed
                $this->SetY(max($blockEndY, $blockStartY + $qrSize) + 6);
                $this->SetX($startX);
            }
            $this->Ln(4); // Structural gap dividing major block objects
        }

        $this->Output();
    }
}