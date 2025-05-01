<?php
session_start();
if (!isset($_SESSION['temp_username'])) {
    header('Location: login.php');
    exit;
}
include_once("../z_db.php");
require '../vendor/autoload.php';
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

$g = new GoogleAuthenticator();
$userRes = mysqli_query($con, "SELECT 2fa_secret FROM admin WHERE username = '".mysqli_real_escape_string($con,$_SESSION['temp_username'])."'");
$row = mysqli_fetch_assoc($userRes);
$secret = $row['2fa_secret'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['2fa_code']);
    if ($g->checkCode($secret, $code)) {
        session_regenerate_id();
        $_SESSION['username']      = $_SESSION['temp_username'];
        $_SESSION['authenticated'] = true;
        unset($_SESSION['temp_username'], $_SESSION['temp_user_id']);
        header('Location: index.php');
        exit;
    } else {
        $msg = "Geçersiz 2FA kodu.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>2FA Doğrulama</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS’ler aynı -->
    <link rel="shortcut icon" href="assets/images/favicon.icon">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/icons.min.css" rel="stylesheet" />
    <link href="assets/css/app.min.css" rel="stylesheet" />
    <link href="assets/css/login.css" rel="stylesheet" />
</head>
<body>
<div class="auth-page-wrapper">
  <div class="auth-page-content">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-5">
          <div class="card mt-5">
            <div class="card-body p-4">
              <div class="text-center mb-3">
                <h5 class="text-primary">2FA Doğrulama</h5>
                <i class="fas fa-shield-alt fa-3x text-primary"></i>
              </div>
              <form method="post">
                <div class="mb-3">
                  <label for="2fa_code" class="form-label">2FA Kodu:</label>
                  <input type="text" id="2fa_code" name="2fa_code" class="form-control" required>
                </div>
                <div class="mt-3 d-grid">
                  <button type="submit" class="btn btn-primary">Doğrula</button>
                </div>
              </form>
              <?php if (isset($msg)): ?>
                <p class="text-danger mt-3"><?= htmlspecialchars($msg) ?></p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div><!-- end auth-page-content -->
  <footer class="footer">
    <div class="container text-center">
      <p class="mb-0">&copy; 2023 Your Company. All rights reserved.</p>
    </div>
  </footer>
</div><!-- end auth-page-wrapper -->
<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/libs/simplebar/simplebar.min.js"></script>
<script src="assets/libs/node-waves/waves.min.js"></script>
<script src="assets/libs/feather-icons/feather.min.js"></script>
<script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
<script src="assets/js/plugins.js"></script>
</body>
</html>
