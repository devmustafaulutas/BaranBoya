<?php
// dashboard/login.php

// 1) HTTPS + Session
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit;
}
session_start();
session_regenerate_id(true);

// 2) Lib + DB
require __DIR__ . '/lib/security.php';
include_once __DIR__ . '/../z_db.php';
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

$g    = new GoogleAuthenticator();
$step = $_POST['step'] ?? '1';
$msg  = '';

// Initialize counters if not set
if (!isset($_SESSION['login_fail_count'])) {
    $_SESSION['login_fail_count'] = 0;
    $_SESSION['login_last_fail']  = null;
}

// 3) FORM İŞLEME (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF
    if (!verifyCsrf($_POST['csrf'] ?? '')) {
        die('Geçersiz CSRF token.');
    }

    // ► STEP 1: Login
    if ($step === '1') {
        $u = mysqli_real_escape_string($con, $_POST['username']);
        $p = mysqli_real_escape_string($con, $_POST['password']);

        $stmt = $con->prepare("
          SELECT id, username, password, `2fa_secret_enc`
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
            // Reset login attempts on success
            $_SESSION['login_fail_count'] = 0;
            // 2FA kurulmuş mu?
            if (empty($row['2fa_secret_enc'])) {
                $_SESSION['username'] = $u;
                header('Location: twofa.php');
                exit;
            } else {
                $_SESSION['temp_username'] = $u;
                $step = '2';
            }
        }
    }

    // ► STEP 2: 2FA veya Backup
    elseif ($step === '2') {
        $code = trim($_POST['2fa_code'] ?? '');

        // Fetch 2FA data
        $stmt = $con->prepare("
          SELECT `2fa_secret_enc`, `backup_codes`, totp_fail_count, totp_last_fail
            FROM admin
           WHERE username = ? LIMIT 1
        ");
        $stmt->bind_param('s', $_SESSION['temp_username']);
        $stmt->execute();
        $r = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $secret     = decryptSecret($r['2fa_secret_enc']);
        $backHashes = json_decode($r['backup_codes'] ?? '[]', true);

        // Check TOTP
        if ($g->checkCode($secret, $code)) {
            // reset fail count
            $upd = $con->prepare("UPDATE admin SET totp_fail_count = 0 WHERE username = ?");
            $upd->bind_param('s', $_SESSION['temp_username']);
            $upd->execute();

            session_regenerate_id(true);
            $_SESSION['username']      = $_SESSION['temp_username'];
            $_SESSION['authenticated'] = true;
            unset($_SESSION['temp_username']);
            header('Location: index.php');
            exit;
        }
        // Check backup code
        elseif (verifyBackupCode($code, $backHashes)) {
            // remove used
            $remain = array_filter($backHashes, fn($h)=>!password_verify($code,$h));
            $upd2 = $con->prepare("UPDATE admin SET backup_codes = ? WHERE username = ?");
            $json = json_encode(array_values($remain));
            $upd2->bind_param('ss',$json,$_SESSION['temp_username']);
            $upd2->execute();

            session_regenerate_id(true);
            $_SESSION['username']      = $_SESSION['temp_username'];
            $_SESSION['authenticated'] = true;
            unset($_SESSION['temp_username']);
            header('Location: index.php');
            exit;
        } else {
            // increment TOTP fail count
            $stmt2 = $con->prepare("
              UPDATE admin
                 SET totp_fail_count = totp_fail_count+1,
                     totp_last_fail  = NOW()
               WHERE username = ?
            ");
            $stmt2->bind_param('s', $_SESSION['temp_username']);
            $stmt2->execute();
            $msg = 'Geçersiz 2FA veya kurtarma kodu.';
        }
    }
}

// —————————————————————————————————
// 4) KİLİT LOGİĞİNİ 
// Login kilidi
$loginLocked    = false;
$loginRemaining = 0;
if ($step==='1' && $_SESSION['login_fail_count']>=5) {
    $last   = strtotime($_SESSION['login_last_fail']);
    $until  = $last + 300;
    $now    = time();
    if ($now < $until) {
        $loginLocked    = true;
        $loginRemaining = $until - $now;
    } else {
        $_SESSION['login_fail_count'] = 0;
    }
}

// 2FA brute–force kilidi
$twofaLocked    = false;
$twofaRemaining = 0;
if ($step === '2' && isset($_SESSION['temp_username'])) {
    // Use UNIX_TIMESTAMP to avoid timezone issues
    $stmt = $con->prepare(
        "SELECT totp_fail_count, UNIX_TIMESTAMP(totp_last_fail) AS last_fail_ts
           FROM admin
          WHERE username = ? LIMIT 1"
    );
    $stmt->bind_param('s', $_SESSION['temp_username']);
    $stmt->execute();
    $rr = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($rr['totp_fail_count'] >= 5) {
        $last   = intval($rr['last_fail_ts']);
        $unlock = $last + 300;
        $now    = time();
        if ($now < $unlock) {
            $twofaLocked    = true;
            $twofaRemaining = $unlock - $now;
        } else {
            // Reset totp fail count after lock expires
            $upd = $con->prepare("UPDATE admin SET totp_fail_count = 0 WHERE username = ?");
            $upd->bind_param('s', $_SESSION['temp_username']);
            $upd->execute();
        }
    }
}
// CSRF token
$csrf = csrfToken();
?>
<!DOCTYPE html>
<html lang="tr">
<head><meta charset="utf-8"><title>Giriş</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/app.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
  <div class="card p-4" style="width:100%;max-width:400px;">
    <h5 class="text-center mb-3"><?= $step==='1'?'Admin Giriş':'2FA Doğrulama' ?></h5>

    <?php if($step==='1' && $loginLocked): ?>
      <div class="alert alert-warning text-center">
        5 yanlış deneme. Lütfen <span id="cd1"></span> sonra deneyin.
      </div>
      <script>
      (function(){
        var rem = <?= $loginRemaining ?>, el = document.getElementById('cd1');
        function t(){ if(rem<=0) return location.reload(); el.textContent = Math.floor(rem/60)+'m '+rem%60+'s'; rem--; }
        t(); setInterval(t,1000);
      })();
      </script>

    <?php elseif($step==='2' && $twofaLocked): ?>
      <div class="alert alert-warning text-center">
        5 yanlış 2FA deneme. Lütfen <span id="cd2"></span> sonra deneyin.
      </div>
      <script>
      (function(){
        var rem = <?= $twofaRemaining ?>, el = document.getElementById('cd2');
        function t(){ if(rem<=0) return location.reload(); el.textContent = Math.floor(rem/60)+'m '+rem%60+'s'; rem--; }
        t(); setInterval(t,1000);
      })();
      </script>

    <?php else: ?>
      <?php if($msg): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($msg) ?></div>
      <?php endif; ?>
      <form method="post">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
        <input type="hidden" name="step" value="<?= htmlspecialchars($step) ?>">

        <?php if($step==='1'): ?>
          <div class="mb-3"><label for="u">Kullanıcı Adı</label>
          <input id="u" name="username" class="form-control" required autofocus></div>
          <div class="mb-3"><label for="p">Şifre</label>
          <input id="p" name="password" type="password" class="form-control" required></div>
        <?php else: ?>
          <div class="mb-3"><label for="c">2FA veya Kurtarma Kodu</label>
          <input id="c" name="2fa_code" class="form-control" required autofocus></div>
        <?php endif; ?>

        <button class="btn btn-primary w-100">
          <?= $step==='1'?'Giriş Yap':'Doğrula' ?>
        </button>
      </form>
    <?php endif; ?>

  </div>
</body>
</html>
