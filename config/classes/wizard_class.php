<?php require_once '../database/v2_db.php';

    final class wizardCodex {
        public function __construct(private PDO $pdo) {}

        // Create Resume
        public function setFullResume(array $postData): int {
            try {
                $this->pdo->beginTransaction();

                // 1️⃣ Insert Resume
                $stmtResume = $this->pdo->prepare("
                    INSERT INTO resumes (title, headline, summary, user_id)
                    VALUES (:title, :headline, :summary, :user_id)
                ");

                $stmtResume->execute([
                    ':title'       => $postData['title'],
                    ':headline'    => $postData['headline'] ?? null,
                    ':summary'     => $postData['summary'] ?? null,
                    ':user_id'     => $postData['user_id']
                ]);

                $resumeId = (int) $this->pdo->lastInsertId();

                // 2️⃣ Insert Experience
                if (!empty($postData['experience'])) {
                    $stmtExp = $this->pdo->prepare("
                        INSERT INTO work_experience
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
                        (title, institute, start_date, end_date, summary, resume_id)
                        VALUES (:title, :institute, :start_date, :end_date, :summary, :resume_id)
                    ");

                    foreach ($postData['education'] as $edu) {
                        $stmtEdu->execute([
                            ':title'     => $edu['title'],
                            ':institute'     => $edu['institute'],
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
                        VALUES (:name, :category, :sort_order, NOW(), :resume_id)
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
                if (!empty($postData['contacts'])) {
                    $stmtEdu = $this->pdo->prepare("
                        INSERT INTO contacts
                        (fullname, phone, city, country, summary, resume_id)
                        VALUES (:title, :institute, :start_date, :end_date, :summary, :resume_id)
                    ");

                    $stmtEdu->execute([
                        ':fullname'  => $postData['fullname'],
                        ':phone'     => $postData['phone'],
                        ':city'      => $postData['city'],
                        ':country'   => $postData['country'],
                        ':resume_id' => $resumeId
                    ]);
                }

                // 4️⃣ If everything worked
                $this->pdo->commit();
                return $resumeId;

            } catch (\Throwable $e) {
                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }
                error_log((string)$e);
                throw $e;
            }
        }
    }