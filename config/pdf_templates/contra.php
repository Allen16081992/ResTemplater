<?php declare(strict_types=1);
    // Load files
    require_once __DIR__ . '/abstract_template.conf.php';

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

                //////////////////// HEADLINE ///////////////////
                $currentY = $this->GetY() + 4; 
                $this->SetDrawColor(0, 80, 180); 
                $this->Line($currentY+38, $currentY, 200, $currentY);

                $this->SetXY(70, 25); 
                $this->SetFont('Helvetica', '', 10);
                $this->MultiCell(0, 2, $this->data['headline'], 0, 'L');
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

            $this->Output();
        }
    }