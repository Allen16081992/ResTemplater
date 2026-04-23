<?php
    final class skillCodex {
        public function __construct(private PDO $pdo) {} 

        // Create Skill
        public function createSkill(array $postData): int {
            $stmt = $this->pdo->prepare(
                'INSERT INTO skills (name, category, resume_id) 
                VALUES (:name, :category, :resume_id)'
            );
            $stmt->execute([
                ':name'     => $postData['skill']['name'],
                ':category'  => $postData['skill']['categoy'],
                ':resume_id' => $postData['resume_id']
            ]);
            return (int) $this->pdo->lastInsertId();
        }

        // Update Skill
        public function updateSkill(array $postData): int {
            $stmt = $this->pdo->prepare(
                'UPDATE skills SET name = :name, category = :category WHERE id = :id AND resume_id = :resume_id'
            );
            $stmt->execute([
                'id'        => $postData['id'],
                ':name'     => $postData['name'],
                ':category' => $postData['category'],
                ':resume_id'=> $postData['resume_id']
            ]);
            return (int) $this->pdo->lastInsertId();
        }

        // Delete Skill
        public function deleteSkill(int $id, int $resID) {
            $stmt = $this->pdo->prepare('DELETE FROM skills WHERE id = :id AND resume_id = :resume_id');
            $stmt->execute([
                ':id'=> $id,
                ':resume_id'=> $resID
            ]);
            return $stmt->rowCount() > 0;
        }
    }