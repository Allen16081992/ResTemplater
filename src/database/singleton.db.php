<?php
    require_once "./config/define.db.php";

    class Database {
        private static $instance;
        private $pdo;

        // Instantiate a single PDO connection
        private function __construct() {
            try { 
                // Establish a PDO database connection
                $this->pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD,
                    // Prevent charset encoding injections.
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'")
                );
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                error_log("Failed to connect to the database: " . $e->getMessage(), 0);
                throw new Exception("Failed to connect to the database. MySQL may have crashed.");
            }
        }

        // (Singleton pattern) global access point
        public static function getInstance() {
            if (!self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        // Return the PDO instance
        public function connect() {
            return $this->pdo;
        }
    }