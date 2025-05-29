<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../z_db.php';
require_once __DIR__ . '/lib/security.php';
