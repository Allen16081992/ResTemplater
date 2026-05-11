    CREATE DATABASE IF NOT EXISTS `careerwitch_db`;
    USE `careerwitch_db`;
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

    CREATE TABLE IF NOT EXISTS `contacts` (
    id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    fullname       VARCHAR(100) NOT NULL,
    phone          VARCHAR(32) NULL,
    city           VARCHAR(120) NULL,
    country        VARCHAR(120) NULL,
    image_url      VARCHAR(2048) NULL,
    image_active   INT NULL,
    created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    user_id        INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),

    UNIQUE KEY uq_pers_accounts (user_id),

    CONSTRAINT fk_pers_accounts
        FOREIGN KEY (user_id) REFERENCES accounts(id)
        ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    INSERT INTO `accounts` (`id`, `email`, `password_hash`, `birth_date`) VALUES (1, 'hallohallo@gmail.com', '$argon2id$v=19$m=65536,t=2,p=1$Tm5KUG54Wks4MmI4MGVGZw$Q5eN9yv6TRCY5mA9svTu2w', '1998-01-25');
    INSERT INTO `contacts` (`fullname`, `phone`, `city`, `country`, `user_id`) VALUES ('Jade Greenhill', '634177567', 'Wieringen', 'Netherlands', 1);
    ALTER TABLE accounts AUTO_INCREMENT = 2;
    ALTER TABLE contacts AUTO_INCREMENT = 2;

    CREATE TABLE IF NOT EXISTS `resumes` (
        id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
        title VARCHAR(180) NOT NULL,
        headline VARCHAR(850) NULL,
        created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        user_id      INT UNSIGNED NOT NULL,
        PRIMARY KEY (id),

        KEY idx_resume_user (user_id),

        CONSTRAINT fk_res_accounts
        FOREIGN KEY (user_id) REFERENCES accounts(id)
        ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    
    CREATE TABLE IF NOT EXISTS `experience` (
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
            FOREIGN KEY (resume_id) REFERENCES resumes(id)
            ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS `education` (
        id          INT UNSIGNED NOT NULL AUTO_INCREMENT,     
        program     VARCHAR(100) NOT NULL,
        school      VARCHAR(120) NOT NULL,
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
            FOREIGN KEY (resume_id) REFERENCES resumes(id)
            ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS `projects` (
        id          INT UNSIGNED NOT NULL AUTO_INCREMENT,     
        title       VARCHAR(100) NOT NULL, 
        role       VARCHAR(100) NOT NULL,
        summary VARCHAR(2048) NULL,
        sort_order  INT NOT NULL DEFAULT 0,
        resume_id   INT UNSIGNED NOT NULL,
        PRIMARY KEY (id),
        CONSTRAINT fk_proj_resume FOREIGN KEY (resume_id) REFERENCES resumes(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS `experience_bullets` (
        id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
        text        TEXT NOT NULL,
        sort_order  INT NOT NULL DEFAULT 0,
        created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        work_id     INT UNSIGNED NOT NULL,
        PRIMARY KEY (id),

        KEY idx_bullet_work_sort (work_id, sort_order),

    CONSTRAINT fk_bullet_work
        FOREIGN KEY (work_id) REFERENCES experience(id)
        ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS `education_bullets` (
        id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
        text        TEXT NOT NULL,
        sort_order  INT NOT NULL DEFAULT 0,
        created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        acad_id     INT UNSIGNED NOT NULL,
        PRIMARY KEY (id),

    KEY idx_bullet_acad_sort (acad_id, sort_order),

    CONSTRAINT fk_bullet_acad
        FOREIGN KEY (acad_id) REFERENCES education(id)
        ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS `project_bullets` (
        id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
        text        TEXT NOT NULL,
        sort_order  INT NOT NULL DEFAULT 0,
        project_id  INT UNSIGNED NOT NULL,
        resume_id   INT UNSIGNED NOT NULL,
        PRIMARY KEY (id),

        KEY uq_project_resume_sort (project_id, sort_order),

        CONSTRAINT fk_project_resume
            FOREIGN KEY (resume_id) REFERENCES resumes(id)
            ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS `skills` (
        id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
        name       VARCHAR(100) NOT NULL,
        category   ENUM('language','framework','tool','platform','other') NOT NULL DEFAULT 'other',
        level      ENUM('basic','intermediate','advanced') NULL,
        sort_order INT NOT NULL DEFAULT 0,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        resume_id  INT UNSIGNED NOT NULL,
        PRIMARY KEY (id),

        UNIQUE KEY uq_skill_resume_name (resume_id, name),

        CONSTRAINT fk_tech_resume
            FOREIGN KEY (resume_id) REFERENCES resumes(id)
            ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS `socials` (
        id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
        media_url      VARCHAR(500) NOT NULL,
        sort_order INT NOT NULL DEFAULT 0,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        resume_id  INT UNSIGNED NOT NULL,
        PRIMARY KEY (id),

        UNIQUE KEY uq_socials_resume_media (resume_id, media_url),

        CONSTRAINT fk_socials_resume
            FOREIGN KEY (resume_id) REFERENCES resumes(id)
            ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;