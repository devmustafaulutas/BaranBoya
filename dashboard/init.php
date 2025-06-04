<?php
// /vogue/dashboard/init.php

// 1) Oturum adını belirliyoruz:
session_name("ADMIN_SESSION");

// 2) Çerez parametrelerini dinamik olarak oluşturuyoruz:
//    Tarayıcıya Set-Cookie göndereceğimiz "path" değeri, uygulamanın gerçek URL yapısına uygun olmalı.
//    Örneğin, site localhost/vogue/dashboard altında çalışıyorsa, path '/vogue/dashboard/' olmalı.

// $_SERVER['SCRIPT_NAME'] örneğin: "/vogue/dashboard/login.php"
$scriptName = $_SERVER['SCRIPT_NAME'];
// dirname("/vogue/dashboard/login.php") -> "/vogue/dashboard"
$cookiePath = rtrim(dirname($scriptName), '/') . '/'; // sonuç: "/vogue/dashboard/"

// Domain’i alınırken port varsa onu atıyoruz:
$rawHost = $_SERVER['HTTP_HOST'];
$domain = preg_replace('/:\d+$/', '', $rawHost);

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => $cookiePath,        // Örn. "/vogue/dashboard/"
    'domain'   => $domain,            // Örn. "localhost" (port içermeden)
    'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    'httponly' => true,
    'samesite' => 'Strict',
]);

// 3) Oturumu başlatıyoruz
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 4) Kimlik kontrolü: login.php veya twofa.php ise oturum kontrolü yapmıyoruz.
//    Diğer sayfalarda $_SESSION['authenticated'] || redirect(login.php)
$self = basename($_SERVER['SCRIPT_NAME']);
if ($self === 'login.php') {
    // Burada sadece formu gösterelim, kimlik sorgusu yok
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

// 5) Gerekli dosyaları yükleyelim
require_once __DIR__ . '/../z_db.php';
require_once __DIR__ . '/lib/security.php';
