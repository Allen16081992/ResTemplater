<?php require_once '../database/v2_db.php';

    final class socialCodex {
        public function __construct(private PDO $pdo) {} 

        // Create Social
        public function createSocial(array $postData): int {
            $stmt = $this->pdo->prepare(
                'INSERT INTO social (media_url, resume_id) VALUES (:media_url, :resume_id)'
            );
            $stmt->execute([
                ':media_url' => $postData['media_url'],
                ':resume_id' => $postData['resume_id']
            ]);
            return (int) $this->pdo->lastInsertId();
        }

        // Update Social
        public function updateSocial(array $postData): int {
            $stmt = $this->pdo->prepare(
                'UPDATE social SET media_url = :media_url WHERE id = :id AND resume_id = :resume_id'
            );
            $stmt->execute([
                'id'        => $postData['id'],
                ':media_url'=> $postData['media_url'],
                ':resume_id'=> $postData['resume_id']
            ]);
            return (int) $this->pdo->lastInsertId();
        }

        // Delete Social
        public function deleteSocial(int $id, int $resID) {
            $stmt = $this->pdo->prepare('DELETE FROM social WHERE id = :id AND resume_id = :resume_id');
            $stmt->execute([
                ':id'=> $id,
                ':resume_id'=> $resID
            ]);
            return $stmt->rowCount() > 0;
        }
    }