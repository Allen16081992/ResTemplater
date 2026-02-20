<?php require_once '../database/v2_db.php';

    final class resumeCodex {
        public function __construct(private PDO $pdo) {} 

        // Create Resume
        public function createResume($title, $desc, $uid): int {
            $desc = $desc === '' ? null : $desc;
            $stmt = $this->pdo->prepare("INSERT INTO `resume` (resume_title, summary, user_id) VALUES (:resume_title, :summary, :user_id)");
            $stmt->execute([
                ':resume_title'=> $title,
                ':summary'     => $desc,
                ':user_id'     => $uid
            ]);
            return (int) $this->pdo->lastInsertId();
        }

        // Fetch Resume (Read)
        public function loadResumes(int $uid) {
            // Select user records
            $stmt = $this->pdo->prepare('SELECT id, resume_title, summary, updated_at FROM `resume` WHERE user_id = :user_id');
            $stmt->execute([':user_id' => $uid]);
            $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $list;
        }

        // Update Resume
        public function updateResume($title, $desc, $uid): int {
            $stmt = $this->pdo->prepare("UPDATE `resume` SET resume_title = :resume_title, summary = :summary WHERE user_id = :user_id;");
            $stmt->execute([
                ':resume_title'=> $title,
                ':summary'     => $desc,
                ':user_id'     => $uid
            ]);
            return (int) $this->pdo->lastInsertId();
        }

        // Delete Resume
        public function deleteResume(int $resumeID, int $uid) {
            $stmt = $this->pdo->prepare('DELETE FROM `resume` WHERE resume_id = :resume_id AND user_id = :user_id');
            $stmt->execute([
                ':resume_id'=> $resumeID,
                ':user_id'  => $uid
            ]);
            return $stmt->rowCount() > 0;
        }
    }