<?php
require_once __DIR__ . '/init.php';

$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $p = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        [
          'expires'  => time() - 42000,
          'path'     => $p['path'],
          'domain'   => $p['domain'],
          'secure'   => $p['secure'],
          'httponly' => $p['httponly'],
          'samesite' => 'Strict',     
        ]
    );
}
session_destroy();

if (!empty($_COOKIE['remember_me'])) {
    list($userId, $token) = explode(':', $_COOKIE['remember_me'], 2);
    require_once __DIR__ . '/../z_db.php';
    $del = $con->prepare("DELETE FROM admin_remember WHERE user_id = ?");
    $del->bind_param('i', $userId);
    $del->execute();
    setcookie('remember_me', '', time() - 3600, '/', '', true, true);
}

header('Location: login.php');
exit;
