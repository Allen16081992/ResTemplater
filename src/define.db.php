<?php 
    // Database Configuration Source
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'careerpunkdb');
    define('DB_USER', 'root');
    define('DB_PASSWORD', ''); 

    // -- MySQL / MariaDB. Use InnoDB to support foreign keys
    // -- DB SQL Script, paste & run ready
    
    // CREATE TABLE IF NOT EXISTS `accounts` (
    // id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    // email         VARCHAR(255) NOT NULL,
    // password_hash VARCHAR(255) NOT NULL,
    // birth_date    DATE NULL,
    // created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    // updated_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    // PRIMARY KEY (id),

    // UNIQUE KEY uq_accounts_email (email)

    // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    // CREATE TABLE IF NOT EXISTS `personal` (
    // id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    // firstname      VARCHAR(100) NOT NULL,
    // lastname       VARCHAR(100) NOT NULL,
    // phone          VARCHAR(32) NULL,
    // city           VARCHAR(120) NULL,
    // country        VARCHAR(120) NULL,
    // postalcode     VARCHAR(20) NULL,
    // image_url      VARCHAR(2048) NULL,
    // created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    // updated_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    // user_id        INT UNSIGNED NOT NULL,
    // PRIMARY KEY (id),

    // UNIQUE KEY uq_pers_accounts (user_id),

    // CONSTRAINT fk_pers_accounts
    //     FOREIGN KEY (user_id) REFERENCES accounts(id)
    //     ON DELETE CASCADE
    //     ON UPDATE CASCADE
    // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    // INSERT INTO `accounts` (`email`, `password_hash`, `birth_date`) VALUES ('hallohallo@gmail.com', '$argon2id$v=19$m=65536,t=2,p=1$Tm5KUG54Wks4MmI4MGVGZw$Q5eN9yv6TRCY5mA9svTu2w', '2026-01-25');

    // CREATE TABLE IF NOT EXISTS `resume` (
    //     id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    //     resume_title VARCHAR(100) NOT NULL,
    //     user_id      INT UNSIGNED NOT NULL,
    //     PRIMARY KEY (id),

    //     KEY idx_resume_user (user_id),

    //     CONSTRAINT fk_res_accounts
    //         FOREIGN KEY (user_id) REFERENCES accounts(id)
    //         ON DELETE CASCADE
    //         ON UPDATE CASCADE
    // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    
    // CREATE TABLE IF NOT EXISTS `work_experience` (
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

    //     KEY idx_work_resume_sort (resume_id, sort_order),

    //     CONSTRAINT fk_work_resume
    //         FOREIGN KEY (resume_id) REFERENCES resume(id)
    //         ON DELETE CASCADE
    //         ON UPDATE CASCADE
    // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    // CREATE TABLE IF NOT EXISTS `academics` (
    //     id          INT UNSIGNED NOT NULL AUTO_INCREMENT,     
    //     title       VARCHAR(100) NOT NULL,
    //     institute   VARCHAR(120) NOT NULL,
    //     start_date  DATE NOT NULL,
    //     end_date    DATE NULL,
    //     summary     VARCHAR(2048) NULL,
    //     sort_order  INT NOT NULL DEFAULT 0,
    //     created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    //     updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    //     resume_id   INT UNSIGNED NOT NULL,
    //     PRIMARY KEY (id),

    //     KEY idx_acad_resume_sort (resume_id, sort_order),

    //     CONSTRAINT fk_acad_resume
    //         FOREIGN KEY (resume_id) REFERENCES resume(id)
    //         ON DELETE CASCADE
    //         ON UPDATE CASCADE
    // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    // CREATE TABLE IF NOT EXISTS `work_experience_bullets` (
    // id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    // text          TEXT NOT NULL,
    // sort_order    INT NOT NULL DEFAULT 0,
    // work_id       INT UNSIGNED NOT NULL,
    // PRIMARY KEY (id),

    // KEY idx_bullet_work_sort (work_id, sort_order),

    // CONSTRAINT fk_bullet_work
    //     FOREIGN KEY (work_id) REFERENCES work_experience(id)
    //     ON DELETE CASCADE
    //     ON UPDATE CASCADE
    // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    // CREATE TABLE IF NOT EXISTS `acad_experience_bullets` (
    // id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    // text          TEXT NOT NULL,
    // sort_order    INT NOT NULL DEFAULT 0,
    // acad_id       INT UNSIGNED NOT NULL,
    // PRIMARY KEY (id),

    // KEY idx_bullet_acad_sort (acad_id, sort_order),

    // CONSTRAINT fk_bullet_acad
    //     FOREIGN KEY (acad_id) REFERENCES academics(id)
    //     ON DELETE CASCADE
    //     ON UPDATE CASCADE
    // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    // CREATE TABLE IF NOT EXISTS `technical_skills` (
    //     id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    //     name       VARCHAR(100) NOT NULL,
    //     category   ENUM('language','framework','tool','platform','other') NOT NULL DEFAULT 'other',
    //     level      ENUM('basic','intermediate','advanced') NULL,
    //     sort_order INT NOT NULL DEFAULT 0,
    //     resume_id  INT UNSIGNED NOT NULL,
    //     PRIMARY KEY (id),

    //     UNIQUE KEY uq_skill_resume_name (resume_id, name),

    //     CONSTRAINT fk_tech_resume
    //         FOREIGN KEY (resume_id) REFERENCES resume(id)
    //         ON DELETE CASCADE
    //         ON UPDATE CASCADE
    // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


    PaperWitch
I want to create a modern Resume designer platform for students and young professionals.
# Features:
- Workspace: area for the editor
- Experience: add/remove their job experience
- Education: add/remove their college courses
- Skills: add/remove skills
- Contact: personal details
- Users: who can create and save multiple resumes, change its layouts, structure, login and sign up.
- Sections: inside the Hybrid-SPA that act as webpages
- Build: with PHP and Javascript under the hood, lightweight, aimed at raw performance, ease of use, and no suprise-signup/paywalls.
- Play: the build-in Side-scroller. As they play, fill in question clouds in npc/for and have a resume at the end of the level (or a part of it.)
---------+++------------
snake_case only / ERD
-------------------------
Accounts (User)
- id # Int # Primary key
- display name # string # varchar()
- email # string # varchar() # UNIQUE
- Pwd # string # varchar()
- birthday # DATE
- created_at # timestamp (Maybe current)
- updated_at # timestamp

Contacts (Personalia)
- id # Int # Primary Key
- firstname # string # varchar()
- lastname # string # varchar()
- phone # ? # ?
- City # string # varchar()
- Country # string # varchar()
- postalcode # string # varchar()
- image_url # string # varchar()
- Social_media1 # string # varchar()
- Social_media2 # string # varchar()
- Social_media3 # string # varchar()
- user_id # int # Index/fk
- created_at # timestamp
- updated_at # timestamp

Resumes
- id # int # Primary key
- title # string # varchar()
- user_id # int # Index/fk
- created_at # timestamp
- updated_at # timestamp

Experiences (joint table)
- id # int # Primary key
- From # 
- Until #
- Profession # string # varchar()
- Company
- Description # string # varchar()
- resume_id # int # Index/fk <---
- user_id # int # Index/fk <---
- created_at # timestamp
- updated_at # timestamp

Educations (joint table)
- id # int # Primary key
- From # 
- Until #
- Course/Study # string # varchar()
- Institute
- Description # string # varchar()
- resume_id # int # Index/fk <---
- user_id # int # Index/fk <---
- created_at # timestamp
- updated_at # timestamp

Skills (joint table)
- id # int # Primary key
- Name # string # varchar() NOT NULL
- resume_id # int # Index/fk <---
- user_id # int # Index/fk <---
- created_at # timestamp
- updated_at # timestamp

Bullet points (joint table)
- id # int # Primary key
- Title # string # varchar() # NULL
- Desc. # string # varchar() # NOT NULL
- exp_id # int # Index/fk <---
- edu_id # int # Index/fk <---
- resume_id # int # Index/fk <---
- user_id # int # Index/fk <--- (foreign key)
- created_at # timestamp
- updated_at # timestamp