<?php
    session_start();
    require_once '../dbConnection.php';

    if (isset($_SESSION['user']['id'])) {
        logAction($pdo, $_SESSION['user']['id'], 'logout');
    }

    unset($_SESSION['user']);
    header('Location: ../../login.php');
?>