<?php // Dhr. Allen Pieter
// Start a session for handling data and error messages.
require_once 'peripherals/session_management.config.php';
sessionRegen(); // Call the periodic session regeneration

// Invoke the (improved) database connection and FPDF library.
require_once 'idb.config.php';
require_once 'fpdf185/fpdf.php';

class ResumePDF extends FPDF {
    private $pdo;
    private $data;

    public function __construct() {
        parent::__construct();
        $database = new Database();
        $this->pdo = $database->connect();
        $this->SetAutoPageBreak(true, 15);
    }

    public function fetchData($resumeID, $userID) {
        $result = array();
        $tables = array('resume', 'accounts', 'contact', 'profile', 'experience', 'education', 'techskill', 'motivation');

        // Loop through each table and fetch data
        foreach ($tables as $table) {
            if ($table === 'resume') {
                $stmt = $this->pdo->prepare("SELECT resumetitle FROM `$table` WHERE resumeID = ?");
                $stmt->execute([$resumeID]);
                $result[$table] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } elseif ($table === 'accounts') {
                $stmt = $this->pdo->prepare("SELECT email FROM `$table` WHERE userID = ?");
                $stmt->execute([$userID]);
                $result[$table] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } elseif ($table === 'contact') {
                $stmt = $this->pdo->prepare("SELECT * FROM `$table` WHERE userID = ?");
                $stmt->execute([$userID]);
                $result[$table] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $stmt = $this->pdo->prepare("SELECT * FROM `$table` WHERE resumeID = ? AND userID = ?");
                $stmt->execute([$resumeID, $userID]);
                $result[$table] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        if (empty($result)) {
            $_SESSION['error'] = 'No data found.';
        }
        $this->data = $result; 
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
        $this->SetFont('Arial', '', 12);
        
        // Sanitize data using htmlspecialchars
        // Resume title becomes Filename
        if (isset($this->data['resume'][0]['resumetitle'])) {
            $doc = htmlspecialchars($this->data['resume'][0]['resumetitle']); 
            $this->SetTitle('My '.$doc.' BT');
        }
        // Name and Contact
        if (isset($this->data['contact'][0])) {
            $firstname = htmlspecialchars($this->data['contact'][0]['firstname']);
            $surname = htmlspecialchars($this->data['contact'][0]['lastname']);
            $initials = substr($firstname, 0, 1) . substr($surname, 0, 1);
            $city = htmlspecialchars($this->data['contact'][0]['city']);
            $country = htmlspecialchars($this->data['contact'][0]['country']);
            $phone = htmlspecialchars($this->data['contact'][0]['phone']);
            $email = htmlspecialchars($this->data['accounts'][0]['email']); 
        }
        // Work Experience
        if (isset($this->data['experience'])) {
            $workTitles = array_column($this->data['experience'], 'worktitle');
            $workCompany = array_column($this->data['experience'], 'company');
            $workFirstDate = array_column($this->data['experience'], 'firstDate');
            $workLastDate = array_column($this->data['experience'], 'lastDate');
            $workSummary = array_column($this->data['experience'], 'workdesc');
            // Sanitize
            $workTitles = array_map('htmlspecialchars', $workTitles);
            $workCompany = array_map('htmlspecialchars', $workCompany);
            $workFirstDate = array_map('htmlspecialchars', $workFirstDate);
            $workLastDate = array_map('htmlspecialchars', $workLastDate);
            $workSummary = array_map('htmlspecialchars', $workSummary);
        }
        // Education
        if (isset($this->data['education'])) {
            $eduTitles = array_column($this->data['education'], 'edutitle');
            $eduCompany = array_column($this->data['education'], 'company');
            $eduFirstDate = array_column($this->data['education'], 'firstDate');
            $eduLastDate = array_column($this->data['education'], 'lastDate');
            $eduSummary = array_column($this->data['education'], 'edudesc');
            // Sanitize
            $eduTitles = array_map('htmlspecialchars', $eduTitles);
            $eduCompany = array_map('htmlspecialchars', $eduCompany);
            $eduFirstDate = array_map('htmlspecialchars', $eduFirstDate);
            $eduLastDate = array_map('htmlspecialchars', $eduLastDate);
            $eduSummary = array_map('htmlspecialchars', $eduSummary);
        }
        // Skills
        if (isset($this->data['techskill'])) {
            $techTitle = array_column($this->data['techskill'], 'techtitle');
            $language = array_column($this->data['techskill'], 'language');
            $interest = array_column($this->data['techskill'], 'interest');
            // Sanitize
            $techTitle = array_map('htmlspecialchars', $techTitle);
            $language = array_map('htmlspecialchars', $language);
            $interest = array_map('htmlspecialchars', $interest);
        }
        // Motivation
        if (isset($this->data['motivation'])) {
            $motivation = array_column($this->data['motivation'], 'motdesc');
            // Sanitize
            $motivation = array_map('htmlspecialchars', $motivation);
        }

        //////////////////// TRADEMARK ///////////////////
        $imagePath = '../img/MyInitials.png';
        $building = '../img/icons/buildings-24.png'; 
        $envelope = '../img/icons/envelope-24.png';
        $mobile = '../img/icons/phone-24.png'; 
        //$world = '../img/icons/world-24.png';

        //////////////////// HEADER ///////////////////

        // Set Trademark
        $this->Image($imagePath, 10, 10, 30); // Adjust positioning and dimensions

        // Set Initials
        $this->SetXY(14, 24); // Set the position for text
        $this->SetFont('Arial', 'I', 14);
        $this->SetFontSize(41); // Set the font size to 16
        $this->SetTextColor(255,255,255); // Set font color to red (RGB values)
        $this->Cell(0, 10, $initials, 0, 0, 'L');

        // Set Name
        $this->SetXY(75, 25); // Set the position for text
        $this->SetTextColor(0,0,0); // Set font color to red (RGB values)
        $this->Cell(0, 10, $firstname.' '.$surname, 0, 1, '');

        // Set font
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(0,0,0); // Set font color to red (RGB values)

        // Add a line break
        $this->Ln(15);

        //////////////////// Contact ///////////////////

        // Set contact information
        $this->SetFont('Arial', '', 12);
        $this->SetDrawColor(0,80,180); //0,155,119 Emerald Green //0,80,180 Baltic Blue
        $this->Image($mobile, 10, 50, 5); $this->Cell(50, 5, $phone, 1, 0, 'C'); 
        $this->Image($envelope, 61, 50, 5);  $this->Cell(90, 5, $email, 1, 0, 'C'); 
        $this->Image($building, 150, 50, 5); $this->Cell(50, 5, $city, 1, 0, 'C');

        // Add a line break
        $this->Ln(15);

        //$this->SetX($this->GetPageWidth() - 10); 
        //$this->Cell(-5, 5, $country, 0, 1, 'R'); 
        //$this->Image($world, 195, 25, 5); 
        //$this->Ln(4); // add a line break

        //////////////////// Profile ///////////////////

        if (isset($this->data['profile'][0]['profiledesc'])) {
            // Set Profile
            $this->SetFont('Arial', '', 10);
            //$this->Cell(0, 5, 'Profiel', 0, 1, 'C'); 
            $this->SetFont('Arial', 'B', 14);
            // Set description
            $profiledesc = $this->data['profile'][0]['profiledesc'];
            $this->SetFont('Arial', 'I', 10);
            $this->SetFillColor(230,230,0);
            $this->MultiCell(0, 5, html_entity_decode($profiledesc), 0, 'C', 0);
            // Add a line break
            $this->Ln(5);
        }

        /////////////////////// WORK EXPERIENCE ////////////////////////
        $this->SetFont('Arial', '', 14);
        $this->Cell(0, 10, 'Werkervaring', 1, 1, 'C');
        $this->Ln(2);

        // Show values from array position specifically. Limit - 3 jobs.
        if (count($workTitles) >= 1) {
            $this->SetFont('Arial', '', 10);
            // DATES
            if (isset($workFirstDate[0]) && isset($workLastDate[0])) {
                $this->Cell(22, 10, $workFirstDate[0], 0, 0, '');
                $this->Cell(5, 10, '-', 0, 0, '');
                $this->Cell(30, 10, $workLastDate[0], 0, 0, '');
            }
            $this->SetFont('Arial', 'B', 12);           
            // PROFESSION, COMPANY, SUMMARY
            if (isset($workTitles[0]) && isset($workCompany[0])) {
                $this->Cell(60, 10, $workTitles[0], 0, 0, 'L');
                $this->Cell(50, 10, $workCompany[0], 0, 1, '');
            }
            $this->SetFont('Arial', '', 10);    
            if (isset($workSummary[0])) {
                $this->SetFontSize(10);
                $this->MultiCell(0, 5, html_entity_decode($workSummary[0]));
                $this->Ln(5);
            }
        }
        if (count($workTitles) >= 2) {
            $this->SetFont('Arial', '', 10);
            if (isset($workFirstDate[1]) && isset($workLastDate[1])) {
                $this->Cell(22, 10, $workFirstDate[1], 0, 0, '');
                $this->Cell(5, 10, '-', 0, 0, '');
                $this->Cell(30, 10, $workLastDate[1], 0, 0, '');
            }
            $this->SetFont('Arial', 'B', 12);
            if (isset($workTitles[1]) && isset($workCompany[1])) {
                $this->Cell(60, 10, $workTitles[1], 0, 0, 'L');
                $this->Cell(50, 10, $workCompany[1], 0, 1, '');
            }
            $this->SetFont('Arial', 'I', 10);
            if (isset($workSummary[1])) {
                $this->SetFontSize(10);
                $this->MultiCell(0, 5, html_entity_decode($workSummary[1]));
                $this->Ln(5);
            }
        } 
        if (count($workTitles) >= 3) {
            $this->SetFont('Arial', '', 10);
            if (isset($workFirstDate[2]) && isset($workLastDate[2])) {
                $this->Cell(22, 10, $workFirstDate[2], 0, 0, '');
                $this->Cell(5, 10, '-', 0, 0, '');
                $this->Cell(30, 10, $workLastDate[2], 0, 0, '');
            }
            $this->SetFont('Arial', 'B', 12);
            if (isset($workTitles[2]) && isset($workCompany[2])) {
                $this->Cell(60, 10, $workTitles[2], 0, 0, 'L');
                $this->Cell(50, 10, $workCompany[2], 0, 1, '');
            }
            $this->SetFont('Arial', 'I', 10);
            if (isset($workSummary[2])) {
                $this->SetFontSize(10);
                $this->MultiCell(0, 5, html_entity_decode($workSummary[2]));
                $this->Ln(5);
            }
        }

        /////////////////////// EDUCATION ////////////////////////
        $this->SetFont('Arial', '', 14);
        $this->Cell(0, 10, 'Opleiding', 1, 1, 'C');
        $this->Ln(2);

        // Show values from array position specifically. Limit - 3 jobs.
        if (count($eduTitles) >= 1) {
            $this->SetFont('Arial', '', 10);
            // DATES
            if (isset($eduFirstDate[0]) && isset($eduLastDate[0])) {
                $this->Cell(22, 10, $eduFirstDate[0], 0, 0, '');
                $this->Cell(5, 10, '-', 0, 0, '');
                $this->Cell(30, 10, $eduLastDate[0], 0, 0, '');
            }
            $this->SetFont('Arial', 'B', 12);
            // PROFESSION, COMPANY, SUMMARY
            if (isset($eduTitles[0]) && isset($eduCompany[0])) {
                $this->Cell(60, 10, $eduTitles[0], 0, 0, 'L');
                $this->Cell(50, 10, $eduCompany[0], 0, 1, '');
            }
            $this->SetFont('Arial', 'I', 10);
            if (isset($eduSummary[0])) {
                $this->SetFontSize(10);
                $this->MultiCell(0, 5, html_entity_decode($eduSummary[0]));
                $this->Ln(5);
            }
        }
        if (count($eduTitles) >= 2) {
            $this->SetFont('Arial', '', 10);
            if (isset($eduFirstDate[1]) && isset($eduLastDate[1])) {
                $this->Cell(22, 10, $eduFirstDate[1], 0, 0, '');
                $this->Cell(5, 10, '-', 0, 0, '');
                $this->Cell(30, 10, $eduLastDate[1], 0, 0, '');
            }
            $this->SetFont('Arial', 'B', 12);
            if (isset($eduTitles[1]) && isset($eduCompany[1])) {
                $this->Cell(60, 10, $eduTitles[1], 0, 0, 'L');
                $this->Cell(50, 10, $eduCompany[1], 0, 1, '');
            }
            $this->SetFont('Arial', 'I', 10);
            if (isset($eduSummary[1])) {
                $this->SetFontSize(10);
                $this->MultiCell(0, 5, html_entity_decode($eduSummary[1]));
                $this->Ln(5);
            }
        } 
        if (count($eduTitles) >= 3) {
            $this->SetFont('Arial', '', 10);
            if (isset($eduFirstDate[2]) && isset($eduLastDate[2])) {
                $this->Cell(22, 10, $eduFirstDate[2], 0, 0, '');
                $this->Cell(5, 10, '-', 0, 0, '');
                $this->Cell(30, 10, $eduLastDate[2], 0, 0, '');
            }
            $this->SetFont('Arial', 'B', 12);
            if (isset($eduTitles[2]) && isset($eduCompany[2])) {
                $this->Cell(60, 10, $eduTitles[2], 0, 0, 'L');
                $this->Cell(50, 10, $eduCompany[2], 0, 1, '');
            }
            $this->SetFont('Arial', 'I', 10);
            if (isset($eduSummary[2])) {
                $this->SetFontSize(10);
                $this->MultiCell(0, 5, html_entity_decode($eduSummary[2]));
                $this->Ln(5);
            }
        }
        
        /////////////////////// HARD & SOFT SKILLS ////////////////////////

        $this->SetLineWidth(1);
        $this->SetFont('Arial', '', 10);
        $this->Cell(63, 6, 'Vaardigheden', 1, 0, 'C');
        $this->Cell(64, 6, 'Talen', 1, 0, 'C');
        $this->Cell(63, 6, 'Interessen', 1, 0, 'C');

        // Add a line break
        $this->Ln(8);

        if (isset($this->data['techskill'][0]['techtitle']) || isset($this->data['techskill'][0]['language']) || isset($this->data['techskill'][0]['interest'])) {
            $this->SetFont('Arial', 'B', 12);
        }
        
        // Show values from array position specifically. Limit - 3 jobs.
        // Determine the maximum number of entries to display    
        $maxEntries = max(count($techTitle), count($language), count($interest));

        // Loop through the entries
        for ($i = 0; $i < $maxEntries; $i++) {
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(20, 5, '', 0, 0, '');
            // SKILLS, LANGUAGES, INTERESTS
            $this->Cell(60, 5, isset($techTitle[$i]) ? $techTitle[$i] : '', 0, 0, 'L');
            $this->Cell(8, 5, '', 0, 0, 'L');
            $this->Cell(60, 5, isset($language[$i]) ? $language[$i] : '', 0, 0, 'L');
            $this->Cell(60, 5, isset($interest[$i]) ? $interest[$i] : '', 0, 1, 'L');
            $this->Ln(5);
        }
        
        // Show motivation
        if (isset($motivation[0])) {
            $this->AddPage();
            $this->SetFont('Arial', '', 15);
    
            // Add Custom header
            $this->Cell(0, 10, 'Motivatie', 0, 0, 'C');
    
            // Add a line break
            $this->Ln(15);

            $this->SetFont('Arial', '', 10);
            $this->MultiCell(0, 5, $motivation[0], 0, 0, '');
        }

        $this->Output();
    }
}

if (isset($_SESSION['resumeID'])) {
    $resumeID = $_SESSION['resumeID'];
    $userID = $_SESSION['user_id'];

    $resumePDF = new ResumePDF();
    $resumePDF->fetchData($resumeID, $userID);
    $resumePDF->generatePDF();
} else {
    // No resume selected.
    $_SESSION['error'] = 'Select a resume to view as PDF.';
    header('location: ../client.php');          
    exit();
}