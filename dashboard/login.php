<?php
// /vogue/dashboard/login.php

// 1) init.php dosyasının yolunu düzeltip yüklüyoruz:
require_once __DIR__ . '/init.php';

use Sonata\GoogleAuthenticator\GoogleAuthenticator;
global $con;

// “Remember me” çerez kontrolü
if (empty($_SESSION['authenticated']) && !empty($_COOKIE['remember_me'])) {
    list($userId, $token) = explode(':', $_COOKIE['remember_me'], 2);

    $stmt = $con->prepare("
        SELECT token_hash, expires_at
          FROM admin_remember
         WHERE user_id = ?
    ");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (
        $row
        && new DateTime() < new DateTime($row['expires_at'])
        && password_verify($token, $row['token_hash'])
    ) {
        $u = $con->prepare("SELECT username FROM admin WHERE id = ? LIMIT 1");
        $u->bind_param('i', $userId);
        $u->execute();
        $uRow = $u->get_result()->fetch_assoc();
        $u->close();

        if ($uRow) {
            session_regenerate_id(true);
            $_SESSION['username']      = $uRow['username'];
            $_SESSION['authenticated'] = true;
        }
    } else {
        setcookie('remember_me', '', time() - 3600, $cookiePath, $domain, true, true);
    }
}

if (!empty($_SESSION['authenticated'])) {
    header('Location: index.php');
    exit;
}

$g      = new GoogleAuthenticator();
$step   = $_POST['step'] ?? '1';
$msg    = '';

if (!isset($_SESSION['login_fail_count'])) {
    $_SESSION['login_fail_count'] = 0;
    $_SESSION['login_last_fail']  = null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf($_POST['csrf'] ?? '')) {
        die('Geçersiz CSRF token.');
    }

    if ($step === '1') {
        $u        = mysqli_real_escape_string($con, $_POST['username']);
        $p        = mysqli_real_escape_string($con, $_POST['password']);
        $remember = !empty($_POST['remember_me']);

        $stmt = $con->prepare("
            SELECT id, username, password, `twofa_enabled`, `2fa_secret`, `2fa_secret_enc`
              FROM admin
             WHERE username = ? LIMIT 1
        ");
        $stmt->bind_param('s', $u);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$row || $row['password'] !== $p) {
            $msg = 'Kullanıcı/şifre hatalı.';
            $_SESSION['login_fail_count']++;
            $_SESSION['login_last_fail'] = date('Y-m-d H:i:s');
        } else {
            $_SESSION['login_fail_count'] = 0;
            if ($remember) {
                $_SESSION['remember_me'] = true;
            } else {
                unset($_SESSION['remember_me']);
            }

            if ($row['twofa_enabled'] === 0) {
                $_SESSION['username'] = $u;
                header('Location: twofa.php');
                exit;
            } else {
                $_SESSION['temp_username'] = $u;
                $step = '2';
            }
        }
    }
    elseif ($step === '2' && isset($_SESSION['temp_username'])) {
        $code = trim($_POST['2fa_code'] ?? '');

        $stmt = $con->prepare("
            SELECT id, `2fa_secret`, `2fa_secret_enc`, `backup_codes`,
                   totp_fail_count, totp_last_fail
              FROM admin
             WHERE username = ? LIMIT 1
        ");
        $stmt->bind_param('s', $_SESSION['temp_username']);
        $stmt->execute();
        $r = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!empty($r['2fa_secret_enc'])) {
            $secret = decryptSecret($r['2fa_secret_enc']);
        } else {
            $secret = $r['2fa_secret'];
        }
        $backups = json_decode($r['backup_codes'] ?? '[]', true);

        if ($r['totp_fail_count'] >= 5 && time() - strtotime($r['totp_last_fail']) < 300) {
            $msg = 'Çok fazla deneme. Lütfen 5 dk bekleyin.';
        }
        elseif ($g->checkCode($secret, $code)) {
            $upd = $con->prepare("UPDATE admin SET totp_fail_count = 0 WHERE id = ?");
            $upd->bind_param('i', $r['id']);
            $upd->execute();

            session_regenerate_id(true);
            $_SESSION['username']      = $_SESSION['temp_username'];
            $_SESSION['authenticated'] = true;
            unset($_SESSION['temp_username']);

            if (!empty($_SESSION['remember_me'])) {
                $token     = bin2hex(random_bytes(32));
                $tokenHash = password_hash($token, PASSWORD_DEFAULT);
                $expires   = date('Y-m-d H:i:s', time() + 86400*30);

                $ins = $con->prepare("
                    INSERT INTO admin_remember (user_id, token_hash, expires_at)
                    VALUES (?, ?, ?)
                ");
                $ins->bind_param('iss', $r['id'], $tokenHash, $expires);
                $ins->execute();

                setcookie(
                  'remember_me',
                  "{$r['id']}:{$token}",
                  time()+86400*30,
                  $cookiePath,
                  $domain,
                  true,
                  true
                );
                unset($_SESSION['remember_me']);
            }

            header('Location: index.php');
            exit;
        }
        elseif (verifyBackupCode($code, $backups)) {
            $remain = array_filter($backups, fn($h) => !password_verify($code, $h));
            $upd2 = $con->prepare("UPDATE admin SET backup_codes = ? WHERE id = ?");
            $json = json_encode(array_values($remain));
            $upd2->bind_param('si', $json, $r['id']);
            $upd2->execute();

            session_regenerate_id(true);
            $_SESSION['username']      = $_SESSION['temp_username'];
            $_SESSION['authenticated'] = true;
            unset($_SESSION['temp_username']);

            header('Location: index.php');
            exit;
        } else {
            $stmt2 = $con->prepare("
                UPDATE admin
                   SET totp_fail_count = totp_fail_count + 1,
                       totp_last_fail  = NOW()
                 WHERE id = ?
            ");
            $stmt2->bind_param('i', $r['id']);
            $stmt2->execute();
            $msg = 'Geçersiz 2FA veya kurtarma kodu.';
        }
    }
}

$loginLocked    = false;
$loginRemaining = 0;
if ($step === '1' && $_SESSION['login_fail_count'] >= 5) {
    $last   = strtotime($_SESSION['login_last_fail']);
    $unlock = $last + 300;
    $now    = time();
    if ($now < $unlock) {
        $loginLocked    = true;
        $loginRemaining = $unlock - $now;
    } else {
        $_SESSION['login_fail_count'] = 0;
    }
}

$csrf = csrfToken();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <title>Admin Giriş | Dashboard</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/app.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
  <div class="card p-4" style="width:100%;max-width:400px;">

    <h5 class="text-center mb-3">
      <?= $step === '1' ? 'Admin Giriş' : '2FA Doğrulama' ?>
    </h5>

    <?php if ($msg): ?>
      <div class="alert alert-danger text-center"><?= htmlspecialchars($msg, ENT_QUOTES) ?></div>
    <?php endif; ?>

    <form method="post" action="login.php">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES) ?>">
      <input type="hidden" name="step" value="<?= htmlspecialchars($step, ENT_QUOTES) ?>">

      <?php if ($step === '1'): ?>
        <div class="mb-3">
          <label for="u" class="form-label">Kullanıcı Adı</label>
          <input id="u" name="username" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
          <label for="p" class="form-label">Şifre</label>
          <input id="p" name="password" type="password" class="form-control" required>
        </div>
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" id="remember_me" name="remember_me">
          <label class="form-check-label" for="remember_me">Beni hatırla</label>
        </div>
      <?php else: ?>
        <div class="mb-3">
          <label for="c" class="form-label">2FA veya Kurtarma Kodu</label>
          <input id="c" name="2fa_code" class="form-control" required autofocus>
        </div>
      <?php endif; ?>

      <?php if ($loginLocked && $step === '1'): ?>
        <div class="alert alert-warning text-center">
          Çok fazla deneme. Lütfen <span id="cd1"></span> sonra deneyin.
        </div>
        <script>
          (function(){
            var rem = <?= $loginRemaining ?>, el = document.getElementById('cd1');
            function t(){
              if(rem <= 0) return location.reload();
              var m = Math.floor(rem/60), s = rem % 60;
              el.textContent = (m<10?'0'+m:m) + 'm ' + (s<10?'0'+s:s) + 's';
              rem--;
            }
            t();
            setInterval(t, 1000);
          })();
        </script>
      <?php endif; ?>

      <button class="btn btn-primary w-100">
        <?= $step === '1' ? 'Giriş Yap' : 'Doğrula' ?>
      </button>
    </form>
  </div>
</body>
</html>
