<?php
    final class wizardCodex {
        public function __construct(private PDO $pdo) {}

        // Create Resume
        public function setFullResume(array $postData): void {
            try {
                $this->pdo->beginTransaction();

                // 1️⃣ Insert Resume
                $stmtResume = $this->pdo->prepare("
                    INSERT INTO resumes (title, headline, user_id)
                    VALUES (:title, :headline, :user_id)
                ");

                $stmtResume->execute([
                    ':title'       => $postData['title'] ?? $postData['fullname'],
                    ':headline'    => $postData['headline'] ?? rand(0,100),
                    ':user_id'     => $_SESSION['session_data']['user_id'] ?? ''
                ]);

                $resumeId = (int) $this->pdo->lastInsertId();

                // 2️⃣ Insert Experience
                if (!empty($postData['experience'])) {
                    $stmtExp = $this->pdo->prepare("
                        INSERT INTO experience
                        (title, employer, start_date, end_date, summary, resume_id)
                        VALUES (:title, :employer, :start_date, :end_date, :summary, :resume_id)
                    ");

                    foreach ($postData['experience'] as $exp) {
                        $stmtExp->execute([
                            ':title'      => $exp['title'],
                            ':employer'   => $exp['employer'],
                            ':start_date' => $exp['start_date'],
                            ':end_date'   => $exp['end_date'] ?? null,
                            ':summary'    => $exp['summary'] ?? null,
                            ':resume_id'  => $resumeId
                        ]);
                    }
                }

                // 3️⃣ Insert Education
                if (!empty($postData['education'])) {
                    $stmtEdu = $this->pdo->prepare("
                        INSERT INTO education
                        (program, school, start_date, end_date, summary, resume_id)
                        VALUES (:program, :school, :start_date, :end_date, :summary, :resume_id)
                    ");

                    foreach ($postData['education'] as $edu) {
                        $stmtEdu->execute([
                            ':program'     => $edu['program'],
                            ':school'     => $edu['school'],
                            ':start_date' => $edu['start_date'],
                            ':end_date'   => $edu['end_date'] ?? null,
                            ':summary'   => $edu['summary'] ?? null,
                            ':resume_id'  => $resumeId
                        ]);
                    }
                }

                // 3️⃣ Insert Skills
                if (!empty($postData['skills'])) {
                    $stmtSki = $this->pdo->prepare("
                        INSERT INTO skills (name, category, sort_order, resume_id)
                        VALUES (:name, :category, :sort_order, :resume_id)
                    ");

                    $sort = 1;
                    foreach ($postData['skills'] as $qi) {
                        $stmtSki->execute([
                            ':name'      => $qi['name'],
                            ':category'  => $qi['category'],
                            ':sort_order'=> $sort,
                            ':resume_id' => $resumeId
                        ]);
                        $sort++;
                    }
                }

                // 3️⃣ Insert Contacts
                $stmtCon = $this->pdo->prepare('SELECT 1 FROM contacts WHERE user_id = :user_id LIMIT 1');
                $stmtCon->execute([':user_id' => $_SESSION['session_data']['user_id'] ?? '']);

                if ($stmtCon->fetch() === false) {
                    $stmtEdu = $this->pdo->prepare("
                        INSERT INTO contacts
                        (fullname, phone, city, country, user_id)
                        VALUES (:fullname, :phone, :city, :country, :user_id)
                    ");

                    $stmtEdu->execute([
                        ':fullname'  => $postData['fullname'],
                        ':phone'     => $postData['phone'],
                        ':city'      => $postData['city'],
                        ':country'   => $postData['country'],
                        ':user_id'   => $_SESSION['session_data']['user_id'] ?? ''
                    ]);
                }

                // 4️⃣ If everything worked
                $this->pdo->commit();
                $_SESSION['success'] = "Your paper is saved.";

            } catch (\Throwable $e) {
                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }
                error_log((string)$e);
                throw $e;
            }
        }
    }