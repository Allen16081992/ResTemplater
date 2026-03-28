<?php require_once '../database/v2_db.php';

    final class socialCodex {
        public function __construct(private PDO $pdo) {} 


        // Create Social
        public function createSocial(array $postData): int {
            // $stmt = $this->pdo->prepare(
            //     'INSERT INTO skills (name, category, resume_id) 
            //     VALUES (:name, :category, :resume_id)'
            // );
            // $stmt->execute([
            //     ':name'     => $postData['skill']['name'],
            //     ':category'  => $postData['skill']['categoy'],
            //     ':resume_id' => $postData['resume_id']
            // ]);
            return (int) $this->pdo->lastInsertId();
        }

        // Update Social
        public function updateSocial(array $postData): int {
            // $stmt = $this->pdo->prepare(
            //     'UPDATE skills SET name = :name, category = :category WHERE id = :id AND resume_id = :resume_id'
            // );
            // $stmt->execute([
            //     'id'        => $postData['id'],
            //     ':name'     => $postData['name'],
            //     ':category' => $postData['category'],
            //     ':resume_id'=> $postData['resume_id']
            // ]);
            return (int) $this->pdo->lastInsertId();
        }

        // Delete Social
        public function deleteSocial(int $id, int $resID) {
            // $stmt = $this->pdo->prepare('DELETE FROM skills WHERE id = :id AND resume_id = :resume_id');
            // $stmt->execute([
            //     ':id'=> $id,
            //     ':resume_id'=> $resID
            // ]);
            // return $stmt->rowCount() > 0;
        }
    }