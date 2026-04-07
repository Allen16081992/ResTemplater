<?php // Start Session
    require_once './session_manager.conf.php';
    SessionBook::invokeSession();

    class educationControl {
        public function __construct(private array $postData) {}

        public function handle(): void {
            if (isset($this->postData['template'])) {
                switch ($this->postData['template']) {
                case 'vintage':
                    require_once '../pdf_templates/vintage.src.php';
                    break;
                case 'default':
                    require_once '../pdf_templates/default.src.php';
                    break;
                case 'new_contro':
                    require_once '../pdf_templates/business.src.php';
                    break;
                default:
                    require_once '../pdf_templates/vintage.src.php';
                }
            } else {
                $_SESSION['error'] = 404;
                header('Location: ../../error.php'); 
                exit();
            }
        }
    }