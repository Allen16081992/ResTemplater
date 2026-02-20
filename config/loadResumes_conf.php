<?php // Load the user's resumes
    if (isset($_SESSION['session_data']['user_id'])) {
        // Load PHP files
        require_once './database/v2_db.php';
        require_once './classes/resume_class.php';

        // DB querry (only after validation)
        $uid = $_SESSION['session_data']['user_id'];
        $pdo = Database::Connect();
        $model = new resumeCodex($pdo); 
        $resumes = $model->loadResumes($uid);
    }