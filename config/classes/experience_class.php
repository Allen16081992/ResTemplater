<?php require_once '../database/v2_db.php';

    final class experienceCodex {
        public function __construct(private PDO $pdo) {} 

        // Create Experience
        public function createExperience(array $postData): int {
            $stmt = $this->pdo->prepare(
                'INSERT INTO work_experience (title, employer, start_date, end_date, summary, resume_id) 
                VALUES (:title, :employer, :start_date, :end_date, :summary, :resume_id)'
            );
            $stmt->execute([
                ':title'     => $postData['title'],
                ':employer'  => $postData['employer'],
                ':start_date'=> $postData['start_date'],
                ':end_date'  => $postData['end_date'],
                ':summary'   => $postData['summary'],
                ':resume_id' => $postData['resume_id']
            ]);
            return (int) $this->pdo->lastInsertId();
        }

        // Update Experience
        public function updateExperience(array $postData): int {
            $stmt = $this->pdo->prepare(
                'UPDATE work_experience SET title = :title, employer = :employer, start_date = :start_date, end_date = :end_date, summary = :summary WHERE resume_id = :resume_id'
            );
            $stmt->execute([
                ':title'     => $postData['title'],
                ':employer'  => $postData['employer'],
                ':start_date'=> $postData['start_date'],
                ':end_date'  => $postData['end_date'],
                ':summary'   => $postData['summary'],
                ':resume_id' => $postData['resume_id']
            ]);
            return (int) $this->pdo->lastInsertId();
        }

        // Delete Experience
        public function deleteExperience(int $resumeID) {
            $stmt = $this->pdo->prepare('DELETE FROM work_experience WHERE resume_id = :resume_id');
            $stmt->execute([':resume_id'=> $resumeID]);
            return $stmt->rowCount() > 0;
        }
    }