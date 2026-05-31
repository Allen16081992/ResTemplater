<?php
    final class socialCodex {
        public function __construct(private PDO $pdo) {} 

        // Create Social
        public function createSocial(array $postData): int {
            try {
                $stmt = $this->pdo->prepare('INSERT INTO socials (media_url, resume_id) VALUES (:media_url, :resume_id)');
                $stmt->execute([
                    ':media_url' => $postData['media_url'],
                    ':resume_id' => $postData['resume_id']
                ]);
                return (int) $this->pdo->lastInsertId();
            } catch (PDOException $e) {
                // Log the details to fix later, then return -1
                error_log("Social Insert Error: " . $e->getMessage());
                return -1;
            }
        }

        // Update Social
        public function updateSocial(array $postData): int {
            try {
                $stmt = $this->pdo->prepare('UPDATE socials SET media_url = :media_url WHERE id = :id AND resume_id = :resume_id');
                $stmt->execute([
                    ':id'=> $postData['id'],
                    ':media_url'=> $postData['media_url'],
                    ':resume_id'=> $postData['resume_id']
                ]);
                return $stmt->rowCount();
            } catch (PDOException $e) {
                // Log the details to fix later, then return -1
                error_log("Social Update Error: " . $e->getMessage());
                return -1;
            }
        }

        // Delete Social
        public function deleteSocial(int $id, int $resid) {
            try {
                $stmt = $this->pdo->prepare('DELETE FROM socials WHERE id = :id AND resume_id = :resume_id');
                $stmt->execute([
                    ':id'=> $id,
                    ':resume_id'=> $resid
                ]);
                return $stmt->rowCount();
            } catch (PDOException $e) {
                // Log the details to fix later, then return -1
                error_log("Social Delete Error: " . $e->getMessage());
                return -1;
            }
        }
    }