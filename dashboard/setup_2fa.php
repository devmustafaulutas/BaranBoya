<?php
session_start();
if (!isset($_SESSION['temp_username'])) {
    header('Location: login.php');
    exit;
}
include_once("../z_db.php");
require '../vendor/autoload.php';

use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

$g = new GoogleAuthenticator();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $secret = $_SESSION['2fa_secret_temp'] ?? null;
    if (!$secret) exit("2FA gizli anahtarı bulunamadı.");

    $user = mysqli_real_escape_string($con, $_SESSION['temp_username']);
    $sql = "UPDATE admin SET 2fa_secret = '$secret' WHERE username = '$user'";
    if (mysqli_query($con, $sql)) {
        unset($_SESSION['2fa_secret_temp'], $_SESSION['temp_username'], $_SESSION['temp_user_id']);
        exit("2FA başarıyla etkinleştirildi.<br><a href='login.php'>Tekrar giriş yap</a>");
    }
    exit("Hata: " . mysqli_error($con));
}

// GET: secret oluştur
$secret = $g->generateSecret();
$_SESSION['2fa_secret_temp'] = $secret;
$qrCodeUrl = GoogleQrUrl::generate($_SESSION['temp_username'], $secret, 'YourAppName');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>2FA Kurulumu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Aynı CSS'leri ekleyin -->
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
        <div class="col-lg-6">
          <div class="card mt-5">
            <div class="card-body p-4 text-center">
              <h5 class="text-primary mb-3">2FA Kurulumu</h5>
              <p>Google Authenticator uygulamasında <strong>YourAppName</strong> hesabını eklemek için QR kodu tarayın:</p>
              <img src="<?= htmlspecialchars($qrCodeUrl) ?>" alt="QR Code" class="mb-4">
              <form method="post">
                <button type="submit" class="btn btn-success">2FA’yı Etkinleştir</button>
              </form>
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
