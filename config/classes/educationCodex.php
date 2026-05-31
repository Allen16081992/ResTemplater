<?php 
    final class educationCodex {
        public function __construct(private PDO $pdo) {} 

        // ==========================================================
        // 1. EDUCATION
        // ========================================================== 

        // Create Education
        public function createEducation(array $postData): int {
            try {
                $stmt = $this->pdo->prepare(
                    'INSERT INTO education (program, school, start_date, end_date, summary, resume_id) 
                    VALUES (:program, :school, :start_date, :end_date, :summary, :resume_id)'
                );
                $stmt->execute([
                    ':program'   => $postData['program'],
                    ':school'    => $postData['school'],
                    ':start_date'=> $postData['start_date'],
                    ':end_date'  => $postData['end_date'],
                    ':summary'   => $postData['summary'],
                    ':resume_id' => $postData['resume_id']
                ]);
                return (int) $this->pdo->lastInsertId();
            } catch (PDOException $e) {
                // Log the details to fix later, then return -1 
                error_log("Education Insert Error: " . $e->getMessage());
                return -1; 
            }
        }

        // Update Education
        public function updateEducation(array $postData): int {
            try {
                $stmt = $this->pdo->prepare(
                    'UPDATE education SET program = :program, school = :school, start_date = :start_date, end_date = :end_date, summary = :summary WHERE id = :id AND resume_id = :resume_id'
                );
                $stmt->execute([
                    ':id'        => $postData['id'],
                    ':program'   => $postData['program'],
                    ':school'    => $postData['school'],
                    ':start_date'=> $postData['start_date'],
                    ':end_date'  => $postData['end_date'],
                    ':summary'   => $postData['summary'],
                    ':resume_id' => $postData['resume_id']
                ]);
                return (int) $stmt->rowCount();
            } catch (PDOException $e) {
                // Log the details to fix later, then return -1 
                error_log("Education Update Error: " . $e->getMessage());
                return -1; 
            }
        }

        // Delete Education
        public function deleteEducation(int $edu, int $resid) {
            try {
                $stmt = $this->pdo->prepare('DELETE FROM education WHERE id = :id AND resume_id = :resume_id');
                $stmt->execute([
                    ':id'=> $edu,
                    ':resume_id'=> $resid
                ]);
                return $stmt->rowCount();
            } catch (PDOException $e) {
                // Log the details to fix later, then return -1
                error_log("Education Delete Error: " . $e->getMessage());
                return -1;
            }
        }

        // ==========================================================
        // 2. BULLET POINTS
        // ========================================================== 

        // Create Education_Bulletpoint
        public function createBulletpoint(string $desc, ?int $sort, int $edu): bool {
            try {
                $stmt = $this->pdo->prepare('INSERT INTO education_bullets (text, sort_order, education_id) VALUES (:text, :sort, :education_id)');
                $stmt->execute([
                    ':text' => $desc,
                    ':sort' => $sort,
                    ':education_id'=> $edu
                ]);
                return (int) $this->pdo->lastInsertId();
            } catch (PDOException $e) {
                // Log the details to fix later, then return -1
                error_log("Exducation Bulletpoint Insert Error: " . $e->getMessage());
                return -1;
            }
        }
    }