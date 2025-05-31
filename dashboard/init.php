<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$self = basename($_SERVER['SCRIPT_NAME']);

if ($self === 'login.php') {
}
elseif ($self === 'twofa.php') {
    if (empty($_SESSION['temp_username'])) {
        header('Location: login.php');
        exit;
    }
}
else {
    if (empty($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
        header('Location: login.php');
        exit;
    }
}

require_once __DIR__ . '/../z_db.php';
require_once __DIR__ . '/lib/security.php';
