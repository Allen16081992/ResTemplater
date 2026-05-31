<?php
    final class projectCodex {
        public function __construct(private PDO $pdo) {} 

        // ==========================================================
        // 1. PROJECTS
        // ========================================================== 

        // Create Project
        public function createProject(array $postData): int {
            try {
                $stmt = $this->pdo->prepare('INSERT INTO projects (title, role, summary, resume_id) VALUES (:title, :role, :summary, :resume_id)');
                $stmt->execute([
                    ':title'    => $postData['title'],
                    ':role'     => $postData['role'],
                    ':summary'  => $postData['summary'],
                    ':resume_id'=> $postData['resume_id']
                ]);
                return (int) $this->pdo->lastInsertId();
            } catch (PDOException $e) {
                // Log the details to fix later, then return -1 
                error_log("Projects Insert Error: " . $e->getMessage());
                return -1; 
            }
        }

        // Update Project
        public function updateProject(array $postData): int {
            try {
                $stmt = $this->pdo->prepare('UPDATE projects SET title = :title, role = :role, summary = :summary WHERE id = :id AND resume_id = :resume_id');
                $stmt->execute([
                    ':id'       => $postData['id'],
                    ':title'    => $postData['title'],
                    ':role'     => $postData['role'],
                    ':summary'  => $postData['summary'],
                    ':resume_id'=> $postData['resume_id']
                ]);
                return (int) $stmt->rowCount();
            } catch (PDOException $e) {
                // Log the details to fix later, then return -1 
                error_log("Projects Update Error: " . $e->getMessage());
                return -1; 
            }
        }

        // Delete Project
        public function deleteProject(int $pro, int $resid) {
            try {
                $stmt = $this->pdo->prepare('DELETE FROM projects WHERE id = :id AND resume_id = :resume_id');
                $stmt->execute([
                    ':id'=> $pro,
                    ':resume_id'=> $resid
                ]);
                return $stmt->rowCount();
            } catch (PDOException $e) {
                // Log the details to fix later, then return -1 
                error_log("Projects Delete Error: " . $e->getMessage());
                return -1; 
            }
        }

        // ==========================================================
        // 2. BULLET POINTS
        // ========================================================== 

        // Create Projects_Bulletpoint
        public function createBulletpoint(string $desc, ?int $sort, int $pro): bool {
            try {
                $stmt = $this->pdo->prepare('INSERT INTO projects_bullets (text, sort_order, projects_id) VALUES (:text, :sort, :projects_id)');
                $stmt->execute([
                    ':text' => $desc,
                    ':sort' => $sort,
                    ':projects_id'=> $pro
                ]);
                return (int) $this->pdo->lastInsertId();
            } catch (PDOException $e) {
                // Log the details to fix later, then return -1
                error_log("Exducation Bulletpoint Insert Error: " . $e->getMessage());
                return -1;
            }
        }
    }