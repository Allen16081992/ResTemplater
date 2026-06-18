<?php declare(strict_types=1);
    // Load files
    require_once __DIR__ . '/abstract_template.php';

    class ContraTemplate extends BaseTemplate {
        function drawContraShard() {
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

        function Header() {
            if ($this->PageNo() == 1) {
                $this->drawContraShard(); // Visual stamp

                // Text parameters
                ////////////////////   NAME   ///////////////////
                $this->SetXY(70, 8);                  // Set position
                $this->SetFont('Helvetica', 'I', 14); // Set font
                $this->SetFontSize(41);               // Set size
                $this->Cell(0, 10, 'James Cook', 0, 1, 'L');

                // //////////////////// HEADLINE ///////////////////
                // $currentY = $this->GetY() + 6; 
                // $this->SetDrawColor(0, 80, 180); 
                // $this->Line($currentY+38, $currentY, 160, $currentY);
                // $this->SetXY(70, 25); 
                // $this->SetFont('Helvetica', '', 10);
                // $this->MultiCell(0, 4, $this->data['headline'], 0, 'L');

                // // Move the cursor to the end of the MultiCell + 5mm buffer
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
            $this->AddPage();

            //////////////////// LAYOUT CALCULATIONS ////////////////////
            // Calculate margins for a centered feel
            $pageWidth = 210;
            $contentWidth = 150; // The width of your "text block"
            $margin = ($pageWidth - $contentWidth) / 2;

            // Apply the margins
            $this->SetLeftMargin($margin);
            $this->SetRightMargin($margin);
            $this->SetX($margin); // Reset the cursor to the new left margin

            // Everything you write now will start safely below the headline
            $this->SetFont('Helvetica', '', 10);

            error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
            ob_start();
            // --- LAYOUT 1: PRODUCTION READY LEFT ANCHOR ---
            $startX = $this->GetX();
            $blockStartY = $this->GetY();

            $qrSize = 20;
            $textOffset = 25; 
            $contentX = $startX + $textOffset;

            // 1. Render QR Code
            if (class_exists('QRcode')) {
                $tempFile = __DIR__ . '/../../assets/images/temp/exp_qr_layout1.png';
                QRcode::png("https://yourprojectlink.com", $tempFile, 'M', 4, 2);
                if (file_exists($tempFile)) {
                    $this->Image($tempFile, $startX, $blockStartY, $qrSize);
                    unlink($tempFile);
                }
            }

            // 2. Title & Employer
            $this->SetXY($contentX, $blockStartY);
            $this->SetFont('Helvetica', 'B', 12);
            $this->SetTextColor(0, 0, 0);
            $this->Cell(0, 6, 'Lead IT Engineer - Global Tech Solutions', 0, 1, 'L');

            // 3. Dates & Location
            $this->SetX($contentX);
            $this->SetFont('Helvetica', 'I', 9);
            $this->SetTextColor(100, 100, 100);
            $this->Cell(0, 5, 'Jan 2022 - Present | Rotterdam, NL', 0, 1, 'L');

            // 4. Description
            $this->SetX($contentX);
            $this->SetFont('Helvetica', '', 10);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(0, 4, "Redesigned the internal server architecture and optimized hardware performance. Implemented custom automation scripts for diagnostic reporting, similar to the work done on the ResTemplater project.", 0, 'L');

            // Reset cursor below tallest element
            $this->SetY(max($this->GetY(), $blockStartY + $qrSize) + 8);
            $this->SetX($startX);

            // --- LAYOUT 2: PRODUCTION READY SPLIT HEADER ---
            $startX = $this->GetX();
            $blockStartY = $this->GetY();

            $qrSize = 20;
            $textOffset = 25; 
            $contentX = $startX + $textOffset;

            // 1. Render QR Code
            if (class_exists('QRcode')) {
                $tempFile = __DIR__ . '/../../assets/images/temp/exp_qr_layout2.png';
                QRcode::png("https://github.com/yourprofile", $tempFile, 'M', 4, 1);
                if (file_exists($tempFile)) {
                    $this->Image($tempFile, $startX, $blockStartY, $qrSize);
                    unlink($tempFile);
                }
            }

            // 2. Job Title (Left) and Dates (Right)
            $this->SetXY($contentX, $blockStartY);
            $this->SetFont('Helvetica', 'B', 11);
            $this->SetTextColor(0, 0, 0);
            $this->Cell(80, 6, strtoupper('Senior IT Engineer'), 0, 0, 'L'); 

            $this->SetFont('Helvetica', '', 9); 
            $this->SetTextColor(120, 120, 120);
            $this->Cell(0, 6, 'May 2024 - PRESENT', 0, 1, 'R'); 

            // 3. Company Name
            $this->SetX($contentX);
            $this->SetFont('Helvetica', '', 10);
            $this->SetTextColor(120, 120, 120); 
            $this->Cell(0, 5, 'Tech Solutions Rotterdam', 0, 1, 'L');

            // 4. Description
            $this->SetX($contentX);
            $this->SetFont('Helvetica', '', 9);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(0, 4, "Integrated K+DCAN diagnostic protocols into automated testing suites. Managed hardware lifecycle for high-performance PC builds and server maintenance.", 0, 'L');

            // Reset cursor below tallest element
            $this->SetY(max($this->GetY(), $blockStartY + $qrSize) + 8);
            $this->SetX($startX);

            // --- LAYOUT 3: PRODUCTION READY TIMELINE AXIS ---
            $startX = $this->GetX();
            $blockStartY = $this->GetY();

            $qrSize = 20;       
            $textOffset = 25;   
            $contentX = $startX + $textOffset;

            // 1. Render QR Code
            if (class_exists('QRcode')) {
                $tempFile = __DIR__ . '/../../assets/images/temp/exp_qr_layout3.png';
                QRcode::png("https://github.com/yourprofile/repo", $tempFile, 'M', 4, 1);
                if (file_exists($tempFile)) {
                    $this->Image($tempFile, $startX, $blockStartY, $qrSize);
                    unlink($tempFile);
                }
            }

            // 2. Job Title
            $this->SetXY($contentX, $blockStartY);
            $this->SetFont('Helvetica', 'B', 12);
            $this->SetTextColor(0, 0, 0);
            $this->Cell(0, 5, 'Infrastructure & Systems Engineer', 0, 1, 'L');

            // 3. Company & Dates
            $this->SetX($contentX);
            $this->SetFont('Helvetica', '', 9);
            $this->SetTextColor(100, 100, 100);
            $this->Cell(0, 4, 'Enterprise Dev Labs  |  2024 - 2026', 0, 1, 'L');
            $this->Ln(1);

            // 4. Description
            $this->SetX($contentX);
            $this->SetFont('Helvetica', '', 9.5);
            $this->SetTextColor(30, 30, 30);
            $this->MultiCell(0, 4.5, "Built automated deployment pipelines and optimized server cluster performance. Engineered low-latency diagnostic tools and maintained physical network hardware architectures.", 0, 'L');

            $blockEndY = $this->GetY();

            // 5. Draw Vertical Timeline Spine
            $this->SetDrawColor(220, 220, 220); 
            $this->SetLineWidth(0.3);
            $lineX = $startX + ($qrSize / 2); 
            $this->Line($lineX, $blockStartY + $qrSize + 2, $lineX, $blockEndY - 2);

            // Reset cursor below tallest element
            $this->SetY(max($blockEndY, $blockStartY + $qrSize) + 10);
            $this->SetX($startX);

            // --- LAYOUT 4: PRODUCTION READY INVERTED GRID ---
            $startX = $this->GetX();
            $blockStartY = $this->GetY();

            // Absolute column mapping
            $col1_X = $startX;          // Column 1: Dates & Company
            $col2_X = $startX + 35;     // Column 2: QR Code
            $col3_X = $startX + 59;     // Column 3: Title & Description

            $qrSize = 20;

            // 1. Column 1: Metadata
            $this->SetXY($col1_X, $blockStartY + 1);
            $this->SetFont('Helvetica', 'B', 9);
            $this->SetTextColor(0, 0, 0);
            $this->Cell(32, 4, '2024 - PRESENT', 0, 1, 'L');

            $this->SetX($col1_X); 
            $this->SetFont('Helvetica', '', 9);
            $this->SetTextColor(100, 100, 100);
            $this->Cell(32, 4, 'Tech Solutions NL', 0, 0, 'L');

            // 2. Column 2: QR Code Node
            if (class_exists('QRcode')) {
                $tempFile = __DIR__ . '/../../assets/images/temp/exp_qr_layout4.png';
                QRcode::png("https://github.com/yourprofile", $tempFile, 'M', 4, 1);
                if (file_exists($tempFile)) {
                    $this->Image($tempFile, $col2_X, $blockStartY, $qrSize);
                    unlink($tempFile);
                }
            }

            // 3. Column 3: Title & Description
            $this->SetXY($col3_X, $blockStartY);
            $this->SetFont('Helvetica', 'B', 11.5);
            $this->SetTextColor(0, 0, 0);
            $this->Cell(0, 5, 'Senior IT Engineer', 0, 1, 'L');
            $this->Ln(1);

            $this->SetX($col3_X); 
            $this->SetFont('Helvetica', '', 9);
            $this->MultiCell(0, 4, "Integrated automated diagnostic frameworks across network systems. Spearheaded server room hardware optimizations and maintained low-latency infrastructure arrays.", 0, 'L');

            // Reset cursor below tallest element
            $this->SetY(max($this->GetY(), $blockStartY + $qrSize) + 8);
            $this->SetX($startX);



            $this->Output();
        }
    }