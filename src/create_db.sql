    CREATE TABLE IF NOT EXISTS `users` (
      `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
      `username` VARCHAR(100) NULL,
      `email` VARCHAR(255) NOT NULL,
      `password_hash` VARCHAR(255) NOT NULL,
      `birth_date`      DATE NULL,
      `created_at`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY uq_accounts_email (email)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS `contacts` (
    id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    firstname      VARCHAR(100) NOT NULL,
    lastname       VARCHAR(100) NOT NULL,
    phone          VARCHAR(32) NULL,
    city           VARCHAR(120) NULL,
    country        VARCHAR(120) NULL,
    postalcode     VARCHAR(20) NULL,
    image_url      VARCHAR(2048) NULL,
    user_id        INT UNSIGNED NOT NULL,
    created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),

    -- 1:1 relationship (each user_id can appear only once in contacts)
    UNIQUE KEY uq_contacts_user_id (user_id),

    -- index for FK lookups (MySQL will often create it implicitly, but explicit is fine)
    KEY idx_contacts_user_id (user_id),

    CONSTRAINT fk_contacts_accounts
        FOREIGN KEY (user_id) REFERENCES accounts(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    INSERT INTO `users` (`username`, `email`, `password_hash`, `birthday`) VALUES ('Hallohallo', 'hallohallo@gmail.com', '$argon2id$v=19$m=65536,t=2,p=1$Tm5KUG54Wks4MmI4MGVGZw$Q5eN9yv6TRCY5mA9svTu2w', '2026-01-25');