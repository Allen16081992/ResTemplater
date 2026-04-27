<?php // Load PHP Files
    require_once __DIR__ . '/autoloader.conf.php'; 
    $pdo = Database::Connect(); 
    
    if (isset($_SESSION['session_data']['user_id'])) {
        // Fetch Account data
        $accModel = new userCodex($pdo);
        $uid = $_SESSION['session_data']['user_id'];

        $data['account'] = $accModel->fetchAccount($uid);
        $data['contact'] = $accModel->fetchContact($uid);

        // Fetch Resumes list
        $resModel = new resumeCodex($pdo); 
        $data['papers'] = $resModel->fetchResumes($uid);

        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
    } else {
        // Optionally handle the logged-out state
        header('Location: index.php');
        exit;
    }

    // Load resume dependancies
    if (isset($_POST['selectCV'])) {
        class FetchPaper {
            public function __construct(private PDO $pdo) {} 

            public function fetchResumeData(int $resid, int $userid) {
                $data = array();

                // Fetch Resume data
                $stmt = $this->pdo->prepare('SELECT * FROM resumes WHERE resume_id = :resume_id AND user_id = :user_id LIMIT 1');
                $stmt->execute([
                    ':resume_id' => $resid,
                    ':user_id' => $userid,
                ]);
                $data['resdata'] = $stmt->fetch(PDO::FETCH_ASSOC);

                // Fetch Experience data
                $stmt = $this->pdo->prepare('SELECT * FROM experience WHERE resume_id = :resume_id');
                $stmt->execute([':resume_id' => $resid]);
                $data['experience'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $stmt = $this->pdo->prepare('SELECT * FROM experience_bullets WHERE resume_id = :resume_id');
                $stmt->execute([':resume_id' => $resid]);
                $data['experience_bullet'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Fetch Education data
                $stmt = $this->pdo->prepare('SELECT * FROM education WHERE resume_id = :resume_id');
                $stmt->execute([':resume_id' => $resid]);
                $data['education'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $stmt = $this->pdo->prepare('SELECT * FROM education_bullets WHERE resume_id = :resume_id');
                $stmt->execute([':resume_id' => $resid]);
                $data['education_bullet'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Fetch Skills data
                $stmt = $this->pdo->prepare('SELECT * FROM skills WHERE resume_id = :resume_id');
                $stmt->execute([$resid]);
                $data['skills'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Fetch Social Media link data
                $stmt = $this->pdo->prepare('SELECT * FROM socials WHERE resume_id = :resume_id');
                $stmt->execute([$resid]);
                $data['socials'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (empty($data['resdata'])) {
                    $_SESSION['error'] = 'Resume not found.';
                }
                return $data;
            }
        }

        // Fetch Job Experiences
        $resID = $_POST['resume_id'];
        $model = new FetchPaper($pdo); 
        $data = $model->fetchResumeData($resID, $uid);
        unset($pdo);
    }