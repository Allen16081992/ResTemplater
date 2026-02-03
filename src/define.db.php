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

    // -- MySQL / MariaDB
    // -- Use InnoDB to support foreign keys
    
    // CREATE TABLE IF NOT EXISTS `accounts` (
    // id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    // username  VARCHAR(100) NULL,
    // email         VARCHAR(255) NOT NULL,
    // password_hash           VARCHAR(255) NOT NULL,
    // birth_date      DATE NULL,
    // created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    // updated_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    // PRIMARY KEY (id),
    // UNIQUE KEY uq_accounts_email (email)
    // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    // CREATE TABLE IF NOT EXISTS `contacts` (
    // id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    // firstname      VARCHAR(100) NOT NULL,
    // lastname       VARCHAR(100) NOT NULL,
    // phone          VARCHAR(32) NULL,
    // city           VARCHAR(120) NULL,
    // country        VARCHAR(120) NULL,
    // postalcode     VARCHAR(20) NULL,
    // image_url      VARCHAR(2048) NULL,
    // user_id        INT UNSIGNED NOT NULL,
    // created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    // updated_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    // PRIMARY KEY (id),

    // -- 1:1 relationship (each user_id can appear only once in contacts)
    // UNIQUE KEY uq_contacts_user_id (user_id),

    // -- index for FK lookups (MySQL will often create it implicitly, but explicit is fine)
    // KEY idx_contacts_user_id (user_id),

    // CONSTRAINT fk_contacts_accounts
    //     FOREIGN KEY (user_id) REFERENCES accounts(id)
    //     ON DELETE CASCADE
    //     ON UPDATE CASCADE
    // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    // INSERT INTO `users` (`username`, `email`, `password_hash`, `birthday`) VALUES ('Hallohallo', 'hallohallo@gmail.com', '$argon2id$v=19$m=65536,t=2,p=1$Tm5KUG54Wks4MmI4MGVGZw$Q5eN9yv6TRCY5mA9svTu2w', '2026-01-25');


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