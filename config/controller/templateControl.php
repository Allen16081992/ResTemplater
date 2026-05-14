<?php // Session Mechanics
    SessionBook::invokeSession();

    class templateControl {
        public function __construct(private array $postData) {}

        public function handle(): void {
            // If $this->postData['action'] is "template:read|vintage"
            $template = explode('|', $this->postData['action'])[1] ?? 'default';

            // 1. Pick the specific "Painter" (The Child Class)
            switch ($template) {
                case 'vintage':
                    require_once __DIR__ . '/../pdf_templates/vintage.src.php';
                    $pdf = new VintageTemplate();
                    break;
                case 'business':
                    require_once __DIR__ . '/../pdf_templates/business.src.php';
                    $pdf = new BusinessTemplate();
                    break;
                default:
                    require_once __DIR__ . '/../pdf_templates/vintage.src.php';
                    $pdf = new VintageTemplate();
            }

            // 2. Use the "Brain" from the Abstract Class (The Parent)
            if (isset($_SESSION['session_data']['user_id'])) {
                // Logged in user: Fetch from DB
                $pdf->fetchData($_SESSION['session_data']['resumeID'], $_SESSION['session_data']['user_id']);
            } else {
                // Visitor: Load from $_POST
                $pdf->loadPostData($this->postData);
            }

            // 3. Execute!
            $pdf->generatePDF();
        }
    }