<?php
    final class experienceCodex {
        public function __construct(private PDO $pdo) {} 

        // ==========================================================
        // 1. EXPERIENCE
        // ========================================================== 

        // Create Experience
        public function createExperience(array $postData): int {
            try {
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
            } catch (PDOException $e) {
                // Log the details to fix later, then return -1 
                error_log("Experience Insert Error: " . $e->getMessage());
                return -1; 
            }
        }

        // Fetch Experience
        public function getExperience(int $resid): array {
            try {
                $stmt = $this->pdo->prepare('SELECT * FROM experience WHERE resume_id = :resume_id ORDER BY start_date DESC');
                $stmt->execute([':resume_id' => $resid]);
                return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            } catch (PDOException $e) {
                error_log("Experience Fetch Error: " . $e->getMessage());
                return [];
            }
        }

        // Update Experience
        public function updateExperience(array $postData): int {
            try {
                $stmt = $this->pdo->prepare(
                    'UPDATE experience SET title = :title, employer = :employer, start_date = :start_date, end_date = :end_date, summary = :summary WHERE id = :id AND resume_id = :resume_id'
                );
                $stmt->execute([
                    ':id'        => $postData['id'],
                    ':title'     => $postData['title'],
                    ':employer'  => $postData['employer'],
                    ':start_date'=> $postData['start_date'],
                    ':end_date'  => $postData['end_date'],
                    ':summary'   => $postData['summary'],
                    ':resume_id' => $postData['resume_id']
                ]);
                return (int) $stmt->rowCount();
            } catch (PDOException $e) {
                // Log the details to fix later, then return -1 
                error_log("Experience Update Error: " . $e->getMessage());
                return -1; 
            }
        }

        // Delete Experience
        public function deleteExperience(int $exp, int $resid) {
            try {
                $stmt = $this->pdo->prepare('DELETE FROM experience WHERE id = :exp_id AND resume_id = :resume_id');
                $stmt->execute([
                    ':exp_id'=> $exp,
                    ':resume_id'=> $resid
                ]);
                return $stmt->rowCount();
            } catch (PDOException $e) {
                // Log the details to fix later, then return -1
                error_log("Experience Delete Error: " . $e->getMessage());
                return -1;
            }
        }

        // ==========================================================
        // 2. BULLET POINTS
        // ========================================================== 

        // Create Experience_Bulletpoint
        public function createBulletpoint(string $desc, ?int $sort, int $exp): bool {
            try {
                $stmt = $this->pdo->prepare('INSERT INTO experience_bullets (text, sort_order, experience_id) VALUES (:text, :sort, :experience_id)');
                $stmt->execute([
                    ':text' => $desc,
                    ':sort' => $sort,
                    ':experience_id'=> $exp
                ]);
                return (int) $this->pdo->lastInsertId();
            } catch (PDOException $e) {
                // Log the details to fix later, then return -1
                error_log("Experience Bulletpoint Insert Error: " . $e->getMessage());
                return -1;
            }
        }

        // Fetch all Bullets for a specific Resume (Joined for efficiency)
        public function getBulletpoints(int $resid): array {
            try {
                $stmt = $this->pdo->prepare('
                    SELECT b.* FROM experience_bullets b
                    JOIN experience e ON b.experience_id = e.id
                    WHERE e.resume_id = :resume_id
                    ORDER BY e.id, b.sort_order
                ');
                $stmt->execute([':resume_id' => $resid]);
                return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            } catch (PDOException $e) {
                error_log("Experience Bulletpoint Fetch Error: " . $e->getMessage());
                return [];
            }
        }
    }