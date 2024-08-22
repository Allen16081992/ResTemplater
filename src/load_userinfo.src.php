<?php
    require_once __DIR__ . '../../src/account.class.php';

    class UserInfo extends Account {
        
        public function loadUserdata() {
            $this->readUser();
        }

    }

    $data = new UserInfo();
    $data->loadUserdata();