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
        public function updateResume(string $title, string $desc, int $uid) {
            try {
                $stmt = $this->pdo->prepare('UPDATE resumes SET title = :title, headline = :headline WHERE user_id = :user_id');
                $stmt->execute([
                    ':title'    => $title,
                    ':headline' => $desc,
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
                
                $stmt = $this->pdo->prepare("SELECT * FROM experience_bullets WHERE exp_id IN ($inQuery)");
                $stmt->execute($expIds);
                $data['experience_bullets'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            // 3. Fetch education bullets based on education IDs
            $data['education_bullets'] = [];
            if (!empty($data['education'])) {
                $eduIds = array_column($data['education'], 'id');
                $inQuery = implode(',', array_fill(0, count($eduIds), '?'));
                
                $stmt = $this->pdo->prepare("SELECT * FROM education_bullets WHERE edu_id IN ($inQuery)");
                $stmt->execute($eduIds);
                $data['education_bullets'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            // 4. Fetch project bullets based on project IDs
            $data['project_bullets'] = [];
            if (!empty($data['projects'])) {
                $projectIds = array_column($data['projects'], 'id');
                $inQuery = implode(',', array_fill(0, count($projectIds), '?'));
                
                $stmt = $this->pdo->prepare("SELECT * FROM project_bullets WHERE project_id IN ($inQuery)");
                $stmt->execute($projectIds);
                $data['project_bullets'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            return $data; 
        }
    }