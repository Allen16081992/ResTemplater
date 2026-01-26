<?php // Database Configuration
    require_once "../src/define.db.php";

    final class Database {
        public static function connect(): PDO {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

            try {
                return new PDO($dsn, DB_USER, DB_PASSWORD, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                error_log("Failed to connect to the database: " . $e->getMessage());
                throw new RuntimeException("Failed to connect to the database. MySQL may have crashed.");
            }
        }
    }