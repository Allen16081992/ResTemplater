<?php 
    // Database Configuration Source
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'careerpunkdb');
    define('DB_USER', 'root');
    define('DB_PASSWORD', ''); 

    // CREATE TABLE IF NOT EXISTS `users` (
    //   `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    //   `username` VARCHAR(64) NULL,
    //   `email` VARCHAR(255) NOT NULL,
    //   `password_hash` VARCHAR(255) NOT NULL,
    //   `birthday` DATE NOT NULL,
    //   `registered` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    //   PRIMARY KEY (`id`),
    //   UNIQUE KEY `uq_users_email` (`email`),
    //   UNIQUE KEY `uq_users_username` (`username`)
    // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    // INSERT INTO `users` (`username`, `email`, `password_hash`, `birthday`) VALUES ('Hallohallo', 'hallohallo@gmail.com', '$argon2id$v=19$m=65536,t=2,p=1$Tm5KUG54Wks4MmI4MGVGZw$Q5eN9yv6TRCY5mA9svTu2w', '2026-01-25');