<?php require_once '../database/v2_db.php';

    final class educationCodex {
        public function __construct(private PDO $pdo) {} 

        // Create Experience
        public function createEducation(array $postData): int {
            $stmt = $this->pdo->prepare(
                'INSERT INTO education (title, institute, start_date, end_date, summary, resume_id) 
                VALUES (:title, :institute, :start_date, :end_date, :summary, :resume_id)'
            );
            $stmt->execute([
                ':title'     => $postData['title'],
                ':institute' => $postData['institute'],
                ':start_date'=> $postData['start_date'],
                ':end_date'  => $postData['end_date'],
                ':summary'   => $postData['summary'],
                ':resume_id' => $postData['resume_id']
            ]);
            return (int) $this->pdo->lastInsertId();
        }

        // Update Experience
        public function updateEducation(array $postData): int {
            $stmt = $this->pdo->prepare(
                'UPDATE education SET title = :title, institute = :institute, start_date = :start_date, end_date = :end_date, summary = :summary WHERE resume_id = :resume_id'
            );
            $stmt->execute([
                ':title'     => $postData['title'],
                ':institute' => $postData['institute'],
                ':start_date'=> $postData['start_date'],
                ':end_date'  => $postData['end_date'],
                ':summary'   => $postData['summary'],
                ':resume_id' => $postData['resume_id']
            ]);
            return (int) $this->pdo->lastInsertId();
        }

        // Delete Experience
        public function deleteEducation(int $edu, int $resume) {
            $stmt = $this->pdo->prepare('DELETE FROM education WHERE edu_id = :edu_id AND resume_id = :resume_id');
            $stmt->execute([
                ':edu_id'=> $edu,
                ':resume_id'=> $resume
            ]);
            return $stmt->rowCount() > 0;
        }
    }