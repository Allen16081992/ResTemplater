<?php 
    // Database Definers
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'cd134616_careerwitch_db');
    define('DB_CHARSET', 'utf8mb4');
    define('DB_USER', 'cd134616_careerwitch_db');
    define('DB_PASSWORD', 't6JAW96JTddDRHhuThg2');

    // CREATE TABLE IF NOT EXISTS `accounts` (
    // id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    // email         VARCHAR(255) NOT NULL,
    // password_hash VARCHAR(255) NOT NULL,
    // birth_date    DATE NULL,
    // created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    // updated_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    // PRIMARY KEY (id),

    // UNIQUE KEY uq_accounts_email (email)

    // ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

    // CREATE TABLE IF NOT EXISTS `contacts` (
    // id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    // fullname       VARCHAR(100) NOT NULL,
    // phone          VARCHAR(32) NULL,
    // city           VARCHAR(120) NULL,
    // country        VARCHAR(120) NULL,
    // image_url      VARCHAR(2048) NULL,
    // image_active   INT NULL,
    // created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    // updated_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    // user_id        INT UNSIGNED NOT NULL,
    // PRIMARY KEY (id),

    // UNIQUE KEY uq_accounts_user (user_id),

    // CONSTRAINT fk_accounts_user
    //     FOREIGN KEY (user_id) REFERENCES accounts(id)
    //     ON DELETE CASCADE
    // ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

    // // INSERT INTO `accounts` (`email`, `password_hash`, `birth_date`) VALUES ('hallohallo@gmail.com', '$argon2id$v=19$m=65536,t=2,p=1$Tm5KUG54Wks4MmI4MGVGZw$Q5eN9yv6TRCY5mA9svTu2w', '1998-01-25');

    // CREATE TABLE IF NOT EXISTS `resumes` (
    //     id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    //     title VARCHAR(180) NOT NULL,
    //     summary VARCHAR(2048) NULL,
    //     created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    //     updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    //     user_id      INT UNSIGNED NOT NULL,
    //     PRIMARY KEY (id),

    //     KEY idx_resume_user (user_id),

    //     CONSTRAINT fk_resumes_user
    //     FOREIGN KEY (user_id) REFERENCES accounts(id)
    //     ON DELETE CASCADE
    // ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
    
    // CREATE TABLE IF NOT EXISTS `experience` (
    //     id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    //     title       VARCHAR(100) NOT NULL,
    //     employer    VARCHAR(120) NOT NULL,
    //     start_date  DATE NOT NULL,
    //     end_date    DATE NULL,
    //     summary     VARCHAR(2048) NULL,
    //     sort_order  INT NOT NULL DEFAULT 0,
    //     created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    //     updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    //     resume_id   INT UNSIGNED NOT NULL,
    //     PRIMARY KEY (id),

    //     KEY uq_exper_resume_sort (resume_id, sort_order),

    //     CONSTRAINT fk_exper_resume
    //         FOREIGN KEY (resume_id) REFERENCES resumes(id)
    //         ON DELETE CASCADE
    // ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

    // CREATE TABLE IF NOT EXISTS `education` (
    //     id          INT UNSIGNED NOT NULL AUTO_INCREMENT,     
    //     program     VARCHAR(100) NOT NULL,
    //     school      VARCHAR(120) NOT NULL,
    //     start_date  DATE NOT NULL,
    //     end_date    DATE NULL,
    //     summary     VARCHAR(2048) NULL,
    //     sort_order  INT NOT NULL DEFAULT 0,
    //     created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    //     updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    //     resume_id   INT UNSIGNED NOT NULL,
    //     PRIMARY KEY (id),

    //     KEY uq_edu_resume_sort (resume_id, sort_order),

    //     CONSTRAINT fk_edu_resume
    //         FOREIGN KEY (resume_id) REFERENCES resumes(id)
    //         ON DELETE CASCADE
    // ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

    // CREATE TABLE IF NOT EXISTS `experience_bullets` (
    //     id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    //     text        TEXT NOT NULL,
    //     sort_order  INT NOT NULL DEFAULT 0,
    //     created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    //     updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    //     experience_id     INT UNSIGNED NOT NULL,
    //     PRIMARY KEY (id),

    //     KEY uq_bullet_exper_sort (experience_id, sort_order),

    // CONSTRAINT fk_bullet_exper
    //     FOREIGN KEY (experience_id) REFERENCES experience(id)
    //     ON DELETE CASCADE
    // ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

    // CREATE TABLE IF NOT EXISTS `education_bullets` (
    //     id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    //     text        TEXT NOT NULL,
    //     sort_order  INT NOT NULL DEFAULT 0,
    //     created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    //     updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    //     education_id     INT UNSIGNED NOT NULL,
    //     PRIMARY KEY (id),

    // KEY uq_bullet_edu_sort (education_id, sort_order),

    // CONSTRAINT fk_bullet_edu
    //     FOREIGN KEY (education_id) REFERENCES education(id)
    //     ON DELETE CASCADE
    // ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

    // CREATE TABLE IF NOT EXISTS `skills` (
    //     id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    //     name       VARCHAR(100) NOT NULL,
    //     category   VARCHAR(100) NOT NULL DEFAULT 'other',
    //     level      ENUM('basic','intermediate','advanced') NULL,
    //     sort_order INT NOT NULL DEFAULT 0,
    //     created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    //     updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    //     resume_id  INT UNSIGNED NOT NULL,
    //     PRIMARY KEY (id),

    //     UNIQUE KEY uq_skills_resume_name (resume_id, name),

    //     CONSTRAINT fk_skills_resume
    //         FOREIGN KEY (resume_id) REFERENCES resumes(id)
    //         ON DELETE CASCADE
    // ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

    // CREATE TABLE IF NOT EXISTS `socials` (
    //     id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    //     media_url      VARCHAR(500) NOT NULL,
    //     sort_order INT NOT NULL DEFAULT 0,
    //     created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    //     updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    //     resume_id  INT UNSIGNED NOT NULL,
    //     PRIMARY KEY (id),

    //     UNIQUE KEY uq_socials_resume_media (resume_id, media_url),

    //     CONSTRAINT fk_socials_resume
    //         FOREIGN KEY (resume_id) REFERENCES resumes(id)
    //         ON DELETE CASCADE
    // ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

    // CREATE TABLE IF NOT EXISTS `projects` (
    //     id          INT UNSIGNED NOT NULL AUTO_INCREMENT,     
    //     title       VARCHAR(100) NOT NULL, 
    //     role        VARCHAR(100) NOT NULL,
    //     summary     VARCHAR(2048) NULL,
    //     sort_order  INT NOT NULL DEFAULT 0,
    //     resume_id   INT UNSIGNED NOT NULL,
    //     PRIMARY KEY (id),
    //     CONSTRAINT fk_proj_resume FOREIGN KEY (resume_id) REFERENCES resumes(id) ON DELETE CASCADE
    // ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

    // CREATE TABLE IF NOT EXISTS `projects_bullets` (
    //     id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    //     text        TEXT NOT NULL,
    //     sort_order  INT NOT NULL DEFAULT 0,
    //     project_id  INT UNSIGNED NOT NULL,
    //     PRIMARY KEY (id),

    //     KEY uq_proj_resume_sort (project_id, sort_order),

    //     CONSTRAINT fk_proj_resume
    //         FOREIGN KEY (resume_id) REFERENCES projects(id)
    //         ON DELETE CASCADE
    // ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


    //     PaperWitch
    // I want to create a modern Resume designer platform for students and young professionals.
    // # Features:
    // - Workspace: area for the editor
    // - Experience: add/remove their job experience
    // - Education: add/remove their college courses
    // - Skills: add/remove skills
    // - Contact: personal details
    // - Users: who can create and save multiple resumes, change its layouts, structure, login and sign up.
    // - Sections: inside the Hybrid-SPA that act as webpages
    // - Build: with PHP and Javascript under the hood, lightweight, aimed at raw performance, ease of use, and no suprise-signup/paywalls.
    // - Play: the build-in Side-scroller. As they play, fill in question clouds in npc/for and have a resume at the end of the level (or a part of it.)
    // ---------+++------------
    // snake_case only / ERD
    // -------------------------