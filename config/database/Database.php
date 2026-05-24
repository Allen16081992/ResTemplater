<?php // v2 Database Configuration
    require_once __DIR__ . '/../../src/define.db.php';

    final class Database {
        public static function connect(): PDO {
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s',
                DB_HOST, 
                DB_NAME, 
                DB_CHARSET
            );

            try {
                return new PDO($dsn, DB_USER, DB_PASSWORD, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::ATTR_TIMEOUT            => 5
                ]);
            } catch (PDOException $e) {
                error_log("Failed to connect to the database: " . $e->getMessage());
                throw new RuntimeException(
                    "Failed to connect to the database. MySQL may have crashed."
                );
            }
        }
    }

    // Before hosting
    // enable oldForm('email') in section_login.php