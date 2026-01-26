<?php require_once '../database/v2_db.php';

    final class loginCodex {
        public function __construct(private PDO $pdo) {}

        public function getUser(string $email): array|false {
            $stmt = $this->pdo->prepare('SELECT userID, fullname, email, password_hash FROM users WHERE email = :email;');
            $stmt->execute(['email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

    final class signupCodex {
        public function __construct(private PDO $pdo) {}

        public function existUser(string $email, ?string $username): bool {
            if ($username === null || $username === '') {
                $stmt = $this->pdo->prepare('SELECT 1 FROM users WHERE email = :email LIMIT 1;');
                $stmt->execute(['email' => $email]);
                return $stmt->fetchColumn() !== false;
            }

            $stmt = $this->pdo->prepare('SELECT 1 FROM users WHERE email = :email OR username = :username LIMIT 1;');
            $stmt->execute([
                'email'    => $email,
                'username' => $username,
            ]);
            return $stmt->fetchColumn() !== false;
        }

        public function setUser(array $postData): int {
            $options = [
                'memory_cost' => 65536,  // 64 MiB (in KiB)
                'time_cost' => 2,       // 4 iterations
                'threads' => 1         // 4 parallel threads
            ]; 
            $hashSigil = password_hash($postData['pwd'], PASSWORD_ARGON2ID, $options); 

            $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password_hash, birthday) VALUES (:username, :email, :password_hash, :birthday)");
            $stmt->execute([
                'username'       => ($postData['username'] ?? '') !== '' ? $postData['username'] : null,
                ':email'         => $postData['email'],
                ':password_hash' => $hashSigil,
                ':birthday'      => $postData['date'], // 'YYYY-MM-DD'
            ]);
            return (int) $this->pdo->lastInsertId();
        }
    }