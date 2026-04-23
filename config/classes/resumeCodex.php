<?php
    final class resumeCodex {
        public function __construct(private PDO $pdo) {} 

        // Create Resume
        public function createResume($title, $desc, $uid): int {
            $desc = $desc === '' ? null : $desc;
            $stmt = $this->pdo->prepare('INSERT INTO resumes (title, summary, user_id) VALUES (:title, :summary, :user_id)');
            $stmt->execute([
                ':title'=> $title,
                ':summary'     => $desc,
                ':user_id'     => $uid
            ]);
            return (int) $this->pdo->lastInsertId();
        }

        // Fetch Resume (Read)
        public function fetchResumes(int $uid) {
            // Select user records
            $stmt = $this->pdo->prepare('SELECT id, title, summary, updated_at FROM `resumes` WHERE user_id = :user_id');
            $stmt->execute([':user_id' => $uid]);
            $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $list;
        }

        // Update Resume
        public function updateResume($title, $desc, $uid): int {
            $stmt = $this->pdo->prepare('UPDATE resumes SET title = :title, summary = :summary WHERE user_id = :user_id');
            $stmt->execute([
                ':title'=> $title,
                ':summary'     => $desc,
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
    }