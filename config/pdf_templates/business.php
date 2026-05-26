<?php declare(strict_types=1);
    // Load files
    require_once __DIR__ . '/abstract_template.conf.php';

    class BusinessTemplate extends BaseTemplate {
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

        function Header() {
            if ($this->PageNo() == 1) {
                //////////////////// INITIALS ///////////////////
                $imagePath = realpath(__DIR__ . '/../../assets/images/MyInitials.png');
                $initials = $this->abbrFullname($this->data['contact']['fullname'], 'short');

                // Set Trademark
                $this->Image($imagePath, 20, 10, 30); // Adjust positioning and dimensions

                // Set Initials
                $this->SetXY(25, 24); // Set the position for text
                $this->SetFont('Times', 'I', 14);
                $this->SetFontSize(41); // Set the font size to 16
                $this->SetTextColor(255,255,255); // Set font color to red (RGB values)
                $this->Cell(0, 10, $initials, 0, 0, 'L');

                // Set Name
                $this->SetXY(75, 25); // Set the position for text
                $this->SetTextColor(0,0,0); // Set font color to red (RGB values)
                $this->Cell(0, 10, $this->data['contact']['fullname'], 0, 1, '');
                $this->Ln(10);
                

                //////////////////// HEADLINE ///////////////////
                if ($this->data['headline'] !== '') {
                    $currentY = $this->GetY() + 2; 
                    $this->SetDrawColor(0, 80, 180); 
                    $this->Line(10, $currentY, 200, $currentY);
                    
                    $this->SetFont('Times', '', 11);
                    $this->SetX(77); // Set the position for text
                    $this->MultiCell(0, 10, $this->data['headline'], 0, 'L');

                    // Set the cursor for the next section (Contact)
                    $this->SetY($currentY + 10); 
                }
                $this->Ln(5);
            }
        }
        
        function Footer() {
            if ($this->PageNo() > 1) {
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
            $name = $this->data['contact']['fullname'] ?? 'My Scroll';
            $today = $this->printDate('today');

            if (isset($this->data['resume'][0]['resume_title'])) {
                $doc = htmlspecialchars($this->data['resume'][0]['resume_title']); 
                $this->SetTitle($doc.' '.$today);
            } else { 
                // Wizard
                $doc = htmlspecialchars($name); 
                $today = $this->printDate('today');
                $this->SetTitle($this->abbrFullname($doc).' '.$today);
            }


            //////////////////// CONTACT (OPTIE 1) //////////////////
            $this->SetDrawColor(0, 80, 180); 
            $this->SetFont('Times', '', 10);
            
            // We bouwen één lange string van alle contactinfo
            $zip     = '3011 MC';
            $city    = $this->data['contact']['city'];
            $phone   = $this->data['contact']['phone'];
            $email   = $this->data['contact']['email'];
            $country = $this->data['contact']['country'];
            
            $contactString = "{$phone}   |   {$email}   |   {$zip}, {$city}   |   {$country}";
            
            // Print de string perfect gecentreerd over de hele pagina (breedte 190mm)
            $this->Cell(190, 0, $contactString, 0, 1, 'C'); 

            // Trek de strakke lijn direct daaronder
            $lineY = $this->GetY() + 2;
            //$this->Line(10, $lineY, 200, $lineY);

            // Ruimte na de lijn
            $this->SetY($lineY + 6);


            //////////////////// LAYOUT CALCULATIONS //////////////////
            $topOfColumnsY = $this->GetY(); 
            $hasSocials = !empty($this->data['social']);

            // Define widths and positions based on whether socials exist
            if (class_exists('QRcode') && $hasSocials) {
                $contentX = 60;      // Start further right
                $contentWidth = 140; // Narrower (200 - 60)
            } else {
                $contentX = 10;      // Start at the left margin
                $contentWidth = 190; // Full width (Standard A4 content area)
            }


            ///////////////////////// (TWO COLUMN) ///////////////////////////
            //////////////////// LEFT COLUMN: SOCIAL MEDIA //////////////////
            if (class_exists('QRcode') && $hasSocials) {
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


            //////////////////// RIGHT COLUMN: ADAPTIVE STACK //////////////////
            $this->SetY($topOfColumnsY); 

            // 1. Determine the Order
            if (empty($this->data['experience'])) {
                // If no experience, Projects "teleports" to the top
                $order = ['projects', 'education'];
            } else {
                // Standard traditional order
                $order = ['experience', 'education', 'projects'];
            }

            // 2. The Master Loop
            foreach ($order as $section) {
                if (empty($this->data[$section])) continue;

                $this->SetX($contentX);
                $currentY = $this->GetY();

                // Draw the vertical "L-bracket" line if socials exist
                if ($hasSocials) {
                    // We use a safe estimate (40), but FPDF handles the overflow
                    $this->Line(200, $currentY, 200, $currentY + 40);
                }

                // Render the Header based on the section key
                $headerTitle = ucfirst($section);
                $this->alignSubtitle($headerTitle, $contentX, $currentY);

                foreach ($this->data[$section] as $item) {
                    $this->SetX($contentX);
                    
                    // --- TITLE ---
                    $this->SetFont('Times', 'B', 11);
                    // Experience and Projects use 'title', Education uses 'program'
                    $titleText = $item['title'] ?? $item['program'] ?? '';
                    $this->Cell(0, 6, $this->sanitize($titleText), 0, 1, 'L');

                    // --- SUBTITLE ---
                    $this->SetX($contentX);
                    $this->SetFont('Times', '', 10);
                    
                    if ($section === 'projects') {
                        $this->Cell(0, 5, $this->sanitize($item['role']), 0, 1, 'L');
                    } else {
                        $org = $item['employer'] ?? $item['school'] ?? '';
                        $date = isset($item['start_date']) ? ($item['start_date'] . ' - ' . ($item['end_date'] ?: 'Present')) : '';
                        $this->Cell(0, 5, $this->sanitize($org) . ' | ' . $date, 0, 1, 'L');
                    }

                    // --- SUMMARY ---
                    if (!empty($item['summary'])) {
                        $this->SetX($contentX);
                        $this->SetFont('Times', '', 9);
                        $this->SetTextColor(50, 50, 50);
                        $this->MultiCell($contentWidth - 5, 4, $this->sanitize($item['summary']), 0, 'L');
                        $this->SetTextColor(0, 0, 0);
                    } else {
                        // Summary is empty: Verify bullet points
                        $bulletKey = ($section === 'experience') ? 'exp_bullets' : (($section === 'education') ? 'edu_bullets' : 'pro_bullets');
                        $foreignKey = ($section === 'experience') ? 'experience_id' : (($section === 'education') ? 'education_id' : 'projects_id');
                        
                        // Verify if they contain any data
                        if (!empty($this->data[$bulletKey])) {
                            foreach ($this->data[$bulletKey] as $bullet) {
                                // Display non-empty bullet items that match the parent id.
                                if ($bullet[$foreignKey] == $item['id'] && !empty($bullet['summary'])) {
                                    $this->SetX($contentX + 3); // Klein beetje inspringen voor de strakke look
                                    $this->SetFont('Times', '', 9);
                                    $this->SetTextColor(50, 50, 50);
                                    
                                    $this->Cell(4, 4, chr(149), 0, 0);
                                    $this->MultiCell($contentWidth - 12, 4, $this->sanitize($bullet['summary']), 0, 'L');
                                    $this->SetTextColor(0, 0, 0);
                                }
                            }
                        }
                    }
                    $this->Ln(4);
                }
                $this->Ln(2);
            }


            //////////////////// SKILLS SECTION //////////////////
            // Define the right key from the database
            $skillsData = $this->data['skills'] ?? [];

            if (!empty($skillsData)) {
                $this->SetX($contentX);
                $skillY = $this->GetY();

                // 1. Corrigeer de titel naar 'Skills'
                $this->alignSubtitle('Skills', $contentX, $skillY);
                
                // Bereken de kolombreedte op basis van de beschikbare ruimte rechts
                $colWidth = (200 - $contentX) / 2; 
                $count = 0;

                foreach ($skillsData as $skill) {
                    // Verdeel de skills netjes over 2 kolommen (links en rechts)
                    $this->SetX($contentX + ($count % 2 * $colWidth));            
                    $this->SetFont('Times', 'B', 10);
                    
                    // chr(149) is jouw vertrouwde Times-bulletpoint
                    $this->Cell($colWidth, 5, chr(149) . ' ' . $this->sanitize($skill['name']), 0, ($count % 2 == 1 ? 1 : 0), 'L');
                    $count++; 
                }
                
                // Als we op een oneven aantal zijn geëindigd, sluiten we de regel netjes af
                if ($count % 2 != 0) $this->Ln(5);

                // Teken de verticale lijn aan de rechterkant als er socials actief zijn
                if (!empty($this->data['social'])) {
                    $this->Line(200, $skillY, 200, $this->GetY());
                }
            }
            //////////////////// SKILLS SECTION //////////////////
            // $this->SetX($contentX);
            // $skillY = $this->GetY();

            // $this->alignSubtitle('Education', $contentX, $skillY);
            // if (!empty($this->data['skills'])) {
            //     $colWidth = (200 - $contentX) / 2; // Divide available space by 2
            //     $count = 0;

            //     foreach ($this->data['skills'] as $skill) {
            //         $this->SetX($contentX + ($count % 2 * $colWidth));            
            //         $this->SetFont('Times', 'B', 10);
            //         $this->Cell($colWidth, 5, chr(149) . ' ' . $skill['name'], 0, ($count % 2 == 1 ? 1 : 0), 'L');
            //         $count++; // chr(149) is a bulletpoint!
            //     }
                
            //     // Add a newline if we ended on an odd number
            //     if ($count % 2 != 0) $this->Ln(5);

            //     // Draw the Adaptive Vertical Line (Same logic as Education)
            //     if (!empty($this->data['social'])) {
            //         $this->Line(200, $skillY, 200, $this->GetY());
            //     }
            // }

            // Add a line break
            $this->Ln(8);

            $this->Output();
        }
    }

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