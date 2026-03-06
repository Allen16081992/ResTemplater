<?php require_once './session_manager.conf.php';
SessionBook::logoutUser();
header('Location: ../index.php', true, 303);
exit;