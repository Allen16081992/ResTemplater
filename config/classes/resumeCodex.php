<?php
    final class resumeCodex {
        public function __construct(private PDO $pdo) {} 

        // Create Resume
        public function createResume(string $title, string $desc, int $uid): int {
            $desc = $desc === '' ? null : $desc;
            try {
                $stmt = $this->pdo->prepare('INSERT INTO resumes (title, headline, user_id) VALUES (:title, :headline, :user_id)');
                $stmt->execute([
                    ':title'=> $title,
                    ':headline'     => $desc,
                    ':user_id'     => $uid
                ]);
                return (int) $this->pdo->lastInsertId();
            } catch (PDOException $e) {
                // Log the details to fix later, then return -1
                error_log("Resume Insert Error: " . $e->getMessage());
                return -1;
            }
        }

        // Duplicate Resume
        public function cloneResume(int $rid, int $uid): int {
            try {
                $this->pdo->beginTransaction();

                // 1. Fetch the original to get the title/headline
                $original = $this->fetchResume($rid, $uid);
                $sections = $this->fetchCompositeData($rid); // Get the rest
                if (!$original) throw new Exception("Original not found.");

                // 2. Create the new Parent
                $newTitle = $original['title'] . " (Copy)";
                $newRid = $this->createResume($newTitle, $original['headline'], $uid);

                $いち = new experienceCodex($this->pdo); 
                $に   = new educationCodex($this->pdo);
                $さん = new projectCodex($this->pdo); 
                $よん = new skillCodex($this->pdo);  
                $ご   = new socialCodex($this->pdo);  

                // Now, cast the duplication rituals...
                foreach ($sections['experience'] as $exp) {
                    // Take existing experience data.
                    $clonedData = $exp;
                    
                    // Swap resume_id for the new one.
                    $clonedData['resume_id'] = $newRid;

                    // Now '1' can consume the array exactly how it likes
                    $newEXP = $いち->createExperience($clonedData);

                    // Re-link the bullets to the NEW experience ID
                    if ($newEXP > 0) {
                        foreach ($sections['experience_bullets'] as $bullet) {
                            // Only copy bullets that belonged to the OLD experience ID
                            if ($bullet['experience_id'] == $exp['id']) {
                                $いち->createBulletpoint($bullet['text'], $bullet['sort_order'], $newEXP);
                            }
                        }
                    }
                }
                // Education Loop
                foreach ($sections['education'] as $edu) {
                    $clonedData = $edu;
                    $clonedData['resume_id'] = $newRid;
                    $newEDU = $に->createEducation($clonedData);

                    if ($newEDU > 0) {
                        foreach ($sections['education_bullets'] as $bullet) {
                            // FIX: Check against $edu['id']
                            if ($bullet['education_id'] == $edu['id']) { 
                                $に->createBulletpoint($bullet['text'], $bullet['sort_order'], $newEDU);
                            }
                        }
                    }
                }
                // Projects Loop
                foreach ($sections['projects'] as $pro) {
                    $clonedData = $pro;
                    $clonedData['resume_id'] = $newRid;
                    $newPRO = $さん->createProject($clonedData);

                    if ($newPRO > 0) {
                        foreach ($sections['projects_bullets'] as $bullet) {
                            // FIX: Check against $pro['id']
                            if ($bullet['projects_id'] == $pro['id']) {
                                $さん->createBulletpoint($bullet['text'], $bullet['sort_order'], $newPRO);
                            }
                        }
                    }
                }
                foreach ($sections['skills'] as $ski) {
                    // 1. Prepare the payload to match the createSkill 'Contract'
                    $payload = [
                        'resume_id' => $newRid,
                        'skill' => [
                            'name'     => $ski['name'],
                            'category' => $ski['category']
                        ]
                    ];

                    // 2. Pass the correctly structured payload to '4'
                    $newSKI = $よん->createSkill($payload);
                }
                foreach ($sections['socials'] as $soc) {
                    // Take existing experience data.
                    $clonedData = $soc;
                    
                    // Swap resume_id for the new one.
                    $clonedData['resume_id'] = $newRid;

                    // Now '3' can consume the array exactly how it likes
                    $newSOC = $ご->createSocial($clonedData);
                }

                // Repeat for Education and Projects...
                $this->pdo->commit();
                return $newRid;

            } catch (Exception $e) {
                $this->pdo->rollBack();
                error_log("Clone Resume Failed: " . $e->getMessage());
                return -1;
            }
        }

        // Fetch All Resumes (Read)
        public function fetchResumes(int $uid) {
            $stmt = $this->pdo->prepare('SELECT id, title, updated_at FROM `resumes` WHERE user_id = :user_id');
            $stmt->execute([':user_id' => $uid]);
            $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $list;
        }

        // Fetch Resume (Read)
        public function fetchResume(int $rid, int $uid) {
            $stmt = $this->pdo->prepare('SELECT * FROM `resumes` WHERE id = :resume_id AND user_id = :user_id');
            $stmt->execute([
                ':resume_id' => $rid,
                ':user_id' => $uid
            ]);
            $list = $stmt->fetch(PDO::FETCH_ASSOC);
            return $list;
        }

        // Update Resume
        public function updateResume(string $title, string $desc, int $resid, int $uid) {
            try {
                $stmt = $this->pdo->prepare('UPDATE resumes SET title = :title, headline = :headline WHERE id = :resume_id AND user_id = :user_id');
                $stmt->execute([
                    ':title'    => $title,
                    ':headline' => $desc,
                    ':resume_id'=> $resid,
                    ':user_id'  => $uid
                ]);
                return (int) $stmt->rowCount(); 
            } catch (PDOException $e) {
                // Log the details to fix later, then return -1 
                error_log("Resume Update Error: " . $e->getMessage());
                return -1; 
            }
        }

        // Delete Resume
        public function deleteResume(int $resumeID, int $uid) {
            try {
                $stmt = $this->pdo->prepare('DELETE FROM resumes WHERE id = :resume_id AND user_id = :user_id');
                $stmt->execute([
                    ':resume_id'=> $resumeID,
                    ':user_id'  => $uid
                ]);
                return (int) $stmt->rowCount();
            } catch (PDOException $e) {
                // Log the details to fix later, then return -1
                error_log("Resume Delete Error: " . $e->getMessage());
                return -1;
            }
        }

        // Weird duck in the mix
        public function fetchCompositeData(int $resid): array {
            $data = [];
            
            // 1. Fetch tables that belong directly to the resume
            $directTables = ['experience', 'education', 'projects', 'skills', 'socials'];

            foreach ($directTables as $table) {
                $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE resume_id = :resume_id");
                $stmt->execute([':resume_id' => $resid]);
                $data[$table] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            // 2. Fetch experience bullets based on the experience IDs we just found
            $data['experience_bullets'] = [];
            if (!empty($data['experience'])) {
                $expIds = array_column($data['experience'], 'id');
                $inQuery = implode(',', array_fill(0, count($expIds), '?'));
                
                $stmt = $this->pdo->prepare("SELECT * FROM experience_bullets WHERE experience_id IN ($inQuery)");
                $stmt->execute($expIds);
                $data['experience_bullets'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            // 3. Fetch education bullets based on education IDs
            $data['education_bullets'] = [];
            if (!empty($data['education'])) {
                $eduIds = array_column($data['education'], 'id');
                $inQuery = implode(',', array_fill(0, count($eduIds), '?'));
                
                $stmt = $this->pdo->prepare("SELECT * FROM education_bullets WHERE education_id IN ($inQuery)");
                $stmt->execute($eduIds);
                $data['education_bullets'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            // 4. Fetch project bullets based on project IDs
            $data['project_bullets'] = [];
            if (!empty($data['projects'])) {
                $projectIds = array_column($data['projects'], 'id');
                $inQuery = implode(',', array_fill(0, count($projectIds), '?'));
                
                $stmt = $this->pdo->prepare("SELECT * FROM project_bullets WHERE projects_id IN ($inQuery)");
                $stmt->execute($projectIds);
                $data['project_bullets'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            return $data; 
        }
    }