<?php
    final class skillCodex {
        public function __construct(private PDO $pdo) {} 

        // Create Skill
        public function createSkill(array $data): int {
            try {
                $stmt = $this->pdo->prepare('INSERT INTO skills (name, category, resume_id) VALUES (:name, :category, :resume_id)');
                $stmt->execute([
                    ':name'       => $data['name'],
                    ':category'   => $data['category'],
                    ':resume_id'  => $data['resume_id']
                ]);
                return (int) $this->pdo->lastInsertId();
            } catch (PDOException $e) {
                // Log the details to fix later, then return -1 
                error_log("Skills Insert Error: " . $e->getMessage());
                return -1; 
            }
        }

        // Fetch Education
        public function getSkill(int $resid): array {
            try {
                $stmt = $this->pdo->prepare('SELECT * FROM skills WHERE resume_id = :resume_id ORDER BY created_at ASC');
                $stmt->execute([':resume_id' => $resid]);
                return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            } catch (PDOException $e) {
                error_log("Skills Fetch Error: " . $e->getMessage());
                return [];
            }
        }

        // Update Skill
        public function updateSkill(array $postData): int {
            try {
                $stmt = $this->pdo->prepare('UPDATE skills SET name = :name, category = :category WHERE id = :id AND resume_id = :resume_id');
                $stmt->execute([
                    'id'        => $postData['id'],
                    ':name'     => $postData['name'],
                    ':category' => $postData['category'],
                    ':resume_id'=> $postData['resume_id']
                ]);
                return (int) $stmt->rowCount();
            } catch (PDOException $e) {
                // Log the details to fix later, then return -1 
                error_log("Skills Update Error: " . $e->getMessage());
                return -1; 
            }
        }

        // Delete Skill
        public function deleteSkill(int $id, int $resid) {
            try {
                $stmt = $this->pdo->prepare('DELETE FROM skills WHERE id = :id AND resume_id = :resume_id');
                $stmt->execute([
                    ':id'=> $id,
                    ':resume_id'=> $resid
                ]);
                return (int) $stmt->rowCount();
            } catch (PDOException $e) {
                // Log the details to fix later, then return -1 
                error_log("Skills Delete Error: " . $e->getMessage());
                return -1; 
            }
        }
    }