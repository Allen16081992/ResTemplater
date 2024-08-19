<?php
    require_once __DIR__ . '../../src/account.class.php';

    class loadUserdata extends Account {
        
        public function loadUserdata() {
            $this->readUser();
        }

    }

    $data = new loadUserdata();
    $data->loadUserdata();