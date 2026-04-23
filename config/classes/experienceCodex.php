<?php
    final class experienceCodex {
        public function __construct(private PDO $pdo) {} 

        // Create Experience
        public function createExperience(array $postData): int {
            $stmt = $this->pdo->prepare(
                'INSERT INTO experience (title, employer, start_date, end_date, summary, resume_id) 
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
                'UPDATE experience SET title = :title, employer = :employer, start_date = :start_date, end_date = :end_date, summary = :summary WHERE resume_id = :resume_id'
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
        public function deleteExperience(int $exp, int $resume) {
            $stmt = $this->pdo->prepare('DELETE FROM experience WHERE exp_id = :exp_id AND resume_id = :resume_id');
            $stmt->execute([
                ':exp_id'=> $exp,
                ':resume_id'=> $resume
            ]);
            return $stmt->rowCount() > 0;
        }
    }