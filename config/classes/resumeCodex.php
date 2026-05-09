<?php
    final class resumeCodex {
        public function __construct(private PDO $pdo) {} 

        // Create Resume
        public function createResume(string $title, string $desc, int $uid): int {
            $desc = $desc === '' ? null : $desc;
            $stmt = $this->pdo->prepare('INSERT INTO resumes (title, headline, user_id) VALUES (:title, :headline, :user_id)');
            $stmt->execute([
                ':title'=> $title,
                ':headline'     => $desc,
                ':user_id'     => $uid
            ]);
            return (int) $this->pdo->lastInsertId();
        }

        // Fetch All Resumes (Read)
        public function fetchResumes(int $uid) {
            // Select user records
            $stmt = $this->pdo->prepare('SELECT id, title, updated_at FROM `resumes` WHERE user_id = :user_id');
            $stmt->execute([':user_id' => $uid]);
            $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $list;
        }

        // Fetch * Resume (Read)
        public function fetchResume(int $rid, int $uid) {
            // Select user records
            $stmt = $this->pdo->prepare('SELECT * FROM `resumes` WHERE id = :resume_id AND user_id = :user_id');
            $stmt->execute([
                ':resume_id' => $rid,
                ':user_id' => $uid
            ]);
            $list = $stmt->fetch(PDO::FETCH_ASSOC);
            return $list;
        }

        // Update Resume
        public function updateResume(string $title, string $desc, int $uid): int {
            $stmt = $this->pdo->prepare('UPDATE resumes SET title = :title, headline = :headline WHERE user_id = :user_id');
            $stmt->execute([
                ':title'=> $title,
                ':headline'     => $desc,
                ':user_id'     => $uid
            ]);
            return (int) $this->pdo->lastInsertId();
        }

        // Delete Resume
        public function deleteResume(int $resumeID, int $uid) {
            $stmt = $this->pdo->prepare('DELETE FROM resumes WHERE resume_id = :resume_id AND user_id = :user_id');
            $stmt->execute([
                ':resume_id'=> $resumeID,
                ':user_id'  => $uid
            ]);
            return $stmt->rowCount() > 0;
        }

        // Weird duck in the mix
        public function fetchCompositeData(int $resid): array {
            $data = [];
            
            // Defined tables to loop over (keep code DRY)
            $sections = ['experience', 'experience_bullets', 'education', 'education_bullets', 'projects', 'project_bullets', 'skills', 'socials'];

            foreach ($sections as $table) {
                $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE id = :resume_id");
                $stmt->execute([':resume_id' => $resid]);
                $data[$table] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            return $data; // CRITICAL: You need this!
        }
    }