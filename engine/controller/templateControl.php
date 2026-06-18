<?php
    class templateControl {
        public function __construct(private array $postData) {}

        public function handle(): void {
            $template = explode('|', $this->postData['action'])[1] ?? 'default';

            // 1. Pick the specific "Painter" (The Child Class)
            switch ($template) {
                case 'vintage':
                    require_once __DIR__ . '/../pdf_templates/vintage.php';
                    $pdf = new VintageTemplate();
                    break;
                case 'business':
                    require_once __DIR__ . '/../pdf_templates/business.php';
                    $pdf = new BusinessTemplate();
                    break;
                case 'contra':
                    require_once __DIR__ . '/../pdf_templates/contra.php';
                    $pdf = new ContraTemplate();
                    break;
                default:
                    require_once __DIR__ . '/../pdf_templates/vintage.php';
                    $pdf = new VintageTemplate();
            }

            // 2. Use the "Brain" from the Abstract Class (The Parent)
            if (isset($_SESSION['session_data']['user_id'])) {
                // Logged in user: Fetch from DB
                $pdf->fetchData($this->postData['resume_id'], $_SESSION['session_data']['user_id']);
            } else {
                // Visitor: Load from $_POST
                $pdf->loadPostData($this->postData);
            }

            // 3. Execute!
            $pdf->generatePDF();
        }
    }