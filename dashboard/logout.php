<?php
session_start();
$_SESSION = [];

if (!empty($_COOKIE['remember_me'])) {
    list($userId, $token) = explode(':', $_COOKIE['remember_me'], 2);
    require_once __DIR__ . '/../z_db.php';
    $del = $con->prepare("DELETE FROM admin_remember WHERE user_id = ?");
    $del->bind_param('i', $userId);
    $del->execute();
    $del->close();
    setcookie('remember_me', '', time() - 3600, '/', '', true, true);
}

if (ini_get("session.use_cookies")) {
    $p = session_get_cookie_params();
    setcookie(
      session_name(),
      '',
      time() - 42000,
      $p['path'],
      $p['domain'],
      $p['secure'],
      $p['httponly']
    );
}

session_destroy();

header('Location: login.php');
exit;
?>
