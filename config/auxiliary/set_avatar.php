<?php
require_once '../session_manager.conf.php';
SessionBook::invokeSession();

if (isset($_POST['avatar'])) {
    $flag = filter_var($_POST['avatar'], FILTER_VALIDATE_INT);

    if ($flag === 1) {
        $_SESSION['avatar'] = true;
    } else {
        unset($_SESSION['avatar']);
    }
}
die;