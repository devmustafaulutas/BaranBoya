<?php
// dashboard/init.php

// 1) Oturum yoksa başlat
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2) Kullandığımız dosyanın adı
$self = basename($_SERVER['SCRIPT_NAME']);

// 2a) Giriş ekranı her zaman skip
if ($self === 'login.php') {
    // hiçbir kontrol
}
// 2b) twofa.php yalnızca temp_username varsa
elseif ($self === 'twofa.php') {
    if (empty($_SESSION['temp_username'])) {
        header('Location: login.php');
        exit;
    }
}
// 2c) diğer tüm sayfalar için authenticated kontrolü
else {
    if (empty($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
        header('Location: login.php');
        exit;
    }
}

// 3) DB ve ortak güvenlik fonksiyonları
require_once __DIR__ . '/../z_db.php';
require_once __DIR__ . '/lib/security.php';
