<?php
    final class projectCodex {
        public function __construct(private PDO $pdo) {} 

        // Create Project
        public function createProject(array $postData): int {
            $stmt = $this->pdo->prepare('INSERT INTO projects (title, role, summary, resume_id) VALUES (:title, :role, :summary, :resume_id)');
            $stmt->execute([
                ':title'    => $postData['title'],
                ':role'     => $postData['role'],
                ':summary'  => $postData['summary'],
                ':resume_id'=> $postData['resume_id']
            ]);
            return (int) $this->pdo->lastInsertId();
        }

        // Update Project
        public function updateProject(array $postData): int {
            $stmt = $this->pdo->prepare('UPDATE projects SET title = :title, role = :role, summary = :summary WHERE resume_id = :resume_id');
            $stmt->execute([
                ':title'    => $postData['title'],
                ':role'     => $postData['role'],
                ':summary'  => $postData['summary'],
                ':resume_id'=> $postData['resume_id']
            ]);
            return (int) $this->pdo->lastInsertId();
        }

        // Delete Project
        public function deleteProject(int $exp, int $resume) {
            $stmt = $this->pdo->prepare('DELETE FROM projects WHERE project_id = :project_id AND resume_id = :resume_id');
            $stmt->execute([
                ':project_id'=> $exp,
                ':resume_id'=> $resume
            ]);
            return $stmt->rowCount() > 0;
        }
    }