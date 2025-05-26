<?php
session_start();
include_once("../z_db.php");
require '../vendor/autoload.php';
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

$g      = new GoogleAuthenticator();
$msg    = '';
// Adım bilgisi: '1' = kullanıcı/şifre, '2' = 2FA
$step   = $_POST['step'] ?? '1';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step === '1') {
        // ────────── 1️⃣ Kullanıcı adı + şifre kontrolü ──────────
        $u = mysqli_real_escape_string($con, $_POST['username']);
        $p = mysqli_real_escape_string($con, $_POST['password']);

        $res = mysqli_query($con, "
            SELECT id, username, password, 2fa_secret
            FROM admin
            WHERE username = '$u'
        ");
        if ($row = mysqli_fetch_assoc($res)) {
            // Düz metin kontrolü (hash kullanmıyorsak)
            if ($row['password'] === $p) {
                // 2FA etkin mi?
                if (!empty($row['2fa_secret'])) {
                    $_SESSION['temp_user_id']   = $row['id'];
                    $_SESSION['temp_username']  = $row['username'];
                    $step = '2';
                } else {
                    // Direkt login
                    session_regenerate_id(true);
                    $_SESSION['username']      = $row['username'];
                    $_SESSION['authenticated'] = true;
                    header('Location: index.php');
                    exit;
                }
            } else {
                $msg = "Şifre yanlış.";
            }
        } else {
            $msg = "Kullanıcı bulunamadı.";
        }
    }
    elseif ($step === '2') {
        // ────────── 2️⃣ 2FA kodu kontrolü ──────────
        if (!isset($_SESSION['temp_username'])) {
            header('Location: login.php');
            exit;
        }
        $userRes = mysqli_query($con, "
            SELECT 2fa_secret
            FROM admin
            WHERE username = '" . mysqli_real_escape_string($con, $_SESSION['temp_username']) . "'
        ");
        $qr     = mysqli_fetch_assoc($userRes);
        $secret = $qr['2fa_secret'] ?? '';

        $code = trim($_POST['2fa_code'] ?? '');
        if ($g->checkCode($secret, $code)) {
            session_regenerate_id(true);
            $_SESSION['username']      = $_SESSION['temp_username'];
            $_SESSION['authenticated'] = true;
            unset($_SESSION['temp_username'], $_SESSION['temp_user_id']);
            header('Location: index.php');
            exit;
        } else {
            $msg = "Geçersiz 2FA kodu.";
            $step = '2';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8" />
    <title>Giriş | Baran Boya</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.icon">
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css -->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- Custom Css -->
    <link href="assets/css/login.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="auth-page-wrapper">
  <div class="auth-page-content">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-5">
          <div class="card mt-5">
            <div class="card-body p-4">

              <?php if ($step === '2'): ?>
                <!-- 2FA Doğrulama Formu -->
                <div class="text-center mb-3">
                  <h5 class="text-primary">2FA Doğrulama</h5>
                  <i class="fas fa-shield-alt fa-3x text-primary"></i>
                </div>
                <form method="post">
                  <input type="hidden" name="step" value="2">
                  <div class="mb-3">
                    <label for="2fa_code" class="form-label">2FA Kodu:</label>
                    <input type="text" id="2fa_code" name="2fa_code"
                           class="form-control" required autofocus>
                  </div>
                  <div class="mt-3 d-grid">
                    <button type="submit" class="btn btn-primary">Doğrula</button>
                  </div>
                </form>
              <?php else: ?>
                <!-- 1️⃣ Kullanıcı Adı/Şifre Formu -->
                <div class="text-center mt-2 mb-3">
                  <h5 class="text-primary">Admin Giriş</h5>
                  <i class="fas fa-user-shield fa-3x text-primary"></i>
                </div>
                <div class="p-2 mt-4">
                  <form method="post">
                    <input type="hidden" name="step" value="1">
                    <div class="mb-3">
                      <label for="username" class="form-label">Kullanıcı Adı:</label>
                      <input type="text" id="username" name="username"
                             class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                      <label for="password" class="form-label">Şifre:</label>
                      <input type="password" id="password" name="password"
                             class="form-control" required>
                    </div>
                    <div class="mt-3 d-grid">
                      <button type="submit" class="btn btn-primary">Giriş Yap</button>
                    </div>
                  </form>
                </div>
              <?php endif; ?>

              <?php if ($msg): ?>
                <p class="text-danger mt-3"><?= htmlspecialchars($msg) ?></p>
              <?php endif; ?>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div><!-- end auth-page-content -->
</div><!-- end auth-page-wrapper -->

<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/libs/simplebar/simplebar.min.js"></script>
<script src="assets/libs/node-waves/waves.min.js"></script>
<script src="assets/libs/feather-icons/feather.min.js"></script>
<script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
<script src="assets/js/plugins.js"></script>
<script src="assets/js/login.js"></script>
</body>
</html>
