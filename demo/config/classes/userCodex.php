<?php // Load PHP Files
    require_once __DIR__ . '/../mixedGrimoire.php';

    final class userCodex {
        public function __construct(private PDO $pdo) {}

        // ==========================================================
        // 1. LOGIN CODEX
        // ========================================================== 
        public function findByEmail(string $email): array|false {
            $stmt = $this->pdo->prepare('SELECT id, password_hash FROM accounts WHERE email = :email LIMIT 1');
            $stmt->execute([':email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // ==========================================================
        // 2. SIGNUP CODEX
        // ========================================================== 
        public function emailExists(string $email): bool {
            $stmt = $this->pdo->prepare('SELECT 1 FROM accounts WHERE email = :email LIMIT 1');
            $stmt->execute([':email' => $email]);
            return $stmt->fetchColumn() !== false;
        }

        public function createAccount(array $postData): int {
            $hashSigil = mixedGrimoire::pwHasher($postData['pwd']);
            $stmt = $this->pdo->prepare('INSERT INTO accounts (email, password_hash, birth_date) VALUES (:email, :hashSigil, :birth_date)');
            $stmt->execute([
                ':email'      => $postData['email'],
                ':hashSigil'  => $hashSigil,
                ':birth_date' => $postData['date'] // 'YYYY-MM-DD'
            ]);
            return (int)$this->pdo->lastInsertId();
        }

        // ==========================================================
        // 3. USER CODEX
        // ========================================================== 
        public function fetchAccount(int $uid): array|false {
            $stmt = $this->pdo->prepare('SELECT email FROM accounts WHERE id = :id LIMIT 1');
            $stmt->execute([':id' => $uid]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function updateEmail(int $uid, string $email): int {
            $stmt = $this->pdo->prepare('UPDATE accounts SET email = :email WHERE id = :id LIMIT 1');
            $stmt->execute([':email' => $email, ':id' => $uid]);
            return $stmt->rowCount();
        }

        public function updateHash(int $uid, string $pwdHash): int {
            $stmt = $this->pdo->prepare('UPDATE accounts SET password_hash = :hashSigil WHERE id = :id LIMIT 1');
            $stmt->execute([':hashSigil' => $pwdHash, ':id' => $uid]);
            return $stmt->rowCount();
        }

        public function deleteAccount(int $uid): int {
            $stmt = $this->pdo->prepare('DELETE FROM accounts WHERE id = :id LIMIT 1');
            $stmt->execute([':id' => $uid]);
            return $stmt->rowCount();
        }

        // ==========================================================
        // 3. CONTACT CODEX
        // ========================================================== 
        public function fetchRow(int $uid): array|false {
            $stmt = $this->pdo->prepare('SELECT user_id FROM contacts WHERE user_id = :user_id LIMIT 1');
            $stmt->execute([':user_id' => $uid]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function fetchContact(int $uid): array|false {
            $stmt = $this->pdo->prepare('SELECT fullname, phone, city, country FROM contacts WHERE user_id = :user_id LIMIT 1');
            $stmt->execute([':user_id' => $uid]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function createContact(array $postData): int {
            $stmt = $this->pdo->prepare('INSERT INTO contacts (fullname, phone, city, country, user_id) VALUES (:fullname, :phone, :city, :country, :user_id)');
            $stmt->execute([
                ':fullname' => $postData['fullname'],
                ':phone'     => $postData['phone'],
                ':city'      => $postData['city'],
                ':country'   => $postData['country'],
                ':user_id'   => $postData['user_id']
            ]);
            return (int) $this->pdo->lastInsertId();
        }
        
        public function updateContact(array $postData): int {
            $stmt = $this->pdo->prepare('UPDATE contacts SET fullname = :fullname, phone = :phone, city = :city, country = :country WHERE user_id = :user_id LIMIT 1');
            $stmt->execute([
                ':fullname' => $postData['fullname'],
                ':phone'     => $postData['phone'],
                ':city'      => $postData['city'],
                ':country'   => $postData['country'],
                ':user_id'   => $postData['user_id']
            ]);
            return $stmt->rowCount();
        }

        public function deleteContact(int $uid): int {
            $stmt = $this->pdo->prepare('DELETE FROM contacts WHERE user_id = :user_id LIMIT 1');
            $stmt->execute([':user_id' => $uid]);
            return $stmt->rowCount();
        }
    }