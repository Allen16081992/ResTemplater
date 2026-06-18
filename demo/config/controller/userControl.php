<?php // PHP Files
    require_once __DIR__ . '/../mixedGrimoire.php';
    
    class userControl {
        public function __construct(private array $postData) {}
       
        private function oldForm(): void {
            $oldForm = [
                'fullname' => $this->postData['fullname'] ?? '',
                'phone' => $this->postData['phone'] ?? '',
                'city' => $this->postData['city'] ?? '',
                'country' => $this->postData['country'] ?? '',
                'email' => $this->postData['email'] ?? ''
            ];
        }

        public function handle(): void {
            // 1. Extract the Intent (everything after the colon)
            // ltrim ensures we remove the ':'
            $module = strstr($this->postData['action'], ':', true) ?: $this->postData['action'];
            $intent = ltrim(strchr($this->postData['action'], ':'), ':');

            // DB lookup
            $pdo = Database::Connect();
            $model = new userCodex($pdo); 

            // 2. Account deletion
            if ($intent == 'closure') {
                $exist = $model->findByEmail($this->postData['email']);
                if (!$exist) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'User not found.';
                    ViewBook::revert('closure');
                    return; 
                }

                // If user id match, close account
                if ($exist['id'] === (int)$this->postData['id']) {
                    // Verify password
                    $pwd = (string)($this->postData['pwd'] ?? '');
                    if (!mixedGrimoire::checkHash($pwd, $exist['password_hash'])) {
                        // Hold error message + set previous UI state
                        $_SESSION['error'] = ['pwd' => 'Incorrect password.'];
                        ViewBook::revert('closure');
                        return;
                    }
                }

                // Delete user (only after validation)
                $burn = $model->deleteAccount($exist['id']);
                if ($burn <= 0) {
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'Failed to delete account.';
                    ViewBook::revert('closure');
                    return;
                }

                // Set success message + redirect homepage
                $_SESSION['success'] = 'Account closed. Data erased.';
                ViewBook::revert('home');
                return;
            }

            // Validate for missing value, but skip password
            if ($this->postData['pwd'] == '') { 
                unset($this->postData['pwd']); 
            }

            $errors = ValidGrimoire::emptyField($this->postData);
            if (!empty($errors)) {
                $this->oldForm();
                // Hold error message + set previous UI state
                $_SESSION['error'] = $errors;
                ViewBook::revert('profile');
                return;
            }

            if ($module == 'account') {
                // echo "<pre>POST Token: "; 
                // var_dump($_POST['csrf_token'] ?? 'NOT SET'); 
                // echo "<br>SESSION Token: "; 
                // var_dump($this->postData ?? 'NOT SET ENTRIES'); 
                // echo "</pre>";
                // die("Stopping here to see tokens.");

                // Validate email format
                $email = trim((string)($this->postData['email'] ?? ''));
                if ($msg = ValidGrimoire::validateEmail($email)) {
                    $errors['email'] = $msg;
                }

                if (isset($this->postData['pwd'])) {
                    // Validate password
                    $pwd = trim((string)($this->postData['pwd'] ?? ''));
                    if ($msg = ValidGrimoire::validatePwd($pwd)) {
                        $errors['pwd'] = $msg;
                    }
                }

                // Final check: if errors exist, send them back together
                if (!empty($errors)) {
                    $this->oldForm();
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = $errors;
                    ViewBook::revert('profile');
                    return;
                }     

                $exist = $model->fetchRow($_SESSION['session_data']['user_id']);
                if (!$exist) {
                    $this->oldForm();
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'Update failed. User not found.';
                    ViewBook::revert('profile');
                    return; 
                }
                
                // Verify if task was successful
                $update = $model->updateEmail($_SESSION['session_data']['user_id'], $email);
                if ($update <= 0) {
                    $this->oldForm();
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'Failed to update account.';
                    ViewBook::revert('profile');
                    return;
                }

                if (!empty($pwd)) {
                    $passw = $model->updateHash($_SESSION['session_data']['user_id'], $pwd);
                    if ($passw <= 0) {
                        $this->oldForm();
                        // Hold error message + set previous UI state
                        $_SESSION['error'] = 'Updating account failed.';
                        ViewBook::revert('profile');
                        return;
                    }
                }
                $_SESSION['success'] = 'Account updated.';

            } elseif ($module == 'contact') {
                $errors = [];

                // Collect errors
                $fields = ['fullname', 'city', 'country'];
                foreach ($fields as $field) {
                    // 1. Trim and cast to string
                    $value = trim((string)($this->postData[$field] ?? ''));

                    // 2. Overwrite the original postData with the "clean" version
                    $this->postData[$field] = $value;

                    // 3. Validate using the updated field
                    if ($msg = ValidGrimoire::validateName($value, true)) {
                        $errors[$field] = $msg;
                    }
                }

                // Validate phone
                $phone = $this->postData['phone'];
                if ($msg = ValidGrimoire::validatePhone($phone)) {
                    $errors['phone'] = $msg;
                }

                // Final check: if errors exist, send them back together
                if (!empty($errors)) {
                    $this->oldForm();
                    $_SESSION['error'] = $errors;
                    ViewBook::revert('profile');
                    return;
                }

                // Assign the user id to array
                $this->postData['user_id'] = $_SESSION['session_data']['user_id'];

                // Verify if contact information exist
                $exist = $model->fetchContact($this->postData['user_id']);
                if (!$exist) {
                    $contact = $model->createContact($this->postData);
                } else {
                    $contact = $model->updateContact($this->postData);
                }

                // Verify if task was successful
                if ($contact <= 0) {
                    $this->oldForm();
                    // Hold error message + set previous UI state
                    $_SESSION['error'] = 'Failed to update personal info.';
                    ViewBook::revert('profile');
                    return;
                }
                $_SESSION['success'] = 'Personal info updated.';
            }
            ViewBook::revert('profile');
            exit;
        }
    }