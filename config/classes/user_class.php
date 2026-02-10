<?php require_once '../database/v2_db.php';

    final class loginCodex {
        public function __construct(private PDO $pdo) {}

        // Fetch User info
        public function getUser(string $email): array|false {
            $stmt = $this->pdo->prepare('SELECT id, username, email, password_hash FROM accounts WHERE email = :email LIMIT 1;');
            $stmt->execute(['email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

    final class signupCodex {
        public function __construct(private PDO $pdo) {}

        // Check User Existence
        public function lookupUser(string $email): bool {
            $stmt = $this->pdo->prepare('SELECT 1 FROM accounts WHERE email = :email LIMIT 1');
            $stmt->execute(['email' => $email]);
            return $stmt->fetchColumn() !== false;
        }

        // Create New User
        public function setUser(array $postData): int {
            $options = [
                'memory_cost' => 65536,  // 64 MiB (in KiB)
                'time_cost' => 2,       // 4 iterations
                'threads' => 1         // 4 parallel threads
            ]; 
            $hashSigil = password_hash($postData['pwd'], PASSWORD_ARGON2ID, $options); 

            $stmt = $this->pdo->prepare("INSERT INTO accounts (email, password_hash, birth_date) VALUES (:email, :hashSigil, :birth_date)");
            $stmt->execute([
                ':email'         => $postData['email'],
                ':hashSigil' => $hashSigil,
                ':birth_date'      => $postData['date'], // 'YYYY-MM-DD'
            ]);
            return (int) $this->pdo->lastInsertId();
        }
    }

    final class userCodex {
        public function __construct(private PDO $pdo) {}

        // Search Email
        public function lookupEmail(string $email, $userID): bool {
            $stmt = $this->pdo->prepare('SELECT 1 FROM users WHERE email = :email AND id != :id LIMIT 1');
            $stmt->execute(['email' => $email, 'user_id' => $userID]);
            return $stmt->fetchColumn() !== false;
        }

        // Update Account
        public function updateAccount(string $email, $pwd, $userID): bool {
            $options = [
                'memory_cost' => 65536,  // 64 MiB (in KiB)
                'time_cost' => 2,       // 4 iterations
                'threads' => 1         // 4 parallel threads
            ]; 
            $hashSigil = password_hash($pwd, PASSWORD_ARGON2ID, $options); 

            $stmt = $this->pdo->prepare("UPDATE accounts SET email = :email, password_hash = :pwd WHERE user_id = :user_id;");
            $stmt->execute([
                ':email'     => $email,
                ':hashSigil' => $hashSigil,
                ':user_id'   => $userID
            ]);
            return (int) $this->pdo->lastInsertId();
        }

        // Delete Account
        public function wipeAccount(array $postData): void {
            $stmt = $this->pdo->prepare("DELETE FROM accounts WHERE email = :email AND user_id = :user_id");
            $stmt->execute([
                ':email' => $postData['email'],
                ':user_id' => $postData['user_id']
            ]);
        }

        // Search Contact / Personalia
        public function getContact(string $uid): array|false {
            $stmt = $this->pdo->prepare('SELECT firstname, lastname, phone, city, postalcode, country FROM contacts WHERE id = :id;');
            $stmt->execute([':id' => $uid]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // Create Contact / Personalia
        public function setContact(array $postData): int {
            $stmt = $this->pdo->prepare("INSERT INTO contacts (firstname, lastname, phone, city, country, postalcode, user_id) VALUES (:firstname, :lastname, :phone, :city, :country, :postalcode, :user_id)");
            $stmt->execute([
                ':firstname' => $postData['firstname'],
                ':lastname'  => $postData['lastname'],
                ':phone'     => $postData['phone'],
                ':city'      => $postData['city'],
                ':country'   => $postData['country'],
                ':postalcode'=> $postData['postalcode'],
                ':user_id'   => $postData['user_id']
            ]);
            return (int) $this->pdo->lastInsertId();
        }
        
        // Update Contact / Personalia
        public function updateContact(array $postData): int {
            $stmt = $this->pdo->prepare("UPDATE contacts SET firstname = :firstname, lastname = :lastname, phone = :phone, city = :city, country = :country, postalcode = :postalcode WHERE user_id = :user_id;");
            $stmt->execute([
                ':firstname' => $postData['firstname'],
                ':lastname'  => $postData['lastname'],
                ':phone'     => $postData['phone'],
                ':city'      => $postData['city'],
                ':country'   => $postData['country'],
                ':postalcode'=> $postData['postalcode'],
                ':user_id'   => $postData['user_id']
            ]);
            return (int) $this->pdo->lastInsertId();
        }
    }