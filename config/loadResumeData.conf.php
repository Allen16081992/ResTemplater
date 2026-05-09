<?php // Load PHP Files
    require_once __DIR__ . '/autoloader.conf.php'; 
    $pdo = Database::Connect();
    $data = [
        'account' => [],
        'contact' => [],
        'papers'  => [],
        'active_paper' => null
    ];
    
    if (isset($_SESSION['session_data']['user_id'])) {
        // 1. Set variable for readability
        $uid = $_SESSION['session_data']['user_id'];

        // 2. Fetch Account data
        $accModel = new userCodex($pdo);
        $data['account'] = $accModel->fetchAccount($uid);
        $data['contact'] = $accModel->fetchContact($uid);

        // 3. Fetch Resume list
        $resModel = new resumeCodex($pdo); 
        $data['papers'] = $resModel->fetchResumes($uid);

        // Load resume dependancies
        if (isset($_GET['resume_id'])) {
            // 1. Set variable for readability
            $rid = $_GET['resume_id'];

            // 2. Fetch the "Master" record first to verify ownership
            $resInfo = $resModel->fetchResume($rid, $uid); // Check ownership

            if ($resInfo) {
                // 3. Ownership confirmed! Grab relational data
                $data['active_paper'] = [
                    'master' => $resInfo, // Add the master info (title, headline) into the array too
                    'sections' => $resModel->fetchCompositeData($rid)
                ];
                $_SESSION['action'] = 'builder';
            } else {
                // Forbidden or Non-existent
                $_SESSION['error'] = "That spellbook does not belong to you.";
                header('Location: client.php');
                exit;
            }
        }

    } elseif (isset($_POST['wizard'])) {
        // Visitors may be here.
        $_SESSION['action'] = $_POST['wizard'];
        
    } elseif (!isset($_SESSION['session_data']['user_id']) && !isset($_POST['wizard'])) {
        // Optionally handle the logged-out state
        header('Location: index.php');
        exit;
    }
    return $data;
    // echo "<pre>";
    // print_r($data['papers']);
    // echo "</pre>";