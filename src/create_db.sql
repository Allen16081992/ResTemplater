    CREATE TABLE IF NOT EXISTS `accounts` (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    email         VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    birth_date    DATE NULL,
    created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),

    UNIQUE KEY uq_accounts_email (email)

    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS `personal` (
    id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    firstname      VARCHAR(100) NOT NULL,
    lastname       VARCHAR(100) NOT NULL,
    phone          VARCHAR(32) NULL,
    city           VARCHAR(120) NULL,
    country        VARCHAR(120) NULL,
    postalcode     VARCHAR(20) NULL,
    image_url      VARCHAR(2048) NULL,
    created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    user_id        INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),

    UNIQUE KEY uq_pers_accounts (user_id),

    CONSTRAINT fk_pers_accounts
        FOREIGN KEY (user_id) REFERENCES accounts(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    INSERT INTO `accounts` (`email`, `password_hash`, `birth_date`) VALUES ('hallohallo@gmail.com', '$argon2id$v=19$m=65536,t=2,p=1$Tm5KUG54Wks4MmI4MGVGZw$Q5eN9yv6TRCY5mA9svTu2w', '2026-01-25');

    CREATE TABLE IF NOT EXISTS `resume` (
        id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
        resume_title VARCHAR(100) NOT NULL,
        user_id      INT UNSIGNED NOT NULL,
        PRIMARY KEY (id),

        KEY idx_resume_user (user_id),

        CONSTRAINT fk_res_accounts
            FOREIGN KEY (user_id) REFERENCES accounts(id)
            ON DELETE CASCADE
            ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    
    CREATE TABLE IF NOT EXISTS `work_experience` (
        id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
        title       VARCHAR(100) NOT NULL,
        employer    VARCHAR(120) NOT NULL,
        start_date  DATE NOT NULL,
        end_date    DATE NULL,
        summary     VARCHAR(2048) NULL,
        sort_order  INT NOT NULL DEFAULT 0,
        created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        resume_id   INT UNSIGNED NOT NULL,
        PRIMARY KEY (id),

        KEY idx_work_resume_sort (resume_id, sort_order),

        CONSTRAINT fk_work_resume
            FOREIGN KEY (resume_id) REFERENCES resume(id)
            ON DELETE CASCADE
            ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS `education` (
        id          INT UNSIGNED NOT NULL AUTO_INCREMENT,     
        title       VARCHAR(100) NOT NULL,
        institute   VARCHAR(120) NOT NULL,
        start_date  DATE NOT NULL,
        end_date    DATE NULL,
        summary     VARCHAR(2048) NULL,
        sort_order  INT NOT NULL DEFAULT 0,
        created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        resume_id   INT UNSIGNED NOT NULL,
        PRIMARY KEY (id),

        KEY idx_acad_resume_sort (resume_id, sort_order),

        CONSTRAINT fk_acad_resume
            FOREIGN KEY (resume_id) REFERENCES resume(id)
            ON DELETE CASCADE
            ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS `work_experience_bullets` (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    text          TEXT NOT NULL,
    sort_order    INT NOT NULL DEFAULT 0,
    work_id       INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),

    KEY idx_bullet_work_sort (work_id, sort_order),

    CONSTRAINT fk_bullet_work
        FOREIGN KEY (work_id) REFERENCES work_experience(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS `education_bullets` (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    text          TEXT NOT NULL,
    sort_order    INT NOT NULL DEFAULT 0,
    acad_id       INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),

    KEY idx_bullet_acad_sort (acad_id, sort_order),

    CONSTRAINT fk_bullet_acad
        FOREIGN KEY (acad_id) REFERENCES education(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS `technical_skills` (
        id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
        name       VARCHAR(100) NOT NULL,
        category   ENUM('language','framework','tool','platform','other') NOT NULL DEFAULT 'other',
        level      ENUM('basic','intermediate','advanced') NULL,
        sort_order INT NOT NULL DEFAULT 0,
        resume_id  INT UNSIGNED NOT NULL,
        PRIMARY KEY (id),

        UNIQUE KEY uq_skill_resume_name (resume_id, name),

        CONSTRAINT fk_tech_resume
            FOREIGN KEY (resume_id) REFERENCES resume(id)
            ON DELETE CASCADE
            ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;